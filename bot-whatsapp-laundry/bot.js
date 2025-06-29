import {
  Browsers,
  makeWASocket,
  useMultiFileAuthState,
  DisconnectReason,
  // Pastikan DisconnectReason diimpor jika Anda menggunakannya secara eksplisit
} from "baileys";
import P from "pino";
import fs from "fs/promises"; // Untuk menghapus folder sesi
import QRCode from "qrcode"; // Untuk menampilkan QR code di terminal

export async function startSock(usePairingCode = true) {
  const { state, saveCreds } = await useMultiFileAuthState("session"); // Ambil state dan saveCreds

  const sock = makeWASocket({
    printQRInTerminal: !usePairingCode,
    auth: state, // Gunakan state di sini
    logger: P({ level: "debug" }), // Ubah ke 'debug' untuk troubleshooting
    browser: Browsers.macOS("Desktop"), // Contoh browser, bisa disesuaikan
  });

  // Jangan panggil requestPairingCode di sini lagi

  sock.ev.on("connection.update", async (update) => {
    const { connection, lastDisconnect, qr } = update;

    if (connection === "close") {
      const statusCode = lastDisconnect?.error?.output?.statusCode;
      console.error(
        `[CONNECTION CLOSE] Koneksi ditutup. Kode: ${statusCode}, Alasan:`,
        lastDisconnect?.error?.message
      );

      let shouldAttemptReconnect = false;

      if (statusCode === DisconnectReason.restartRequired) {
        // Kode 515
        console.log(
          "[CONNECTION CLOSE] Restart diperlukan setelah pairing berhasil. Mencoba menghubungkan ulang..."
        );
        shouldAttemptReconnect = true;
      } else if (statusCode === DisconnectReason.loggedOut) {
        console.log(
          "[CONNECTION CLOSE] Akun keluar (loggedOut). Hapus folder 'session' dan mulai ulang manual jika perlu."
        );
        // await fs.rm("session", { recursive: true, force: true }).catch(err => console.error("Gagal hapus session:", err));
        shouldAttemptReconnect = false; // Jangan reconnect otomatis jika logged out
      } else if (statusCode === 401) {
        // Unauthorized
        console.log(
          "[CONNECTION CLOSE] Error Unauthorized (401). Menghapus folder 'session' dan restart."
        );
        try {
          // Pastikan fs diimpor: import fs from 'fs/promises';
          await fs.rm("session", { recursive: true, force: true });
        } catch (err) {
          console.error("Gagal menghapus folder session:", err);
        }
        shouldAttemptReconnect = true; // Coba restart koneksi
      } else {
        // Untuk error lain yang mungkin bisa diretry
        console.log(
          `[CONNECTION CLOSE] Akan mencoba menghubungkan ulang untuk kode error: ${statusCode}`
        );
        shouldAttemptReconnect = true;
      }

      if (shouldAttemptReconnect) {
        console.log("[RECONNECTING] Memulai ulang koneksi...");
        startSock(usePairingCode); // Panggil fungsi utama Anda untuk memulai koneksi lagi
        // 'usePairingCode' mungkin tidak relevan lagi jika sudah pairing via QR
        // tapi pastikan fungsi startSock bisa menangani sesi yang sudah ada.
      }
    } else if (connection === "open") {
      console.log("Koneksi berhasil terbuka!");
      console.log("User:", sock.user?.id); // Cek apakah user sudah ada
      console.log("Registered:", sock.authState.creds.registered); // Cek status registrasi

      // // CONTOH MENGIRIM PESAN
      // // const nomorTujuan = "6285727311989"; // Ganti dengan nomor tujuan Anda
      // const nomorTujuan = "6282328839199"; // Ganti dengan nomor tujuan Anda
      // const recipientJid = `${nomorTujuan}@s.whatsapp.net`;
      // const pesanTeks =
      //   "Halo! Ini adalah pesan otomatis dari bot Baileys saya. 😊";
      // try {
      //   console.log(`Mencoba mengirim pesan ke: ${recipientJid}`);
      //   const sentMsg = await sock.sendMessage(recipientJid, {
      //     text: pesanTeks,
      //   });
      //   console.log("Pesan berhasil terkirim:", sentMsg);
      //   // sentMsg berisi informasi tentang pesan yang terkirim, termasuk timestamp dan ID pesan
      // } catch (error) {
      //   console.error("Gagal mengirim pesan:", error);
      // }
      // // Kirim pesan jika sudah terhubung dan terdaftar
      // if (sock.user && sock.authState.creds.registered) {
      //   console.log(`Berhasil terhubung dengan: ${sock.user.id.split(":")[0]}`);
      // }

      // Minta pairing code HANYA JIKA koneksi 'open', usePairingCode true, DAN belum terdaftar/login
      if (usePairingCode && !sock.authState.creds.registered) {
        // Atau bisa juga !sock.user
        console.log(
          "Koneksi terbuka, meminta pairing code karena belum terdaftar..."
        );
        try {
          const code = await sock.requestPairingCode("6282328839199"); // Nomor HP bot
          console.log(`\x1b[44;1m\x1b[20Pairing code:\x20\x1b[0m\x20${code}`);
        } catch (err) {
          console.error("Gagal meminta pairing code:", err);
        }
      }
    }

    // Jika Anda menggunakan QR dan tidak pairing code
    if (qr && !usePairingCode) {
      console.log(await QRCode.toString(qr, { type: "terminal" }));
    }
  });

  sock.ev.on("creds.update", saveCreds); // Panggil saveCreds dari useMultiFileAuthState

  return sock;
}

// Panggil fungsi untuk memulai
// (async () => {
//   try {
//     await startSock(false); // true untuk menggunakan pairing code
//   } catch (error) {
//     console.error("Gagal memulai startSock:", error);
//   }
// })();
