<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Riwayat Pemesanan</h1>
        <p class="text-muted mb-0">Daftar semua pemesanan tiket Anda</p>
    </div>
    <div>
        <a href="index.php?page=pelanggan_dashboard" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
    </div>
</div>

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

<div class="card">
    <div class="card-body">
        <?php if (empty($riwayatPemesanan)): ?>
            <div class="text-center py-5">
                <i class="fas fa-ticket-alt fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Belum ada riwayat pemesanan</h4>
                <p class="text-muted">Silakan buat pemesanan tiket pertama Anda</p>
                <a href="index.php?page=pelanggan_dashboard" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Cari Tiket
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Booking</th>
                            <th>Jadwal</th>
                            <th>Jumlah Tiket</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Tanggal Pesan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($riwayatPemesanan as $index => $pemesanan): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td>
                                <strong class="text-primary"><?php echo htmlspecialchars($pemesanan['kode_booking'] ?? 'N/A'); ?></strong>
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($pemesanan['asal'] . ' → ' . $pemesanan['tujuan']); ?></div>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    <?php echo date('d M Y', strtotime($pemesanan['waktu_berangkat'])); ?>
                                </small>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    <?php echo date('H:i', strtotime($pemesanan['waktu_berangkat'])); ?>
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-secondary"><?php echo $pemesanan['jumlah_tiket']; ?> tiket</span>
                            </td>
                            <td>
                                <strong class="text-success">Rp <?php echo number_format($pemesanan['total_harga'], 0, ',', '.'); ?></strong>
                            </td>
                            <td>
                                <?php
                                $status = $pemesanan['status_pembayaran'] ?? 'pending';
                                $badgeClass = 'bg-warning text-dark';
                                $statusText = 'Menunggu Pembayaran';
                                $statusIcon = 'fas fa-clock';
                                
                                if ($status === 'success' || $status === 'paid') {
                                    $badgeClass = 'bg-success';
                                    $statusText = 'Berhasil';
                                    $statusIcon = 'fas fa-check-circle';
                                } elseif ($status === 'failed') {
                                    $badgeClass = 'bg-danger';
                                    $statusText = 'Gagal';
                                    $statusIcon = 'fas fa-times-circle';
                                }
                                ?>
                                <span class="badge <?php echo $badgeClass; ?>">
                                    <i class="<?php echo $statusIcon; ?> me-1"></i>
                                    <?php echo $statusText; ?>
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?php echo date('d M Y H:i', strtotime($pemesanan['tgl_pesan'])); ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="index.php?page=pelanggan_detail&id=<?php echo $pemesanan['id_pemesanan']; ?>" 
                                       class="btn btn-outline-primary" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($status === 'pending'): ?>
                                        <a href="index.php?page=pelanggan_upload_bukti&id=<?php echo $pemesanan['id_pemesanan']; ?>" 
                                           class="btn btn-outline-success" title="Upload Bukti Pembayaran">
                                            <i class="fas fa-upload"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($status === 'success' || $status === 'paid'): ?>
                                        <a href="index.php?page=pelanggan_tiket&id=<?php echo $pemesanan['id_pemesanan']; ?>" 
                                           class="btn btn-outline-info" title="Lihat E-Tiket">
                                            <i class="fas fa-ticket-alt"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($riwayatPemesanan)): ?>
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-primary">Total Pemesanan</h5>
                <h3 class="text-primary"><?php echo count($riwayatPemesanan); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-warning">Menunggu Pembayaran</h5>
                <h3 class="text-warning">
                    <?php 
                    $pending = 0;
                    foreach ($riwayatPemesanan as $pemesanan) {
                        if ($pemesanan['status_pembayaran'] === 'pending') $pending++;
                    }
                    echo $pending;
                    ?>
                </h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-success">Berhasil</h5>
                <h3 class="text-success">
                    <?php 
                    $success = 0;
                    foreach ($riwayatPemesanan as $pemesanan) {
                        if ($pemesanan['status_pembayaran'] === 'success' || $pemesanan['status_pembayaran'] === 'paid') $success++;
                    }
                    echo $success;
                    ?>
                </h3>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
