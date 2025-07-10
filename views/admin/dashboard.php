<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-tachometer-alt"></i>
            Admin Dashboard
        </h1>
        <p class="page-subtitle">Selamat datang di panel administrasi sistem tiket bus</p>
    </div>
    <div class="text-muted">
        <i class="fas fa-calendar me-1"></i>
        <?php echo date('d M Y'); ?>
    </div>
</div>

<!-- Welcome Section -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-user-shield text-danger" style="font-size: 4rem;"></i>
                </div>
                <h2 class="text-danger mb-2">Selamat Datang, Administrator!</h2>
                <p class="text-muted lead">Kelola sistem pemesanan tiket bus dengan mudah melalui menu navigasi di sebelah kiri</p>
                <hr class="my-4">
                <p class="text-muted">Gunakan menu sidebar untuk mengakses fitur-fitur administrasi seperti:</p>
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            <i class="fas fa-calendar-alt fa-2x text-primary mb-2"></i>
                            <h6>Kelola Jadwal</h6>
                            <small class="text-muted">Tambah, edit, hapus jadwal bus</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            <i class="fas fa-credit-card fa-2x text-warning mb-2"></i>
                            <h6>Konfirmasi Pembayaran</h6>
                            <small class="text-muted">Validasi bukti pembayaran</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            <i class="fas fa-chart-bar fa-2x text-success mb-2"></i>
                            <h6>Laporan</h6>
                            <small class="text-muted">Lihat statistik dan laporan</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            <i class="fas fa-route fa-2x text-info mb-2"></i>
                            <h6>Kelola Rute</h6>
                            <small class="text-muted">Manajemen rute perjalanan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

