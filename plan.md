# Sistem Pemesanan Tiket Bus Online - PLAN

## Notes
- Proyek: Sistem Pemesanan Tiket Bus Online berbasis website.
- Backend: PHP 8.1+, arsitektur MVC, database MySQL/MariaDB.
- Frontend: Bootstrap 5, tema biru (#0D6EFD) & putih (#FFFFFF), clean code.
- Struktur direktori: /controllers, /models, /views, /public.
- Struktur direktori proyek dan file koneksi database telah dibuat.
- Semua query database harus menggunakan prepared statements (PDO/MySQLi).
- Skeleton class model utama telah diimplementasikan di /models.
- Alur registrasi dan login sudah terimplementasi dengan controller, view, dan validasi ke database.
- Ikuti diagram kelas, activity, sequence, dan use case sesuai dokumen desain.
- Kelas utama: User (dan turunannya), Pemesanan, Tiket, Pembayaran, Jadwal, Bus, Laporan.
- Relasi antar tabel harus sesuai diagram kelas (halaman 23 PDF).
- Alur pencarian & pemesanan tiket pelanggan (cari jadwal, pilih kursi, booking, error handling) telah selesai dan terhubung ke database.
- Halaman admin kelola jadwal, tambah jadwal, dan edit jadwal sudah didesain ulang dengan tema biru/putih Bootstrap 5 yang konsisten.
- Halaman dashboard admin dan konfirmasi pembayaran sudah didesain ulang dengan statistik dinamis dan tema konsisten.
- Fitur petugas loket (verifikasi tiket, validasi, flash message, routing) telah selesai diimplementasikan.
- Fitur logout dan keamanan sesi (session_regenerate_id, session destroy, routing, UI) telah selesai diimplementasikan.
- Implementasi halaman tambah/edit pengguna DITUNDA sesuai permintaan user, hanya fitur lihat daftar pengguna yang diaktifkan.
- Sedang dilakukan review dan penyesuaian kode (model, relasi, atribut, method) agar sesuai dengan class diagram UML terbaru di uml.md
- Hasil review awal: struktur class, atribut, dan sebagian besar method model PHP sudah sesuai class diagram UML, namun perlu detail review dan update untuk relasi antar class serta beberapa method spesifik (misal: generate(), lihatRiwayat(), dsb)
- Method utama Pelanggan (pesanTiket, bayarOnline, lihatRiwayat) sudah diimplementasikan sesuai class diagram UML
- Method utama Admin (kelolaJadwal, kelolaTiket, konfirmasiPembayaran, generateLaporan) sudah diimplementasikan sesuai class diagram UML

## Task List
- [x] Generate skrip SQL CREATE TABLE untuk semua tabel sesuai class diagram dan relasi.
- [x] Review dan validasi struktur tabel bersama user.
- [x] Buat struktur folder proyek MVC.
- [x] Implementasi koneksi database (PHP PDO/MySQLi).
- [x] Implementasi class model sesuai diagram kelas.
- [x] Implementasi controller dan view untuk fitur utama (registrasi, login, pemesanan, pembayaran, dsb).
- [x] Implementasi fitur pencarian & pemesanan tiket (dashboard pelanggan).
- [x] Implementasi fitur CRUD Jadwal untuk Admin.
- [x] Styling frontend dengan Bootstrap 5 dan tema biru/putih untuk halaman admin jadwal (kelola, tambah, edit).
- [x] Styling frontend untuk halaman admin lain (dashboard, konfirmasi pembayaran, laporan, dsb).
- [x] Implementasi fitur admin (konfirmasi pembayaran).
- [x] Implementasi fitur laporan admin (model, controller, view, routing, filter tanggal).
- [x] Implementasi fitur petugas loket (verifikasi tiket, data pengunjung).
- [x] Implementasi fitur logout dan keamanan sesi.
- [ ] Implementasi fitur manajemen pengguna admin (CRUD user, reset password, dsb).
  - [x] Implementasi halaman lihat daftar pengguna (tanpa tambah/edit/hapus)
  - [ ] Aktifkan fitur edit & hapus pengguna jika diinginkan
- [ ] Review dan sinkronisasi ulang semua model (User, Pelanggan, PetugasLoket, Admin, Pemesanan, Tiket, Pembayaran, Jadwal, Bus, Laporan) dengan class diagram UML terbaru
  - [x] Review dan update atribut & method pada model Pelanggan agar sesuai UML
  - [x] Review dan update atribut & method pada model Admin agar sesuai UML
  - [ ] Review dan update atribut & method pada setiap model lain agar sesuai UML
  - [ ] Review dan update relasi antar model/class sesuai class diagram

## Current Goal
Review & perbaiki sistem sesuai diagram UML terbaru