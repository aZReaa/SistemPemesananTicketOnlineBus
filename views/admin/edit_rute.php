<?php
// File: views/admin/edit_rute.php
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Edit Rute
                </h5>
            </div>
            <div class="card-body">
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        <?php if ($_GET['error'] == 'incomplete'): ?>
                            Data tidak lengkap. Silakan lengkapi semua field.
                        <?php else: ?>
                            Gagal mengupdate rute. Silakan coba lagi.
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <form action="index.php?page=admin_update_rute" method="POST">
                    <input type="hidden" name="id_rute" value="<?php echo htmlspecialchars($rute['id_rute']); ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_po" class="form-label">Nama PO Bus</label>
                                <input type="text" class="form-control" id="nama_po" name="nama_po" 
                                       value="<?php echo htmlspecialchars($rute['nama_po']); ?>" 
                                       placeholder="Contoh: PO Litha" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kelas" class="form-label">Kelas Bus</label>
                                <select class="form-control" id="kelas" name="kelas" required>
                                    <option value="">Pilih Kelas</option>
                                    <option value="Ekonomi" <?php echo ($rute['kelas'] == 'Ekonomi') ? 'selected' : ''; ?>>Ekonomi</option>
                                    <option value="Bisnis" <?php echo ($rute['kelas'] == 'Bisnis') ? 'selected' : ''; ?>>Bisnis</option>
                                    <option value="Eksekutif" <?php echo ($rute['kelas'] == 'Eksekutif') ? 'selected' : ''; ?>>Eksekutif</option>
                                    <option value="Super Eksekutif" <?php echo ($rute['kelas'] == 'Super Eksekutif') ? 'selected' : ''; ?>>Super Eksekutif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kota_asal" class="form-label">Kota Asal</label>
                                <select class="form-control" id="kota_asal" name="kota_asal" required>
                                    <option value="">Pilih Kota Asal</option>
                                    <option value="Makassar" <?php echo ($rute['kota_asal'] == 'Makassar') ? 'selected' : ''; ?>>Makassar</option>
                                    <option value="Manado" <?php echo ($rute['kota_asal'] == 'Manado') ? 'selected' : ''; ?>>Manado</option>
                                    <option value="Kendari" <?php echo ($rute['kota_asal'] == 'Kendari') ? 'selected' : ''; ?>>Kendari</option>
                                    <option value="Palu" <?php echo ($rute['kota_asal'] == 'Palu') ? 'selected' : ''; ?>>Palu</option>
                                    <option value="Gorontalo" <?php echo ($rute['kota_asal'] == 'Gorontalo') ? 'selected' : ''; ?>>Gorontalo</option>
                                    <option value="Bone" <?php echo ($rute['kota_asal'] == 'Bone') ? 'selected' : ''; ?>>Bone</option>
                                    <option value="Parepare" <?php echo ($rute['kota_asal'] == 'Parepare') ? 'selected' : ''; ?>>Parepare</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kota_tujuan" class="form-label">Kota Tujuan</label>
                                <select class="form-control" id="kota_tujuan" name="kota_tujuan" required>
                                    <option value="">Pilih Kota Tujuan</option>
                                    <option value="Makassar" <?php echo ($rute['kota_tujuan'] == 'Makassar') ? 'selected' : ''; ?>>Makassar</option>
                                    <option value="Manado" <?php echo ($rute['kota_tujuan'] == 'Manado') ? 'selected' : ''; ?>>Manado</option>
                                    <option value="Kendari" <?php echo ($rute['kota_tujuan'] == 'Kendari') ? 'selected' : ''; ?>>Kendari</option>
                                    <option value="Palu" <?php echo ($rute['kota_tujuan'] == 'Palu') ? 'selected' : ''; ?>>Palu</option>
                                    <option value="Gorontalo" <?php echo ($rute['kota_tujuan'] == 'Gorontalo') ? 'selected' : ''; ?>>Gorontalo</option>
                                    <option value="Bone" <?php echo ($rute['kota_tujuan'] == 'Bone') ? 'selected' : ''; ?>>Bone</option>
                                    <option value="Parepare" <?php echo ($rute['kota_tujuan'] == 'Parepare') ? 'selected' : ''; ?>>Parepare</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kapasitas" class="form-label">Kapasitas Kursi</label>
                                <input type="number" class="form-control" id="kapasitas" name="kapasitas" 
                                       value="<?php echo htmlspecialchars($rute['kapasitas']); ?>"
                                       placeholder="Contoh: 40" min="20" max="60" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jarak_km" class="form-label">Jarak (KM)</label>
                                <input type="number" class="form-control" id="jarak_km" name="jarak_km" 
                                       value="<?php echo htmlspecialchars($rute['jarak_km']); ?>"
                                       placeholder="Contoh: 680" min="1" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nama_rute" class="form-label">Nama Rute (Auto Generated)</label>
                        <input type="text" class="form-control" id="nama_rute" name="nama_rute" 
                               value="<?php echo htmlspecialchars($rute['nama_rute']); ?>" readonly>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Rute Bus
                        </button>
                        <a href="index.php?page=admin_rutes" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-generate nama rute berdasarkan PO, kota asal dan tujuan
document.getElementById('nama_po').addEventListener('input', generateNamaRute);
document.getElementById('kota_asal').addEventListener('change', generateNamaRute);
document.getElementById('kota_tujuan').addEventListener('change', generateNamaRute);

function generateNamaRute() {
    const namaPO = document.getElementById('nama_po').value;
    const kotaAsal = document.getElementById('kota_asal').value;
    const kotaTujuan = document.getElementById('kota_tujuan').value;
    
    if (namaPO && kotaAsal && kotaTujuan) {
        document.getElementById('nama_rute').value = namaPO + ' (' + kotaAsal + ' - ' + kotaTujuan + ')';
    }
}
</script>
