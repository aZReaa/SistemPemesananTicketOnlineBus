Sulawesi Indonesian
---

### A. Latar Belakang

Seiring pesatnya perkembangan teknologi informasi kebutuhan masyarakat akan layanan berbasis digital terus meningkat. Salah satu sektor yang terdampak signifikan adalah industri transportasi, khususnya layanan pemesanan tiket bus. Selama ini mayoritas pemesanan tiket bus masih mengandalkan metode konvensional, seperti datang langsung ke terminal atau agen resmi. Praktik ini menimbulkan sejumlah kendala, diantaranya:

1.  **Antrean panjang di loket fisik**, penumpang harus menghabiskan waktu lama untuk mengantre, terutama pada musim ramai (liburan/akhir pekan), sehingga berisiko terlambat mendapatkan tiket atau bahkan kehilangan jadwal keberangkatan.
2.  **Risiko kehabisan tiket**, informasi ketersediaan tiket tidak real-time, sehingga penumpang mungkin sudah datang ke terminal tetapi kehabisan tiket karena belum ada sistem pemesanan instan, oleh karena itu pelanggan mengalami kerugian emosional dan finansial.
3.  **Proses manual yang tidak efisien**, pencatatan manual rentan human error dan juga analisis data membutuhkan waktu yang lama. Hal ini tentunya mempengaruhi produktivitas internal perusahaan.
4.  **Pembayaran konvensional yang berisiko**, transaksi tunai di loket berpotensi menimbulkan kesalahan perhitungan atau kehilangan uang. Di era digital ini, mayoritas pelanggan khususnya generasi muda juga lebih menginginkan kemudahan pembayaran digital.
5.  **Pemasaran terbatas**, ketergantungan pada promosi fisik (spanduk, brosur) membuat jangkauan pemasaran sempit. Pebisnis pun kalah bersaing dengan layanan serupa yang telah memanfaatkan teknologi digital.

Untuk menyelesaikan permasalahan di atas, dibutuhkan sistem pemesanan tiket bus yang lebih praktis, efisien, dan dapat diakses kapan saja dan di mana saja. Salah satu solusinya adalah dengan membangun sistem pemesanan tiket bus berbasis website. Dengan adanya sistem ini, calon penumpang dapat dengan mudah mencari informasi jadwal keberangkatan, memilih tempat duduk, serta melakukan pembayaran secara online tanpa perlu datang ke lokasi fisik.

Website pemesanan tiket bus juga memberikan keuntungan bagi pebisnis yang memanfaatkannya, seperti memperluas jangkauan pemasaran, mengurangi beban operasional, dan meningkatkan pelayanan kepada pelanggan. Dengan pengelolaan data yang lebih terstruktur, pebisnis dapat lebih mudah melakukan analisis bisnis serta meningkatkan kualitas layanan di masa depan.

Oleh karena itu, pengembangan sistem pemesanan tiket bus berbasis website menjadi langkah penting dalam mendukung transformasi digital di bidang transportasi dan memenuhi kebutuhan masyarakat modern yang serba cepat dan praktis.

---

### B. Struktur Sistem Informasi Pemesanan Tiket Online Berbasis Website

**a. Pelanggan**
*   Registrasi
*   Login
*   Memesanan tiket
*   Melakukan pembayaran online/offline
*   Log-out

**b. Admin**
*   Login
*   Kelola Jadwal dan tiket
*   Konfirmasi Pembayaran
*   Laporan penjualan
*   Log-out

**c. Petugas Loket**
*   Login
*   Verivikasi tiket
*   Menerima pembayaran offline (jika ada)
*   Melihat data pelanggan
*   Log-out

---

### C. Diagram Use Case

> **Diagram Use Case:**
> *   **Aktor:** Pelanggan, Admin, Petugas Loket.
> *   **Use Cases untuk Pelanggan:**
>     *   Registrasi
>     *   Pesan tiket (`<include>` Login)
>     *   Melakukan Pembayaran (`<include>` Login)
> *   **Use Cases Umum:**
>     *   Login (`<extend>` Logout)
>     *   Logout
> *   **Use Cases untuk Admin:**
>     *   Kelola jadwal dan tiket (`<include>` Login)
>     *   Konfirmasi pembayaran (`<include>` Login)
>     *   Laporan penjualan (`<include>` Login)
> *   **Use Cases untuk Petugas Loket:**
>     *   Verivikasi tiket (`<include>` Login)
>     *   Data pengunjung (`<include>` Login)

**Keterangan :**

**1. Alur untuk Pengguna/Pelanggan :**
*   **a. Registrasi :** Pengguna baru memulai dengan melakukan registrasi untuk membuat akun.
*   **b. Login:** Setelah registrasi atau jika sudah memiliki akun, pengguna login ke sistem.
*   **c. Pesan Tiket :** Pengguna memilih tiket yang diinginkan dan menambahkannya ke keranjang. Ini termasuk dalam proses login.
*   **d. Bayar Online:** Pengguna melakukan pembayaran tiket secara online. Ini termasuk dalam proses login.

**2. Alur untuk Administrator :**
*   **a. Login:** Administrator login ke sistem dengan kredensial mereka.
*   **b. Kelola Jadwal dan Tiket :** Administrator dapat menambahkan, mengedit, atau menghapus jadwal dan tiket. Ini membutuhkan login.
*   **c. Konfirmasi Pembayaran:** Administrator mengkonfirmasi pembayaran yang telah dilakukan oleh pengguna. Ini membutuhkan login.
*   **d. Laporan Penjualan :** Administrator dapat melihat laporan penjualan tiket. Ini membutuhkan login.

**3. Alur untuk Petugas Loket :**
*   **a. Verifikasi Tiket :** Petugas loket memverifikasi tiket yang dimiliki pengguna sebelum masuk ke tempat acara.
*   **b. Data Pengunjung :** Petugas loket dapat mengakses data pengunjung untuk keperluan administrasi.
*   **Logout:** Baik pengguna, administrator, maupun petugas loket dapat logout dari sistem setelah selesai menggunakannya. Ini merupakan proses terpisah yang dapat diakses setelah login.

---

### D. Diagram Activity

**1. Pelanggan**

**a. Registrasi**
> **Flowchart Registrasi:**
> 1.  **Pelanggan:** Klik menu registrasi.
> 2.  **System:** Menampilkan halaman registrasi.
> 3.  **Pelanggan:** Mengisi form registrasi, dan klik registrasi.
> 4.  **System:**
>     *   Jika Benar: Menyimpan data pelanggan -> Mengirim pesan verifikasi email -> Mengaktifkan akun.
>     *   Jika Salah: Menampilkan pesan error.
> 5.  **Pelanggan:** Verifikasi.
> 6.  Proses selesai.

**Keterangan :**
1.  Pelanggan mengakses menu registrasi lalu sistem menampilkan halaman registrasi dengan form berisi kolom nama, email, password, dan konfirmasi password, serta tombol registrasi dibawahnya.
2.  Pelanggan mengisi form registrasi kemudian klik registrasi, jika benar sistem menyimpan data, jika salah sistem menampilkan pesan error dan kembali ke form registrasi.
3.  Setelah menyimpan data pelanggan, sistem mengirimkan pesan verifikasi melalui email yang didaftarkan kepada pelanggan.
4.  Pelanggan memverifikasi pesan yang diterima.
5.  Sistem mengaktifkan akun.

**b. Login**
> **Flowchart Login:**
> 1.  **Pelanggan/Admin/Petugas Loket:** Klik login.
> 2.  **System:** Menampilkan halaman login.
> 3.  **Pelanggan/Admin/Petugas Loket:** Mengisi form login, lalu klik login.
> 4.  **System:**
>     *   Jika Benar: Menampilkan halaman utama.
>     *   Jika Salah: Kembali ke halaman login.
> 5.  Proses selesai.

**Keterangan:**
1.  Pelanggan/admin/petugas loket mengakses menu login, lalu system menampilkan form login yang berisi kolom nama pengguna atau email, dan kolom password, serta tombol login dibawahnya.
2.  Pelanggan/admin/petugas loket mengisi form login lalu klik tombol login, kemudian sistem mengecek validasi email, jika benar sistem menampilkan (masuk ke) halaman utama. Jika salah sistem menampilkan pesan error dan kembali ke form login.

**c. Pesan tiket**
> **Flowchart Pesan Tiket:**
> 1.  **Pelanggan:** Pilih menu pesan tiket.
> 2.  **System:** Menampilkan halaman pesan tiket.
> 3.  **Pelanggan:** Cari bus (masukkan rute atau tanggal).
> 4.  **System:** Tampilkan daftar bus yang tersedia.
> 5.  **Pelanggan:** Pilih bus.
> 6.  **System:** Tampilkan denah kursi dan jadwal.
> 7.  **Pelanggan:** Pilih kursi + konfirmasi jadwal, lalu klik simpan.
> 8.  **System:** Menampilkan pesan "booking sukses".
> 9.  Proses selesai.

**Keterangan :**
1.  Pelanggan mengakses menu pesan tiket, lalu sistem menampilkan halaman utama yang berisi jadwal keberangkatan bus dan kolom pencarian.
2.  Pelanggan memilih bus atau mencari bus berdasarkan rute maupun tanggal di kolom pencarian lalu sistem akan menampilkan daftar bus yang tersedia berdasarkan data yang diinputkan pelanggan.
3.  Pelanggan memilih bus, lalu sistem menampilkan denah kursi visual, adapun jadwal ditampilkan di sisi yang sama.
4.  Selanjutnya pelanggan memilih kursi dan jadwal keberangkatan, lalu klik oke. Kemudian sistem akan membooking kursi sesuai dengan data yang diinput pelanggan.

**d. Melakukan pembayaran**
> **Flowchart Pembayaran:**
> 1.  **Pelanggan:** Pilih menu pembayaran.
> 2.  **System:** Menampilkan form pembayaran.
> 3.  **Pelanggan:** Apload bukti pembayaran.
> 4.  **System:** Menampilkan pesan "Pembayaran Sukses" -> Menampilkan E-tiket.
> 5.  Proses selesai.

**Keterangan :**
1.  Pelanggan mengakses menu pembayaran, kemudian system menampilkan form pembayaran.
2.  Setelah pelanggan melakukan pembayaran, selanjutnya pelanggan meng-upload bukti pembayaran, lalu admin mengecek bukti pembayaran dan menentukan apakah sudah benar atau salah kemudian memberi konfirmasi pada system jika benar sistem akan menandainya sebagai lunas, lalu menerbitkan e-ticket. Sedangkan jika salah sistem akan menampilkan pesan error.

**2. Admin**

**a. Kelola Jadwal dan tiket**
> **Flowchart Kelola Jadwal:**
> 1.  **Admin:** Pilih menu kelola jadwal dan tiket.
> 2.  **System:** Menampilkan halaman kelola jadwal dan tiket.
> 3.  **Admin:** Klik tambah jadwal -> **System:** Menampilkan form tambah jadwal.
> 4.  **Admin:** Input jadwal, klik simpan -> **System:** Proses simpan.
> 5.  **Admin:** Klik edit jadwal -> **System:** Menampilkan halaman edit jadwal.
> 6.  **Admin:** Ubah data dan klik simpan -> **System:** Proses simpan.
> 7.  **Admin:** Klik hapus -> **System:** Proses hapus.
> 8.  Proses selesai.

**Keterangan :**
1.  Admin mengakses menu kelola jadwal dan tiket, lalu sistem menampilkan halaman kelola jadwal dan tiket.
2.  Admin kemudian mengelola jadwal lalu menyimpan perubahan, lalu sistem memvalidasi dan menyimpan perubahan ke database, lalu mengirim konfirmasi sukses ke admin.

**b. Konfirmasi Pembayaran**
> **Flowchart Konfirmasi Pembayaran:**
> 1.  **Admin:** Pilih menu pembayaran (Booking).
> 2.  **System:** Menampilkan daftar pembayaran "Pending".
> 3.  **Admin:** Konfirmasi pembayaran.
> 4.  **System:** Mengirim pesan pembayaran sukses ke pelanggan -> Terbitkan E-Ticket.
> 5.  Proses selesai.

**Keterangan :**
1.  Admin mengakses menu pembayaran, lalu system menampilkan daftar pembayaran yang pending.
2.  Admin mengonfirmasi bukti pembayaran, lalu sistem akan mengirim pesan pembayaran sukses ke pelanggan, kemudian E-tiket.

**c. Laporan penjualan**
> **Flowchart Laporan Penjualan:**
> 1.  **Admin:** Akses menu laporan penjualan.
> 2.  **System:** Menampilkan semua laporan penjualan.
> 3.  **Admin:** Cari berdasarkan periode tanggal/jenis tiket/event.
> 4.  **System (Decision):**
>     *   Jika Ya (ditemukan): Tampilkan data penjualan -> **Admin:** Klik unduh -> **System:** Proses mengunduh.
>     *   Jika Tidak: Menampilkan pesan error.
> 5.  Proses selesai.

**Keterangan :**
1.  Admin mengakses menu laporan penjualan, lalu system menampilkan semua laporan penjualan, dengan kolom cari di atasnya.
2.  Admin bisa langsung memilih laporan penjualan yang ditampilkan atau mencari laporan spesifik di kolom pencarian, admin bisa mencari berdasarkan tanggal, jenis tiket, atau event. Jika data yang diinput admin ada, system akan menampilkan data penjualan dalam bentuk tabel atau grafik. Jika data yang diinput tidak ada, system akan menampilkan pesan error.
3.  Setelah data tampil, admin memutuskan apakah hanya sekedar ingin melihat data, atau ingin menyimpan/mengunduh, jika ingin mengunduh, admin klik unduh. Lalu sistem akan memproses.

**3. Petugas Loket**

**a. Verifikasi tiket**
> **Flowchart Verifikasi Tiket:**
> 1.  **Petugas Loket:** Akses menu verifikasi tiket.
> 2.  **System:** Menampilkan form verifikasi tiket.
> 3.  **Petugas Loket:** Scan QR code (Decode kode booking) atau Masukkan code booking manual.
> 4.  **System (Decision):**
>     *   Jika Benar: Update status ticket ke "Digunakan".
>     *   Jika Salah: Menampilkan pesan "Ticket Tidak Valid".
> 5.  Proses selesai.

**Keterangan :**
1.  Petugas loket kemudian mengakses menu verifikasi ticket, lalu system menampilkan form verifikasi.
2.  Petugas loket mengecek ticket (code booking) pelanggan dengan cara scan code atau masukkan code manual. Selanjutnya system akan mengecek validitas. Jika code booking tidak valid system akan menampilkan pesan code booking tidak dapat digunakan. Jika code booking valid system akan meng-update status ticket menjadi digunakan.

**b. Data Pelanggan**
> **Flowchart Data Pelanggan:**
> 1.  **Petugas Loket:** Akses menu data pelanggan.
> 2.  **System:** Menampilkan data pelanggan.
> 3.  **Petugas Loket:** Pilih atau cari berdasarkan tanggal/nama/event.
> 4.  **System (Decision):**
>     *   Jika Valid: Tampilkan data.
>     *   Jika Invalid: Menampilkan pesan error.
> 5.  Proses selesai.

**Keterangan :**
1.  Petugas loket kemudian mengakses menu data pengunjung, lalu system menampilkan semua data pengunjung.
2.  Petugas melakukan pencarian atau memilih data berdasarkan tanggal/nama/event untuk menyaring informasi lebih lanjut. Setelah data yang diinginkan muncul, petugas loket menentukan apakah ingin menyimpan atau mencetak data, lalu Sistem kemudian memproses permintaan dan melakukan validasi terhadap data input yang diberikan petugas.
3.  Jika input tidak valid, sistem akan menampilkan pesan error sebagai respon atas kesalahan input, namun Jika input valid, maka sistem akan menampilkan data sesuai dengan kriteria pencarian.

**c. Log-out**
> **Flowchart Log-out:**
> 1.  **Pelanggan/Admin/Petugas Loket:** Pilih akun.
> 2.  **System:** Menampilkan detail tentang akun.
> 3.  **User:** Pilih log-out.
> 4.  **System:** Menampilkan pesan "Konfirmasi Log-out".
> 5.  **User:** Pilih Ya atau Tidak.
> 6.  **System (Decision):**
>     *   Jika Ya: Hapus session data, lalu redirect ke halaman login.
>     *   Jika Tidak: Kembali ke detail akun.
> 7.  Proses selesai.

**Keterangan :**
1.  Pelanggan/Admin/Petugas loket kemudian mengakses fitur akun lalu system menampilkan form akun yang berisi detail akun.
2.  Selanjutnya Pelanggan/Admin/Petugas loket pilih log-out dan sistem akan menampilkan pesan untuk mengkonfirmasi bahwa pengguna yakin ingin log-out, selanjutnya pengguna memilih iya atau tidak. Jika pengguna memilih tidak maka pesan akan hilang dan pengguna kembali ke form detail akun. Sedangkan jika pengguna memilih iya maka sistem akan menghapus session data, lalu mengarahkan ulang pengguna ke halaman login.

---

### E. Sequence Diagram

**1. Pelanggan**

**a. Registrasi**
> **Sequence: Registrasi**
> 1.  **Pelanggan -> Sistem:** Klik menu registrasi.
> 2.  **Sistem -> Pelanggan:** Menampilkan halaman registrasi.
> 3.  **Pelanggan -> Sistem:** Mengisi halaman registrasi, lalu klik registrasi.
> 4.  **Sistem -> Database:** Memproses data.
> 5.  **Database -> Sistem:** [Valid] Menyimpan data pelanggan.
> 6.  **Sistem -> Pelanggan:** Mengirim pesan verifikasi email.
> 7.  **Database -> Sistem:** [Invalid] Mengirim pesan error.
> 8.  **Sistem -> Pelanggan:** Mengirim pesan error.
> 9.  **Pelanggan -> Sistem:** Verifikasi.
> 10. **Sistem:** Mengaktifkan akun.

**Keterangan :**
1.  Pelanggan mengklik menu registrasi lalu Sistem menampilkan halaman registrasi untuk diisi oleh pelanggan.
2.  Permintaan ini dikirim ke sistem untuk diproses.
3.  Sistem menerima data registrasi dan mulai memprosesnya.
4.  Sistem kemudian melakukan validasi data (misalnya memeriksa apakah email sudah terdaftar atau format data sesuai).
5.  Jika data valid, Sistem menyimpan data pelanggan ke dalam database.
6.  Jika data tidak valid, Sistem mengirimkan pesan error ke pelanggan (misalnya: email sudah digunakan, data tidak lengkap, dll).
7.  Proses berhenti sampai pelanggan memperbaiki data.

**b. Login**
> **Sequence: Login**
> 1.  **Pengguna -> Sistem:** Klik login.
> 2.  **Sistem -> Pengguna:** Menampilkan halaman login.
> 3.  **Pengguna -> Sistem:** Mengisi halaman login, lalu klik login.
> 4.  **Sistem -> Database:** Memproses data (validasi).
> 5.  **Database -> Sistem:** [Valid] Menampilkan halaman utama.
> 6.  **Sistem -> Pengguna:** Menampilkan halaman utama.
> 7.  **Database -> Sistem:** [Invalid] Password salah.
> 8.  **Sistem -> Pengguna:** Password salah.

**Keterangan :**
1.  Pengguna mengklik tombol login pada sistem, lalu Sistem menampilkan halaman login yang berisi form untuk username dan password.
2.  Pengguna memasukkan username dan password, lalu menekan tombol login.
3.  Sistem mengirim data ke database untuk divalidasi.
4.  Sistem mengecek apakah username dan password sesuai dengan yang tersimpan di database.
5.  Jika data valid, Sistem menampilkan halaman utama sesuai dengan hak akses pengguna.
6.  Pengguna berhasil login.
7.  Jika data tidak valid, Sistem menampilkan pesan “Password salah” kepada pengguna. Pengguna diminta mengulangi proses login.

**c. Pesan tiket**
> **Sequence: Pesan Tiket**
> 1.  **Pelanggan -> Sistem:** Pilih menu pesan tiket.
> 2.  **Sistem -> Pelanggan:** Tampil menu pesan tiket.
> 3.  **Pelanggan -> Sistem:** Cari Bus (Masukkan rute/tanggal).
> 4.  **Sistem -> Pelanggan:** Tampil daftar bus yang tersedia.
> 5.  **Pelanggan -> Sistem:** Pilih bus.
> 6.  **Sistem -> Pelanggan:** Tampilkan denah kursi dan jadwal.
> 7.  **Pelanggan -> Sistem:** Pilih kursi + Konfirmasi jadwal, lalu Klik simpan.
> 8.  **Sistem -> Database:** Proses Simpan.
> 9.  **Sistem -> Pelanggan:** Menampilkan pesan booking sukses.

**Keterangan :**
1.  Pelanggan mengakses sistem dan memilih menu pesan tiket, lalu Sistem merespons dengan menampilkan halaman pemesanan tiket.
2.  Pelanggan memasukkan rute dan tanggal keberangkatan untuk mencari bus yang tersedia, lalu Sistem kemudian menampilkan daftar bus yang sesuai dengan kriteria pencarian tersebut.
3.  Setelah melihat daftar bus yang tersedia, admin melakukan pemilihan bus yang diinginkan, lalu Sistem kemudian menampilkan denah kursi dan jadwal keberangkatan untuk bus yang dipilih tersebut.
4.  Pelanggan memilih kursi yang tersedia serta mengonfirmasi jadwal keberangkatan, lalu mengklik tombol simpan.
5.  Sistem melakukan proses penyimpanan data pemesanan ke database.

**d. Pembayaran**
> **Sequence: Pembayaran**
> 1.  **Pelanggan -> Sistem:** Pilih menu pembayaran.
> 2.  **Sistem -> Pelanggan:** Tampil form pembayaran.
> 3.  **Pelanggan -> Sistem:** Upload bukti pembayaran.
> 4.  **Sistem -> Database:** Proses data pembayaran.
> 5.  **Sistem -> Pelanggan:** Tampil pesan pembayaran sukses dan E-Tiket.

**Keterangan :**
1.  Proses diawali oleh Pelanggan yang mengakses sistem dan memilih menu pembayaran, lalu Sistem merespons dengan menampilkan form pembayaran, yang berisi kolom atau tombol untuk mengunggah bukti pembayaran.
2.  Pelanggan melakukan unggah (upload) bukti pembayaran, misalnya berupa file gambar atau PDF bukti transfer, lalu Bukti ini dikirimkan ke sistem untuk diproses.
3.  Sistem menerima bukti pembayaran dan kemudian melakukan pemrosesan data, lalu Sistem menyimpan data tersebut ke dalam database, sekaligus melakukan validasi jika diperlukan (misalnya mengecek apakah jumlah dan jadwal cocok dengan pemesanan sebelumnya).
4.  Setelah data berhasil diproses, sistem menampilkan pesan bahwa pembayaran berhasil, dan juga menghasilkan serta menampilkan E-Tiket kepada admin.

**2. Admin**

**a. Kelola jadwal dan tiket**
> **Sequence: Kelola Jadwal**
> 1.  **Admin -> Sistem:** Pilih menu kelola jadwal dan tiket.
> 2.  **Sistem -> Admin:** Tampil menu kelola jadwal dan tiket.
> 3.  **Admin -> Sistem:** Klik tambah jadwal.
> 4.  **Sistem -> Admin:** Menampilkan form tambah jadwal.
> 5.  **Admin -> Sistem:** Input jadwal, Klik simpan.
> 6.  **Sistem -> Database:** Proses simpan.
> 7.  **Admin -> Sistem:** Klik edit jadwal.
> 8.  **Sistem -> Admin:** Menampilkan halaman edit jadwal.
> 9.  **Admin -> Sistem:** Ubah data dan Klik simpan.
> 10. **Sistem -> Database:** Proses Simpan.
> 11. **Admin -> Sistem:** Klik hapus.
> 12. **Sistem -> Database:** Proses hapus.

**Keterangan :**
1.  **Admin Memilih Menu** : Admin memilih menu untuk mengelola jadwal dan tiket.
2.  **Sistem Menampilkan Menu** : Sistem menampilkan menu pengelolaan jadwal dan tiket kepada admin.
3.  **Admin Menambahkan Jadwal** : Admin mengklik tombol "Tambah Jadwal".
4.  **Sistem Menampilkan Form** : Sistem menampilkan form untuk menambahkan jadwal baru.
5.  **Admin Memasukkan Data dan Menyimpan** : Admin memasukkan data jadwal dan mengklik tombol "Simpan".
6.  **Sistem Menyimpan Data ke Database** : Sistem memproses penyimpanan data jadwal ke database.
7.  **Admin Mengedit Jadwal** : Admin mengklik tombol "Edit Jadwal".
8.  **Sistem Menampilkan Halaman Edit** : Sistem menampilkan halaman untuk mengedit jadwal yang dipilih.
9.  **Admin Mengubah Data dan Menyimpan** : Admin mengubah data jadwal dan mengklik tombol "Simpan".
10. **Sistem Menyimpan Perubahan ke Database** : Sistem memproses penyimpanan perubahan data ke database.
11. **Admin Menghapus Jadwal** : Admin mengklik tombol "Hapus".
12. **Sistem Menghapus Data dari Database**: Sistem memproses penghapusan data jadwal dari database.

**b. Konfirmasi pembayaran**
> **Sequence: Konfirmasi Pembayaran**
> 1.  **Admin -> Sistem:** Klik menu booking.
> 2.  **Sistem -> Admin:** Menampilkan halaman pembayaran "pending".
> 3.  **Admin -> Sistem:** Konfirmasi pembayaran.
> 4.  **Sistem -> Pelanggan:** Mengirim pesan pembayaran sukses.
> 5.  **Admin -> Sistem:** Menerbitkan E-tiket.

**Keterangan:**
1.  **Admin mengklik menu booking** : Proses dimulai saat admin mengakses menu pemesanan tiket.
2.  **Sistem menampilkan halaman pembayaran "pending"** : Sistem merespon dengan menampilkan halaman pembayaran, dan status pembayaran sementara ditandai sebagai "pending".
3.  **Admin mengkonfirmasi pembayaran** : Admin mengkonfirmasi bahwa pembayaran telah dilakukan.
4.  **Sistem mengirimkan pesan pembayaran sukses ke pelanggan** : Setelah konfirmasi, sistem memberitahu pelanggan bahwa pembayaran telah berhasil.
5.  **Sistem menerbitkan E-tiket ke Admin** : Sistem kemudian menerbitkan E-tiket dan memberikannya kepada Admin.

**3. Petugas loket**

**a. Verifikasi tiket**
> **Sequence: Verifikasi Tiket**
> 1.  **Petugas loket -> Sistem:** Klik menu verifikasi tiket.
> 2.  **Sistem -> Petugas loket:** Menampilkan halaman verifikasi tiket.
> 3.  **Petugas loket -> Sistem:** Scan code booking/ masukkan code secara manual.
> 4.  **Sistem -> Database:** Memproses data.
> 5.  **Database -> Sistem:** [Valid] Update status ticket ke "Digunakan".
> 6.  **Sistem -> Petugas loket:** Update status tiket ke "Digunakan".
> 7.  **Database -> Sistem:** [Invalid] Tampilkan pesan tiket tidak valid.
> 8.  **Sistem -> Petugas loket:** Tampilkan pesan tiket tidak valid.

**Keterangan:**
1.  **Petugas loket mengklik menu verifikasi tiket** : Proses dimulai ketika petugas loket mengakses menu verifikasi tiket pada sistem.
2.  **Sistem menampilkan halaman verifikasi tiket** : Sistem merespon dengan menampilkan antarmuka untuk memasukkan atau memindai kode booking tiket.
3.  **Petugas loket melakukan scan kode booking atau memasukkan kode secara manual** : Petugas kemudian memindai kode QR/barcode pada tiket atau memasukkan kode booking secara manual ke dalam sistem.
4.  **Sistem memproses data**: Sistem mengirimkan kode booking ke database untuk verifikasi.
5.  **Database memvalidasi kode booking**: Database memeriksa apakah kode booking valid dan belum digunakan.
6.  **Jika kode booking valid** :
    *   a. Database mengupdate status tiket menjadi "Digunakan".
    *   b. Sistem menginformasikan kepada petugas loket bahwa tiket valid.
7.  **Jika kode booking tidak valid** : Sistem menginformasikan kepada petugas loket bahwa tiket tidak valid (misalnya, karena sudah digunakan, kode salah, atau tiket tidak ditemukan).

**b. Data pelanggan**
> **Sequence: Data Pelanggan**
> 1.  **Petugas Loket -> Sistem:** Klik menu data pelanggan.
> 2.  **Sistem -> Petugas Loket:** Menampilkan halaman data pelanggan.
> 3.  **Petugas Loket -> Sistem:** Cari berdasarkan tanggal/nama/event pada kolom pencarian.
> 4.  **Sistem -> Database:** [Request Data]
> 5.  **Database -> Sistem:** [Ada] Menampilkan data.
> 6.  **Sistem -> Petugas Loket:** Menampilkan data.
> 7.  **Database -> Sistem:** [Tidak ada] Menampilkan pesan error.
> 8.  **Sistem -> Petugas Loket:** Menampilkan pesan error.

**Keterangan :**
1.  Proses dimulai ketika Petugas loket mengakses menu untuk melihat data pelanggan.
2.  Sistem menampilkan halaman data pelanggan: Sistem merespon dengan menampilkan halaman yang memungkinkan pencarian data.
3.  Petugas loket memasukkan kriteria pencarian, seperti tanggal, nama, atau event, ke dalam kolom pencarian.
4.  Sistem memproses data dan mengirim permintaan ke Database : Sistem mengirimkan kriteria pencarian ke database untuk mengambil data yang sesuai.
5.  Database mencari dan mengembalikan data yang sesuai dengan kriteria pencarian.
6.  Jika data ditemukan (valid) :
    *   a. Database mengirimkan data kembali ke sistem.
    *   b. Sistem menampilkan data dalam bentuk tabel pada halaman.
7.  Jika data tidak ditemukan (invalid) :
    *   a. Database mengirimkan pesan error ke sistem.
    *   b. Sistem menampilkan pesan error kepada admin.
8.  Petugas loket mengklik tombol download: Setelah data ditampilkan, admin dapat mengunduh data tersebut.
9.  Sistem melakukan proses download dan menampilkan pesan sukses: Sistem memproses unduhan dan memberikan konfirmasi sukses kepada Petugas loket.

**c. Log-out**
> **Sequence: Log-out**
> 1.  **User -> Sistem:** Klik akun.
> 2.  **Sistem -> User:** Menampilkan halaman pengguna.
> 3.  **User -> Sistem:** Klik log-out.
> 4.  **Sistem -> User:** Menampilkan pesan konfirmasi "Log-out".
> 5.  **User -> Sistem:** Klik ya/tidak.
> 6.  **Sistem -> Database:** [Jika Ya] Hapus system data.
> 7.  **Sistem -> User:** [Jika Ya] Redirect ke halaman login.
> 8.  **Sistem -> User:** [Jika Tidak] Kembali ke halaman pengguna.

**Keterangan:**
1.  **Admin/Petugas Loket/Pelanggan mengklik akun** : Proses dimulai ketika pengguna mengakses menu akun.
2.  **Sistem menampilkan halaman pengguna** : Sistem menampilkan halaman profil pengguna.
3.  **Pengguna mengklik log-out**: Pengguna memilih untuk keluar dari sistem.
4.  **Sistem menampilkan pesan konfirmasi "Log-out"** : Sistem menampilkan pesan konfirmasi untuk memastikan pengguna ingin log out.
5.  **Pengguna memilih Ya/Tidak** : Pengguna memilih untuk melanjutkan log out ("Ya") atau membatalkan ("Tidak").
6.  **Jika pengguna memilih "Ya"**:
    *   a. Sistem menghapus data sesi pengguna.
    *   b. Sistem mengarahkan pengguna kembali ke halaman login.
7.  **Jika pengguna memilih "Tidak"** :
    *   a. Sistem tetap menampilkan halaman pengguna.

---

### F. Class Diagram

> **Deskripsi Class Diagram:**
>
> **Class: Pelanggan/Petugas Loket/Admin (User Base Class)**
> *   **Attributes:**
>     *   `+ id_user: string`
>     *   `+ username: string`
>     *   `+ password: string`
>     *   `+ email: string`
>     *   `+ no_telp: string`
> *   **Methods:**
>     *   `+ login()`
>     *   `+ logout()`
>
> **Class: Pelanggan (Inherits from User)**
> *   **Attributes:**
>     *   `+ alamat: string`
> *   **Methods:**
>     *   `+ registrasi()`
>     *   `+ pesanTiket()`
>     *   `+ bayarOnline()`
>     *   `+ lihatRiwayat()`
>
> **Class: Petugas Loket (Inherits from User)**
> *   **Methods:**
>     *   `+ verifikasiTiket()`
>     *   `+ terimaPembayaranOffline()`
>     *   `+ lihatDataPengunjung()`
>
> **Class: Admin (Inherits from User)**
> *   **Methods:**
>     *   `+ kelolaJadwal()`
>     *   `+ kelolaTiket()`
>     *   `+ konfirmasiPembayaran()`
>     *   `+ generateLaporan()`
>
> **Class: Pemesanan**
> *   **Attributes:**
>     *   `+ id_pemesan: string`
>     *   `+ tgl_pesan: datetime`
>     *   `+ status: enum [pending, paid, cancel]`
>     *   `+ total_harga: decimal`
> *   **Methods:**
>     *   `+ konfirmasiPembayaran()`
>
> **Class: Tiket**
> *   **Attributes:**
>     *   `+ id_tiket: string`
>     *   `+ kode_booking: string`
>     *   `+ nomor_kursi: string`
>     *   `+ status: enum [available, booked, used]`
>
> **Class: Pembayaran**
> *   **Attributes:**
>     *   `+ id_pembayaran: string`
>     *   `+ metode: enum [transfer, tunai]`
>     *   `+ jumlah: decimal`
>     *   `+ status: enum [pending, success, failed]`
>     *   `+ waktu_pembayaran: datetime`
>
> **Class: Jadwal**
> *   **Attributes:**
>     *   `+ id_jadwal: string`
>     *   `+ waktu_berangkat: datetime`
>     *   `+ waktu_tiba: datetime`
>     *   `+ harga: decimal`
>
> **Class: Bus**
> *   **Attributes:**
>     *   `+ id_bus: string`
>     *   `+ nama_po: string`
>     *   `+ kelas: string`
>     *   `+ kapasitas: integer`
>
> **Class: Laporan**
> *   **Attributes:**
>     *   `+ id_laporan: string`
>     *   `+ periode: string`
>     *   `+ total_penjualan: decimal`
> *   **Methods:**
>     *   `+ generate()`
>
> **Relationships:**
> *   Pelanggan `1` -- `0..*` Pemesanan
> *   Pemesanan `1` -- `1..*` Tiket
> *   Pemesanan `1` -- `1` Pembayaran
> *   Jadwal `1` -- `1` Bus
> *   Tiket `1` -- `1` Jadwal
> *   Admin `1` -- `1..*` Laporan
> *   The User base class has associations with Pelanggan, Petugas Loket, and Admin (inheritance).