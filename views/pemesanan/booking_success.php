<?php
// views/pemesanan/booking_success.php
require_once __DIR__ . '/../layout/unified_header.php';
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center">
                <div class="card-header bg-success text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-check-circle me-2"></i>
                        Pemesanan Berhasil!
                    </h4>
                </div>
                <div class="card-body">
                    <p class="card-text">Terima kasih, <strong><?php echo htmlspecialchars($nama_pemesan ?? $_SESSION['username']); ?></strong>. Pesanan Anda telah kami terima.</p>
                    
                    <div class="alert alert-primary" role="alert">
                        <h4 class="alert-heading">Kode Booking Anda:</h4>
                        <p class="display-6"><strong><?php echo htmlspecialchars($kode_booking ?? 'N/A'); ?></strong></p>
                        <hr>
                        <p class="mb-0">Harap simpan kode ini untuk melakukan konfirmasi pembayaran dan verifikasi tiket.</p>
                    </div>

                    <ul class="list-group list-group-flush my-4">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Jumlah Tiket yang Dipesan
                            <span class="badge bg-primary rounded-pill"><?php echo count($seats ?? []); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Nomor Kursi
                            <span><?php echo htmlspecialchars(implode(', ', $seats ?? [])); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Total Pembayaran</strong>
                            <strong class="text-primary">Rp <?php echo number_format($total_harga ?? 0, 0, ',', '.'); ?></strong>
                        </li>
                    </ul>

                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-clock me-2"></i>
                        <strong>Langkah Selanjutnya:</strong>
                        <p class="mb-0">Silakan lakukan pembayaran sebelum batas waktu yang ditentukan. Anda dapat mengakses menu pembayaran dari dashboard atau riwayat pemesanan.</p>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="index.php?page=pelanggan_riwayat" class="btn btn-primary">
                            <i class="fas fa-credit-card me-2"></i>
                            Bayar Sekarang
                        </a>
                        <a href="index.php?page=pelanggan_dashboard" class="btn btn-secondary">
                            <i class="fas fa-home me-2"></i>
                            Kembali ke Dashboard
                        </a>
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Batas waktu pembayaran: 2 jam dari sekarang.
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
