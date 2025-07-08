<?php
// Session sudah dimulai di index.php

class PelangganController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->checkAuth();
    }

    /**
     * Memeriksa apakah pengguna terotentikasi dan merupakan pelanggan.
     */
    private function checkAuth() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pelanggan') {
            header('Location: index.php?page=login&error=unauthorized');
            exit;
        }
    }

    /**
     * Menampilkan dashboard pelanggan.
     */
    public function showDashboard() {
        $userRole = $_SESSION['role'] ?? 'pelanggan';
        $pageTitle = "Dashboard Pelanggan";
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => 'index.php?page=pelanggan_dashboard']
        ];

        require_once __DIR__ . '/../models/Pemesanan.php';
        require_once __DIR__ . '/../models/Jadwal.php';
        
        $pemesananModel = new Pemesanan($this->pdo);
        $jadwalModel = new Jadwal($this->pdo);
        
        $riwayatPemesanan = $pemesananModel->getByPelanggan($_SESSION['user_id']);
        $kotaList = $jadwalModel->getAllCities();
        
        require_once __DIR__ . '/../views/layout/unified_header.php';
        require_once __DIR__ . '/../views/pelanggan/dashboard.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Menampilkan riwayat pemesanan pelanggan.
     */
    public function showRiwayatPemesanan() {
        $userRole = $_SESSION['role'] ?? 'pelanggan';
        $pageTitle = "Riwayat Pemesanan";
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => 'index.php?page=pelanggan_dashboard'],
            ['text' => 'Riwayat Pemesanan', 'href' => 'index.php?page=pelanggan_riwayat']
        ];

        require_once __DIR__ . '/../models/Pemesanan.php';
        $pemesananModel = new Pemesanan($this->pdo);
        
        $riwayatPemesanan = $pemesananModel->getByPelanggan($_SESSION['user_id']);
        
        require_once __DIR__ . '/../views/layout/unified_header.php';
        require_once __DIR__ . '/../views/pelanggan/riwayat_pemesanan.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Menampilkan detail pemesanan.
     */
    public function showDetailPemesanan() {
        $id_pemesanan = $_GET['id'] ?? 0;
        
        require_once __DIR__ . '/../models/Pemesanan.php';
        $pemesananModel = new Pemesanan($this->pdo);
        
        $pemesanan = $pemesananModel->getDetailById($id_pemesanan);
        
        // Pastikan pemesanan ini milik pelanggan yang login
        if (!$pemesanan || $pemesanan['id_pelanggan'] !== $_SESSION['user_id']) {
            header('Location: index.php?page=pelanggan_dashboard&error=notfound');
            exit;
        }

        $userRole = $_SESSION['role'] ?? 'pelanggan';
        $pageTitle = "Detail Pemesanan";
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => 'index.php?page=pelanggan_dashboard'],
            ['text' => 'Riwayat Pemesanan', 'href' => 'index.php?page=pelanggan_riwayat'],
            ['text' => 'Detail Pemesanan', 'href' => 'index.php?page=pelanggan_detail&id=' . $id_pemesanan]
        ];
        
        require_once __DIR__ . '/../views/layout/unified_header.php';
        require_once __DIR__ . '/../views/pelanggan/detail_pemesanan.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Menampilkan form upload bukti pembayaran.
     */
    public function showUploadBukti() {
        $id_pemesanan = $_GET['id'] ?? 0;
        
        require_once __DIR__ . '/../models/Pemesanan.php';
        $pemesananModel = new Pemesanan($this->pdo);
        
        $pemesanan = $pemesananModel->getDetailById($id_pemesanan);
        
        // Pastikan pemesanan ini milik pelanggan yang login
        if (!$pemesanan || $pemesanan['id_pelanggan'] !== $_SESSION['user_id']) {
            header('Location: index.php?page=pelanggan_dashboard&error=notfound');
            exit;
        }

        $userRole = $_SESSION['role'] ?? 'pelanggan';
        $pageTitle = "Upload Bukti Pembayaran";
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => 'index.php?page=pelanggan_dashboard'],
            ['text' => 'Riwayat Pemesanan', 'href' => 'index.php?page=pelanggan_riwayat'],
            ['text' => 'Upload Bukti', 'href' => 'index.php?page=pelanggan_upload_bukti&id=' . $id_pemesanan]
        ];
        
        require_once __DIR__ . '/../views/layout/unified_header.php';
        require_once __DIR__ . '/../views/pelanggan/upload_bukti.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Memproses upload bukti pembayaran.
     */
    public function processUploadBukti() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=pelanggan_dashboard');
            exit;
        }

        $id_pemesanan = $_POST['id_pemesanan'] ?? 0;
        
        require_once __DIR__ . '/../models/Pemesanan.php';
        require_once __DIR__ . '/../models/Pembayaran.php';
        
        $pemesananModel = new Pemesanan($this->pdo);
        $pembayaranModel = new Pembayaran($this->pdo);
        
        $pemesanan = $pemesananModel->getDetailById($id_pemesanan);
        
        // Pastikan pemesanan ini milik pelanggan yang login
        if (!$pemesanan || $pemesanan['id_pelanggan'] !== $_SESSION['user_id']) {
            header('Location: index.php?page=pelanggan_dashboard&error=notfound');
            exit;
        }

        // Handle file upload
        if (isset($_FILES['bukti_pembayaran']) && $_FILES['bukti_pembayaran']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['bukti_pembayaran'];
            
            // Validasi file
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
            $maxFileSize = 2 * 1024 * 1024; // 2MB
            
            if (!in_array($file['type'], $allowedTypes)) {
                $_SESSION['error_message'] = 'Format file tidak diizinkan. Gunakan JPG, PNG, atau PDF.';
                header('Location: index.php?page=pelanggan_upload_bukti&id=' . $id_pemesanan);
                exit;
            }
            
            if ($file['size'] > $maxFileSize) {
                $_SESSION['error_message'] = 'Ukuran file terlalu besar. Maksimal 2MB.';
                header('Location: index.php?page=pelanggan_upload_bukti&id=' . $id_pemesanan);
                exit;
            }
            
            $uploadDir = __DIR__ . '/../public/uploads/bukti_pembayaran/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileName = time() . '_' . basename($file['name']);
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                // Simpan ke database dengan path relatif
                $pembayaranData = [
                    'id_pemesanan' => $id_pemesanan,
                    'metode_pembayaran' => 'transfer',
                    'jumlah' => $pemesanan['total_harga'],
                    'bukti_pembayaran' => 'uploads/bukti_pembayaran/' . $fileName,
                    'status' => 'pending'
                ];
                
                if ($pembayaranModel->create($pembayaranData)) {
                    $_SESSION['success_message'] = 'Bukti pembayaran berhasil diupload. Menunggu konfirmasi admin.';
                    header('Location: index.php?page=pelanggan_riwayat');
                } else {
                    $_SESSION['error_message'] = 'Gagal menyimpan data pembayaran.';
                    header('Location: index.php?page=pelanggan_upload_bukti&id=' . $id_pemesanan);
                }
            } else {
                $_SESSION['error_message'] = 'Gagal mengupload file.';
                header('Location: index.php?page=pelanggan_upload_bukti&id=' . $id_pemesanan);
            }
        } else {
            $errorMsg = 'File tidak valid atau tidak ada file yang dipilih.';
            if (isset($_FILES['bukti_pembayaran']['error'])) {
                switch ($_FILES['bukti_pembayaran']['error']) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $errorMsg = 'Ukuran file terlalu besar.';
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $errorMsg = 'File tidak terupload dengan sempurna.';
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $errorMsg = 'Tidak ada file yang dipilih.';
                        break;
                }
            }
            $_SESSION['error_message'] = $errorMsg;
            header('Location: index.php?page=pelanggan_upload_bukti&id=' . $id_pemesanan);
        }
        exit;
    }

    /**
     * Menampilkan profil pelanggan.
     */
    public function showProfil() {
        $userRole = $_SESSION['role'] ?? 'pelanggan';
        $pageTitle = "Profil Saya";
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => 'index.php?page=pelanggan_dashboard'],
            ['text' => 'Profil Saya', 'href' => 'index.php?page=pelanggan_profil']
        ];

        require_once __DIR__ . '/../models/Pelanggan.php';
        $pelangganModel = new Pelanggan($this->pdo);
        
        $pelanggan = $pelangganModel->findById($_SESSION['user_id']);
        
        require_once __DIR__ . '/../views/layout/unified_header.php';
        require_once __DIR__ . '/../views/pelanggan/profil.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Memproses update profil pelanggan.
     */
    public function updateProfil() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=pelanggan_profil');
            exit;
        }

        $data = [
            'nama' => $_POST['nama'] ?? '',
            'email' => $_POST['email'] ?? '',
            'telepon' => $_POST['telepon'] ?? '',
            'alamat' => $_POST['alamat'] ?? ''
        ];

        require_once __DIR__ . '/../models/Pelanggan.php';
        $pelangganModel = new Pelanggan($this->pdo);
        
        if ($pelangganModel->update($_SESSION['user_id'], $data)) {
            header('Location: index.php?page=pelanggan_profil&success=updated');
        } else {
            header('Location: index.php?page=pelanggan_profil&error=update');
        }
        exit;
    }

    /**
     * Memproses booking dari form pemilihan kursi.
     * Method ini sesuai dengan UML: pesanTiket()
     */
    public function pesanTiket() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=home');
            exit;
        }

        $id_jadwal = $_POST['id_jadwal'] ?? 0;
        $seats = $_POST['seats'] ?? [];
        $id_pelanggan = $_SESSION['user_id'];

        if (empty($seats) || empty($id_jadwal)) {
            $_SESSION['error_message'] = 'Data pemesanan tidak lengkap. Silakan pilih kursi terlebih dahulu.';
            header('Location: index.php?page=select_seat&id_jadwal=' . $id_jadwal);
            exit;
        }

        require_once __DIR__ . '/../models/Pemesanan.php';
        require_once __DIR__ . '/../models/Jadwal.php';
        require_once __DIR__ . '/../models/Tiket.php';

        $jadwalModel = new Jadwal($this->pdo);
        $pemesananModel = new Pemesanan($this->pdo);
        $tiketModel = new Tiket($this->pdo);

        // Dapatkan harga dari jadwal
        $jadwal = $jadwalModel->findById($id_jadwal);
        if (!$jadwal) {
            header('Location: index.php?page=home&error=notfound');
            exit;
        }
        $harga_per_tiket = $jadwal['harga'];
        $total_harga = $harga_per_tiket * count($seats);

        // Buat pemesanan sesuai UML
        $kode_booking = $pemesananModel->createPemesanan($id_pelanggan, $id_jadwal, $seats, $total_harga);

        if ($kode_booking) {
            // Set template variables untuk unified header
            $pageTitle = 'Pemesanan Berhasil - Sistem Tiket Bus';
            $userRole = $_SESSION['role'] ?? 'pelanggan';
            $breadcrumbs = [
                ['text' => 'Dashboard', 'href' => 'index.php?page=pelanggan_dashboard'],
                ['text' => 'Pemesanan Berhasil', 'href' => '#']
            ];
            
            $nama_pemesan = $_SESSION['username'];
            require_once __DIR__ . '/../views/layout/unified_header.php';
            require_once __DIR__ . '/../views/pemesanan/booking_success.php';
            require_once __DIR__ . '/../views/layout/footer.php';
        } else {
            $_SESSION['error_message'] = 'Gagal membuat pesanan. Salah satu kursi yang Anda pilih mungkin sudah tidak tersedia.';
            header('Location: index.php?page=select_seat&id_jadwal=' . $id_jadwal);
            exit;
            exit;
        }
    }

    /**
     * Method bayarOnline sesuai dengan UML
     */
    public function bayarOnline() {
        $id_pemesanan = $_GET['id'] ?? 0;
        
        require_once __DIR__ . '/../models/Pemesanan.php';
        $pemesananModel = new Pemesanan($this->pdo);
        
        $pemesanan = $pemesananModel->getDetailById($id_pemesanan);
        
        // Pastikan pemesanan ini milik pelanggan yang login
        if (!$pemesanan || $pemesanan['id_pelanggan'] !== $_SESSION['user_id']) {
            header('Location: index.php?page=pelanggan_dashboard&error=notfound');
            exit;
        }

        // Set template variables untuk unified header
        $userRole = $_SESSION['role'] ?? 'pelanggan';
        $pageTitle = "Upload Bukti Pembayaran - Sistem Tiket Bus";
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => 'index.php?page=pelanggan_dashboard'],
            ['text' => 'Riwayat Pemesanan', 'href' => 'index.php?page=pelanggan_riwayat'],
            ['text' => 'Upload Bukti', 'href' => 'index.php?page=pelanggan_upload_bukti&id=' . $id_pemesanan]
        ];
        
        require_once __DIR__ . '/../views/layout/unified_header.php';
        require_once __DIR__ . '/../views/pelanggan/upload_bukti.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Method lihatRiwayat sesuai dengan UML
     */
    public function lihatRiwayat() {
        require_once __DIR__ . '/../models/Pemesanan.php';
        $pemesananModel = new Pemesanan($this->pdo);
        
        $riwayatPemesanan = $pemesananModel->getByPelanggan($_SESSION['user_id']);
        
        require_once __DIR__ . '/../views/layout/pelanggan_header.php';
        require_once __DIR__ . '/../views/pelanggan/riwayat_pemesanan.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Method registrasi sesuai dengan UML
     */
    public function registrasi() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require_once __DIR__ . '/../views/layout/header.php';
            require_once __DIR__ . '/../views/auth/registrasi.php';
            require_once __DIR__ . '/../views/layout/footer.php';
            return;
        }

        $data = [
            'username' => $_POST['username'] ?? '',
            'password' => $_POST['password'] ?? '',
            'email' => $_POST['email'] ?? '',
            'no_telp' => $_POST['no_telp'] ?? '',
            'alamat' => $_POST['alamat'] ?? ''
        ];

        // Validasi data
        if (empty($data['username']) || empty($data['password']) || empty($data['email'])) {
            $_SESSION['error_message'] = 'Data tidak lengkap. Silakan lengkapi form registrasi.';
            require_once __DIR__ . '/../views/layout/header.php';
            require_once __DIR__ . '/../views/auth/registrasi.php';
            require_once __DIR__ . '/../views/layout/footer.php';
            return;
        }

        require_once __DIR__ . '/../models/Pelanggan.php';
        $pelangganModel = new Pelanggan($this->pdo);

        if ($pelangganModel->create($data)) {
            $_SESSION['success_message'] = 'Registrasi berhasil! Silakan login.';
            header('Location: index.php?page=login');
        } else {
            $_SESSION['error_message'] = 'Registrasi gagal. Email atau username mungkin sudah digunakan.';
            require_once __DIR__ . '/../views/layout/header.php';
            require_once __DIR__ . '/../views/auth/registrasi.php';
            require_once __DIR__ . '/../views/layout/footer.php';
        }
    }

    /**
     * Menampilkan e-tiket untuk pemesanan yang sudah lunas
     */
    public function showTiket() {
        $id_pemesanan = $_GET['id'] ?? 0;
        
        require_once __DIR__ . '/../models/Pemesanan.php';
        $pemesananModel = new Pemesanan($this->pdo);
        
        $pemesanan = $pemesananModel->getDetailById($id_pemesanan);
        
        // Pastikan pemesanan ini milik pelanggan yang login
        if (!$pemesanan || $pemesanan['id_pelanggan'] !== $_SESSION['user_id']) {
            header('Location: index.php?page=pelanggan_dashboard&error=notfound');
            exit;
        }
        
        // Pastikan pemesanan sudah lunas
        $status = $pemesanan['status_pembayaran'] ?? 'pending';
        if ($status !== 'success' && $status !== 'paid') {
            header('Location: index.php?page=pelanggan_detail&id=' . $id_pemesanan . '&error=not_paid');
            exit;
        }

        $userRole = $_SESSION['role'] ?? 'pelanggan';
        $pageTitle = "E-Tiket " . $pemesanan['kode_booking'];
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => 'index.php?page=pelanggan_dashboard'],
            ['text' => 'Riwayat Pemesanan', 'href' => 'index.php?page=pelanggan_riwayat'],
            ['text' => 'E-Tiket', 'href' => 'index.php?page=pelanggan_tiket&id=' . $id_pemesanan]
        ];
        
        // Ensure all required data is available for the view
        $pemesanan['nama_po'] = $pemesanan['nama_po'] ?? 'PT Travel Bus Indonesia';
        $pemesanan['kelas'] = $pemesanan['kelas'] ?? 'Ekonomi';
        $pemesanan['nama_pelanggan'] = $pemesanan['nama_pelanggan'] ?? $_SESSION['username'];
        
        require_once __DIR__ . '/../views/layout/unified_header.php';
        require_once __DIR__ . '/../views/pelanggan/tiket.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Menampilkan halaman booking sukses
     */
    public function showBookingSuccess() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $kode_booking = $_GET['kode_booking'] ?? '';
        
        if (empty($kode_booking)) {
            header('Location: index.php?page=pelanggan_dashboard');
            exit;
        }

        // Set template variables
        $pageTitle = 'Pemesanan Berhasil - Sistem Tiket Bus';
        $userRole = $_SESSION['role'] ?? 'pelanggan';
        $breadcrumbs = [
            ['text' => 'Dashboard', 'href' => 'index.php?page=pelanggan_dashboard'],
            ['text' => 'Pemesanan Berhasil', 'href' => '#']
        ];
        
        // Data untuk ditampilkan
        $nama_pemesan = $_SESSION['username'];
        
        require_once __DIR__ . '/../views/pemesanan/booking_success.php';
    }
}
?>
