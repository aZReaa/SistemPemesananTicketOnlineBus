<?php
// File: views/pelanggan/detail_pemesanan.php
?>
<div class="<?php
// File: views/pelanggan/detail_pemesanan.php
?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3><i class="fas fa-ticket-alt me-2"></i>Detail Pemesanan</h3>
                <a href="index.php?page=pelanggan_riwayat" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Riwayat
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Kolom Detail Pemesanan -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Detail Pemesanan</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Kode Booking</h6>
                            <p class="fs-5 fw-bold text-primary"><?php echo htmlspecialchars($pemesanan['kode_booking'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Status Pembayaran</h6>
                            <?php
                            $status = $pemesanan['status_pembayaran'] ?? 'pending';
                            $badgeClass = 'bg-warning text-dark';
                            $statusText = 'Menunggu Pembayaran';
                            $statusIcon = 'fas fa-clock';
                            
                            if ($status === 'success' || $status === 'paid') {
                                $badgeClass = 'bg-success';
                                $statusText = 'Lunas';
                                $statusIcon = 'fas fa-check-circle';
                            } elseif ($status === 'failed') {
                                $badgeClass = 'bg-danger';
                                $statusText = 'Gagal';
                                $statusIcon = 'fas fa-times-circle';
                            }
                            ?>
                            <span class="badge <?php echo $badgeClass; ?> fs-6">
                                <i class="<?php echo $statusIcon; ?> me-1"></i>
                                <?php echo $statusText; ?>
                            </span>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informasi Perjalanan</h6>
                            <p class="mb-1"><strong>Asal:</strong> <?php echo htmlspecialchars($pemesanan['asal']); ?></p>
                            <p class="mb-1"><strong>Tujuan:</strong> <?php echo htmlspecialchars($pemesanan['tujuan']); ?></p>
                            <p class="mb-1"><strong>Tanggal Berangkat:</strong> <?php echo date('d M Y', strtotime($pemesanan['waktu_berangkat'])); ?></p>
                            <p class="mb-1"><strong>Waktu Berangkat:</strong> <?php echo date('H:i', strtotime($pemesanan['waktu_berangkat'])); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Informasi Bus</h6>
                            <p class="mb-1"><strong>Nama Bus:</strong> <?php echo htmlspecialchars($pemesanan['nama_po'] ?? 'N/A'); ?></p>
                            <p class="mb-1"><strong>Kelas:</strong> <?php echo htmlspecialchars($pemesanan['kelas'] ?? 'N/A'); ?></p>
                        </div>
                    </div>

                    <hr>

                    <h6>Detail Penumpang</h6>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Penumpang</th>
                                <th>Nomor Kursi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $kursi_list = [];
                            if (!empty($pemesanan['nomor_kursi'])) {
                                $kursi_list = explode(',', $pemesanan['nomor_kursi']);
                            }
                            
                            if (!empty($kursi_list)): 
                                foreach ($kursi_list as $kursi): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($pemesanan['nama_pelanggan'] ?? $_SESSION['username'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars(trim($kursi)); ?></td>
                                </tr>
                                <?php endforeach; 
                            else: ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($pemesanan['nama_pelanggan'] ?? $_SESSION['username'] ?? 'N/A'); ?></td>
                                    <td>N/A</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kolom Total & Aksi -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Total Pembayaran</h5>
                    <p class="display-6 text-success fw-bold">Rp <?php echo number_format($pemesanan['total_harga'], 0, ',', '.'); ?></p>
                    <hr>
                    <p class="text-muted">Tanggal Pemesanan: <?php echo date('d M Y H:i', strtotime($pemesanan['tgl_pesan'])); ?></p>
                    
                    <?php if ($status === 'pending'): ?>
                    <div class="d-grid gap-2 mt-4">
                        <a href="index.php?page=pelanggan_upload_bukti&id=<?php echo $pemesanan['id_pemesanan']; ?>" class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i>Upload Bukti Pembayaran
                        </a>
                        <button class="btn btn-outline-danger" onclick="confirmCancel()">
                            <i class="fas fa-times me-2"></i>Batalkan Pemesanan
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($status === 'success' || $status === 'paid'): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-qrcode me-2"></i>QR Code Tiket
                    </h5>
                </div>
                <div class="card-body text-center">
                    <?php 
                    require_once __DIR__ . '/../../models/Tiket.php';
                    $tiketModel = new Tiket($pdo);
                    $qrUrl = $tiketModel->generateQRCode($pemesanan['kode_booking'] ?? 'unknown', [
                        'id_pemesanan' => $pemesanan['id_pemesanan'],
                        'nama_pelanggan' => $pemesanan['nama_pelanggan'],
                        'rute' => $pemesanan['asal'] . ' → ' . $pemesanan['tujuan'],
                        'tanggal' => date('Y-m-d', strtotime($pemesanan['waktu_berangkat']))
                    ]);
                    ?>
                    <img src="<?php echo htmlspecialchars($qrUrl); ?>" alt="QR Code Tiket" class="img-fluid mb-3" style="max-width: 200px;">
                    <p class="text-muted small">Tunjukkan QR code ini kepada petugas untuk verifikasi tiket</p>
                    <a href="<?php echo htmlspecialchars($qrUrl); ?>" download="qr-tiket-<?php echo $pemesanan['kode_booking'] ?? 'unknown'; ?>.png" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-download me-2"></i>Download QR Code
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function confirmCancel() {
    if(confirm('Apakah Anda yakin ingin membatalkan pemesanan ini?')) {
        // Arahkan ke skrip pembatalan atau kirim form
        // Contoh: window.location.href = 'index.php?page=cancel_booking&id=<?php echo $pemesanan['id_pemesanan']; ?>';
        alert('Fitur pembatalan belum diimplementasikan.');
    }
}
</script>
                    <thead>
                        <tr>
                            <th>Nama Penumpang</th>
                            <th>Nomor Kursi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pemesanan['tiket'] as $tiket): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($tiket['nama_penumpang']); ?></td>
                            <td><?php echo htmlspecialchars($tiket['nomor_kursi']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Kolom Total & Aksi -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Total Pembayaran</h5>
                <p class="display-6 text-success fw-bold">Rp <?php echo number_format($pemesanan['total_harga'], 0, ',', '.'); ?></p>
                <hr>
                <p class="text-muted">Tanggal Pemesanan: <?php echo date('d M Y H:i', strtotime($pemesanan['tgl_pesan'])); ?></p>
                
                <?php if ($status === 'pending'): ?>
                <div class="d-grid gap-2 mt-4">
                    <a href="index.php?page=pelanggan_upload_bukti&id=<?php echo $pemesanan['id_pemesanan']; ?>" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>Upload Bukti Pembayaran
                    </a>
                    <button class="btn btn-outline-danger" onclick="confirmCancel()">
                        <i class="fas fa-times me-2"></i>Batalkan Pemesanan
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($status === 'success' || $status === 'paid'): ?>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-qrcode me-2"></i>QR Code Tiket
                </h5>
            </div>
            <div class="card-body text-center">
                <?php 
                require_once __DIR__ . '/../../models/Tiket.php';
                $tiketModel = new Tiket($pdo);
                $qrUrl = $tiketModel->generateQRCode($pemesanan['kode_booking'] ?? 'unknown', [
                    'id_pemesanan' => $pemesanan['id_pemesanan'],
                    'nama_pelanggan' => $pemesanan['nama_pelanggan'],
                    'rute' => $pemesanan['asal'] . ' → ' . $pemesanan['tujuan'],
                    'tanggal' => date('Y-m-d', strtotime($pemesanan['waktu_berangkat']))
                ]);
                ?>
                <img src="<?php echo htmlspecialchars($qrUrl); ?>" alt="QR Code Tiket" class="img-fluid mb-3" style="max-width: 200px;">
                <p class="text-muted small">Tunjukkan QR code ini kepada petugas untuk verifikasi tiket</p>
                <a href="<?php echo htmlspecialchars($qrUrl); ?>" download="qr-tiket-<?php echo $pemesanan['kode_booking'] ?? 'unknown'; ?>.png" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-download me-2"></i>Download QR Code
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function confirmCancel() {
    if(confirm('Apakah Anda yakin ingin membatalkan pemesanan ini?')) {
        // Arahkan ke skrip pembatalan atau kirim form
        // Contoh: window.location.href = 'index.php?page=cancel_booking&id=<?php echo $pemesanan['id_pemesanan']; ?>';
        alert('Fitur pembatalan belum diimplementasikan.');
    }
}
</script>
