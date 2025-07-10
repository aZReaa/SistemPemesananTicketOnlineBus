<?php

// File: public/index.php
// Deskripsi: Titik masuk utama (Front Controller) untuk aplikasi.

// Memulai sesi untuk manajemen login
session_start();

// Memuat file konfigurasi database dan semua model
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Pelanggan.php';
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/PetugasLoket.php';
// ... (model lain akan dimuat sesuai kebutuhan)

// Routing Sederhana
// Menentukan halaman yang akan ditampilkan berdasarkan parameter 'page' di URL
// Contoh: http://localhost/travel/public/index.php?page=register
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Memuat controller yang sesuai
// Untuk saat ini, kita akan fokus pada alur otentikasi (login/register)
switch ($page) {
    case 'register':
        require_once __DIR__ . '/../controllers/AuthController.php';
        $authController = new AuthController($pdo);
        $authController->showRegistrationForm();
        break;

    case 'register_process':
        require_once __DIR__ . '/../controllers/AuthController.php';
        $authController = new AuthController($pdo);
        $authController->processRegistration();
        break;

    case 'login':
        require_once __DIR__ . '/../controllers/AuthController.php';
        $authController = new AuthController($pdo);
        $authController->showLoginForm();
        break;

    case 'process_login':
        require_once __DIR__ . '/../controllers/AuthController.php';
        $authController = new AuthController($pdo);
        $authController->processLogin();
        break;

    case 'logout':
        require_once __DIR__ . '/../controllers/AuthController.php';
        $authController = new AuthController($pdo);
        $authController->logout();
        break;



    case 'home':
        require_once __DIR__ . '/../controllers/PageController.php';
        $pageController = new PageController($pdo);
        $pageController->showHome();
        break;

    default:
        // Redirect ke login jika tidak ada page yang cocok
        header('Location: index.php?page=login');
        exit;
        break;

    case 'search_results':
        require_once __DIR__ . '/../controllers/JadwalController.php';
        $jadwalController = new JadwalController($pdo);
        $jadwalController->search();
        break;

    case 'select_seat':
        require_once __DIR__ . '/../controllers/JadwalController.php';
        $jadwalController = new JadwalController($pdo);
        $jadwalController->showSeatSelection();
        break;

    case 'process_booking':
        require_once __DIR__ . '/../controllers/PelangganController.php';
        $pelangganController = new PelangganController($pdo);
        $pelangganController->pesanTiket();
        break;

    case 'booking_success':
        require_once __DIR__ . '/../controllers/PelangganController.php';
        $pelangganController = new PelangganController($pdo);
        $pelangganController->showBookingSuccess();
        break;

    // Rute untuk Pelanggan
    case 'pelanggan_dashboard':
        require_once __DIR__ . '/../controllers/PelangganController.php';
        $pelangganController = new PelangganController($pdo);
        $pelangganController->showDashboard();
        break;

    case 'pelanggan_riwayat':
        require_once __DIR__ . '/../controllers/PelangganController.php';
        $pelangganController = new PelangganController($pdo);
        $pelangganController->showRiwayatPemesanan();
        break;

    case 'pelanggan_detail':
        require_once __DIR__ . '/../controllers/PelangganController.php';
        $pelangganController = new PelangganController($pdo);
        $pelangganController->showDetailPemesanan();
        break;

    case 'pelanggan_upload_bukti':
        require_once __DIR__ . '/../controllers/PelangganController.php';
        $pelangganController = new PelangganController($pdo);
        $pelangganController->showUploadBukti();
        break;

    case 'pelanggan_process_upload':
        require_once __DIR__ . '/../controllers/PelangganController.php';
        $pelangganController = new PelangganController($pdo);
        $pelangganController->processUploadBukti();
        break;

    case 'pelanggan_tiket':
        require_once __DIR__ . '/../controllers/PelangganController.php';
        $pelangganController = new PelangganController($pdo);
        $pelangganController->showTiket();
        break;

    // Admin Routes
    case 'admin_dashboard':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $adminController = new AdminController($pdo);
        $adminController->showDashboard();
        break;

    case 'admin_manage_schedule':
    case 'admin_schedule':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $adminController = new AdminController($pdo);
        $adminController->kelolaJadwal();
        break;

    case 'admin_manage_tiket':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $adminController = new AdminController($pdo);
        $adminController->kelolaTiket();
        break;

    case 'admin_manage_confirmations':
    case 'admin_payments':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $adminController = new AdminController($pdo);
        $adminController->konfirmasiPembayaran();
        break;

    case 'admin_reports':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $adminController = new AdminController($pdo);
        $adminController->generateLaporan();
        break;

    case 'admin_rutes':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $adminController = new AdminController($pdo);
        $adminController->showManageRutes();
        break;

    case 'admin_add_rute':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $adminController = new AdminController($pdo);
        $adminController->showAddRuteForm();
        break;

    case 'admin_process_add_rute':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $adminController = new AdminController($pdo);
        $adminController->processAddRute();
        break;

    case 'admin_add_schedule':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $adminController = new AdminController($pdo);
        $adminController->showAddScheduleForm();
        break;

    case 'admin_store_schedule':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $adminController = new AdminController($pdo);
        $adminController->storeSchedule();
        break;

    case 'admin_edit_schedule':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $adminController = new AdminController($pdo);
        $adminController->showEditScheduleForm();
        break;

    case 'admin_update_schedule':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $adminController = new AdminController($pdo);
        $adminController->updateSchedule();
        break;

    case 'admin_delete_schedule':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $adminController = new AdminController($pdo);
        $adminController->deleteSchedule();
        break;

    case 'admin_update_ticket_status':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $adminController = new AdminController($pdo);
        $adminController->updateTicketStatus();
        break;

    case 'admin_process_confirmation':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $adminController = new AdminController($pdo);
        $adminController->processConfirmation();
        break;

    // Petugas Routes
    case 'petugas_dashboard':
        require_once __DIR__ . '/../controllers/PetugasController.php';
        $petugasController = new PetugasController($pdo);
        $petugasController->dashboard();
        break;
        
    case 'petugas_verifikasi':
        require_once __DIR__ . '/../controllers/PetugasController.php';
        $petugasController = new PetugasController($pdo);
        $petugasController->verifyTicket();
        break;

    case 'petugas_validate_ticket':
        require_once __DIR__ . '/../controllers/PetugasController.php';
        $petugasController = new PetugasController($pdo);
        $petugasController->validateTicket();
        break;
        
    case 'petugas_process_qr':
        require_once __DIR__ . '/../controllers/PetugasController.php';
        $petugasController = new PetugasController($pdo);
        $petugasController->processQRScan();
        break;
        
    case 'petugas_data_pelanggan':
        require_once __DIR__ . '/../controllers/PetugasController.php';
        $petugasController = new PetugasController($pdo);
        $petugasController->lihatDataPelanggan();
        break;

    case 'search_schedule':
        require_once __DIR__ . '/../controllers/JadwalController.php';
        $jadwalController = new JadwalController($pdo);
        $jadwalController->showSearchForm();
        break;
}

?>
