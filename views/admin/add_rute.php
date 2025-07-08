<?php
// File: views/admin/add_rute.php
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>Tambah Rute Baru
                </h5>
            </div>
            <div class="card-body">
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">Gagal menambahkan rute. Silakan coba lagi.</div>
                <?php endif; ?>

                <form action="index.php?page=admin_process_add_rute" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_po" class="form-label">Nama PO Bus</label>
                                <input type="text" class="form-control" id="nama_po" name="nama_po" 
                                       placeholder="Contoh: PO Litha" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kelas" class="form-label">Kelas Bus</label>
                                <select class="form-control" id="kelas" name="kelas" required>
                                    <option value="">Pilih Kelas</option>
                                    <option value="Ekonomi">Ekonomi</option>
                                    <option value="Bisnis">Bisnis</option>
                                    <option value="Eksekutif">Eksekutif</option>
                                    <option value="Super Eksekutif">Super Eksekutif</option>
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
                                    <option value="Makassar">Makassar</option>
                                    <option value="Manado">Manado</option>
                                    <option value="Kendari">Kendari</option>
                                    <option value="Palu">Palu</option>
                                    <option value="Gorontalo">Gorontalo</option>
                                    <option value="Bone">Bone</option>
                                    <option value="Parepare">Parepare</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kota_tujuan" class="form-label">Kota Tujuan</label>
                                <select class="form-control" id="kota_tujuan" name="kota_tujuan" required>
                                    <option value="">Pilih Kota Tujuan</option>
                                    <option value="Makassar">Makassar</option>
                                    <option value="Manado">Manado</option>
                                    <option value="Kendari">Kendari</option>
                                    <option value="Palu">Palu</option>
                                    <option value="Gorontalo">Gorontalo</option>
                                    <option value="Bone">Bone</option>
                                    <option value="Parepare">Parepare</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kapasitas" class="form-label">Kapasitas Kursi</label>
                                <input type="number" class="form-control" id="kapasitas" name="kapasitas" 
                                       placeholder="Contoh: 40" min="20" max="60" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jarak_km" class="form-label">Jarak (KM)</label>
                                <input type="number" class="form-control" id="jarak_km" name="jarak_km" 
                                       placeholder="Contoh: 680" min="1" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nama_rute" class="form-label">Nama Rute (Auto Generated)</label>
                        <input type="text" class="form-control" id="nama_rute" name="nama_rute" readonly>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Rute Bus
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
