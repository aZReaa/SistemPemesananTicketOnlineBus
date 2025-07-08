<?php
// views/jadwal/search_form.php
require_once __DIR__ . '/../layout/unified_header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-search me-2"></i>
                        Cari Jadwal Bus
                    </h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="index.php">
                        <input type="hidden" name="page" value="search_results">
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="kota_asal" class="form-label">Kota Asal</label>
                                <select name="kota_asal" id="kota_asal" class="form-select" required>
                                    <option value="">Pilih Kota Asal</option>
                                    <?php 
                                    echo "<!-- Debug: " . count($kotaList) . " kota ditemukan -->";
                                    foreach ($kotaList as $kota): ?>
                                        <option value="<?= htmlspecialchars($kota) ?>"><?= htmlspecialchars($kota) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="kota_tujuan" class="form-label">Kota Tujuan</label>
                                <select name="kota_tujuan" id="kota_tujuan" class="form-select" required>
                                    <option value="">Pilih Kota Tujuan</option>
                                    <?php foreach ($kotaList as $kota): ?>
                                        <option value="<?= htmlspecialchars($kota) ?>"><?= htmlspecialchars($kota) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="tanggal_keberangkatan" class="form-label">Tanggal Keberangkatan</label>
                                <input type="date" name="tanggal_keberangkatan" id="tanggal_keberangkatan" 
                                       class="form-control" required min="<?= date('Y-m-d') ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>
                                    Cari Jadwal
                                </button>
                                <a href="index.php?page=pelanggan_dashboard" class="btn btn-secondary ms-2">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Kembali ke Dashboard
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
