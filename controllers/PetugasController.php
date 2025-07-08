<?php
// Session sudah dimulai di index.php

class PetugasController {
    private $pdo;
    private $petugasLoket;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->checkAuth();
        
        // Inisialisasi model PetugasLoket
        require_once __DIR__ . '/../models/PetugasLoket.php';
        $this->petugasLoket = new PetugasLoket($pdo);
        $this->petugasLoket->setIdPetugas($_SESSION['user_id']);
    }

    /**
     * Memeriksa apakah pengguna terotentikasi dan merupakan petugas loket.
     * Jika tidak, alihkan ke halaman login.
     */
    private function checkAuth() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'petugas_loket') {
            header('Location: index.php?page=login&error=unauthorized');
            exit;
        }
    }

    /**
     * Menampilkan halaman verifikasi dan menangani pencarian tiket.
     */
    public function verifyTicket() {
        $ticketDetails = null;
        $searchPerformed = false;
        $flashMessage = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']); // Hapus setelah dibaca

        // Tangani pencarian dari POST (input form) atau GET (setelah redirect)
        $kode_tiket_search = $_POST['kode_tiket'] ?? $_GET['kode_tiket'] ?? null;

        if ($kode_tiket_search) {
            $kode_tiket = trim($kode_tiket_search);
            // Gunakan fungsi cariTiket untuk pencarian saja
            $result = $this->petugasLoket->cariTiket($kode_tiket);
            
            if ($result['success']) {
                $ticketDetails = $result['data'];
                $flashMessage = 'Tiket ditemukan. Periksa detail dan klik tombol validasi jika ingin memproses tiket.';
            } else {
                $flashMessage = $result['message'];
            }
            $searchPerformed = true;
        }

        // Set variables for view
        $userRole = 'petugas_loket';
        $pageTitle = 'Verifikasi Tiket - Petugas';
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => 'index.php?page=petugas_dashboard'],
            ['text' => 'Verifikasi Tiket', 'href' => '']
        ];

        include __DIR__ . '/../views/layout/unified_header.php';
        include __DIR__ . '/../views/petugas/verify_ticket.php';
        include __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Memvalidasi tiket dan mengubah statusnya menjadi 'digunakan'.
     */
    public function validateTicket() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kode_booking'])) {
            $kode_booking = $_POST['kode_booking'];
            
            // Gunakan fungsi verifikasiTiket untuk update status
            $result = $this->petugasLoket->verifikasiTiket($kode_booking);
            
            if ($result['success']) {
                $_SESSION['flash_message'] = $result['message'];
            } else {
                $_SESSION['flash_message'] = $result['message'];
            }
            
            // Redirect kembali ke halaman verifikasi
            header('Location: index.php?page=petugas_verifikasi&kode_tiket=' . urlencode($kode_booking));
            exit;
        }

        // Jika akses langsung, redirect ke halaman utama petugas
        header('Location: index.php?page=petugas_verifikasi');
        exit;
    }

    /**
     * Terima pembayaran offline
     */
    public function terimaPembayaran() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_pemesanan = $_POST['id_pemesanan'] ?? null;
            $jumlah_bayar = $_POST['jumlah_bayar'] ?? null;

            if ($id_pemesanan && $jumlah_bayar) {
                $result = $this->petugasLoket->terimaPembayaranOffline($id_pemesanan, $jumlah_bayar);
                
                if ($result['success']) {
                    $_SESSION['flash_message'] = $result['message'] . 
                        (isset($result['kembalian']) && $result['kembalian'] > 0 ? 
                         ' Kembalian: Rp ' . number_format($result['kembalian'], 0, ',', '.') : '');
                } else {
                    $_SESSION['flash_message'] = $result['message'];
                }
            } else {
                $_SESSION['flash_message'] = 'Data pembayaran tidak lengkap';
            }
        }

        header('Location: index.php?page=petugas_verifikasi');
        exit;
    }

    /**
     * Method terimaPembayaranOffline sesuai dengan UML
     */
    public function terimaPembayaranOffline() {
        $pendingPayments = $this->petugasLoket->getPendingPayments();
        
        // Set variables for view
        $userRole = 'petugas_loket';
        $pageTitle = 'Pembayaran Offline - Petugas';
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => 'index.php?page=petugas_dashboard'],
            ['text' => 'Pembayaran Offline', 'href' => '']
        ];
        
        include __DIR__ . '/../views/layout/unified_header.php';
        include __DIR__ . '/../views/petugas/pembayaran_offline.php';
        include __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Lihat data pelanggan
     */
    public function lihatDataPelanggan() {
        $tanggal_mulai = $_GET['tanggal_mulai'] ?? date('Y-m-d');
        $tanggal_selesai = $_GET['tanggal_selesai'] ?? date('Y-m-d');
        $filter_nama = $_GET['filter_nama'] ?? null;

        $result = $this->petugasLoket->lihatDataPelanggan($tanggal_mulai, $tanggal_selesai, $filter_nama);
        
        $data_pelanggan = $result['success'] ? $result['data'] : [];
        $total_pelanggan = $result['success'] ? $result['total'] : 0;

        // Set variables for view
        $userRole = 'petugas_loket';
        $pageTitle = 'Data Pelanggan - Petugas';
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => 'index.php?page=petugas_dashboard'],
            ['text' => 'Data Pelanggan', 'href' => '']
        ];

        include __DIR__ . '/../views/layout/unified_header.php';
        include __DIR__ . '/../views/petugas/data_pelanggan.php';
        include __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Dashboard petugas loket
     */
    public function dashboard() {
        // Statistik hari ini
        $today = date('Y-m-d');
        $stats = $this->getDailyStats($today);
        
        // Data pemesanan pending untuk ditampilkan
        $pendingPayments = $this->getPemesananPending();
        
        // Set variables for view
        $userRole = 'petugas_loket';
        $pageTitle = 'Dashboard - Petugas Loket';
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => '']
        ];
        
        include __DIR__ . '/../views/layout/unified_header.php';
        include __DIR__ . '/../views/petugas/dashboard.php';
        include __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Get statistik harian
     */
    private function getDailyStats($tanggal) {
        try {
            // Query yang mengakomodasi struktur database yang ada
            $query = "SELECT 
                        COUNT(DISTINCT t.id_tiket) as total_tiket_verified,
                        COUNT(DISTINCT CASE WHEN t.status = 'used' THEN t.id_tiket END) as tiket_digunakan,
                        (SELECT COUNT(DISTINCT u.id_user) FROM users u WHERE u.role = 'pelanggan') as total_pelanggan
                      FROM tiket t
                      LEFT JOIN pemesanan p ON t.id_pemesanan = p.id_pemesanan
                      LEFT JOIN jadwal j ON t.id_jadwal = j.id_jadwal
                      WHERE DATE(j.waktu_berangkat) = :tanggal";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':tanggal', $tanggal);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Jika query gagal, berikan nilai default
            if (!$result) {
                $result = [
                    'total_tiket_verified' => 0,
                    'tiket_digunakan' => 0,
                    'total_pelanggan' => 0
                ];
            }
            
            return $result;
        } catch (PDOException $e) {
            // Return default values jika terjadi error
            return [
                'total_tiket_verified' => 0,
                'tiket_digunakan' => 0,
                'total_pelanggan' => 0
            ];
        }
    }

    /**
     * Get pemesanan yang belum dibayar
     */
    private function getPemesananPending() {
        try {
            $query = "SELECT p.*, u.nama, j.asal, j.tujuan, j.tanggal_berangkat, j.jam_berangkat
                      FROM pemesanan p
                      JOIN users u ON p.id_pelanggan = u.id_user
                      JOIN jadwal j ON p.id_jadwal = j.id_jadwal
                      WHERE p.status_pembayaran = 'pending'
                      ORDER BY p.tanggal_pemesanan DESC
                      LIMIT 10";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Process QR code scan for ticket verification
     */
    public function processQRScan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qr_data'])) {
            require_once __DIR__ . '/../models/Tiket.php';
            $tiketModel = new Tiket($this->pdo);
            
            $qrData = $_POST['qr_data'];
            
            // Debug: Log QR data for troubleshooting
            error_log("QR Data received: " . $qrData);
            
            $decoded = $tiketModel->validateQRCode($qrData);
            
            if ($decoded && isset($decoded['booking_code'])) {
                $kode_booking = $decoded['booking_code'];
                
                // Debug: Log booking code
                error_log("Booking code extracted: " . $kode_booking);
                
                // Gunakan cariTiket untuk pencarian saja, bukan langsung verifikasi
                $result = $this->petugasLoket->cariTiket($kode_booking);
                
                if ($result['success']) {
                    $_SESSION['flash_message'] = 'QR Code berhasil dipindai. Tiket ditemukan, periksa detail dan klik validasi jika ingin memproses.';
                    header('Location: index.php?page=petugas_verifikasi&kode_tiket=' . urlencode($kode_booking));
                } else {
                    $_SESSION['flash_message'] = 'QR Code berhasil dipindai, tetapi ' . $result['message'];
                    header('Location: index.php?page=petugas_verifikasi');
                }
            } else {
                $_SESSION['flash_message'] = 'QR Code tidak valid atau format tidak sesuai. Pastikan QR code berasal dari tiket yang sah.';
                // Debug: Log invalid QR data
                error_log("Invalid QR Code: " . $qrData);
                header('Location: index.php?page=petugas_verifikasi');
            }
            exit;
        }

        header('Location: index.php?page=petugas_verifikasi');
        exit;
    }
}
?>
