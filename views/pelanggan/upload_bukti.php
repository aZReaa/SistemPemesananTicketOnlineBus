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

<!-- Upload Bukti Pembayaran -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Upload Bukti Pembayaran</h5>
                    <a href="index.php?page=pelanggan_riwayat" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Riwayat
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Detail Pemesanan -->
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light h-100">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>Detail Pemesanan
                            </h6>
                            <div class="small">
                                <p><strong>Kode:</strong> <?php echo htmlspecialchars($pemesanan['kode_booking'] ?? 'N/A'); ?></p>
                                <p><strong>Rute:</strong> <?php echo htmlspecialchars(($pemesanan['asal'] ?? 'N/A') . ' → ' . ($pemesanan['tujuan'] ?? 'N/A')); ?></p>
                                <p><strong>Tanggal:</strong> <?php echo date('d M Y', strtotime($pemesanan['waktu_berangkat'])); ?></p>
                                <p><strong>Waktu:</strong> <?php echo date('H:i', strtotime($pemesanan['waktu_berangkat'])); ?></p>
                                <p><strong>Tiket:</strong> <?php echo $pemesanan['jumlah_tiket'] ?? 0; ?> tiket</p>
                                <p class="mb-0"><strong class="text-success">Total: Rp <?php echo number_format($pemesanan['total_harga'], 0, ',', '.'); ?></strong></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Info Transfer Bank -->
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light h-100">
                            <h6 class="text-success mb-3">
                                <i class="fas fa-university me-2"></i>Info Transfer
                            </h6>
                            <div class="small">
                                <p><strong>Bank:</strong> BCA</p>
                                <p><strong>No. Rekening:</strong> 1234567890</p>
                                <p><strong>Atas Nama:</strong> PT Travel Bus Indonesia</p>
                                <p><strong class="text-success">Jumlah: Rp <?php echo number_format($pemesanan['total_harga'] ?? 0, 0, ',', '.'); ?></strong></p>
                            </div>
                            <div class="alert alert-warning alert-sm mt-3 py-2">
                                <strong>Catatan:</strong>
                                <ul class="mb-0 small">
                                    <li>Upload bukti jelas</li>
                                    <li>Sesuai jumlah transfer</li>
                                    <li>Proses 1x24 jam</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Upload -->
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light h-100">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-upload me-2"></i>Upload Bukti
                            </h6>
                            
                            <form action="index.php?page=pelanggan_process_upload" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id_pemesanan" value="<?php echo $pemesanan['id_pemesanan']; ?>">
                                
                                <div class="mb-3">
                                    <label for="bukti_pembayaran" class="form-label fw-bold small">File Bukti</label>
                                    <input type="file" class="form-control form-control-sm" id="bukti_pembayaran" name="bukti_pembayaran" 
                                           accept="image/*,.pdf" required>
                                    <div class="form-text small">JPG, PNG, PDF. Max 2MB.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label fw-bold small">Keterangan</label>
                                    <textarea class="form-control form-control-sm" id="keterangan" name="keterangan" rows="2" 
                                              placeholder="Opsional..."></textarea>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-upload me-1"></i>Upload
                                    </button>
                                    <a href="index.php?page=pelanggan_riwayat" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-arrow-left me-1"></i>Kembali
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
// Simple file validation
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
</script>
