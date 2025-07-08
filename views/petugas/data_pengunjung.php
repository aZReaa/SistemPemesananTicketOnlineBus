<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-users"></i>
            Data Pengunjung
        </h1>
        <p class="page-subtitle">Lihat dan filter data pengunjung berdasarkan tanggal dan nama</p>
    </div>
</div>

<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-filter me-2"></i>
            Filter Data
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="index.php">
            <input type="hidden" name="page" value="petugas_data_pengunjung">
        
            <div class="row">
                <div class="col-md-3">
                    <label for="tanggal_mulai">Tanggal Mulai:</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" 
                           value="<?= htmlspecialchars($tanggal_mulai) ?>" 
                           class="form-control" required>
                </div>
                
                <div class="col-md-3">
                    <label for="tanggal_selesai">Tanggal Selesai:</label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" 
                           value="<?= htmlspecialchars($tanggal_selesai) ?>" 
                           class="form-control" required>
                </div>
                
                <div class="col-md-3">
                    <label for="filter_nama">Filter Nama:</label>
                    <input type="text" id="filter_nama" name="filter_nama" 
                           value="<?= htmlspecialchars($filter_nama ?? '') ?>" 
                           class="form-control" placeholder="Nama penumpang">
                </div>
                
                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary form-control">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </div>
        </form>
            </div>
        </div>
        
        <!-- Summary -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-primary">
                            <i class="fas fa-chart-bar me-2"></i>
                            Ringkasan Data
                        </h5>
                        <p class="mb-1"><strong>Total Pengunjung:</strong> <?= $total_pengunjung ?> orang</p>
                        <p class="mb-0"><strong>Periode:</strong> <?= date('d/m/Y', strtotime($tanggal_mulai)) ?> - <?= date('d/m/Y', strtotime($tanggal_selesai)) ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Data Table -->
        <?php if (!empty($data_pengunjung)): ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Penumpang</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Kode Booking</th>
                            <th>Rute</th>
                            <th>Bus</th>
                            <th>Tanggal Berangkat</th>
                            <th>Jam Berangkat</th>
                            <th>Status Tiket</th>
                            <th>Tanggal Digunakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_pengunjung as $index => $pengunjung): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($pengunjung['nama']) ?></td>
                                <td><?= htmlspecialchars($pengunjung['email']) ?></td>
                                <td><?= htmlspecialchars($pengunjung['no_telepon']) ?></td>
                                <td><strong><?= htmlspecialchars($pengunjung['kode_booking']) ?></strong></td>
                                <td><?= htmlspecialchars($pengunjung['asal']) ?> - <?= htmlspecialchars($pengunjung['tujuan']) ?></td>
                                <td><?= htmlspecialchars($pengunjung['nama_bus']) ?> (<?= htmlspecialchars($pengunjung['nomor_bus']) ?>)</td>
                                <td><?= date('d/m/Y', strtotime($pengunjung['tanggal_berangkat'])) ?></td>
                                <td><?= date('H:i', strtotime($pengunjung['jam_berangkat'])) ?></td>
                                <td>
                                    <span class="status-badge status-<?= $pengunjung['status_tiket'] ?>">
                                        <?= ucfirst($pengunjung['status_tiket']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?= $pengunjung['tanggal_digunakan'] ? 
                                        date('d/m/Y H:i', strtotime($pengunjung['tanggal_digunakan'])) : 
                                        '-' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Tidak ada data pengunjung ditemukan untuk periode yang dipilih.
            </div>
        <?php endif; ?>
        
        <!-- Navigation -->
        <div class="mt-4">
            <a href="index.php?page=petugas_verifikasi" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Verifikasi Tiket
            </a>
        </div>
    </div>
</div>
