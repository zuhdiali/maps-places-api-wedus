require("./config/bot");
const { makeWASocket, useMultiFileAuthState } = require("baileys");
const P = require("pino");
const fs = require("fs");
const schedule = require("node-schedule");

const { PrismaClient } = require("@prisma/client");
const prisma = new PrismaClient();

//  UTILS
// const { question } = require("./utils/question");
const rl = require("readline");

function question(text = "question") {
  return new Promise((resolve) => {
    const rl = require("readline").createInterface({
      input: process.stdin,
      output: process.stdout,
    });
    rl.question(`\x1b[32;1m?\x1b[0m\x20\x1b[1m${text}\x1b[0m`, (answer) => {
      rl.close();
      resolve(answer);
    });
  });
}

async function cekSemuaService() {
  const allService = await prisma.service.findMany();
  allService.forEach((service) => {
    serviceIn = new Date(service.createdAt);

    let serviceInHours = serviceIn.getHours();
    serviceInHours += service.hours;

    serviceIn.setHours(serviceInHours);
    if (serviceIn <= new Date() && service.status === 1) {
      console.log(
        `\x1b[32;1mLaundry\x20${service.name}\x1b[0m\x20\x1b[33;1msudah selesai\x1b[0m`
      );
    } else {
      console.log(
        `\x1b[31;1mLaundry\x20${service.name}\x1b[0m\x20\x1b[33;1mbelum\x20selesai\x1b[0m`
      );
    }
  });
}

async function startSock(usePairingCode = true) {
  const session = await useMultiFileAuthState("session");

  const sock = makeWASocket({
    printQRInTerminal: !usePairingCode,
    auth: session.state,
    logger: P({ level: "silent" }).child({ level: "silent" }),
  });

  if (usePairingCode && !sock.user && !sock.authState.creds.registered) {
    console.log("masuk sini");
    const phoneNumber = await question("Enter phone number: +");
    // console.log("Pairing code is required. Please scan the QR code.");
    const code = await sock.requestPairingCode("6282328839199");
    console.log(`\x1b[44;1m\x20Pairing code:\x20\x1b[0m\x20${code}`);
    // sock.authState.creds = session.state;
  }

  sock.ev.on("connection.update", async ({ connection, lastDisconnect }) => {
    if (connection === "close") {
      console.log(lastDisconnect.error);
      const { statusCode } = lastDisconnect.error.output.payload;

      if (statusCode === 401 && lastDisconnect.error === "Unauthorized") {
        await fs.promises.rm("session", { recursive: true, force: true });
      }

      return startSock();

      // const shouldReconnect = lastDisconnect.error?.output?.statusCode !== 401;
      // console.log("Connection closed. Reconnecting...", { shouldReconnect });
      // if (shouldReconnect) {
      //   startSock(usePairingCode).catch((err) => console.error(err));
      // }
    } else if (connection === "open") {
      // VALIDASI NOMOR WA
      if (global.bot.number !== sock.user.id.split(":")[0]) {
        console.log(
          `\x1b[35;1mNomor yang digunakan tidak memliki akses untuk menggunakan bot ini\x1b[0m\n -> SILAHKAN MEMESAN SCIRPT INI KE OWNER BOT\n -> \x1b[32;1m${global.bot.adsUrl}\x1b[0m\n -> \x1b[32;1m${global.bot.number}\x1b[0m\n`
        );
        return process.exit();
      }

      // JIKAS BERHASIL
      console.log("berhasil terhubung dengan: " + sock.user.id.split(":")[0]);
      // Kirim pesan otomatis ke nomor tertentu
      const jid = "6282328839199@s.whatsapp.net"; // Ganti dengan nomor tujuan
      sock.sendMessage(jid, { text: "Halo! Ini pesan otomatis dari bot." });
    }
  });

  // sock.ev.on("connection.update", async (update) => {
  //   const { connection, lastDisconnect, qr } = update;
  //   if (connection == "connecting" || !!qr) {
  //     // your choice
  //     const code = await sock.requestPairingCode(
  //       phoneNumber.replace(/\D/g, "")
  //     );
  //     console.log(`\x1b[44;1m\x20Pairing code:\x20\x1b[0m\x20${code}`);
  //   } else if (connection === "close") {
  //     console.log("Connection closed. Reconnecting...");
  //     startSock(usePairingCode).catch((err) => console.error(err));
  //   }
  // });

  sock.ev.on("creds.update", session.saveCreds);

  //   sock.ev.on("connection.update", async (update) => {
  //     const { connection, lastDisconnect, qr } = update;
  //     if (connection == "connecting" || !!qr) {
  //       // your choice
  //       const code = await sock.requestPairingCode(phoneNumber);
  //       // send the pairing code somewhere
  //     }
  //   });
}

// module.exports = {
//   startSock,
//   cekSemuaService,
// };
startSock(true);
// cekSemuaService()
//   .then(async () => {
//     await prisma.$disconnect();
//     console.log("Disconnected from database");
//   })
//   .catch(async (err) => {
//     console.error(err);
//     await prisma.$disconnect();
//     process.exit(1);
//   });
