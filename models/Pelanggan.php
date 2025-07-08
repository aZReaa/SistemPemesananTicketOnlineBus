<?php

// File: models/Pelanggan.php
// Deskripsi: Kelas untuk entitas Pelanggan, turunan dari User.

require_once 'User.php';

class Pelanggan extends User {
    // Properti tambahan khusus untuk Pelanggan
    private $alamat;

    /**
     * Constructor untuk inisialisasi.
     * @param PDO $pdo Objek koneksi database.
     */
    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->role = 'pelanggan';
    }

    /**
     * Metode untuk mendaftarkan pelanggan baru.
     * @param array $data Data registrasi (username, password, email, no_telp, alamat).
     * @return bool True jika registrasi berhasil, false jika gagal.
     */
    public function registrasi($data) {
        // 1. Hash password untuk keamanan
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        // 2. Mulai transaksi database
        $this->pdo->beginTransaction();

        try {
            // 3. Cek apakah username atau email sudah ada
            $stmtCheck = $this->pdo->prepare("SELECT id_user FROM users WHERE username = ? OR email = ?");
            $stmtCheck->execute([$data['username'], $data['email']]);
            if ($stmtCheck->fetch()) {
                $this->pdo->rollBack();
                return false; // User sudah ada
            }

            // 4. Sisipkan data ke tabel 'users'
            $stmtUser = $this->pdo->prepare(
                "INSERT INTO users (username, email, password, no_telp, role) VALUES (?, ?, ?, ?, ?)"
            );
            $stmtUser->execute([
                $data['username'],
                $data['email'],
                $hashedPassword,
                $data['no_telp'],
                $this->role
            ]);

            // 5. Dapatkan ID user yang baru saja dibuat
            $id_user = $this->pdo->lastInsertId();

            // 6. Sisipkan data ke tabel 'pelanggan'
            $stmtPelanggan = $this->pdo->prepare(
                "INSERT INTO pelanggan (id_pelanggan, alamat) VALUES (?, ?)"
            );
            $stmtPelanggan->execute([$id_user, $data['alamat']]);

            // 7. Jika semua berhasil, commit transaksi
            $this->pdo->commit();
            return true;

        } catch (PDOException $e) {
            // 8. Jika terjadi error, batalkan transaksi (rollback)
            $this->pdo->rollBack();
            // Sebaiknya catat error ini ke log, bukan menampilkannya langsung
            // error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Metode untuk memesan tiket.
     */
    public function pesanTiket() {
        // Logika untuk proses pemesanan tiket.
        // ... implementasi menyusul ...
    }

    /**
     * Metode untuk melakukan pembayaran online (mengunggah bukti).
     */
    public function bayarOnline() {
        // Logika untuk mengunggah bukti pembayaran.
        // ... implementasi menyusul ...
    }

    /**
     * Metode untuk melihat riwayat pemesanan.
     * @return array Riwayat pemesanan.
     */
    public function lihatRiwayat() {
        // Logika untuk mengambil data riwayat pemesanan dari database.
        // ... implementasi menyusul ...
        return [];
    }

    // Getter dan Setter untuk alamat
    public function setAlamat($alamat) {
        $this->alamat = $alamat;
    }

    public function getAlamat() {
        return $this->alamat;
    }
}

?>
