<?php

// File: controllers/JadwalController.php
// Deskripsi: Controller untuk menangani logika terkait jadwal bus.

require_once __DIR__ . '/../models/Jadwal.php';

class JadwalController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Mencari dan menampilkan jadwal bus berdasarkan kriteria.
     */
    public function search() {
        // Memastikan pengguna sudah login
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $jadwalList = [];
        $kotaList = [];
        
        // Mengambil daftar kota untuk dropdown
        $jadwalModel = new Jadwal($this->pdo);
        $kotaList = $jadwalModel->getAllCities();
        
        // Support both GET and POST methods
        $method = $_SERVER['REQUEST_METHOD'];
        $kotaAsal = '';
        $kotaTujuan = '';
        $tanggal = '';
        
        if ($method === 'GET') {
            $kotaAsal = $_GET['kota_asal'] ?? '';
            $kotaTujuan = $_GET['kota_tujuan'] ?? '';
            $tanggal = $_GET['tanggal_keberangkatan'] ?? '';
        } elseif ($method === 'POST') {
            $kotaAsal = $_POST['kota_asal'] ?? '';
            $kotaTujuan = $_POST['kota_tujuan'] ?? '';
            $tanggal = $_POST['tanggal'] ?? '';
        }

        // Mencari jadwal berdasarkan rute dan tanggal
        if (!empty($kotaAsal) && !empty($kotaTujuan)) {
            $jadwalList = $jadwalModel->searchByRoute($kotaAsal, $kotaTujuan, $tanggal);
        } elseif (!empty($tanggal)) {
            $jadwalList = $jadwalModel->searchByDate($tanggal);
        }

        // Set template variables
        $pageTitle = 'Hasil Pencarian - Sistem Tiket Bus';
        $userRole = $_SESSION['role'] ?? 'pelanggan';
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => 'index.php?page=pelanggan_dashboard'],
            ['text' => 'Cari Jadwal', 'href' => 'index.php?page=search_schedule'],
            ['text' => 'Hasil Pencarian', 'href' => '#']
        ];

        // Menampilkan hasil pencarian
        require_once __DIR__ . '/../views/layout/unified_header.php';
        require_once __DIR__ . '/../views/jadwal/search_results.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Menampilkan halaman pemilihan kursi untuk jadwal tertentu.
     */
    public function showSeatSelection() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $id_jadwal = $_GET['id_jadwal'] ?? 0;
        
        if (!$id_jadwal) {
            header('Location: index.php?page=pelanggan_dashboard&error=nojadwal');
            exit;
        }

        // Mengambil data dari model
        require_once __DIR__ . '/../models/Tiket.php';
        $jadwalModel = new Jadwal($this->pdo);
        $tiketModel = new Tiket($this->pdo);

        $jadwal = $jadwalModel->findById($id_jadwal);
        
        if (!$jadwal) {
            // Set template variables untuk error page
            $pageTitle = 'Jadwal Tidak Ditemukan - Sistem Tiket Bus';
            $userRole = $_SESSION['role'] ?? 'pelanggan';
            $breadcrumbs = [
                ['text' => 'Dashboard', 'href' => 'index.php?page=pelanggan_dashboard'],
                ['text' => 'Error', 'href' => '#']
            ];
            
            require_once __DIR__ . '/../views/layout/unified_header.php';
            echo '<div class="container"><div class="alert alert-danger">Jadwal tidak ditemukan. ID: ' . htmlspecialchars($id_jadwal) . '</div></div>';
            require_once __DIR__ . '/../views/layout/footer.php';
            exit;
        }

        $booked_seats = $tiketModel->getBookedSeatsByJadwal($id_jadwal);

        // Set template variables
        $pageTitle = 'Pilih Kursi - Sistem Tiket Bus';
        $userRole = $_SESSION['role'] ?? 'pelanggan';
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => 'index.php?page=pelanggan_dashboard'],
            ['text' => 'Cari Jadwal', 'href' => 'index.php?page=search_schedule'],
            ['text' => 'Pilih Kursi', 'href' => '#']
        ];

        require_once __DIR__ . '/../views/layout/unified_header.php';
        require_once __DIR__ . '/../views/jadwal/select_seat.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Menampilkan form pencarian jadwal
     */
    public function showSearchForm() {
        // Temporary: disable session check for testing
        // if (!isset($_SESSION['user_id'])) {
        //     error_log("User not logged in, redirecting to login");
        //     header('Location: index.php?page=login');
        //     exit;
        // }

        // Mengambil daftar kota untuk dropdown
        $jadwalModel = new Jadwal($this->pdo);
        $kotaList = $jadwalModel->getAllCities();
        
        // Set template variables
        $pageTitle = 'Cari Jadwal - Sistem Tiket Bus';
        $userRole = $_SESSION['role'] ?? 'pelanggan';
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => 'index.php?page=pelanggan_dashboard'],
            ['text' => 'Cari Jadwal', 'href' => '#']
        ];

        // Include view pencarian
        include __DIR__ . '/../views/jadwal/search_form.php';
    }
}

?>
