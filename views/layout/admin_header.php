<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Sistem Tiket Bus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #0dcaf0;
        }
        
        .navbar-brand {
            font-weight: bold;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #dc3545, #b02a37);
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: white;
            padding: 12px 20px;
            margin: 5px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.2);
            color: white;
            transform: translateX(5px);
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background: linear-gradient(135deg, #dc3545, #b02a37);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 15px 20px;
        }
        
        .stats-card {
            border-left: 4px solid var(--danger-color);
        }
        
        .stats-card.success {
            border-left-color: var(--success-color);
        }
        
        .stats-card.warning {
            border-left-color: var(--warning-color);
        }
        
        .stats-card.info {
            border-left-color: var(--info-color);
        }
        
        .navbar-top {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .user-info {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 15px;
            margin: 20px 15px;
            color: white;
        }
        
        .btn-logout {
            background: rgba(220,53,69,0.2);
            border: 1px solid rgba(220,53,69,0.3);
            color: white;
        }
        
        .btn-logout:hover {
            background: var(--danger-color);
            border-color: var(--danger-color);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-top navbar-expand-lg">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="navbar-brand ms-3">
                <i class="fas fa-cog text-danger"></i>
                <span class="fw-bold">Administrator</span>
            </div>
            
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                        <i class="fas fa-user-shield me-2"></i>
                        <?= htmlspecialchars($_SESSION['nama'] ?? $_SESSION['username']) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="index.php?page=logout">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar">
                    <!-- User Info -->
                    <div class="user-info text-center">
                        <i class="fas fa-user-shield fa-3x mb-2"></i>
                        <h6 class="mb-1"><?= htmlspecialchars($_SESSION['nama'] ?? $_SESSION['username']) ?></h6>
                        <small>Administrator</small>
                    </div>
                    
                    <!-- Navigation Menu -->
                    <nav class="nav flex-column">
                        <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] == 'admin_dashboard') ? 'active' : '' ?>" 
                           href="index.php?page=admin_dashboard">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                        
                        <a class="nav-link <?= (isset($_GET['page']) && strpos($_GET['page'], 'admin_schedule') !== false) ? 'active' : '' ?>" 
                           href="index.php?page=admin_schedule">
                            <i class="fas fa-calendar-alt"></i>
                            Kelola Jadwal
                        </a>
                        
                        <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] == 'admin_payments') ? 'active' : '' ?>" 
                           href="index.php?page=admin_payments">
                            <i class="fas fa-credit-card"></i>
                            Konfirmasi Pembayaran
                        </a>
                        
                        <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] == 'admin_reports') ? 'active' : '' ?>" 
                           href="index.php?page=admin_reports">
                            <i class="fas fa-chart-bar"></i>
                            Laporan
                        </a>
                        
                        <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] == 'admin_users') ? 'active' : '' ?>" 
                           href="index.php?page=admin_users">
                            <i class="fas fa-users"></i>
                            Kelola Pengguna
                        </a>
                        
                        <hr class="text-white mx-3">
                        
                        <a class="nav-link btn-logout" href="index.php?page=logout">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content p-4">
                    <?php if (isset($_SESSION['flash_message'])): ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($_SESSION['flash_message']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['flash_message']); ?>
                    <?php endif; ?>
