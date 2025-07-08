<!-- Alert Messages -->
<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?= htmlspecialchars($_SESSION['success_message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <?= htmlspecialchars($_SESSION['error_message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?php
        switch ($_GET['success']) {
            case 'upload':
                echo 'Bukti pembayaran berhasil diupload. Menunggu konfirmasi admin.';
                break;
            default:
                echo 'Operasi berhasil dilakukan.';
                break;
        }
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <?php
        switch ($_GET['error']) {
            case 'notfound':
                echo 'Data tidak ditemukan.';
                break;
            case 'upload':
                echo 'Gagal mengupload file.';
                break;
            case 'db':
                echo 'Gagal menyimpan data.';
                break;
            case 'file':
                echo 'File tidak valid atau tidak ada file yang dipilih.';
                break;
            default:
                echo 'Terjadi kesalahan.';
                break;
        }
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-user-circle text-primary" style="font-size: 4rem;"></i>
                </div>
                <h2 class="text-primary mb-2">Selamat Datang, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
                <p class="text-muted lead">Kelola pemesanan tiket bus Anda dengan mudah</p>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-icon text-primary">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <div class="stats-content">
                <h3><?= count($riwayatPemesanan) ?></h3>
                <p>Total Pemesanan</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-icon text-success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stats-content">
                <h3><?= count(array_filter($riwayatPemesanan, function($p) { return $p['status_pembayaran'] === 'success'; })) ?></h3>
                <p>Tiket Lunas</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-icon text-warning">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stats-content">
                <h3><?= count(array_filter($riwayatPemesanan, function($p) { return $p['status_pembayaran'] === 'pending'; })) ?></h3>
                <p>Menunggu Bayar</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-icon text-danger">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stats-content">
                <h3><?= count(array_filter($riwayatPemesanan, function($p) { return $p['status'] === 'cancelled'; })) ?></h3>
                <p>Dibatalkan</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0">Menu Utama</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="?page=search_schedule" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-search me-2"></i>
                            Cari Tiket
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="?page=pelanggan_riwayat" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-history me-2"></i>
                            Riwayat Pemesanan
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="?page=pelanggan_profil" class="btn btn-outline-primary btn-lg w-100">
                            <i class="fas fa-user me-2"></i>
                            Profil Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
<?php if (!empty($riwayatPemesanan)): ?>
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>Pemesanan Terbaru
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Rute</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $recent = array_slice($riwayatPemesanan, 0, 3);
                            foreach ($recent as $pemesanan): 
                            ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($pemesanan['asal'] . ' → ' . $pemesanan['tujuan']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($pemesanan['nama_po']); ?></small>
                                </td>
                                <td>
                                    <span class="fw-bold"><?php echo date('d M Y', strtotime($pemesanan['waktu_berangkat'])); ?></span><br>
                                    <small class="text-muted"><?php echo date('H:i', strtotime($pemesanan['waktu_berangkat'])); ?></small>
                                </td>
                                <td>
                                    <?php
                                    $status = $pemesanan['status_pembayaran'];
                                    $badgeClass = 'bg-warning';
                                    $statusText = 'Pending';
                                    
                                    if ($status === 'success') {
                                        $badgeClass = 'bg-success';
                                        $statusText = 'Berhasil';
                                    } elseif ($status === 'failed') {
                                        $badgeClass = 'bg-danger';
                                        $statusText = 'Gagal';
                                    }
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?>"><?php echo $statusText; ?></span>
                                </td>
                                <td>
                                    <a href="?page=pelanggan_detail&id=<?php echo $pemesanan['id_pemesanan']; ?>" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                    </table>
                </div>
                
                <?php if (count($riwayatPemesanan) > 3): ?>
                    <div class="text-center p-3 border-top">
                        <a href="?page=pelanggan_riwayat" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i>Lihat Semua Pemesanan
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
