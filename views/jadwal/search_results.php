<?php
// views/jadwal/search_results.php
require_once __DIR__ . '/../layout/unified_header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-search me-2"></i>
                        Hasil Pencarian Jadwal
                    </h4>
                    <p class="card-subtitle">
                        <?php 
                        $kotaAsal = $_GET['kota_asal'] ?? '';
                        $kotaTujuan = $_GET['kota_tujuan'] ?? '';
                        $tanggal = $_GET['tanggal_keberangkatan'] ?? '';
                        
                        if (!empty($kotaAsal) && !empty($kotaTujuan)) {
                            echo "Rute: " . htmlspecialchars($kotaAsal) . " → " . htmlspecialchars($kotaTujuan);
                        }
                        if (!empty($tanggal)) {
                            echo " | Tanggal: " . htmlspecialchars($tanggal);
                        }
                        ?>
                    </p>
                </div>
                <div class="card-body">
                    <?php if (empty($jadwalList)): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Tidak ada jadwal ditemukan!</strong>
                            <p class="mb-0">
                                Coba ubah kriteria pencarian atau pilih tanggal yang berbeda.
                            </p>
                        </div>
                        <div class="text-center">
                            <a href="index.php?page=search_schedule" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>
                                Cari Lagi
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Ditemukan <strong><?= count($jadwalList) ?></strong> jadwal
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>PO Bus</th>
                                        <th>Rute</th>
                                        <th>Kelas</th>
                                        <th>Waktu</th>
                                        <th>Harga</th>
                                        <th>Kapasitas</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($jadwalList as $jadwal): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($jadwal['nama_po']) ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?= htmlspecialchars($jadwal['kota_asal']) ?> → <?= htmlspecialchars($jadwal['kota_tujuan']) ?>
                                                </span>
                                                <small class="text-muted d-block">
                                                    <?= htmlspecialchars($jadwal['jarak_km']) ?> km
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?= htmlspecialchars($jadwal['kelas']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <strong><?= date('H:i', strtotime($jadwal['waktu_berangkat'])) ?></strong>
                                                <small class="text-muted d-block">
                                                    <?= date('d/m/Y', strtotime($jadwal['waktu_berangkat'])) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <strong class="text-primary">
                                                    Rp <?= number_format($jadwal['harga'], 0, ',', '.') ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <?= htmlspecialchars($jadwal['kapasitas']) ?> kursi
                                                </span>
                                            </td>
                                            <td>
                                                <a href="index.php?page=select_seat&id_jadwal=<?= $jadwal['id_jadwal'] ?>" 
                                                   class="btn btn-primary btn-sm">
                                                    <i class="fas fa-chair me-1"></i>
                                                    Pilih Kursi
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mt-3">
                        <a href="index.php?page=search_schedule" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Kembali ke Pencarian
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
                        <div class="col-md-3">
                            <label for="tanggal" class="form-label">Tanggal Keberangkatan</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" 
                                   value="<?php echo htmlspecialchars($_POST['tanggal'] ?? ''); ?>" 
                                   min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary d-block w-100">
                                <i class="fas fa-search me-2"></i>Cari Jadwal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Results -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Daftar Jadwal 
                    <?php if (!empty($jadwalList)): ?>
                        <span class="badge bg-primary"><?php echo count($jadwalList); ?> jadwal ditemukan</span>
                    <?php endif; ?>
                </h5>
            </div>
            <div class="card-body p-0">
                <?php if (empty($jadwalList)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">Tidak ada jadwal ditemukan</h6>
                        <p class="text-muted">Coba ubah kriteria pencarian atau pilih tanggal lain</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Operator Bus</th>
                                    <th>Rute</th>
                                    <th>Kelas</th>
                                    <th>Waktu Berangkat</th>
                                    <th>Waktu Tiba</th>
                                    <th>Kapasitas</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($jadwalList as $jadwal): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?php echo htmlspecialchars($jadwal['nama_po']); ?></div>
                                            <small class="text-muted"><?php echo htmlspecialchars($jadwal['kelas']); ?></small>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary"><?php echo htmlspecialchars($jadwal['kota_asal']); ?></span>
                                            <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                            <span class="badge badge-primary"><?php echo htmlspecialchars($jadwal['kota_tujuan']); ?></span>
                                            <?php if (!empty($jadwal['jarak_km'])): ?>
                                                <br><small class="text-muted"><?php echo $jadwal['jarak_km']; ?> km</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary"><?php echo htmlspecialchars($jadwal['kelas']); ?></span>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?php echo date('H:i', strtotime($jadwal['waktu_berangkat'])); ?></div>
                                            <small class="text-muted"><?php echo date('d M Y', strtotime($jadwal['waktu_berangkat'])); ?></small>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?php echo date('H:i', strtotime($jadwal['waktu_tiba'])); ?></div>
                                            <small class="text-muted"><?php echo date('d M Y', strtotime($jadwal['waktu_tiba'])); ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?php echo $jadwal['kapasitas']; ?> kursi</span>
                                        </td>
                                        <td class="text-end">
                                            <div class="fw-bold text-success">Rp <?php echo number_format($jadwal['harga'], 0, ',', '.'); ?></div>
                                        </td>
                                        <td class="text-center">
                                            <a href="index.php?page=select_seat&id_jadwal=<?php echo $jadwal['id_jadwal']; ?>" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-chair me-1"></i>Pilih Kursi
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
