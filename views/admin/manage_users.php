<?php
// File: views/admin/manage_users.php
// Deskripsi: Halaman untuk admin mengelola semua pengguna.

// Pastikan variabel $users ada dan merupakan array
if (!isset($users) || !is_array($users)) {
    $users = []; // Inisialisasi sebagai array kosong jika tidak ada
}
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

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-users"></i>
            Manajemen Pengguna
        </h1>
        <p class="page-subtitle">Kelola data pengguna sistem</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stats-content">
                <div class="stats-number"><?php echo count($users); ?></div>
                <div class="stats-label">Total Pengguna</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="stats-content">
                <div class="stats-number"><?php echo count(array_filter($users, function($u) { return $u['role'] === 'admin'; })); ?></div>
                <div class="stats-label">Admin</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="stats-content">
                <div class="stats-number"><?php echo count(array_filter($users, function($u) { return $u['role'] === 'petugas'; })); ?></div>
                <div class="stats-label">Petugas</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stats-content">
                <div class="stats-number"><?php echo count(array_filter($users, function($u) { return $u['role'] === 'pelanggan'; })); ?></div>
                <div class="stats-label">Pelanggan</div>
            </div>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="page-content">
    <div class="page-actions mb-3">
        <button type="button" class="btn btn-primary" disabled>
            <i class="fas fa-plus"></i> Tambah Pengguna Baru
        </button>
    </div>

            <div class="table-card">
                <div class="table-header">
                    <h5 class="mb-0">Daftar Pengguna</h5>
                </div>
                <div class="table-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col"><strong>#</strong></th>
                                    <th scope="col"><strong>Username</strong></th>
                                    <th scope="col"><strong>Email</strong></th>
                                    <th scope="col"><strong>Role</strong></th>
                                    <th scope="col"><strong>No. Telp</strong></th>
                                    <th scope="col"><strong>Alamat</strong></th>
                                    <th scope="col" class="text-center"><strong>Aksi</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($users)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="fas fa-users fa-2x mb-2"></i>
                                            <div>Tidak ada data pengguna</div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($users as $index => $user): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td>
                                                <span class="badge <?php 
                                                    echo $user['role'] === 'admin' ? 'bg-danger' : 
                                                        ($user['role'] === 'petugas' ? 'bg-warning' : 'bg-info'); 
                                                ?>">
                                                    <?php echo htmlspecialchars($user['role']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($user['no_telp'] ?? '-'); ?></td>
                                            <td><?php echo htmlspecialchars($user['alamat'] ?? '-'); ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-primary" disabled>
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" disabled>
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
        </div>
    </div>
</div>
