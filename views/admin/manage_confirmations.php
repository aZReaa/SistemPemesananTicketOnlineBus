<?php
if (isset($_GET['success'])) {
    $message = '';
    switch ($_GET['success']) {
        case 'confirmed': $message = 'Pembayaran berhasil dikonfirmasi.'; break;
        case 'rejected': $message = 'Pembayaran berhasil ditolak.'; break;
    }
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">' . htmlspecialchars($message) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
}
if (isset($_GET['error'])) {
    $message = '';
    switch ($_GET['error']) {
        case 'invalidaction': $message = 'Aksi tidak valid.'; break;
        case 'updatefailed': $message = 'Gagal memperbarui status pembayaran.'; break;
        default: $message = 'Terjadi kesalahan yang tidak diketahui.'; break;
    }
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . htmlspecialchars($message) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
}
?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Tgl. Pembayaran</th>
                        <th class="text-end">Jumlah</th>
                        <th class="text-center">Bukti</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pendingPayments)): ?>
                        <tr>
                            <td colspan="6" class="text-center p-4">Tidak ada pembayaran yang menunggu konfirmasi.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pendingPayments as $payment): ?>
                            <tr>
                                <td>#<?php echo htmlspecialchars($payment['id_pemesanan']); ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($payment['nama_pelanggan']); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($payment['email_pelanggan']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars(date('d M Y, H:i', strtotime($payment['waktu_pembayaran']))); ?></td>
                                <td class="text-end">Rp <?php echo number_format($payment['jumlah'], 0, ',', '.'); ?></td>
                                <td class="text-center">
                                    <?php if (!empty($payment['bukti_pembayaran'])): ?>
                                        <a href="<?php echo htmlspecialchars($payment['bukti_pembayaran']); ?>" target="_blank" class="btn btn-outline-info btn-sm">Lihat</a>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <form action="index.php?page=admin_process_confirmation" method="POST" class="d-inline">
                                        <input type="hidden" name="id_pembayaran" value="<?php echo $payment['id_pembayaran']; ?>">
                                        <button type="submit" name="action" value="confirm" class="btn btn-success btn-sm" onclick="return confirm('Anda yakin ingin mengonfirmasi pembayaran ini?');">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menolak pembayaran ini?');">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
