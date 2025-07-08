<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-cash-register"></i>
            Pembayaran Offline
        </h1>
        <p class="page-subtitle">Terima pembayaran tunai untuk pemesanan yang belum lunas</p>
    </div>
</div>

<?php if (!empty($pendingPayments)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Pemesanan Belum Lunas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>Kode Booking</th>
                                    <th>Nama Penumpang</th>
                                    <th>Rute</th>
                                    <th>Tanggal Berangkat</th>
                                    <th>Total Harga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pendingPayments as $pemesanan): ?>
                                    <tr>
                                        <td>
                                            <strong class="text-primary"><?= htmlspecialchars($pemesanan['kode_booking']) ?></strong>
                                        </td>
                                        <td><?= htmlspecialchars($pemesanan['nama']) ?></td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?= htmlspecialchars($pemesanan['asal']) ?> → <?= htmlspecialchars($pemesanan['tujuan']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?= date('d/m/Y H:i', strtotime($pemesanan['tanggal_berangkat'] . ' ' . $pemesanan['jam_berangkat'])) ?>
                                        </td>
                                        <td>
                                            <strong class="text-success">Rp <?= number_format($pemesanan['total_harga'], 0, ',', '.') ?></strong>
                                        </td>
                                        <td>
                                            <button class="btn btn-success btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#paymentModal"
                                                    data-id="<?= $pemesanan['id_pemesanan'] ?>"
                                                    data-kode="<?= htmlspecialchars($pemesanan['kode_booking']) ?>"
                                                    data-nama="<?= htmlspecialchars($pemesanan['nama']) ?>"
                                                    data-total="<?= $pemesanan['total_harga'] ?>">
                                                <i class="fas fa-money-bill-wave me-1"></i>
                                                Terima Bayar
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h4 class="text-muted">Tidak Ada Pembayaran Pending</h4>
                    <p class="text-muted">Semua pemesanan sudah lunas atau belum ada pemesanan baru.</p>
                    <a href="index.php?page=petugas_dashboard" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Modal Pembayaran -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-cash-register me-2"></i>
                    Terima Pembayaran
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="index.php?page=petugas_terima_pembayaran" method="POST">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Pastikan jumlah uang yang diterima sudah benar sebelum konfirmasi.
                    </div>
                    
                    <input type="hidden" id="modal_id_pemesanan" name="id_pemesanan">
                    
                    <div class="mb-3">
                        <label class="form-label">Kode Booking</label>
                        <input type="text" class="form-control" id="modal_kode_booking" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Penumpang</label>
                        <input type="text" class="form-control" id="modal_nama_penumpang" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Total yang Harus Dibayar</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control" id="modal_total_harga" readonly>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Jumlah Uang Diterima <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" 
                                   class="form-control" 
                                   name="jumlah_bayar" 
                                   id="jumlah_bayar"
                                   required 
                                   min="0" 
                                   step="1000"
                                   placeholder="Masukkan jumlah uang">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Kembalian</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control" id="kembalian" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>
                        Konfirmasi Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentModal = document.getElementById('paymentModal');
    const jumlahBayarInput = document.getElementById('jumlah_bayar');
    const kembalianInput = document.getElementById('kembalian');
    let totalHarga = 0;
    
    // Handle modal show
    paymentModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const kode = button.getAttribute('data-kode');
        const nama = button.getAttribute('data-nama');
        const total = parseInt(button.getAttribute('data-total'));
        
        totalHarga = total;
        
        document.getElementById('modal_id_pemesanan').value = id;
        document.getElementById('modal_kode_booking').value = kode;
        document.getElementById('modal_nama_penumpang').value = nama;
        document.getElementById('modal_total_harga').value = total.toLocaleString('id-ID');
        
        // Reset fields
        jumlahBayarInput.value = '';
        kembalianInput.value = '';
    });
    
    // Calculate kembalian
    jumlahBayarInput.addEventListener('input', function() {
        const jumlahBayar = parseInt(this.value) || 0;
        const kembalian = jumlahBayar - totalHarga;
        
        if (kembalian >= 0) {
            kembalianInput.value = kembalian.toLocaleString('id-ID');
            kembalianInput.className = 'form-control text-success';
        } else {
            kembalianInput.value = 'Kurang ' + Math.abs(kembalian).toLocaleString('id-ID');
            kembalianInput.className = 'form-control text-danger';
        }
    });
});
</script>
