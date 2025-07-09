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

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <?php
        switch ($_GET['error']) {
            case 'upload':
                echo 'Gagal mengupload file.';
                break;
            case 'db':
                echo 'Gagal menyimpan data pembayaran.';
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

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Upload Bukti Pembayaran</h1>
                    <p class="text-muted mb-0">Upload bukti transfer untuk konfirmasi pembayaran</p>
                </div>
                <div>
                    <a href="index.php?page=pelanggan_riwayat" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Riwayat
                    </a>
                </div>
            </div>
            
            <div class="row g-4">
                <!-- Detail Pemesanan -->
                <div class="col-md-5">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Detail Pemesanan
                            </h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td width="40%" class="text-muted">Kode Booking:</td>
                                    <td class="fw-bold text-primary"><?php echo htmlspecialchars($pemesanan['kode_booking'] ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Rute:</td>
                                    <td class="fw-bold"><?php echo htmlspecialchars(($pemesanan['asal'] ?? 'N/A') . ' → ' . ($pemesanan['tujuan'] ?? 'N/A')); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tanggal:</td>
                                    <td><?php echo date('d M Y', strtotime($pemesanan['waktu_berangkat'])); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Waktu:</td>
                                    <td><?php echo date('H:i', strtotime($pemesanan['waktu_berangkat'])); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Jumlah Tiket:</td>
                                    <td><?php echo $pemesanan['jumlah_tiket'] ?? 0; ?> tiket</td>
                                </tr>
                                <tr class="border-top">
                                    <td class="text-muted fw-bold">Total Harga:</td>
                                    <td class="text-success fw-bold fs-5">Rp <?php echo number_format($pemesanan['total_harga'], 0, ',', '.'); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Informasi Transfer Bank - ALWAYS VISIBLE -->
                    <div class="card shadow-sm" style="display: block !important; visibility: visible !important; opacity: 1 !important;">
                        <div class="card-header bg-success text-white" style="display: block !important; visibility: visible !important; opacity: 1 !important;">
                            <h6 class="mb-0">
                                <i class="fas fa-university me-2"></i>Informasi Transfer Bank
                            </h6>
                        </div>
                        <div class="card-body" style="display: block !important; visibility: visible !important; opacity: 1 !important;">
                            <div class="alert alert-light border mb-3" style="display: block !important; visibility: visible !important; opacity: 1 !important;">
                                <h6 class="mb-2 text-primary"><i class="fas fa-credit-card me-2"></i>Detail Transfer</h6>
                                <div class="row g-2">
                                    <div class="col-5"><strong>Bank:</strong></div>
                                    <div class="col-7">BCA</div>
                                    <div class="col-5"><strong>No. Rekening:</strong></div>
                                    <div class="col-7">1234567890</div>
                                    <div class="col-5"><strong>Atas Nama:</strong></div>
                                    <div class="col-7">PT Travel Bus Indonesia</div>
                                    <div class="col-5"><strong>Jumlah:</strong></div>
                                    <div class="col-7"><span class="text-success fw-bold">Rp <?php echo number_format($pemesanan['total_harga'] ?? 0, 0, ',', '.'); ?></span></div>
                                </div>
                            </div>
                            
                            <div class="alert alert-warning mb-0" style="display: block !important; visibility: visible !important; opacity: 1 !important;">
                                <h6 class="mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Catatan Penting</h6>
                                <ul class="mb-0 small">
                                    <li>Upload bukti pembayaran yang jelas dan dapat dibaca</li>
                                    <li>Pastikan jumlah transfer sesuai dengan total pembayaran</li>
                                    <li>Konfirmasi akan diproses dalam waktu 1x24 jam</li>
                                    <li>Hubungi customer service jika ada kendala</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Upload -->
                <div class="col-md-7">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-upload me-2"></i>Form Upload Bukti Pembayaran
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="index.php?page=pelanggan_process_upload" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id_pemesanan" value="<?php echo $pemesanan['id_pemesanan']; ?>">
                                
                                <div class="mb-3">
                                    <label for="bukti_pembayaran" class="form-label fw-bold">Pilih File Bukti Pembayaran</label>
                                    <input type="file" class="form-control" id="bukti_pembayaran" name="bukti_pembayaran" 
                                           accept="image/*,.pdf" required>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Format yang diizinkan: JPG, PNG, PDF. Maksimal 2MB.
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label fw-bold">Keterangan (Opsional)</label>
                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="2" 
                                              placeholder="Tambahkan keterangan jika diperlukan..."></textarea>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-upload me-2"></i>Upload Bukti Pembayaran
                                    </button>
                                    <a href="index.php?page=pelanggan_riwayat" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Riwayat
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validasi file upload
document.getElementById('bukti_pembayaran').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const maxSize = 2 * 1024 * 1024; // 2MB
        if (file.size > maxSize) {
            alert('Ukuran file terlalu besar! Maksimal 2MB.');
            e.target.value = '';
            return;
        }
        
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
        if (!validTypes.includes(file.type)) {
            alert('Format file tidak diizinkan! Gunakan JPG, PNG, atau PDF.');
            e.target.value = '';
            return;
        }
    }
});

// Ensure content is always visible
document.addEventListener('DOMContentLoaded', function() {
    // Force all cards to be visible
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.style.display = 'block';
        card.style.visibility = 'visible';
        card.style.opacity = '1';
    });
    
    // Force card bodies to be visible
    const cardBodies = document.querySelectorAll('.card-body');
    cardBodies.forEach(body => {
        body.style.display = 'block';
        body.style.visibility = 'visible';
        body.style.opacity = '1';
    });
    
    // Force alerts to be visible
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.style.display = 'block';
        alert.style.visibility = 'visible';
        alert.style.opacity = '1';
    });
});
</script>

<style>
/* Force all elements to be visible */
.card, .card-body, .card-header, .alert {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* Prevent any collapse or hide issues */
.collapse {
    display: block !important;
    height: auto !important;
}

/* Ensure responsive behavior */
@media (max-width: 768px) {
    .col-md-5, .col-md-7 {
        margin-bottom: 1rem;
    }
}
</style>
