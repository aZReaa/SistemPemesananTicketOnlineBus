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
     * @return bool|string True jika registrasi berhasil, 'duplicate' jika username/email sudah ada, false jika gagal.
     */
    public function registrasi($data) {
        try {
            // 1. Hash password untuk keamanan
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

            // 2. Mulai transaksi database
            $this->pdo->beginTransaction();

            // 3. Cek apakah username atau email sudah ada
            $stmtCheck = $this->pdo->prepare("SELECT id_user, username, email FROM users WHERE username = ? OR email = ?");
            $stmtCheck->execute([$data['username'], $data['email']]);
            $existing = $stmtCheck->fetch();
            
            if ($existing) {
                $this->pdo->rollBack();
                // Debug log
                error_log("Registration failed - duplicate found: " . $existing['username'] . " / " . $existing['email']);
                return 'duplicate'; // Specific return value for duplicate
            }

            // 4. Sisipkan data ke tabel 'users'
            $stmtUser = $this->pdo->prepare(
                "INSERT INTO users (username, email, password, no_telp, role) VALUES (?, ?, ?, ?, ?)"
            );
            $userResult = $stmtUser->execute([
                $data['username'],
                $data['email'],
                $hashedPassword,
                $data['no_telp'],
                $this->role
            ]);

            if (!$userResult) {
                $this->pdo->rollBack();
                error_log("Registration failed - user insert failed");
                return false;
            }

            // 5. Dapatkan ID user yang baru saja dibuat
            $id_user = $this->pdo->lastInsertId();

            if (!$id_user) {
                $this->pdo->rollBack();
                error_log("Registration failed - no user ID returned");
                return false;
            }

            // 6. Sisipkan data ke tabel 'pelanggan'
            $stmtPelanggan = $this->pdo->prepare(
                "INSERT INTO pelanggan (id_pelanggan, alamat) VALUES (?, ?)"
            );
            $pelangganResult = $stmtPelanggan->execute([$id_user, $data['alamat'] ?? '']);

            if (!$pelangganResult) {
                $this->pdo->rollBack();
                error_log("Registration failed - pelanggan insert failed");
                return false;
            }

            // 7. Jika semua berhasil, commit transaksi
            $this->pdo->commit();
            
            // Debug log success
            error_log("Registration successful for user ID: " . $id_user);
            return true;

        } catch (PDOException $e) {
            // 8. Jika terjadi error, batalkan transaksi (rollback)
            $this->pdo->rollBack();
            
            // Enhanced error logging
            error_log("Registration PDO error: " . $e->getMessage() . " | Code: " . $e->getCode());
            
            // Check for specific error types
            if ($e->getCode() == 23000) { // Integrity constraint violation
                if (strpos($e->getMessage(), 'username') !== false || strpos($e->getMessage(), 'email') !== false) {
                    return 'duplicate';
                }
            }
            
            return false;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Registration general error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Alias untuk method registrasi untuk kompatibilitas dengan AuthController
     * @param array $data Data registrasi (username, password, email, no_telp, alamat).
     * @return bool True jika registrasi berhasil, false jika gagal.
     */
    public function create($data) {
        return $this->registrasi($data);
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
