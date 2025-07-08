<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-ticket-alt"></i>
            Kelola Tiket
        </h1>
        <p class="page-subtitle">Manajemen tiket dan status booking</p>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Daftar Tiket
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Kode Booking</th>
                                <th>Pelanggan</th>
                                <th>Bus/Operator</th>
                                <th>Rute</th>
                                <th>Nomor Kursi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($tiketList)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i><br>
                                        <h6 class="text-muted">Tidak ada tiket tersedia</h6>
                                        <p class="text-muted">Tiket akan muncul setelah ada pemesanan</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($tiketList as $tiket): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($tiket['kode_booking']); ?></strong>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($tiket['nama_pelanggan'] ?? 'N/A'); ?>
                                        </td>
                                        <td>
                                            <div>
                                                <strong><?php echo htmlspecialchars($tiket['nama_po'] ?? 'N/A'); ?></strong><br>
                                                <small class="text-muted"><?php echo htmlspecialchars($tiket['kelas'] ?? 'N/A'); ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($tiket['rute_awal'] ?? 'N/A'); ?> → 
                                            <?php echo htmlspecialchars($tiket['rute_akhir'] ?? 'N/A'); ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?php echo htmlspecialchars($tiket['nomor_kursi']); ?></span>
                                        </td>
                                        <td>
                                            <?php 
                                            $statusClass = '';
                                            $statusText = '';
                                            switch ($tiket['status']) {
                                                case 'available':
                                                    $statusClass = 'bg-success';
                                                    $statusText = 'Tersedia';
                                                    break;
                                                case 'booked':
                                                    $statusClass = 'bg-warning';
                                                    $statusText = 'Dipesan';
                                                    break;
                                                case 'used':
                                                    $statusClass = 'bg-secondary';
                                                    $statusText = 'Digunakan';
                                                    break;
                                                default:
                                                    $statusClass = 'bg-dark';
                                                    $statusText = ucfirst($tiket['status']);
                                            }
                                            ?>
                                            <span class="badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <?php if ($tiket['status'] === 'booked'): ?>
                                                    <button type="button" class="btn btn-sm btn-success" 
                                                            onclick="updateTicketStatus(<?php echo $tiket['id_tiket']; ?>, 'used')"
                                                            title="Tandai sebagai digunakan">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        onclick="viewTicketDetails(<?php echo $tiket['id_tiket']; ?>)"
                                                        title="Lihat detail">
                                                    <i class="fas fa-eye"></i>
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

<script>
function updateTicketStatus(ticketId, status) {
    if (confirm('Apakah Anda yakin ingin mengubah status tiket ini?')) {
        window.location.href = `index.php?page=admin_update_ticket_status&id=${ticketId}&status=${status}`;
    }
}

function viewTicketDetails(ticketId) {
    // Implementasi untuk melihat detail tiket
    alert('Fitur detail tiket akan diimplementasikan');
}
</script>
