import schedule from "node-schedule";
import { startSock } from "./bot.js";
import { PrismaClient } from "@prisma/client";
const prisma = new PrismaClient();

let socket = startSock(false); // false untuk tidak menggunakan pairing code

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

const job = schedule.scheduleJob("39 * * * * *", async function () {
  await cekSemuaService();
});
