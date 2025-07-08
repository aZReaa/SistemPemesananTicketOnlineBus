<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-chart-bar"></i>
            Laporan Penjualan
        </h1>
        <p class="page-subtitle">Analisis dan laporan keuangan</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stats-content">
                <div class="stats-number">Rp <?php echo number_format($totalSales ?? 0, 0, ',', '.'); ?></div>
                <div class="stats-label">Total Pendapatan</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="stats-content">
                <div class="stats-number"><?php echo count($salesData ?? []); ?></div>
                <div class="stats-label">Total Transaksi</div>
            </div>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="page-content">
    <!-- Filter Tanggal -->
    <div class="form-card mb-4">
        <div class="form-header">
            <h5 class="mb-0">Filter Laporan</h5>
        </div>
        <div class="form-body">
            <form method="GET" action="index.php">
                        <input type="hidden" name="page" value="admin_reports">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" id="start_date" name="start_date" class="form-control" value="<?php echo htmlspecialchars($startDate); ?>">
                            </div>
                            <div class="col-md-5">
                                <label for="end_date" class="form-label">Tanggal Akhir</label>
                                <input type="date" id="end_date" name="end_date" class="form-control" value="<?php echo htmlspecialchars($endDate); ?>">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Detail Laporan -->
            <div class="table-card">
                <div class="table-header">
                    <h5 class="mb-0">Detail Transaksi</h5>
                </div>
                <div class="table-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID Pesanan</th>
                                    <th>Pelanggan</th>
                                    <th>Tgl. Pembayaran</th>
                                    <th class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($salesData)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-2"></i>
                                            <div>Tidak ada data penjualan pada periode ini</div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($salesData as $sale): ?>
                                        <tr>
                                            <td><span class="badge bg-primary">#<?php echo htmlspecialchars($sale['id_pemesanan']); ?></span></td>
                                            <td><?php echo htmlspecialchars($sale['nama_pelanggan']); ?></td>
                                            <td><?php echo htmlspecialchars(date('d M Y, H:i', strtotime($sale['waktu_pembayaran']))); ?></td>
                                            <td class="text-end fw-bold">Rp <?php echo number_format($sale['jumlah'], 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
