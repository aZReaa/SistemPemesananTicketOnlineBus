<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Sistem Tiket Bus'; ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Unified Theme -->
    <link href="css/unified-theme.css" rel="stylesheet">
    
    <!-- Additional CSS -->
    <?php if (isset($additionalCSS)): ?>
        <?php foreach ($additionalCSS as $css): ?>
            <link href="<?php echo $css; ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Mobile Backdrop -->
    <div class="mobile-backdrop d-md-none" id="mobileBackdrop"></div>
    
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <a href="index.php?page=<?php echo $userRole; ?>_dashboard" class="sidebar-brand">
                <i class="fas fa-bus"></i>
                <span>Travel Bus</span>
            </a>
        </div>
        
        <!-- User Info -->
        <div class="sidebar-user">
            <div class="user-info">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)); ?>
                </div>
                <div class="user-details">
                    <h6><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></h6>
                    <small><?php echo ucfirst(str_replace('_', ' ', $userRole ?? 'guest')); ?></small>
                </div>
            </div>
        </div>
        
        <!-- Navigation Menu -->
        <div class="sidebar-nav">
            <?php 
            $currentPage = $_GET['page'] ?? '';
            $menuItems = [];
            
            // Define menu items based on user role
            switch ($userRole) {
                case 'admin':
                    $menuItems = [
                        ['icon' => 'fas fa-home', 'text' => 'Dashboard', 'href' => 'admin_dashboard'],
                        ['icon' => 'fas fa-calendar-alt', 'text' => 'Kelola Jadwal', 'href' => 'admin_schedule'],
                        ['icon' => 'fas fa-route', 'text' => 'Kelola Rute', 'href' => 'admin_rutes'],
                        ['icon' => 'fas fa-ticket-alt', 'text' => 'Kelola Tiket', 'href' => 'admin_manage_tiket'],
                        ['icon' => 'fas fa-credit-card', 'text' => 'Konfirmasi Pembayaran', 'href' => 'admin_payments'],
                        ['icon' => 'fas fa-users', 'text' => 'Manajemen User', 'href' => 'admin_users'],
                        ['icon' => 'fas fa-chart-bar', 'text' => 'Laporan', 'href' => 'admin_reports'],
                    ];
                    break;
                    
                case 'petugas_loket':
                case 'petugas':
                    $menuItems = [
                        ['icon' => 'fas fa-home', 'text' => 'Dashboard', 'href' => 'petugas_dashboard'],
                        ['icon' => 'fas fa-check-circle', 'text' => 'Verifikasi Tiket', 'href' => 'petugas_verifikasi'],
                        ['icon' => 'fas fa-users', 'text' => 'Data Pelanggan', 'href' => 'petugas_data_pelanggan'],
                    ];
                    break;
                    
                case 'pelanggan':
                    $menuItems = [
                        ['icon' => 'fas fa-home', 'text' => 'Dashboard', 'href' => 'pelanggan_dashboard'],
                        ['icon' => 'fas fa-search', 'text' => 'Cari Tiket', 'href' => 'search_schedule'],
                        ['icon' => 'fas fa-history', 'text' => 'Riwayat Pemesanan', 'href' => 'pelanggan_riwayat'],
                        ['icon' => 'fas fa-user', 'text' => 'Profil', 'href' => 'pelanggan_profil'],
                    ];
                    break;
                    
                default:
                    $menuItems = [
                        ['icon' => 'fas fa-home', 'text' => 'Beranda', 'href' => 'home'],
                        ['icon' => 'fas fa-sign-in-alt', 'text' => 'Login', 'href' => 'login'],
                        ['icon' => 'fas fa-user-plus', 'text' => 'Daftar', 'href' => 'register'],
                    ];
                    break;
            }
            
            // Render menu items
            foreach ($menuItems as $item):
                $isActive = ($currentPage === $item['href']) ? 'active' : '';
            ?>
                <div class="nav-item">
                    <a href="index.php?page=<?php echo $item['href']; ?>" class="nav-link <?php echo $isActive; ?>">
                        <i class="<?php echo $item['icon']; ?>"></i>
                        <span><?php echo $item['text']; ?></span>
                    </a>
                </div>
            <?php endforeach; ?>
            
            <!-- Divider -->
            <div class="nav-divider"></div>
            
            <!-- Logout -->
            <div class="nav-item">
                <a href="index.php?page=logout" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Keluar</span>
                </a>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="navbar-content">
                <div class="d-flex align-items-center">
                    <button class="toggle-btn d-md-none" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <button class="toggle-btn d-none d-md-block" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <?php if (isset($breadcrumbs)): ?>
                        <nav aria-label="breadcrumb" class="ms-3">
                            <ol class="breadcrumb mb-0">
                                <?php foreach ($breadcrumbs as $index => $breadcrumb): ?>
                                    <?php if ($index === count($breadcrumbs) - 1): ?>
                                        <li class="breadcrumb-item active"><?php echo $breadcrumb['text']; ?></li>
                                    <?php else: ?>
                                        <li class="breadcrumb-item">
                                            <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ol>
                        </nav>
                    <?php endif; ?>
                </div>
                
                <div class="navbar-actions">
                    <!-- Notifications (if implemented) -->
                    <?php if (isset($showNotifications) && $showNotifications): ?>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-bell"></i>
                                <span class="badge bg-danger">3</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><h6 class="dropdown-header">Notifikasi</h6></li>
                                <li><a class="dropdown-item" href="#">Pemesanan baru</a></li>
                                <li><a class="dropdown-item" href="#">Pembayaran menunggu konfirmasi</a></li>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <!-- User Menu -->
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                            <div class="user-avatar me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                <?php echo strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)); ?>
                            </div>
                            <span class="d-none d-md-inline"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if ($userRole === 'pelanggan'): ?>
                                <li><a class="dropdown-item" href="index.php?page=pelanggan_profil">
                                    <i class="fas fa-user me-2"></i>Profil
                                </a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="index.php?page=logout">
                                <i class="fas fa-sign-out-alt me-2"></i>Keluar
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Alert Messages -->
            <?php
            // Display success messages
            if (isset($_GET['success'])) {
                $message = '';
                switch ($_GET['success']) {
                    case 'upload':
                        $message = 'File berhasil diupload.';
                        break;
                    case 'updated':
                        $message = 'Data berhasil diperbarui.';
                        break;
                    case 'created':
                        $message = 'Data berhasil dibuat.';
                        break;
                    case 'deleted':
                        $message = 'Data berhasil dihapus.';
                        break;
                    case 'approved':
                        $message = 'Pembayaran berhasil disetujui.';
                        break;
                    case 'rejected':
                        $message = 'Pembayaran berhasil ditolak.';
                        break;
                    default:
                        $message = 'Operasi berhasil dilakukan.';
                }
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                echo '<i class="fas fa-check-circle"></i>' . htmlspecialchars($message);
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                echo '</div>';
            }

            // Display error messages
            if (isset($_GET['error'])) {
                $message = '';
                switch ($_GET['error']) {
                    case 'unauthorized':
                        $message = 'Akses ditolak. Silakan login terlebih dahulu.';
                        break;
                    case 'notfound':
                        $message = 'Data tidak ditemukan.';
                        break;
                    case 'upload':
                        $message = 'Gagal mengupload file.';
                        break;
                    case 'invalid':
                        $message = 'Data tidak valid.';
                        break;
                    case 'failed':
                        $message = 'Operasi gagal dilakukan.';
                        break;
                    default:
                        $message = 'Terjadi kesalahan. Silakan coba lagi.';
                }
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                echo '<i class="fas fa-exclamation-triangle"></i>' . htmlspecialchars($message);
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                echo '</div>';
            }

            // Display session messages
            if (isset($_SESSION['success_message'])) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                echo '<i class="fas fa-check-circle"></i>' . htmlspecialchars($_SESSION['success_message']);
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                echo '</div>';
                unset($_SESSION['success_message']);
            }

            if (isset($_SESSION['error_message'])) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                echo '<i class="fas fa-exclamation-triangle"></i>' . htmlspecialchars($_SESSION['error_message']);
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                echo '</div>';
                unset($_SESSION['error_message']);
            }

            if (isset($_SESSION['flash_message'])) {
                echo '<div class="alert alert-info alert-dismissible fade show" role="alert">';
                echo '<i class="fas fa-info-circle"></i>' . htmlspecialchars($_SESSION['flash_message']);
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                echo '</div>';
                unset($_SESSION['flash_message']);
            }
            ?>

    <!-- JavaScript Functions -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const backdrop = document.getElementById('mobileBackdrop');
            
            if (window.innerWidth <= 768) {
                // Mobile behavior
                sidebar.classList.toggle('show');
                if (sidebar.classList.contains('show')) {
                    backdrop.style.display = 'block';
                    backdrop.style.opacity = '1';
                    document.body.style.overflow = 'hidden';
                } else {
                    backdrop.style.opacity = '0';
                    setTimeout(() => {
                        backdrop.style.display = 'none';
                        document.body.style.overflow = 'auto';
                    }, 300);
                }
            } else {
                // Desktop behavior
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            }
        }
        
        // Close sidebar when clicking backdrop on mobile
        document.getElementById('mobileBackdrop').addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                toggleSidebar();
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('mobileBackdrop');
            
            if (window.innerWidth > 768) {
                sidebar.classList.remove('show');
                backdrop.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });
        
        // Auto-dismiss alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (alert.querySelector('.btn-close')) {
                    const bsAlert = new bootstrap.Alert(alert);
                    setTimeout(() => bsAlert.close(), 5000);
                }
            });
        }, 100);
    </script>
    
    <style>
        .mobile-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
            font-size: 0.9rem;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
            color: var(--text-secondary);
        }
        
        .breadcrumb-item a {
            color: var(--text-secondary);
            text-decoration: none;
        }
        
        .breadcrumb-item a:hover {
            color: var(--primary-blue);
        }
        
        .breadcrumb-item.active {
            color: var(--text-primary);
            font-weight: 500;
        }
    </style>
