<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-user-tie"></i>
            Dashboard Petugas
        </h1>
        <p class="page-subtitle">Selamat datang, <?= htmlspecialchars($_SESSION['nama'] ?? $_SESSION['username']) ?>!</p>
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
            <div class="stats-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stats-content">
                <div class="stats-number"><?= $stats['total_tiket_verified'] ?? 0 ?></div>
                <div class="stats-label">Tiket Diverifikasi</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card success">
            <div class="stats-icon">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <div class="stats-content">
                <div class="stats-number"><?= $stats['tiket_digunakan'] ?? 0 ?></div>
                <div class="stats-label">Tiket Digunakan</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card warning">
            <div class="stats-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stats-content">
                <div class="stats-number"><?= $stats['total_pelanggan'] ?? 0 ?></div>
                <div class="stats-label">Total Pelanggan</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card dark">
            <div class="stats-icon">
                <i class="fas fa-coins"></i>
            </div>
            <div class="stats-content">
                <div class="stats-number">Rp <?= number_format($stats['total_pembayaran_offline'] ?? 0, 0, ',', '.') ?></div>
                <div class="stats-label">Total Pembayaran</div>
            </div>
        </div>
    </div>
</div>
<!-- Menu Utama -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-check-circle me-2"></i>
                    Verifikasi Tiket
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">Verifikasi dan validasi tiket penumpang dengan scan kode booking atau input manual.</p>
                <a href="index.php?page=petugas_verifikasi" class="btn btn-primary">
                    <i class="fas fa-qrcode me-2"></i>
                    Mulai Verifikasi
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-cash-register me-2"></i>
                    Pembayaran Offline
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">Terima pembayaran tunai untuk pemesanan yang belum lunas di loket.</p>
                <a href="index.php?page=petugas_pembayaran_offline" class="btn btn-success">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    Terima Pembayaran
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>
                    Data Pengunjung
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">Lihat dan filter data pengunjung berdasarkan tanggal dan nama.</p>
                <a href="index.php?page=petugas_data_pengunjung" class="btn btn-info">
                    <i class="fas fa-list-alt me-2"></i>
                    Lihat Data
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>
                    Shift Kerja
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">Informasi shift kerja dan jadwal tugas petugas loket.</p>
                <div class="alert alert-light">
                    <strong>Shift Aktif:</strong> <?= date('H:i') ?> - Shift <?= (date('H') < 12) ? 'Pagi' : ((date('H') < 18) ? 'Siang' : 'Malam') ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Aksi Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <a href="index.php?page=petugas_verifikasi" class="btn btn-outline-primary w-100">
                            <i class="fas fa-search me-2"></i>
                            Cari Tiket
                        </a>
                    </div>
                    <div class="col-md-4 mb-2">
                        <a href="index.php?page=petugas_data_pengunjung?tanggal_mulai=<?= date('Y-m-d') ?>&tanggal_selesai=<?= date('Y-m-d') ?>" 
                           class="btn btn-outline-info w-100">
                            <i class="fas fa-calendar-day me-2"></i>
                            Pengunjung Hari Ini
                        </a>
                    </div>
                    <div class="col-md-4 mb-2">
                        <a href="index.php?page=logout" class="btn btn-outline-danger w-100">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pemesanan Pending -->
<?php if (!empty($pendingPayments)): ?>
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>
                    Pemesanan Menunggu Pembayaran
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Kode Booking</th>
                                <th>Nama Penumpang</th>
                                <th>Rute</th>
                                <th>Tanggal Berangkat</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($pendingPayments, 0, 5) as $payment): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($payment['kode_booking']) ?></strong></td>
                                    <td><?= htmlspecialchars($payment['nama']) ?></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= htmlspecialchars($payment['asal']) ?> → <?= htmlspecialchars($payment['tujuan']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($payment['tanggal_berangkat'] . ' ' . $payment['jam_berangkat'])) ?></td>
                                    <td><strong>Rp <?= number_format($payment['total_harga'], 0, ',', '.') ?></strong></td>
                                    <td>
                                        <a href="index.php?page=petugas_pembayaran_offline" class="btn btn-sm btn-success">
                                            <i class="fas fa-cash-register me-1"></i>
                                            Bayar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (count($pendingPayments) > 5): ?>
                    <div class="text-center mt-3">
                        <a href="index.php?page=petugas_pembayaran_offline" class="btn btn-primary">
                            Lihat Semua (<?= count($pendingPayments) ?> total)
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
