<!-- E-Tiket - Print-Ready Version -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 print-hide">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-ticket-alt me-2 text-primary"></i>E-Tiket Bus
                    </h5>
                    <div>
                        <button onclick="printTicket()" class="btn btn-primary me-2">
                            <i class="fas fa-print me-1"></i>Cetak
                        </button>
                        <a href="index.php?page=pelanggan_riwayat" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- E-Tiket Content -->
                <div class="e-tiket-container p-4 border rounded bg-light" style="background-color: white !important; display: block !important; page-break-inside: avoid !important;">
                    <!-- Header Tiket -->
                    <div class="text-center mb-4">
                        <h3 class="text-primary fw-bold">PT TRAVEL BUS INDONESIA</h3>
                        <p class="text-muted mb-0">Tiket Bus Online</p>
                        <div class="badge bg-success mb-2">E-TIKET SAH</div>
                        <hr class="my-3">
                    </div>
                    
                    <!-- Informasi Tiket -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>Informasi Penumpang
                            </h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td width="40%" class="text-muted">Nama Penumpang:</td>
                                    <td class="fw-bold"><?php echo htmlspecialchars($pemesanan['nama_pelanggan'] ?? $_SESSION['username']); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Kode Booking:</td>
                                    <td class="fw-bold text-primary"><?php echo htmlspecialchars($pemesanan['kode_booking'] ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">No. Kursi:</td>
                                    <td class="fw-bold">
                                        <?php 
                                        $kursi_list = [];
                                        if (!empty($pemesanan['nomor_kursi'])) {
                                            $kursi_list = explode(',', $pemesanan['nomor_kursi']);
                                            echo htmlspecialchars(implode(', ', $kursi_list));
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Jumlah Tiket:</td>
                                    <td class="fw-bold"><?php echo $pemesanan['jumlah_tiket'] ?? 0; ?> tiket</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-route me-2"></i>Detail Perjalanan
                            </h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td width="40%" class="text-muted">Rute:</td>
                                    <td class="fw-bold"><?php echo htmlspecialchars(($pemesanan['asal'] ?? 'N/A') . ' → ' . ($pemesanan['tujuan'] ?? 'N/A')); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">PO Bus:</td>
                                    <td class="fw-bold"><?php echo htmlspecialchars($pemesanan['nama_po'] ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Kelas:</td>
                                    <td class="fw-bold"><?php echo htmlspecialchars($pemesanan['kelas'] ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tanggal Berangkat:</td>
                                    <td class="fw-bold"><?php echo date('d M Y', strtotime($pemesanan['waktu_berangkat'])); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Waktu Berangkat:</td>
                                    <td class="fw-bold"><?php echo date('H:i', strtotime($pemesanan['waktu_berangkat'])); ?> WIB</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <!-- QR Code Section -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="alert alert-info">
                                <h6 class="mb-2 text-primary">
                                    <i class="fas fa-exclamation-circle me-2"></i>Petunjuk Penggunaan
                                </h6>
                                <ul class="mb-0 small">
                                    <li>Tunjukkan e-tiket ini kepada petugas saat keberangkatan</li>
                                    <li>Datang minimal 30 menit sebelum waktu keberangkatan</li>
                                    <li>Pastikan membawa identitas yang valid (KTP/SIM/Paspor)</li>
                                    <li>QR Code akan dipindai untuk verifikasi tiket</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="text-center">
                                <h6 class="text-primary mb-2">
                                    <i class="fas fa-qrcode me-2"></i>QR Code Tiket
                                </h6>
                                <?php 
                                require_once __DIR__ . '/../../models/QRCodeGenerator.php';
                                
                                // Use a very simple data format that's guaranteed to work
                                $kode_booking = $pemesanan['kode_booking'] ?? 'TIKET';
                                $qrData = $kode_booking;
                                
                                // Generate QR code URL with simple data
                                $qrUrl = QRCodeGenerator::generateQRCodeURL($qrData, 200);
                                ?>
                                <!-- Enhanced QR code section with visible booking code -->
                                <div class="qr-code-section text-center">
                                    <div class="qr-container border rounded p-3 bg-white" style="max-width: 180px; margin: 0 auto;">
                                        <img src="<?php echo htmlspecialchars($qrUrl); ?>" 
                                             id="qrcode-image"
                                             alt="QR Code Tiket" 
                                             class="img-fluid" 
                                             style="width: 150px; height: 150px;">
                                    </div>
                                    <p class="text-muted small mt-2">Scan untuk verifikasi</p>
                                    
                                    <!-- Always visible booking code -->
                                    <div class="booking-code bg-light p-2 border rounded my-2 fw-bold">
                                        <?php echo htmlspecialchars($pemesanan['kode_booking'] ?? 'TIKET'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer Tiket -->
                    <hr class="my-4">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <strong>Tanggal Pemesanan:</strong> <?php echo date('d M Y H:i', strtotime($pemesanan['tgl_pesan'])); ?><br>
                                <strong>Total Pembayaran:</strong> Rp <?php echo number_format($pemesanan['total_harga'], 0, ',', '.'); ?><br>
                                <strong>Status:</strong> <span class="text-secondary fw-bold">LUNAS</span>
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                E-Tiket ini sah tanpa tanda tangan<br>
                                Dicetak pada: <?php echo date('d M Y H:i'); ?> WIB<br>
                                <strong>Selamat Perjalanan!</strong>
                            </small>
                        </div>
                    </div>
                    
                    <!-- Special Print-Only Section -->
                    <div class="print-only-section" style="display:none; visibility:hidden;" id="print-only-content">
                        <hr>
                        <div class="text-center mt-3 mb-3">
                            <h4>PT TRAVEL BUS INDONESIA</h4>
                            <p>Kode Booking: <strong><?php echo htmlspecialchars($pemesanan['kode_booking'] ?? 'TIKET'); ?></strong></p>
                            <p>Penumpang: <strong><?php echo htmlspecialchars($pemesanan['nama_pelanggan'] ?? $_SESSION['username']); ?></strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* CSS Print yang sangat sederhana */
@media print {
    /* Sembunyikan yang tidak perlu */
    .print-hide, .btn, nav, header, footer, .sidebar, .navbar {
        display: none !important;
    }
    
    /* Pastikan konten tiket terlihat */
    body {
        background: white !important;
        color: black !important;
    }
    
    .e-tiket-container {
        display: block !important;
        visibility: visible !important;
        background: white !important;
        color: black !important;
        padding: 20px !important;
        border: 1px solid #000 !important;
    }
    
    .e-tiket-container * {
        color: black !important;
        background: transparent !important;
    }
    
    /* Pastikan QR code atau placeholder tercetak */
    .qr-container, .qr-placeholder {
        display: block !important;
        visibility: visible !important;
    }
    
    .booking-code {
        font-size: 16pt !important;
        font-weight: bold !important;
        border: 2px solid #000 !important;
        padding: 10px !important;
        text-align: center !important;
    }
    
    @page {
        margin: 1cm;
    }
}
</style>

<!-- Add ticket CSS -->
<link rel="stylesheet" href="public/css/tiket.css">

<script>
// Fungsi cetak yang sangat sederhana
function printTicket() {
    window.print();
}

document.addEventListener('DOMContentLoaded', function() {
    const qrImage = document.getElementById('qrcode-image');
    
    // QR image - gunakan URL yang sederhana
    if (qrImage) {
        const bookingCode = '<?php echo htmlspecialchars($pemesanan['kode_booking'] ?? 'TIKET'); ?>';
        
        // Coba gunakan API eksternal dulu
        qrImage.src = 'https://api.qrserver.com/v1/create-qr-code/?data=' + encodeURIComponent(bookingCode) + '&size=150x150';
        
        qrImage.onerror = function() {
            // Jika API eksternal gagal, coba generator lokal
            qrImage.src = 'simple_qr.php?data=' + encodeURIComponent(bookingCode) + '&size=150';
            
            // Jika masih gagal, tampilkan placeholder
            qrImage.onerror = function() {
                qrImage.style.display = 'none';
                const placeholder = document.createElement('div');
                placeholder.className = 'qr-placeholder bg-light border rounded p-3 text-center';
                placeholder.style.width = '150px';
                placeholder.style.height = '150px';
                placeholder.style.display = 'flex';
                placeholder.style.alignItems = 'center';
                placeholder.style.justifyContent = 'center';
                placeholder.innerHTML = '<div><strong>' + bookingCode + '</strong><br><small>Kode Booking</small></div>';
                qrImage.parentNode.appendChild(placeholder);
            };
        };
    }
});
</script>
