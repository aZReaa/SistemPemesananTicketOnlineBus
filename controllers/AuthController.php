<?php

// File: controllers/AuthController.php
// Deskripsi: Controller untuk menangani otentikasi (registrasi, login, logout).

class AuthController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Menampilkan halaman formulir registrasi.
     */
    public function showRegistrationForm() {
        // Memuat file view untuk formulir registrasi
        require_once __DIR__ . '/../views/auth/registrasi.php';
    }

    /**
     * Memproses data dari formulir registrasi.
     */
    public function register() {
        // Memastikan request adalah POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validasi sederhana (bisa diperketat)
            if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']) || $_POST['password'] !== $_POST['confirm_password']) {
                // Jika validasi gagal, kembalikan ke form dengan pesan error
                $error = "Data tidak lengkap atau password tidak cocok.";
                require_once __DIR__ . '/../views/auth/registrasi.php';
                return;
            }

            // Membuat instance dari model Pelanggan
            $pelanggan = new Pelanggan($this->pdo);

            // Menyiapkan data untuk disimpan
            $data = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => $_POST['password'], // Password harus di-hash sebelum disimpan
                'no_telp' => $_POST['no_telp'],
                'alamat' => $_POST['alamat']
            ];

            // Memanggil metode registrasi dari model
            if ($pelanggan->registrasi($data)) {
                // Jika berhasil, arahkan ke halaman login atau halaman sukses
                echo "Registrasi berhasil! Silakan login.";
                // header('Location: index.php?page=login');
            } else {
                // Jika gagal (misal: username/email sudah ada), tampilkan error
                $error = "Registrasi gagal. Username atau email mungkin sudah digunakan.";
                require_once __DIR__ . '/../views/auth/registrasi.php';
            }
        }
    }

    /**
     * Menampilkan halaman formulir login.
     */
    public function showLoginForm() {
        require_once __DIR__ . '/../views/auth/login.php';
    }

    /**
     * Memproses data dari formulir login.
     */
    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['username_or_email']) || empty($_POST['password'])) {
                $error = "Username/email dan password tidak boleh kosong.";
                require_once __DIR__ . '/../views/auth/login.php';
                return;
            }

            $userModel = new User($this->pdo);
            $user = $userModel->login($_POST['username_or_email'], $_POST['password']);

            if ($user) {
                // Login berhasil, regenerasi ID sesi untuk keamanan
                session_regenerate_id(true);

                // Simpan data pengguna ke sesi
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Arahkan pengguna berdasarkan peran
                switch ($user['role']) {
                    case 'admin':
                        header('Location: index.php?page=admin_dashboard');
                        break;
                    case 'petugas_loket':
                        header('Location: index.php?page=petugas_dashboard');
                        break;
                    default: // pelanggan
                        header('Location: index.php?page=pelanggan_dashboard');
                        break;
                }
                exit;
            } else {
                // Login gagal
                $error = "Password salah atau pengguna tidak ditemukan.";
                require_once __DIR__ . '/../views/auth/login.php';
            }
        }
    }

    /**
     * Memproses logout pengguna.
     */
    public function logout() {
        // Hapus semua variabel sesi
        $_SESSION = array();

        // Hancurkan sesi
        session_destroy();

        // Arahkan ke halaman login dengan pesan
        header('Location: index.php?page=login&success=logout');
        exit;
    }
}

?>
