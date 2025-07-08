<?php
// Helper untuk memformat tanggal ke format yang diterima oleh input datetime-local
function format_datetime_local($datetime_string) {
    if (empty($datetime_string)) return '';
    $date = new DateTime($datetime_string);
    return $date->format('Y-m-d\TH:i');
}
?>

<!-- Page Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="page-content">
            <div class="page-header">
                <h1 class="page-title">Ubah Jadwal</h1>
                <div class="page-actions">
                    <a href="index.php?page=admin_schedule" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="form-card">
                <div class="form-header">
                    <h5 class="mb-0">Informasi Jadwal</h5>
                </div>
                <div class="form-body">
                    <form action="index.php?page=admin_update_schedule" method="POST">
                        <input type="hidden" name="id_jadwal" value="<?php echo htmlspecialchars($jadwal['id_jadwal']); ?>">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="id_rute" class="form-label">Pilih Rute</label>
                                <select class="form-select" id="id_rute" name="id_rute" required>
                                    <option value="">-- Pilih Rute --</option>
                                    <?php foreach ($ruteList as $rute): ?>
                                        <option value="<?php echo $rute['id_rute']; ?>" <?php echo ($rute['id_rute'] == $jadwal['id_rute']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($rute['nama_rute'] . ' - ' . $rute['kelas']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="waktu_berangkat" class="form-label">Waktu Berangkat</label>
                                <input type="datetime-local" class="form-control" id="waktu_berangkat" name="waktu_berangkat" value="<?php echo format_datetime_local($jadwal['waktu_berangkat']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="waktu_tiba" class="form-label">Waktu Tiba</label>
                                <input type="datetime-local" class="form-control" id="waktu_tiba" name="waktu_tiba" value="<?php echo format_datetime_local($jadwal['waktu_tiba']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="harga" class="form-label">Harga</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="harga" name="harga" min="0" value="<?php echo htmlspecialchars($jadwal['harga']); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../layout/footer.php'; ?>
