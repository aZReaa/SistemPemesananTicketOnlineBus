<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-users"></i>
            Data Pelanggan
        </h1>
        <p class="page-subtitle">Lihat dan filter data pelanggan berdasarkan tanggal dan nama</p>
    </div>
</div>

<!-- Filter Form -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-filter me-2 text-primary"></i>
            Filter Data
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="index.php">
            <input type="hidden" name="page" value="petugas_data_pelanggan">
        
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="tanggal_mulai" class="form-label">
                        <i class="fas fa-calendar-alt me-1 text-primary"></i>
                        Tanggal Mulai:
                    </label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" 
                           value="<?= htmlspecialchars($tanggal_mulai) ?>" 
                           class="form-control" required>
                </div>
                
                <div class="col-md-3">
                    <label for="tanggal_selesai" class="form-label">
                        <i class="fas fa-calendar-alt me-1 text-primary"></i>
                        Tanggal Selesai:
                    </label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" 
                           value="<?= htmlspecialchars($tanggal_selesai) ?>" 
                           class="form-control" required>
                </div>
                
                <div class="col-md-4">
                    <label for="filter_nama" class="form-label">
                        <i class="fas fa-search me-1 text-primary"></i>
                        Cari Nama Pelanggan:
                    </label>
                    <input type="text" id="filter_nama" name="filter_nama" 
                           value="<?= htmlspecialchars($filter_nama ?? '') ?>" 
                           class="form-control" placeholder="Masukkan nama pelanggan...">
                </div>
                
                <div class="col-md-2">
                    <label class="form-label d-block">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Cari Data
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Summary -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-chart-bar me-2"></i>
            Ringkasan Data
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary p-3 me-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-users fa-2x text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-0"><?= $total_pelanggan ?></h4>
                        <p class="text-muted mb-0">Total Pelanggan</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-success p-3 me-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-calendar-alt fa-2x text-white"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-nowrap">Periode Pencarian</h6>
                        <p class="mb-0 small">
                            <i class="fas fa-calendar-day me-1"></i> 
                            <?= date('d/m/Y', strtotime($tanggal_mulai)) ?> - <?= date('d/m/Y', strtotime($tanggal_selesai)) ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-light border-primary mb-0">
                    <p class="mb-1"><i class="fas fa-info-circle me-1 text-primary"></i> <strong>Keterangan</strong></p>
                    <small class="d-block mb-1">Data pelanggan menampilkan semua tiket yang sudah terbayar lunas.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list me-2 text-primary"></i>
                Data Pelanggan
            </h5>
            <span class="badge bg-primary"><?= $total_pelanggan ?> data</span>
        </div>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($data_pelanggan)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th style="min-width: 150px;">Nama Pelanggan</th>
                            <th style="min-width: 150px;">Email</th>
                            <th style="min-width: 120px;">No. Telepon</th>
                            <th style="min-width: 120px;">Kode Booking</th>
                            <th style="min-width: 200px;">Rute</th>
                            <th style="min-width: 150px;">Bus</th>
                            <th style="min-width: 150px;">Waktu Berangkat</th>
                            <th class="text-center" style="min-width: 100px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_pelanggan as $index => $pelanggan): ?>
                            <tr>
                                <td class="text-center"><?= $index + 1 ?></td>
                                <td><strong><?= htmlspecialchars($pelanggan['nama'] ?? $pelanggan['username'] ?? '-') ?></strong></td>
                                <td><?= htmlspecialchars($pelanggan['email'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($pelanggan['no_telp'] ?? $pelanggan['no_telepon'] ?? '-') ?></td>
                                <td>
                                    <span class="fw-bold text-primary">
                                        <?= htmlspecialchars($pelanggan['kode_booking'] ?? '-') ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                        $asal = $pelanggan['kota_asal'] ?? $pelanggan['asal'] ?? '-';
                                        $tujuan = $pelanggan['kota_tujuan'] ?? $pelanggan['tujuan'] ?? '-';
                                    ?>
                                    <i class="fas fa-map-marker-alt text-danger me-1"></i><?= htmlspecialchars($asal) ?> 
                                    <i class="fas fa-arrow-right mx-1 text-muted"></i> 
                                    <i class="fas fa-map-marker-alt text-success me-1"></i><?= htmlspecialchars($tujuan) ?>
                                </td>
                                <td>
                                    <i class="fas fa-bus me-1 text-secondary"></i>
                                    <?= htmlspecialchars(($pelanggan['nama_po'] ?? $pelanggan['nama_bus'] ?? '-')) ?>
                                    <span class="badge bg-light text-dark ms-1"><?= htmlspecialchars($pelanggan['kelas'] ?? '-') ?></span>
                                </td>
                                <td>
                                    <?php if (isset($pelanggan['waktu_berangkat'])): ?>
                                        <i class="fas fa-calendar-alt me-1 text-info"></i>
                                        <?= date('d/m/Y', strtotime($pelanggan['waktu_berangkat'])) ?>
                                        <br>
                                        <i class="fas fa-clock me-1 text-muted"></i>
                                        <?= date('H:i', strtotime($pelanggan['waktu_berangkat'])) ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                        $statusClass = 'secondary';
                                        $statusText = '-';
                                        if (isset($pelanggan['status'])) {
                                            switch ($pelanggan['status']) {
                                                case 'used':
                                                    $statusClass = 'secondary';
                                                    $statusText = 'Digunakan';
                                                    $statusIcon = 'check-circle';
                                                    break;
                                                case 'booked':
                                                    $statusClass = 'success';
                                                    $statusText = 'Aktif';
                                                    $statusIcon = 'ticket-alt';
                                                    break;
                                                default:
                                                    $statusClass = 'warning';
                                                    $statusText = ucfirst($pelanggan['status']);
                                                    $statusIcon = 'exclamation-circle';
                                            }
                                        }
                                    ?>
                                    <span class="badge bg-<?= $statusClass ?>">
                                        <i class="fas fa-<?= $statusIcon ?? 'circle' ?> me-1"></i>
                                        <?= $statusText ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info m-4 d-flex align-items-center">
                <i class="fas fa-info-circle fa-2x me-3 text-info"></i>
                <div>
                    <h6 class="mb-1">Tidak ada data</h6>
                    <p class="mb-0">Tidak ada data pelanggan ditemukan untuk periode yang dipilih.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Navigation -->
<div class="d-flex justify-content-between mt-4">
    <a href="index.php?page=petugas_dashboard" class="btn btn-outline-secondary">
        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
    </a>
    <a href="index.php?page=petugas_verifikasi" class="btn btn-primary">
        <i class="fas fa-check-circle me-2"></i>Verifikasi Tiket
    </a>
</div>
