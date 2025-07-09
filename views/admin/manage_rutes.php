<?php
// File: views/admin/manage_rutes.php
?>

<!-- Inline CSS for table header visibility -->
<style>
.table thead th {
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%) !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    font-size: 0.875rem !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none !important;
    padding: 1rem !important;
}
.table thead th strong {
    color: #ffffff !important;
    font-weight: 700 !important;
}
</style>

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

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-route me-2"></i>Daftar Rute Bus
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th><strong>ID</strong></th>
                        <th><strong>Nama Rute</strong></th>
                        <th><strong>Kota Asal</strong></th>
                        <th><strong>Kota Tujuan</strong></th>
                        <th><strong>Nama PO</strong></th>
                        <th><strong>Kelas</strong></th>
                        <th><strong>Kapasitas</strong></th>
                        <th><strong>Jarak (KM)</strong></th>
                        <th><strong>Status</strong></th>
                        <th class="text-center"><strong>Aksi</strong></th>
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
                                <td class="text-center">
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
