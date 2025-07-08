<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-calendar-alt"></i>
            Kelola Jadwal
        </h1>
        <p class="page-subtitle">Manajemen jadwal perjalanan bus</p>
    </div>
    <div>
        <a href="index.php?page=admin_add_schedule" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Jadwal
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Daftar Jadwal
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Operator</th>
                                <th>Rute</th>
                                <th>Waktu Berangkat</th>
                                <th>Waktu Tiba</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($jadwalList)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i><br>
                                        <h6 class="text-muted">Tidak ada jadwal tersedia</h6>
                                        <p class="text-muted">Silakan tambah jadwal baru</p>
                                    </td>
                                </tr>
                            <?php else: ?>
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
                                        </td>
                                        <td><?php echo htmlspecialchars(date('d M Y, H:i', strtotime($jadwal['waktu_berangkat']))); ?></td>
                                        <td><?php echo htmlspecialchars(date('d M Y, H:i', strtotime($jadwal['waktu_tiba']))); ?></td>
                                        <td>
                                            <span class="fw-bold text-success">Rp <?php echo number_format($jadwal['harga'], 0, ',', '.'); ?></span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="index.php?page=admin_edit_schedule&id=<?php echo $jadwal['id_jadwal']; ?>" class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="index.php?page=admin_delete_schedule&id=<?php echo $jadwal['id_jadwal']; ?>" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirmDelete('Apakah Anda yakin ingin menghapus jadwal ini?');">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
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
