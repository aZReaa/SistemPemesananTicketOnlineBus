<?php
// File: views/admin/manage_rutes.php
?>

<?php
// Display success/error messages
if (isset($_GET['success'])) {
    $message = '';
    switch ($_GET['success']) {
        case 'added': $message = 'Rute berhasil ditambahkan.'; break;
        case 'updated': $message = 'Rute berhasil diperbarui.'; break;
        case 'deleted': $message = 'Rute berhasil dihapus.'; break;
    }
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">' . htmlspecialchars($message) . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}
if (isset($_GET['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Terjadi kesalahan.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-route text-primary me-2"></i>Kelola Rute</h2>
        <p class="text-muted">Kelola rute perjalanan bus</p>
    </div>
    <a href="index.php?page=admin_add_rute" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Rute
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Rute</th>
                        <th>Kota Asal</th>
                        <th>Kota Tujuan</th>
                        <th>Nama PO</th>
                        <th>Kelas</th>
                        <th>Kapasitas</th>
                        <th>Jarak (KM)</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ruteList)): ?>
                        <tr>
                            <td colspan="10" class="text-center p-4">Belum ada rute yang tersedia.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($ruteList as $rute): ?>
                            <tr>
                                <td><?php echo $rute['id_rute']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($rute['nama_rute']); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($rute['kota_asal']); ?></td>
                                <td><?php echo htmlspecialchars($rute['kota_tujuan']); ?></td>
                                <td>
                                    <span class="badge bg-info"><?php echo htmlspecialchars($rute['nama_po']); ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($rute['kelas']); ?></span>
                                </td>
                                <td><?php echo $rute['kapasitas']; ?> kursi</td>
                                <td><?php echo $rute['jarak_km']; ?> km</td>
                                <td>
                                    <span class="badge <?php echo $rute['status'] === 'active' ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo ucfirst($rute['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="index.php?page=admin_edit_rute&id=<?php echo $rute['id_rute']; ?>" class="btn btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-outline-danger" onclick="confirmDelete(<?php echo $rute['id_rute']; ?>)" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if(confirm('Apakah Anda yakin ingin menghapus rute ini?')) {
        window.location.href = 'index.php?page=admin_delete_rute&id=' + id;
    }
}
</script>
