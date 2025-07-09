<?php
// Session sudah dimulai di index.php

class AdminController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->checkAuth();
    }

    /**
     * Memeriksa apakah pengguna terotentikasi dan merupakan admin.
     * Jika tidak, alihkan ke halaman login.
     */
    private function checkAuth() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login&error=unauthorized');
            exit;
        }
    }

    /**
     * Menampilkan halaman dashboard utama admin.
     * Method ini sesuai dengan UML
     */
    public function showDashboard() {
        // Mengambil data statistik untuk dashboard
        require_once __DIR__ . '/../models/User.php';
        require_once __DIR__ . '/../models/Jadwal.php';
        require_once __DIR__ . '/../models/Pembayaran.php';
        require_once __DIR__ . '/../models/Pemesanan.php';

        $userModel = new User($this->pdo);
        $jadwalModel = new Jadwal($this->pdo);
        $pembayaranModel = new Pembayaran($this->pdo);
        $pemesananModel = new Pemesanan($this->pdo);

        // Data yang sesuai dengan variable di dashboard view
        $totalPemesanan = $pemesananModel->getTotalCount();
        $pendingPayments = $pembayaranModel->getPendingCount();
        $totalRevenue = $pembayaranModel->getTotalRevenue();
        $totalUsers = $userModel->getTotalCount();

        // Set template variables
        $pageTitle = 'Dashboard Admin - Sistem Tiket Bus';
        $userRole = 'admin';
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => '#']
        ];

        require_once __DIR__ . '/../views/layout/unified_header.php';
        require_once __DIR__ . '/../views/admin/dashboard.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Method kelolaJadwal sesuai dengan UML - sekarang termasuk kelola tiket
     */
    public function kelolaJadwal() {
        require_once __DIR__ . '/../models/Jadwal.php';
        require_once __DIR__ . '/../models/Tiket.php';
        
        $jadwalModel = new Jadwal($this->pdo);
        $tiketModel = new Tiket($this->pdo);
        
        $jadwalList = $jadwalModel->getAll();
        
        // Tambahkan informasi kapasitas untuk setiap jadwal
        foreach ($jadwalList as &$jadwal) {
            $jadwal['kapasitas'] = $tiketModel->getKapasitasByJadwal($jadwal['id_jadwal']) ?? 40;
            $jadwal['tiket_terpesan'] = $tiketModel->getBookedCount($jadwal['id_jadwal']);
            $jadwal['tiket_tersedia'] = $jadwal['kapasitas'] - $jadwal['tiket_terpesan'];
        }

        // Set template variables
        $pageTitle = 'Kelola Jadwal & Tiket - Admin';
        $userRole = 'admin';
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => 'index.php?page=admin_dashboard'],
            ['text' => 'Kelola Jadwal & Tiket', 'href' => '#']
        ];

        require_once __DIR__ . '/../views/layout/unified_header.php';
        require_once __DIR__ . '/../views/admin/manage_schedule.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Method kelolaTiket sesuai dengan UML - redirect ke kelolaJadwal untuk konsistensi
     */
    public function kelolaTiket() {
        // Redirect ke kelolaJadwal karena sekarang terintegrasi
        header('Location: index.php?page=admin_schedule#tiket');
        exit;
    }

    /**
     * Method konfirmasiPembayaran sesuai dengan UML
     */
    public function konfirmasiPembayaran() {
        require_once __DIR__ . '/../models/Pembayaran.php';
        $pembayaranModel = new Pembayaran($this->pdo);
        $pendingPayments = $pembayaranModel->getAllPendingWithDetails();

        // Set template variables
        $pageTitle = 'Konfirmasi Pembayaran - Admin';
        $userRole = 'admin';
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => 'index.php?page=admin_dashboard'],
            ['text' => 'Konfirmasi Pembayaran', 'href' => '#']
        ];

        require_once __DIR__ . '/../views/layout/unified_header.php';
        require_once __DIR__ . '/../views/admin/manage_confirmations.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Method generateLaporan sesuai dengan UML
     */
    public function generateLaporan() {
        require_once __DIR__ . '/../models/Laporan.php';
        $laporanModel = new Laporan($this->pdo);
        
        // Generate laporan berdasarkan filter
        $periode = $_GET['periode'] ?? 'bulanan';
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-t');
        
        $laporan = $laporanModel->generate($periode, $startDate, $endDate);
        
        // Ambil data penjualan untuk ditampilkan
        $salesData = $laporanModel->getSalesByDateRange($startDate, $endDate);
        
        // Hitung total penjualan
        $totalSales = 0;
        if (is_array($salesData)) {
            foreach ($salesData as $sale) {
                $totalSales += $sale['jumlah'] ?? 0;
            }
        } else {
            $salesData = [];
        }

        // Set template variables
        $pageTitle = 'Laporan - Admin';
        $userRole = 'admin';
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => 'index.php?page=admin_dashboard'],
            ['text' => 'Laporan', 'href' => '#']
        ];

        require_once __DIR__ . '/../views/layout/unified_header.php';
        require_once __DIR__ . '/../views/admin/reports.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Menampilkan formulir untuk menambah jadwal baru.
     */
    public function showAddScheduleForm() {
        require_once __DIR__ . '/../models/Rute.php';
        $ruteModel = new Rute($this->pdo);
        $ruteList = $ruteModel->getAllActiveRutes();

        // Set template variables
        $pageTitle = 'Tambah Jadwal - Admin';
        $userRole = 'admin';
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => 'index.php?page=admin_dashboard'],
            ['text' => 'Kelola Jadwal', 'href' => 'index.php?page=admin_schedule'],
            ['text' => 'Tambah Jadwal', 'href' => '#']
        ];

        require_once __DIR__ . '/../views/layout/unified_header.php';
        require_once __DIR__ . '/../views/admin/add_schedule.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Menyimpan jadwal baru dari form ke database.
     */
    public function storeSchedule() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Debug: tampilkan data POST
            error_log("POST data: " . print_r($_POST, true));
            
            // Validasi sederhana
            if (empty($_POST['id_rute']) || empty($_POST['waktu_berangkat']) || empty($_POST['waktu_tiba']) || empty($_POST['harga'])) {
                // Handle error - idealnya, kembali ke form dengan pesan error
                error_log("Validation failed - missing fields");
                header('Location: index.php?page=admin_add_schedule&error=incomplete');
                exit;
            }

            $data = [
                'id_rute' => $_POST['id_rute'],
                'waktu_berangkat' => $_POST['waktu_berangkat'],
                'waktu_tiba' => $_POST['waktu_tiba'],
                'harga' => $_POST['harga'],
            ];

            require_once __DIR__ . '/../models/Jadwal.php';
            $jadwalModel = new Jadwal($this->pdo);

            try {
                if ($jadwalModel->store($data)) {
                    // Jika berhasil, redirect ke halaman manajemen jadwal
                    header('Location: index.php?page=admin_schedule&success=added');
                } else {
                    // Jika gagal, redirect kembali dengan pesan error
                    error_log("Jadwal store failed");
                    header('Location: index.php?page=admin_add_schedule&error=failed');
                }
            } catch (Exception $e) {
                error_log("Exception in storeSchedule: " . $e->getMessage());
                header('Location: index.php?page=admin_add_schedule&error=exception&msg=' . urlencode($e->getMessage()));
            }
            exit;
        }
    }

    /**
     * Menampilkan formulir untuk mengedit jadwal yang ada.
     */
    public function showEditScheduleForm() {
        $id_jadwal = $_GET['id'] ?? 0;

        require_once __DIR__ . '/../models/Jadwal.php';
        require_once __DIR__ . '/../models/Rute.php';

        $jadwalModel = new Jadwal($this->pdo);
        $ruteModel = new Rute($this->pdo);

        $jadwal = $jadwalModel->findById($id_jadwal);
        $ruteList = $ruteModel->getAllActiveRutes();

        if (!$jadwal) {
            // Handle error jika jadwal tidak ditemukan
            header('Location: index.php?page=admin_schedule&error=notfound');
            exit;
        }

        // Set template variables
        $pageTitle = 'Edit Jadwal - Admin';
        $userRole = 'admin';
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => 'index.php?page=admin_dashboard'],
            ['text' => 'Kelola Jadwal', 'href' => 'index.php?page=admin_schedule'],
            ['text' => 'Edit Jadwal', 'href' => '#']
        ];

        require_once __DIR__ . '/../views/layout/unified_header.php';
        require_once __DIR__ . '/../views/admin/edit_schedule.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Memperbarui data jadwal dari form edit ke database.
     */
    public function updateSchedule() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validasi sederhana
            if (empty($_POST['id_jadwal']) || empty($_POST['id_rute']) || empty($_POST['waktu_berangkat']) || empty($_POST['waktu_tiba']) || empty($_POST['harga'])) {
                // Kembali ke form dengan pesan error
                header('Location: index.php?page=admin_edit_schedule&id=' . $_POST['id_jadwal'] . '&error=incomplete');
                exit;
            }

            $id_jadwal = $_POST['id_jadwal'];
            $data = [
                'id_rute' => $_POST['id_rute'],
                'waktu_berangkat' => $_POST['waktu_berangkat'],
                'waktu_tiba' => $_POST['waktu_tiba'],
                'harga' => $_POST['harga'],
            ];

            require_once __DIR__ . '/../models/Jadwal.php';
            $jadwalModel = new Jadwal($this->pdo);

            if ($jadwalModel->update($id_jadwal, $data)) {
                // Jika berhasil, redirect ke halaman manajemen jadwal
                header('Location: index.php?page=admin_schedule&success=updated');
            } else {
                // Jika gagal, redirect kembali dengan pesan error
                header('Location: index.php?page=admin_edit_schedule&id=' . $id_jadwal . '&error=failed');
            }
            exit;
        }
    }

    /**
     * Menghapus data jadwal dari database.
     */
    public function deleteSchedule() {
        $id_jadwal = $_GET['id'] ?? 0;

        require_once __DIR__ . '/../models/Jadwal.php';
        $jadwalModel = new Jadwal($this->pdo);

        if ($jadwalModel->delete($id_jadwal)) {
            header('Location: index.php?page=admin_schedule&success=deleted');
        } else {
            header('Location: index.php?page=admin_schedule&error=deletefailed');
        }
        exit;
    }

    /**
     * Menampilkan halaman untuk mengelola konfirmasi pembayaran.
     */
    public function manageConfirmations() {
        require_once __DIR__ . '/../models/Pembayaran.php';
        $pembayaranModel = new Pembayaran($this->pdo);
        $pendingPayments = $pembayaranModel->getAllPendingWithDetails();

        // Set template variables
        $pageTitle = 'Konfirmasi Pembayaran - Admin';
        $userRole = 'admin';
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => 'index.php?page=admin_dashboard'],
            ['text' => 'Konfirmasi Pembayaran', 'href' => '#']
        ];

        require_once __DIR__ . '/../views/layout/unified_header.php';
        require_once __DIR__ . '/../views/admin/manage_confirmations.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Memproses aksi konfirmasi atau penolakan pembayaran.
     */
    public function processConfirmation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_pembayaran = $_POST['id_pembayaran'] ?? 0;
            $action = $_POST['action'] ?? ''; // 'confirm' or 'reject'

            if (empty($id_pembayaran) || empty($action)) {
                header('Location: index.php?page=admin_payments&error=invalidaction');
                exit;
            }

            $new_status = ($action === 'confirm') ? 'success' : 'failed';

            require_once __DIR__ . '/../models/Pembayaran.php';
            $pembayaranModel = new Pembayaran($this->pdo);

            if ($pembayaranModel->updateStatus($id_pembayaran, $new_status)) {
                $message = ($new_status === 'success') ? 'confirmed' : 'rejected';
                header('Location: index.php?page=admin_payments&success=' . $message);
            } else {
                header('Location: index.php?page=admin_payments&error=updatefailed');
            }
            exit;
        }
    }

    /**
     * Menampilkan halaman untuk mengelola pengguna.
     */
    public function manageUsers() {
        require_once __DIR__ . '/../models/User.php';
        $userModel = new User($this->pdo);
        $users = $userModel->getAll();

        // Set template variables
        $pageTitle = 'Manajemen Pengguna - Admin';
        $userRole = 'admin';
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => 'index.php?page=admin_dashboard'],
            ['text' => 'Manajemen Pengguna', 'href' => '#']
        ];

        require_once __DIR__ . '/../views/layout/unified_header.php';
        require_once __DIR__ . '/../views/admin/manage_users.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Update status tiket
     */
    public function updateTicketStatus() {
        $id_tiket = $_GET['id'] ?? 0;
        $status = $_GET['status'] ?? '';

        if (empty($id_tiket) || empty($status)) {
            header('Location: index.php?page=admin_manage_tiket&error=invalidparameters');
            exit;
        }

        require_once __DIR__ . '/../models/Tiket.php';
        $tiketModel = new Tiket($this->pdo);

        if ($tiketModel->updateStatus($id_tiket, $status)) {
            header('Location: index.php?page=admin_manage_tiket&success=statusupdated');
        } else {
            header('Location: index.php?page=admin_manage_tiket&error=updatefailed');
        }
        exit;
    }

    /**
     * Menampilkan halaman kelola rute
     */
    public function showManageRutes() {
        require_once __DIR__ . '/../models/Rute.php';
        $ruteModel = new Rute($this->pdo);
        $ruteList = $ruteModel->getAllActiveRutes();

        $userRole = 'admin';
        $pageTitle = 'Kelola Rute - Admin';
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => 'index.php?page=admin_dashboard'],
            ['text' => 'Kelola Rute', 'href' => '#']
        ];

        require_once __DIR__ . '/../views/layout/unified_header.php';
        require_once __DIR__ . '/../views/admin/manage_rutes.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Menampilkan form tambah rute
     */
    public function showAddRuteForm() {
        $userRole = 'admin';
        $pageTitle = 'Tambah Rute - Admin';
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => 'index.php?page=admin_dashboard'],
            ['text' => 'Kelola Rute', 'href' => 'index.php?page=admin_rutes'],
            ['text' => 'Tambah Rute', 'href' => '#']
        ];

        require_once __DIR__ . '/../views/layout/unified_header.php';
        require_once __DIR__ . '/../views/admin/add_rute.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Proses tambah rute
     */
    public function processAddRute() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../models/Rute.php';
            $ruteModel = new Rute($this->pdo);

            $data = [
                'nama_rute' => $_POST['nama_rute'],
                'nama_po' => $_POST['nama_po'],
                'kelas' => $_POST['kelas'],
                'kota_asal' => $_POST['kota_asal'],
                'kota_tujuan' => $_POST['kota_tujuan'],
                'kapasitas' => $_POST['kapasitas'],
                'jarak_km' => $_POST['jarak_km']
            ];

            if ($ruteModel->createRute($data)) {
                header('Location: index.php?page=admin_rutes&success=added');
            } else {
                header('Location: index.php?page=admin_add_rute&error=failed');
            }
            exit;
        }
    }

    /**
     * Menampilkan formulir untuk mengedit rute yang ada.
     */
    public function showEditRuteForm() {
        $id_rute = $_GET['id'] ?? 0;

        require_once __DIR__ . '/../models/Rute.php';

        $ruteModel = new Rute($this->pdo);

        $rute = $ruteModel->findById($id_rute);

        if (!$rute) {
            // Handle error jika rute tidak ditemukan
            header('Location: index.php?page=admin_rutes&error=notfound');
            exit;
        }

        // Set template variables
        $pageTitle = 'Edit Rute - Admin';
        $userRole = 'admin';
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => 'index.php?page=admin_dashboard'],
            ['text' => 'Kelola Rute', 'href' => 'index.php?page=admin_rutes'],
            ['text' => 'Edit Rute', 'href' => '#']
        ];

        require_once __DIR__ . '/../views/layout/unified_header.php';
        require_once __DIR__ . '/../views/admin/edit_rute.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Memperbarui data rute dari form edit ke database.
     */
    public function updateRute() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validasi sederhana
            if (empty($_POST['id_rute']) || empty($_POST['nama_rute']) || empty($_POST['nama_po']) || empty($_POST['kelas']) || empty($_POST['kota_asal']) || empty($_POST['kota_tujuan']) || empty($_POST['kapasitas']) || empty($_POST['jarak_km'])) {
                // Kembali ke form dengan pesan error
                header('Location: index.php?page=admin_edit_rute&id=' . $_POST['id_rute'] . '&error=incomplete');
                exit;
            }

            $id_rute = $_POST['id_rute'];
            $data = [
                'nama_rute' => $_POST['nama_rute'],
                'nama_po' => $_POST['nama_po'],
                'kelas' => $_POST['kelas'],
                'kota_asal' => $_POST['kota_asal'],
                'kota_tujuan' => $_POST['kota_tujuan'],
                'kapasitas' => $_POST['kapasitas'],
                'jarak_km' => $_POST['jarak_km'],
            ];

            require_once __DIR__ . '/../models/Rute.php';
            $ruteModel = new Rute($this->pdo);

            if ($ruteModel->update($id_rute, $data)) {
                // Jika berhasil, redirect ke halaman manajemen rute
                header('Location: index.php?page=admin_rutes&success=updated');
            } else {
                // Jika gagal, redirect kembali dengan pesan error
                header('Location: index.php?page=admin_edit_rute&id=' . $id_rute . '&error=failed');
            }
            exit;
        }
    }

    /**
     * Menghapus data rute dari database.
     */
    public function deleteRute() {
        $id_rute = $_GET['id'] ?? 0;

        require_once __DIR__ . '/../models/Rute.php';
        $ruteModel = new Rute($this->pdo);

        if ($ruteModel->delete($id_rute)) {
            header('Location: index.php?page=admin_rutes&success=deleted');
        } else {
            header('Location: index.php?page=admin_rutes&error=deletefailed');
        }
        exit;
    }

}
?>
