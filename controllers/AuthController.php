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
        // Load registrasi page directly (no header/footer)
        require_once __DIR__ . '/../views/auth/registrasi.php';
    }

    /**
     * Memproses data dari formulir registrasi.
     */
    public function processRegistration() {
        // Memastikan request adalah POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validasi sederhana
            if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm_password'])) {
                $_SESSION['error_message'] = "Semua field wajib diisi.";
                $this->showRegistrationForm();
                return;
            }
            
            if ($_POST['password'] !== $_POST['confirm_password']) {
                $_SESSION['error_message'] = "Password dan konfirmasi password tidak cocok.";
                $this->showRegistrationForm();
                return;
            }

            // Validasi format email
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error_message'] = "Format email tidak valid.";
                $this->showRegistrationForm();
                return;
            }

            // Validasi panjang password
            if (strlen($_POST['password']) < 6) {
                $_SESSION['error_message'] = "Password minimal 6 karakter.";
                $this->showRegistrationForm();
                return;
            }

            // Load model Pelanggan
            require_once __DIR__ . '/../models/Pelanggan.php';
            
            try {
                $pelanggan = new Pelanggan($this->pdo);

                // Menyiapkan data untuk disimpan
                $data = [
                    'username' => trim($_POST['username']),
                    'email' => trim($_POST['email']),
                    'password' => $_POST['password'], // Password akan di-hash di model
                    'no_telp' => trim($_POST['no_telp'] ?? ''),
                    'alamat' => trim($_POST['alamat'] ?? '')
                ];

                // Debug: Log attempt
                error_log("Registration attempt for: " . $data['username'] . " (" . $data['email'] . ")");

                // Memanggil metode create dari model
                $result = $pelanggan->create($data);
                
                if ($result === true) {
                    // Jika berhasil, arahkan ke halaman login dengan pesan sukses
                    $_SESSION['success_message'] = "Registrasi berhasil! Selamat datang, " . $data['username'] . ". Silakan login dengan akun baru Anda.";
                    header('Location: index.php?page=login');
                    exit;
                } else {
                    // Debug: Log failure
                    error_log("Registration failed for: " . $data['username'] . " - Result: " . var_export($result, true));
                    
                    // Cek specific error
                    if ($result === 'duplicate') {
                        $_SESSION['error_message'] = "Username atau email sudah digunakan. Silakan gunakan yang lain.";
                    } else {
                        $_SESSION['error_message'] = "Registrasi gagal. Silakan coba lagi atau hubungi administrator.";
                    }
                    $this->showRegistrationForm();
                }
            } catch (Exception $e) {
                // Debug: Log exception
                error_log("Registration exception: " . $e->getMessage());
                
                $_SESSION['error_message'] = "Terjadi kesalahan sistem. Silakan coba lagi. (" . $e->getMessage() . ")";
                $this->showRegistrationForm();
            }
        } else {
            // Jika bukan POST, redirect ke form registrasi
            $this->showRegistrationForm();
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
            // Debug: Log the POST data
            error_log("Login attempt with POST data: " . print_r($_POST, true));
            
            if (empty($_POST['username']) || empty($_POST['password'])) {
                $_SESSION['error_message'] = "Username/email dan password tidak boleh kosong.";
                $this->showLoginForm();
                return;
            }

            $userModel = new User($this->pdo);
            $user = $userModel->login($_POST['username'], $_POST['password']);

            if ($user) {
                // Login berhasil, regenerasi ID sesi untuk keamanan
                session_regenerate_id(true);

                // Simpan data pengguna ke sesi
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Debug: Log successful login
                error_log("Login successful for user: " . $user['username'] . " with role: " . $user['role']);

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
                error_log("Login failed for username: " . $_POST['username']);
                $_SESSION['error_message'] = "Password salah atau pengguna tidak ditemukan.";
                $this->showLoginForm();
            }
        } else {
            $this->showLoginForm();
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
