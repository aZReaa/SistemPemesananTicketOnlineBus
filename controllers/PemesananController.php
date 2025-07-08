<?php
// Session sudah dimulai di index.php

class PemesananController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Menampilkan form pemesanan tiket.
     */
    public function showBookingForm() {
        $id_jadwal = $_GET['id_jadwal'] ?? 0;
        
        if (!$id_jadwal) {
            header('Location: index.php?page=home&error=nojadwal');
            exit;
        }

        require_once __DIR__ . '/../models/Jadwal.php';
        require_once __DIR__ . '/../models/Tiket.php';
        
        $jadwalModel = new Jadwal($this->pdo);
        $tiketModel = new Tiket($this->pdo);
        
        $jadwal = $jadwalModel->findById($id_jadwal);
        if (!$jadwal) {
            header('Location: index.php?page=home&error=notfound');
            exit;
        }
        
        $availableSeats = $tiketModel->getAvailableSeats($id_jadwal);
        
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/jadwal/select_seat.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Konfirmasi pembayaran (method sesuai UML)
     */
    public function konfirmasiPembayaran() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login&error=unauthorized');
            exit;
        }

        $id_pemesanan = $_GET['id'] ?? 0;
        $action = $_GET['action'] ?? '';

        if (!$id_pemesanan || !in_array($action, ['approve', 'reject'])) {
            header('Location: index.php?page=admin_manage_confirmations&error=invalid');
            exit;
        }

        require_once __DIR__ . '/../models/Pemesanan.php';
        require_once __DIR__ . '/../models/Pembayaran.php';

        $pemesananModel = new Pemesanan($this->pdo);
        $pembayaranModel = new Pembayaran($this->pdo);

        if ($action === 'approve') {
            $pembayaranModel->approve($id_pemesanan);
            $pemesananModel->updateStatus($id_pemesanan, 'paid');
            header('Location: index.php?page=admin_manage_confirmations&success=approved');
        } else {
            $pembayaranModel->reject($id_pemesanan);
            $pemesananModel->updateStatus($id_pemesanan, 'failed');
            header('Location: index.php?page=admin_manage_confirmations&success=rejected');
        }
        exit;
    }

    /**
     * Menampilkan detail pemesanan.
     */
    public function showDetail() {
        $id_pemesanan = $_GET['id'] ?? 0;
        
        require_once __DIR__ . '/../models/Pemesanan.php';
        $pemesananModel = new Pemesanan($this->pdo);
        
        $pemesanan = $pemesananModel->getDetailById($id_pemesanan);
        
        if (!$pemesanan) {
            header('Location: index.php?page=home&error=notfound');
            exit;
        }
        
        // Tentukan header berdasarkan role
        $headerFile = '../views/layout/header.php';
        if (isset($_SESSION['role'])) {
            if ($_SESSION['role'] === 'admin') {
                $headerFile = '../views/layout/admin_header.php';
            } elseif ($_SESSION['role'] === 'petugas') {
                $headerFile = '../views/layout/petugas_header.php';
            } elseif ($_SESSION['role'] === 'pelanggan') {
                $headerFile = '../views/layout/pelanggan_header.php';
            }
        }
        
        require_once __DIR__ . '/' . $headerFile;
        require_once __DIR__ . '/../views/pemesanan/detail.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }
}
}
?>
