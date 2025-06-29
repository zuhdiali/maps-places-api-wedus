import express from "express";
const app = express();
const port = 3000;

import schedule from "node-schedule";
import { startSock } from "./bot.js";
// const { cekSemuaService, startSock } = require("./bot-wa");

import { PrismaClient } from "@prisma/client";
const prisma = new PrismaClient();

let socket; // Variabel untuk menyimpan instance Baileys socket

// Fungsi untuk menginisialisasi koneksi WhatsApp
async function initializeWhatsApp() {
  try {
    console.log("Menginisialisasi koneksi WhatsApp...");
    // 'false' berarti menggunakan QR code, bukan pairing code
    socket = await startSock(false);
    console.log("Koneksi WhatsApp berhasil diinisialisasi.");
    // Anda bisa menambahkan logika di sini setelah koneksi siap,
    // misalnya, memastikan sock.user sudah ada jika diperlukan.
  } catch (error) {
    console.error("Gagal menginisialisasi koneksi WhatsApp:", error);
    // Pertimbangkan untuk keluar dari aplikasi atau menangani error ini
    // agar server tidak berjalan tanpa fungsionalitas WhatsApp jika itu kritikal.
    process.exit(1); // Contoh: Keluar jika gagal
  }
}

async function cekSemuaService() {
  // Periksa apakah socket Baileys sudah siap dan sudah login
  if (!socket || !socket.user) {
    console.log(
      "cekSemuaService: Koneksi WhatsApp belum siap atau belum login. Melewati pemeriksaan."
    );
    return;
  }

  console.log("cekSemuaService: Menjalankan pemeriksaan semua layanan...");
  try {
    const allService = await prisma.service.findMany();
    if (allService.length === 0) {
      console.log("cekSemuaService: Tidak ada layanan yang ditemukan.");
      return;
    }

    // Menggunakan Promise.all untuk menangani operasi asinkron dalam loop dengan lebih baik
    // meskipun forEach dengan async callback juga bisa bekerja jika urutan tidak kritikal
    // dan error ditangani per iterasi.
    await Promise.all(
      allService.map(async (service) => {
        const serviceIn = new Date(service.createdAt); // Deklarasi serviceIn
        let serviceInHours = serviceIn.getHours();
        serviceInHours += service.hours; // Pastikan service.hours adalah angka
        serviceIn.setHours(serviceInHours);

        if (serviceIn <= new Date() && service.status === 1) {
          try {
            const recipientJid = `${service.number_phone}@s.whatsapp.net`; // Format JID
            console.log(
              `cekSemuaService: Mencoba mengirim pesan ke: ${recipientJid} untuk layanan ${service.name}`
            );

            await socket.sendMessage(recipientJid, {
              text: `Laundry ${service.name} atas nama ${service.name} sudah selesai. Silakan ambil di toko ABC. Terima kasih!`,
            });
            console.log(
              `cekSemuaService: Pesan berhasil terkirim untuk layanan ${service.name}`
            );

            // Opsional: Update status layanan di database agar tidak dikirim berulang kali
            // await prisma.service.update({
            //   where: { service_id: service.service_id },
            //   data: { status: 2 }, // Misalnya, status 2 = sudah dinotifikasi
            // });
          } catch (error) {
            console.error(
              `cekSemuaService: Gagal mengirim pesan untuk layanan ${service.name} (ID: ${service.service_id}):`,
              error
            );
          }
        } else {
          // Log ini bisa diaktifkan jika perlu
          // console.log(
          //   `cekSemuaService: Laundry ${service.name} (ID: ${service.service_id}) belum selesai atau status tidak sesuai.`
          // );
        }
      })
    );
  } catch (dbError) {
    console.error(
      "cekSemuaService: Gagal mengambil data layanan dari database:",
      dbError
    );
  }
}

app.use(express.json()); // <==== parse request body as JSON

app.get("/", (req, res) => {
  res.send("Hello World!");
});

app.post("/service-check", async (req, res) => {
  console.log(req.body);
  const id = req.body.id;
  const service = await prisma.service.findUnique({
    where: { service_id: id },
  });
  if (!service) {
    return res.status(404).json({ message: "Service not found" });
  }
  const serviceIn = new Date(service.createdAt);
  let serviceInHours = serviceIn.getHours();
  serviceInHours += service.hours;

  serviceIn.setHours(serviceInHours);

  if (serviceIn <= new Date() && service.status === 1) {
    try {
      console.log(`Mencoba mengirim pesan ke: ${service.number_phone}`);
      const recipientJid = `${service.number_phone}@s.whatsapp.net`;
      const sentMsg = await socket.sendMessage(recipientJid, {
        text: `Laundry atas nama ${service.name} sudah selesai. Silakan ambil di toko ABC. Terima kasih!`,
      });
      console.log("Pesan berhasil terkirim:", sentMsg);
      // sentMsg berisi informasi tentang pesan yang terkirim, termasuk timestamp dan ID pesan
    } catch (error) {
      console.error("Gagal mengirim pesan:", error);
    }
    return res.status(200).json({ message: "Service is done" });
  } else {
    console.log(
      `\x1b[31;1mLaundry\x20${service.name}\x1b[0m\x20\x1b[33;1mbelum\x20selesai\x1b[0m`
    );
    return res.status(200).json({ message: "Service is not done" });
  }
});

// Fungsi utama untuk menjalankan aplikasi
async function main() {
  // Inisialisasi WhatsApp terlebih dahulu
  await initializeWhatsApp();
  try {
    // Pastikan Baileys instance ada sebelum menjadwalkan tugas
    if (socket) {
      // Menjadwalkan tugas untuk berjalan pada detik ke-39 setiap menit
      // (Untuk testing, Anda mungkin ingin jadwal yang lebih jarang atau spesifik)
      // Contoh: setiap jam pada menit ke-0 -> "0 * * * *"
      // Contoh: setiap hari jam 9 pagi -> "0 9 * * *"
      const cronExpression = "39 * * * * *"; // Setiap menit pada detik ke-39
      console.log(
        `Menjadwalkan 'cekSemuaService' untuk berjalan dengan cron: ${cronExpression}`
      );

      schedule.scheduleJob(cronExpression, async function () {
        const jobExecutionTime = new Date();
        console.log(
          `Tugas terjadwal 'cekSemuaService' dijalankan pada: ${jobExecutionTime.toLocaleTimeString()}`
        );
        try {
          await cekSemuaService();
        } catch (jobError) {
          console.error(
            `Error saat menjalankan tugas terjadwal 'cekSemuaService' pada ${jobExecutionTime.toLocaleTimeString()}:`,
            jobError
          );
        }
      });
      console.log("'cekSemuaService' berhasil dijadwalkan.");
    } else {
      console.error(
        "Koneksi WhatsApp tidak berhasil diinisialisasi. Tugas tidak akan dijadwalkan."
      );
    }
  } catch (error) {
    console.error("Gagal dalam proses inisialisasi atau penjadwalan:", error);
  }

  // Kemudian jalankan server Express
  app.listen(port, () => {
    console.log(`Aplikasi Express berjalan di port ${port}`);
    if (socket && socket.user) {
      console.log("Bot WhatsApp terhubung dan siap.");
    } else if (socket) {
      console.warn(
        "Bot WhatsApp diinisialisasi tetapi mungkin belum sepenuhnya login/siap. Cek log dari bot.js."
      );
    } else {
      console.error("Bot WhatsApp GAGAL diinisialisasi atau tidak berjalan.");
    }
  });
}

// Jalankan fungsi main
main();
