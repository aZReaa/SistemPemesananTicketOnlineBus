<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-check-circle"></i>
            Verifikasi Tiket
        </h1>
        <p class="page-subtitle">Masukkan kode booking untuk memverifikasi tiket penumpang</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-search me-2"></i>
                    Pencarian Tiket
                </h4>
            </div>
            <div class="card-body">
                <div class="alert alert-light border-primary mb-3">
                    <h6 class="mb-2"><i class="fas fa-info-circle text-primary me-2"></i>Alur Verifikasi Tiket:</h6>
                    <ol class="mb-0 ps-3">
                        <li>Masukkan kode booking manual atau scan QR code untuk <strong>mencari tiket</strong></li>
                        <li>Periksa detail tiket yang ditemukan</li>
                        <li>Klik tombol <strong>"Validasi & Tandai Telah Digunakan"</strong> untuk memproses tiket</li>
                    </ol>
                </div>
                
                <p class="card-text">Masukkan kode booking atau scan QR code untuk melihat detail dan melakukan validasi tiket.</p>
                    
                    <!-- Notifikasi Flash Message -->
                    <?php if (isset($flashMessage) && $flashMessage): ?>
                        <?php 
                            $alertType = 'info';
                            if (strpos($flashMessage, 'berhasil') !== false || strpos($flashMessage, 'ditemukan') !== false) {
                                $alertType = 'success';
                            } elseif (strpos($flashMessage, 'tidak') !== false || strpos($flashMessage, 'error') !== false || strpos($flashMessage, 'Error') !== false) {
                                $alertType = 'danger';
                            } elseif (strpos($flashMessage, 'sudah') !== false || strpos($flashMessage, 'belum') !== false) {
                                $alertType = 'warning';
                            }
                        ?>
                        <div class="alert alert-<?php echo $alertType; ?> alert-dismissible fade show" role="alert">
                            <i class="fas fa-<?php echo $alertType === 'success' ? 'check-circle' : ($alertType === 'danger' ? 'exclamation-triangle' : ($alertType === 'warning' ? 'exclamation-circle' : 'info-circle')); ?> me-2"></i>
                            <?php echo htmlspecialchars($flashMessage); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs" id="verificationTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual" type="button" role="tab" aria-controls="manual" aria-selected="true">
                                <i class="fas fa-keyboard me-2"></i>Input Manual
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="qr-tab" data-bs-toggle="tab" data-bs-target="#qr" type="button" role="tab" aria-controls="qr" aria-selected="false">
                                <i class="fas fa-qrcode me-2"></i>Scan QR Code
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content mt-3" id="verificationTabsContent">
                        <!-- Manual Input Tab -->
                        <div class="tab-pane fade show active" id="manual" role="tabpanel" aria-labelledby="manual-tab">
                            <form action="index.php?page=petugas_verifikasi" method="POST">
                                <div class="mb-2">
                                    <label for="kode_tiket" class="form-label"><strong>Kode Booking Tiket</strong></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-ticket-alt"></i></span>
                                        <input type="text" name="kode_tiket" id="kode_tiket" class="form-control" 
                                               placeholder="Contoh: TKT2024001, BUS240101001" required 
                                               value="<?php echo htmlspecialchars($_POST['kode_tiket'] ?? $_GET['kode_tiket'] ?? ''); ?>">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search me-1"></i>Cari Tiket
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Masukkan kode booking yang tertera pada tiket pelanggan untuk mencari dan melihat detail tiket.
                                </small>
                            </form>
                        </div>

                        <!-- QR Code Scanner Tab -->
                        <div class="tab-pane fade" id="qr" role="tabpanel" aria-labelledby="qr-tab">
                            <div class="text-center">
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-camera me-1"></i>
                                        Gunakan kamera untuk memindai QR code pada e-tiket pelanggan
                                    </small>
                                </div>
                                <div id="qr-scanner" class="mb-3" style="width: 100%; max-width: 400px; margin: 0 auto;">
                                    <div id="qr-reader" style="width: 100%; min-height: 300px; border: 2px dashed #ccc; border-radius: 10px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                                        <div class="text-center">
                                            <i class="fas fa-qrcode fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-1">Siap untuk scan QR code</p>
                                            <small class="text-muted">Klik tombol mulai scan untuk mengaktifkan kamera</small>
                                        </div>
                                    </div>
                                </div>
                                <button id="start-scan" class="btn btn-success me-2">
                                    <i class="fas fa-camera me-2"></i>Mulai Scan
                                </button>
                                <button id="stop-scan" class="btn btn-danger" style="display: none;">
                                    <i class="fas fa-stop me-2"></i>Stop Scan
                                </button>
                                
                                <!-- Hidden form for QR submission -->
                                <form id="qr-form" action="index.php?page=petugas_process_qr" method="POST" style="display: none;">
                                    <input type="hidden" name="qr_data" id="qr_data">
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php if ($searchPerformed): ?>
                        <hr>
                        <!-- Hasil Pencarian -->
                        <div id="ticket-details">
                            <?php if ($ticketDetails): ?>
                                <div class="d-flex align-items-center mb-3">
                                    <h5 class="mb-0 me-2">
                                        <i class="fas fa-ticket-alt text-primary me-2"></i>Detail Tiket Ditemukan
                                    </h5>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Tiket Valid
                                    </span>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th style="width: 35%;" class="table-light">
                                                    <i class="fas fa-barcode me-2"></i>Kode Booking
                                                </th>
                                                <td><strong class="text-primary"><?php echo htmlspecialchars($ticketDetails['kode_booking']); ?></strong></td>
                                            </tr>
                                            <tr>
                                                <th class="table-light">
                                                    <i class="fas fa-user me-2"></i>Penumpang
                                                </th>
                                                <td><?php 
                                                    // Coba ambil nama penumpang dari berbagai kemungkinan field
                                                    $nama_penumpang = $ticketDetails['nama'] ?? $ticketDetails['nama_penumpang'] ?? 
                                                                    $ticketDetails['penumpang'] ?? $ticketDetails['nama_pelanggan'] ?? 
                                                                    'Informasi tidak tersedia';
                                                    echo htmlspecialchars($nama_penumpang); 
                                                ?></td>
                                            </tr>
                                            <tr>
                                                <th class="table-light">
                                                    <i class="fas fa-route me-2"></i>Rute
                                                </th>
                                                <td><strong><?php echo htmlspecialchars($ticketDetails['kota_asal'] . ' → ' . $ticketDetails['kota_tujuan']); ?></strong></td>
                                            </tr>
                                            <tr>
                                                <th class="table-light">
                                                    <i class="fas fa-bus me-2"></i>Bus / PO
                                                </th>
                                                <td><?php echo htmlspecialchars($ticketDetails['nama_po'] . ' (' . $ticketDetails['kelas'] . ')'); ?></td>
                                            </tr>
                                            <tr>
                                                <th class="table-light">
                                                    <i class="fas fa-calendar me-2"></i>Waktu Berangkat
                                                </th>
                                                <td><?php echo htmlspecialchars(date('d M Y, H:i', strtotime($ticketDetails['waktu_berangkat'])) . ' WIB'); ?></td>
                                            </tr>
                                            <tr>
                                                <th class="table-light">
                                                    <i class="fas fa-clock me-2"></i>Waktu Tiba
                                                </th>
                                                <td><strong><?php echo htmlspecialchars(date('d M Y, H:i', strtotime($ticketDetails['waktu_tiba'])) . ' WIB'); ?></strong></td>
                                            </tr>
                                            <tr>
                                                <th class="table-light">
                                                    <i class="fas fa-credit-card me-2"></i>Status Pembayaran
                                                </th>
                                                <td>
                                                    <?php 
                                                    // Coba ambil status pembayaran dari berbagai kemungkinan field
                                                    $status_pembayaran = $ticketDetails['status_pembayaran'] ?? '';
                                                    if (empty($status_pembayaran)) {
                                                        if ($ticketDetails['status'] == 'paid') {
                                                            $status_pembayaran = 'lunas';
                                                        } elseif ($ticketDetails['status'] == 'pending') {
                                                            $status_pembayaran = 'pending';
                                                        } else {
                                                            $status_pembayaran = $ticketDetails['status'] ?? 'tidak diketahui';
                                                        }
                                                    }
                                                    
                                                    $is_lunas = ($status_pembayaran === 'lunas' || $status_pembayaran === 'paid');
                                                    ?>
                                                    <span class="badge bg-<?php echo $is_lunas ? 'success' : 'warning'; ?> fs-6">
                                                        <i class="fas fa-<?php echo $is_lunas ? 'check-circle' : 'exclamation-triangle'; ?> me-1"></i>
                                                        <?php echo ucfirst(htmlspecialchars($status_pembayaran)); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-light">
                                                    <i class="fas fa-ticket-alt me-2"></i>Status Tiket
                                                </th>
                                                <td>
                                                    <?php 
                                                    // Coba ambil status tiket dari berbagai kemungkinan field
                                                    $status_tiket = $ticketDetails['status_tiket'] ?? '';
                                                    if (empty($status_tiket)) {
                                                        if ($ticketDetails['status'] == 'used') {
                                                            $status_tiket = 'digunakan';
                                                        } elseif ($ticketDetails['status'] == 'booked') {
                                                            $status_tiket = 'aktif';
                                                        } elseif ($ticketDetails['status'] == 'available') {
                                                            $status_tiket = 'tersedia';
                                                        } else {
                                                            $status_tiket = $ticketDetails['status'] ?? 'tidak diketahui';
                                                        }
                                                    }
                                                    
                                                    $is_active = ($status_tiket === 'aktif' || $status_tiket === 'booked');
                                                    $is_used = ($status_tiket === 'digunakan' || $status_tiket === 'used');
                                                    ?>
                                                    <span class="badge bg-<?php echo $is_active ? 'success' : ($is_used ? 'secondary' : 'warning'); ?> fs-6">
                                                        <i class="fas fa-<?php echo $is_active ? 'check-circle' : ($is_used ? 'times-circle' : 'exclamation-triangle'); ?> me-1"></i>
                                                        <?php echo ucfirst(htmlspecialchars($status_tiket)); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-light">
                                                    <i class="fas fa-money-bill me-2"></i>Total Harga
                                                </th>
                                                <td><strong>Rp <?php echo number_format($ticketDetails['total_harga'], 0, ',', '.'); ?></strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Tombol Aksi -->
                                <div class="mt-4">
                                    <?php 
                                    // Coba ambil status pembayaran dan tiket dari berbagai kemungkinan field
                                    $status_pembayaran = $ticketDetails['status_pembayaran'] ?? '';
                                    $status_tiket = $ticketDetails['status_tiket'] ?? '';
                                    
                                    // Jika tidak ada status_pembayaran, cek field status dari tabel pemesanan
                                    if (empty($status_pembayaran)) {
                                        if ($ticketDetails['status'] == 'paid') {
                                            $status_pembayaran = 'lunas';
                                        } elseif ($ticketDetails['status'] == 'pending') {
                                            $status_pembayaran = 'pending';
                                        } else {
                                            $status_pembayaran = $ticketDetails['status'] ?? 'tidak diketahui';
                                        }
                                    }
                                    
                                    // Jika tidak ada status_tiket, cek field status dari tabel tiket
                                    if (empty($status_tiket)) {
                                        if ($ticketDetails['status'] == 'used') {
                                            $status_tiket = 'digunakan';
                                        } elseif ($ticketDetails['status'] == 'booked') {
                                            $status_tiket = 'aktif';
                                        } else {
                                            $status_tiket = $ticketDetails['status'] ?? 'tidak diketahui';
                                        }
                                    }
                                    
                                    $is_paid = ($status_pembayaran === 'lunas' || $ticketDetails['status'] === 'paid');
                                    $is_active = ($status_tiket === 'aktif' || $ticketDetails['status'] === 'booked');
                                    $is_used = ($status_tiket === 'digunakan' || $ticketDetails['status'] === 'used');
                                    
                                    // Validasi apakah tiket dapat divalidasi
                                    $can_validate = ($is_paid && $is_active) || ($is_paid && !$is_used);
                                    ?>
                                    
                                    <?php if ($can_validate): ?>
                                        <div class="alert alert-success mb-3">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <strong>Tiket dapat divalidasi!</strong> Tiket sudah lunas dan masih aktif.
                                        </div>
                                        <form action="index.php?page=petugas_validate_ticket" method="POST" onsubmit="return confirm('Anda yakin ingin memvalidasi tiket ini?\n\nTiket akan ditandai sebagai TELAH DIGUNAKAN dan tidak dapat digunakan lagi.');">
                                            <input type="hidden" name="kode_booking" value="<?php echo htmlspecialchars($ticketDetails['kode_booking']); ?>">
                                            <button type="submit" class="btn btn-success btn-lg w-100">
                                                <i class="fas fa-check me-2"></i>Validasi & Tandai Telah Digunakan
                                            </button>
                                        </form>
                                        <small class="text-muted mt-2 d-block">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Setelah validasi, tiket akan ditandai sebagai "digunakan" dan tidak dapat digunakan lagi.
                                        </small>
                                    <?php elseif (!$is_paid): ?>
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i> 
                                            <strong>Tiket belum lunas!</strong> Tidak dapat divalidasi karena pembayaran belum selesai.
                                        </div>
                                    <?php elseif ($is_used): ?>
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i> 
                                            <strong>Tiket sudah digunakan!</strong> Tiket ini sudah pernah divalidasi sebelumnya.
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i> 
                                            <strong>Tiket tidak dapat divalidasi!</strong> Status tiket tidak memenuhi syarat.
                                        </div>
                                    <?php endif; ?>
                                </div>

                            <?php else: ?>
                                <div class="alert alert-danger" role="alert">
                                    <i class="fas fa-times-circle me-2"></i>
                                    <strong>Tiket tidak ditemukan!</strong> 
                                    <br>Pastikan kode booking yang Anda masukkan benar dan tiket masih valid.
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include QR Scanner Library -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
let html5QrcodeScanner = null;
let isScanning = false;

document.getElementById('start-scan').addEventListener('click', function() {
    if (!isScanning) {
        startQRScanner();
    }
});

document.getElementById('stop-scan').addEventListener('click', function() {
    if (isScanning) {
        stopQRScanner();
    }
});

function startQRScanner() {
    const qrReaderElement = document.getElementById('qr-reader');
    
    // Clear any existing content
    qrReaderElement.innerHTML = '';
    
    html5QrcodeScanner = new Html5Qrcode("qr-reader");
    
    Html5Qrcode.getCameras().then(cameras => {
        if (cameras && cameras.length) {
            let cameraId = cameras[0].id;
            
            html5QrcodeScanner.start(
                cameraId,
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                (decodedText, decodedResult) => {
                    // QR code successfully scanned
                    console.log(`QR Code detected: ${decodedText}`);
                    
                    // Show success message
                    qrReaderElement.innerHTML = `
                        <div class="text-center">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="text-success mb-1"><strong>QR Code berhasil dipindai!</strong></p>
                            <small class="text-muted">Memproses data tiket...</small>
                        </div>
                    `;
                    
                    // Stop scanning
                    stopQRScanner();
                    
                    // Submit the QR data
                    document.getElementById('qr_data').value = decodedText;
                    document.getElementById('qr-form').submit();
                },
                (errorMessage) => {
                    // Parse error, ignore it
                }
            ).catch(err => {
                console.error('Error starting QR scanner:', err);
                qrReaderElement.innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <p class="text-warning mb-1"><strong>Error mengakses kamera</strong></p>
                        <small class="text-muted">${err}</small>
                    </div>
                `;
                isScanning = false;
                document.getElementById('start-scan').style.display = 'inline-block';
                document.getElementById('stop-scan').style.display = 'none';
            });
            
            isScanning = true;
            document.getElementById('start-scan').style.display = 'none';
            document.getElementById('stop-scan').style.display = 'inline-block';
        } else {
            qrReaderElement.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-video-slash fa-3x text-danger mb-3"></i>
                    <p class="text-danger mb-1"><strong>Kamera tidak ditemukan</strong></p>
                    <small class="text-muted">Pastikan perangkat memiliki kamera dan izin telah diberikan</small>
                </div>
            `;
        }
    }).catch(err => {
        console.error('Error getting cameras:', err);
        alert('Error accessing camera: ' + err);
    });
}

function stopQRScanner() {
    if (html5QrcodeScanner && isScanning) {
        html5QrcodeScanner.stop().then(() => {
            console.log('QR Scanner stopped');
            isScanning = false;
            document.getElementById('start-scan').style.display = 'inline-block';
            document.getElementById('stop-scan').style.display = 'none';
            
            // Reset the reader display
            document.getElementById('qr-reader').innerHTML = `
                <div class="text-center">
                    <i class="fas fa-qrcode fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-1">Siap untuk scan QR code</p>
                    <small class="text-muted">Klik tombol mulai scan untuk mengaktifkan kamera</small>
                </div>
            `;
        }).catch(err => {
            console.error('Error stopping QR scanner:', err);
        });
    }
}

// Clean up on page unload
window.addEventListener('beforeunload', function() {
    if (isScanning) {
        stopQRScanner();
    }
});
</script>
