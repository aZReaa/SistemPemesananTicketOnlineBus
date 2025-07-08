<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-tachometer-alt"></i>
            Admin Dashboard
        </h1>
        <p class="page-subtitle">Selamat datang di panel administrasi sistem tiket bus</p>
    </div>
    <div class="text-muted">
        <i class="fas fa-calendar me-1"></i>
        <?php echo date('d M Y'); ?>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-content">
                <h3 class="stats-number"><?php echo $totalPemesanan ?? 0; ?></h3>
                <p class="stats-label">Total Pemesanan</p>
            </div>
            <div class="stats-icon">
                <i class="fas fa-ticket-alt"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card warning">
            <div class="stats-content">
                <h3 class="stats-number"><?php echo $pendingPayments ?? 0; ?></h3>
                <p class="stats-label">Pending Pembayaran</p>
            </div>
            <div class="stats-icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card success">
            <div class="stats-content">
                <h3 class="stats-number"><?php echo $totalRevenue ?? 0; ?></h3>
                <p class="stats-label">Total Pendapatan</p>
            </div>
            <div class="stats-icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card dark">
            <div class="stats-content">
                <h3 class="stats-number"><?php echo $totalUsers ?? 0; ?></h3>
                <p class="stats-label">Total Pengguna</p>
            </div>
            <div class="stats-icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Grafik Pemesanan
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">Grafik Pemesanan</h6>
                    <p class="text-muted">Grafik akan ditampilkan di sini</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bell me-2"></i>Notifikasi
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-credit-card text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold">Pembayaran Menunggu</div>
                            <small class="text-muted">5 pembayaran perlu konfirmasi</small>
                        </div>
                    </div>
                    <div class="list-group-item d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-alt text-info"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold">Jadwal Baru</div>
                            <small class="text-muted">Perlu update jadwal minggu depan</small>
                        </div>
                    </div>
                    <div class="list-group-item d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-users text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold">Pengguna Baru</div>
                            <small class="text-muted">3 pengguna baru hari ini</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>Aktivitas Terbaru
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Pengguna</th>
                                <th>Aktivitas</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo date('H:i'); ?></td>
                                <td>John Doe</td>
                                <td>Membuat pemesanan baru</td>
                                <td><span class="badge badge-success">Berhasil</span></td>
                            </tr>
                            <tr>
                                <td><?php echo date('H:i', strtotime('-5 minutes')); ?></td>
                                <td>Jane Smith</td>
                                <td>Upload bukti pembayaran</td>
                                <td><span class="badge badge-warning">Menunggu</span></td>
                            </tr>
                            <tr>
                                <td><?php echo date('H:i', strtotime('-15 minutes')); ?></td>
                                <td>Admin</td>
                                <td>Konfirmasi pembayaran</td>
                                <td><span class="badge badge-success">Berhasil</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

