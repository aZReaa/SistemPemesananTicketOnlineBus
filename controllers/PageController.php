<?php

// File: controllers/PageController.php
// Deskripsi: Controller untuk menangani halaman-halaman umum.

class PageController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Menampilkan halaman utama (dashboard) setelah login.
     */
    public function showHome() {
        // Memeriksa apakah pengguna sudah login
        if (!isset($_SESSION['user_id'])) {
            // Jika belum, arahkan ke halaman login
            header('Location: index.php?page=login');
            exit;
        }

        // Jika sudah login, arahkan ke dashboard sesuai role
        switch ($_SESSION['role']) {
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
    }
}

?>
