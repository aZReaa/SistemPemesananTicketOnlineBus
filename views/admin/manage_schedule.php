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
            <i class="fas fa-calendar-alt"></i>
            Kelola Jadwal & Tiket
        </h1>
        <p class="page-subtitle">Manajemen jadwal perjalanan dan tiket bus</p>
    </div>
    <div>
        <a href="index.php?page=admin_add_schedule" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Jadwal
        </a>
    </div>
</div>

<!-- Tab Navigation -->
<div class="row mb-4">
    <div class="col-12">
        <ul class="nav nav-tabs" id="scheduleTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="jadwal-tab" data-bs-toggle="tab" data-bs-target="#jadwal" type="button" role="tab">
                    <i class="fas fa-calendar-alt me-2"></i>Kelola Jadwal
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tiket-tab" data-bs-toggle="tab" data-bs-target="#tiket" type="button" role="tab">
                    <i class="fas fa-ticket-alt me-2"></i>Kelola Tiket
                </button>
            </li>
        </ul>
    </div>
</div>

<!-- Tab Content -->
<div class="tab-content" id="scheduleTabContent">
    <!-- Jadwal Tab -->
    <div class="tab-pane fade show active" id="jadwal" role="tabpanel">
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
                                <th><strong>Operator</strong></th>
                                <th><strong>Rute</strong></th>
                                <th><strong>Waktu Berangkat</strong></th>
                                <th><strong>Waktu Tiba</strong></th>
                                <th><strong>Harga</strong></th>
                                <th><strong>Total Tiket</strong></th>
                                <th class="text-center"><strong>Aksi</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($jadwalList)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                                            <h5 class="text-muted mb-2">Belum Ada Jadwal</h5>
                                            <p class="text-muted mb-3">Mulai dengan menambahkan jadwal perjalanan pertama</p>
                                            <a href="index.php?page=admin_add_schedule" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>Tambah Jadwal Pertama
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($jadwalList as $jadwal): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-primary"><?php echo htmlspecialchars($jadwal['nama_po']); ?></div>
                                            <small class="text-muted">
                                                <i class="fas fa-star me-1"></i><?php echo htmlspecialchars($jadwal['kelas']); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="route-display">
                                                <span class="badge badge-primary"><?php echo htmlspecialchars($jadwal['kota_asal']); ?></span>
                                                <i class="fas fa-arrow-right mx-2 text-primary"></i>
                                                <span class="badge badge-success"><?php echo htmlspecialchars($jadwal['kota_tujuan']); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?php echo date('d M Y', strtotime($jadwal['waktu_berangkat'])); ?></div>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i><?php echo date('H:i', strtotime($jadwal['waktu_berangkat'])); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?php echo date('d M Y', strtotime($jadwal['waktu_tiba'])); ?></div>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i><?php echo date('H:i', strtotime($jadwal['waktu_tiba'])); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-success">Rp <?php echo number_format($jadwal['harga'], 0, ',', '.'); ?></div>
                                        </td>
                                        <td>
                                            <div class="ticket-info">
                                                <span class="badge badge-info"><?php echo $jadwal['tiket_tersedia']; ?> Tersedia</span>
                                                <br><small class="text-muted">dari <?php echo $jadwal['kapasitas']; ?> kursi</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-primary" onclick="showTiketModal(<?php echo $jadwal['id_jadwal']; ?>)" title="Kelola Tiket">
                                                    <i class="fas fa-ticket-alt"></i>
                                                </button>
                                                <a href="index.php?page=admin_edit_schedule&id=<?php echo $jadwal['id_jadwal']; ?>" class="btn btn-sm btn-warning" title="Edit Jadwal">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="if(confirmDelete('Apakah Anda yakin ingin menghapus jadwal ini?')) { window.location.href='index.php?page=admin_delete_schedule&id=<?php echo $jadwal['id_jadwal']; ?>'; }" title="Hapus Jadwal">
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

    <!-- Tiket Tab -->
    <div class="tab-pane fade" id="tiket" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-ticket-alt me-2"></i>Kelola Tiket per Jadwal
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="selectJadwal" class="form-label fw-bold">Pilih Jadwal:</label>
                        <select class="form-select" id="selectJadwal" onchange="loadTiketData(this.value)">
                            <option value="">-- Pilih Jadwal untuk Melihat Tiket --</option>
                            <?php if (!empty($jadwalList)): ?>
                                <?php foreach ($jadwalList as $jadwal): ?>
                                    <option value="<?php echo $jadwal['id_jadwal']; ?>">
                                        <?php echo htmlspecialchars($jadwal['nama_po'] . ' - ' . $jadwal['kota_asal'] . ' → ' . $jadwal['kota_tujuan'] . ' (' . date('d M Y, H:i', strtotime($jadwal['waktu_berangkat'])) . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Aksi Cepat:</label>
                        <div class="d-flex gap-2">
                            <button class="btn btn-success btn-sm" onclick="generateAllTickets()">
                                <i class="fas fa-magic me-2"></i>Generate Semua Tiket
                            </button>
                            <button class="btn btn-warning btn-sm" onclick="resetAllTickets()">
                                <i class="fas fa-redo me-2"></i>Reset Semua Tiket
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tiket Data Container -->
                <div id="tiketDataContainer">
                    <div class="text-center py-5">
                        <i class="fas fa-ticket-alt fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted mb-2">Pilih Jadwal</h5>
                        <p class="text-muted">Pilih jadwal di atas untuk melihat dan mengelola tiket</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS untuk memastikan tab styling -->
<style>
/* Additional tab styling for better visibility */
.nav-tabs .nav-link {
    position: relative;
    background: transparent;
    border: none;
    color: var(--text-secondary);
    font-weight: 500;
    padding: 1rem 1.5rem;
    border-radius: 0.5rem 0.5rem 0 0;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link:hover {
    background: var(--bg-secondary);
    color: var(--primary-blue);
}

.nav-tabs .nav-link.active {
    background: var(--bg-main);
    color: var(--primary-blue);
    font-weight: 600;
    border-bottom: 2px solid var(--primary-blue);
}

.nav-tabs {
    border-bottom: 1px solid var(--border-light);
    background: var(--bg-secondary);
    padding: 0 1rem;
    border-radius: 0.75rem 0.75rem 0 0;
}

.tab-content {
    background: var(--bg-main);
    border-radius: 0 0 0.75rem 0.75rem;
    min-height: 400px;
}

.tab-pane {
    padding: 0;
}

/* Ensure proper badge styling */
.badge-success {
    background-color: var(--success) !important;
    color: white !important;
}

.badge-warning {
    background-color: var(--warning) !important;
    color: white !important;
}

.badge-info {
    background-color: var(--info) !important;
    color: white !important;
}

.badge-primary {
    background-color: var(--primary-blue) !important;
    color: white !important;
}

/* Table text visibility */
.table tbody td {
    color: var(--text-primary) !important;
}

.text-muted {
    color: var(--text-muted) !important;
}

/* Button group styling */
.btn-group .btn {
    margin-right: 0.25rem;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

/* Alert styling in tabs */
.alert {
    margin: 1rem;
    color: inherit;
}

.alert-info {
    background-color: var(--info-light) !important;
    color: var(--info) !important;
    border-color: var(--info) !important;
}

/* Route display styling */
.route-display {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
}

.route-display .badge {
    white-space: nowrap;
}

/* Ticket info styling */
.ticket-info {
    text-align: center;
}

/* Statistics cards */
.card.bg-success,
.card.bg-warning,
.card.bg-info,
.card.bg-primary {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card.bg-success .card-body,
.card.bg-warning .card-body,
.card.bg-info .card-body,
.card.bg-primary .card-body {
    padding: 1rem;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .route-display {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .route-display .fa-arrow-right {
        transform: rotate(90deg);
        margin: 0.25rem 0;
    }
    
    .btn-group {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .btn-group .btn {
        margin-right: 0;
        width: 100%;
    }
}

/* Enhanced table styling */
.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.02);
}

.table th {
    background: var(--primary-blue);
    color: white;
    font-weight: 600;
    border: none;
}

/* Form improvements */
.form-select:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
}

/* Button improvements */
.btn {
    border-radius: 0.375rem;
    font-weight: 500;
    transition: all 0.15s ease-in-out;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* Empty state styling */
.empty-state {
    padding: 3rem 1rem;
    text-align: center;
}

.empty-state i {
    opacity: 0.5;
}
</style>

<!-- JavaScript for Tab Functionality -->
<script>
// Initialize Bootstrap tabs
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tab functionality
    const triggerTabList = [].slice.call(document.querySelectorAll('#scheduleTab button[data-bs-toggle="tab"]'));
    triggerTabList.forEach(function(triggerEl) {
        const tabTrigger = new bootstrap.Tab(triggerEl);
        
        triggerEl.addEventListener('click', function(event) {
            event.preventDefault();
            tabTrigger.show();
        });
    });
    
    // Set initial active tab
    const activeTab = document.querySelector('#scheduleTab .nav-link.active');
    if (activeTab) {
        const tab = new bootstrap.Tab(activeTab);
        tab.show();
    }
    
    // Handle jadwal selection change
    const selectJadwal = document.getElementById('selectJadwal');
    if (selectJadwal) {
        selectJadwal.addEventListener('change', function() {
            loadTiketData(this.value);
        });
    }
});

function loadTiketData(idJadwal) {
    if (!idJadwal) {
        document.getElementById('tiketDataContainer').innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-ticket-alt fa-4x text-muted mb-3"></i>
                <h5 class="text-muted mb-2">Pilih Jadwal</h5>
                <p class="text-muted">Pilih jadwal di atas untuk melihat dan mengelola tiket</p>
            </div>
        `;
        return;
    }

    // Show loading
    document.getElementById('tiketDataContainer').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <h6 class="text-muted mt-3">Memuat data tiket...</h6>
        </div>
    `;

    // Simulate loading tiket data with more realistic content
    setTimeout(() => {
        // Generate sample ticket data for demonstration
        let ticketRows = '';
        const totalSeats = 40; // Default bus capacity
        const statusOptions = ['Tersedia', 'Dibooking', 'Lunas', 'Check-in'];
        const statusClasses = ['success', 'warning', 'info', 'primary'];
        const sampleNames = ['John Doe', 'Jane Smith', 'Ahmad Rahman', 'Siti Nurhaliza', 'Budi Santoso'];
        
        for (let i = 1; i <= totalSeats; i++) {
            const statusIndex = Math.floor(Math.random() * statusOptions.length);
            const status = statusOptions[statusIndex];
            const statusClass = statusClasses[statusIndex];
            const isBooked = status !== 'Tersedia';
            const bookingCode = isBooked ? `TRV${String(Math.floor(Math.random() * 9999)).padStart(4, '0')}` : '-';
            const passengerName = isBooked ? sampleNames[Math.floor(Math.random() * sampleNames.length)] : '-';
            
            ticketRows += `
                <tr>
                    <td class="text-center fw-bold">${i}</td>
                    <td><span class="badge badge-${statusClass}">${status}</span></td>
                    <td class="fw-bold">${bookingCode}</td>
                    <td>${passengerName}</td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            ${isBooked ? `
                                <button class="btn btn-info btn-sm" onclick="viewTicketDetail('${bookingCode}')" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-warning btn-sm" onclick="editTicket('${bookingCode}')" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="cancelTicket('${bookingCode}')" title="Batalkan">
                                    <i class="fas fa-times"></i>
                                </button>
                            ` : `
                                <button class="btn btn-success btn-sm" onclick="bookSeat(${i})" title="Book Kursi">
                                    <i class="fas fa-plus"></i>
                                </button>
                            `}
                        </div>
                    </td>
                </tr>
            `;
        }
        
        document.getElementById('tiketDataContainer').innerHTML = `
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h4 class="mb-1">24</h4>
                            <small>Tersedia</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h4 class="mb-1">8</h4>
                            <small>Dibooking</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h4 class="mb-1">6</h4>
                            <small>Lunas</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h4 class="mb-1">2</h4>
                            <small>Check-in</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th width="10%"><strong>No. Kursi</strong></th>
                            <th width="15%"><strong>Status</strong></th>
                            <th width="20%"><strong>Kode Booking</strong></th>
                            <th width="25%"><strong>Nama Penumpang</strong></th>
                            <th width="30%" class="text-center"><strong>Aksi</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        ${ticketRows}
                    </tbody>
                </table>
            </div>
        `;
    }, 1500);
}

function generateAllTickets() {
    if (confirm('Generate semua tiket untuk jadwal ini? Ini akan membuat kursi kosong menjadi tersedia.')) {
        // Show loading
        showNotification('Generating tiket...', 'info');
        setTimeout(() => {
            showNotification('Semua tiket berhasil di-generate!', 'success');
            // Reload current tiket data
            const selectJadwal = document.getElementById('selectJadwal');
            if (selectJadwal.value) {
                loadTiketData(selectJadwal.value);
            }
        }, 2000);
    }
}

function resetAllTickets() {
    if (confirm('Reset semua tiket untuk jadwal ini? Semua booking akan dibatalkan!')) {
        showNotification('Mereset tiket...', 'warning');
        setTimeout(() => {
            showNotification('Semua tiket berhasil direset!', 'success');
            const selectJadwal = document.getElementById('selectJadwal');
            if (selectJadwal.value) {
                loadTiketData(selectJadwal.value);
            }
        }, 2000);
    }
}

function viewTicketDetail(bookingCode) {
    alert(`Melihat detail tiket: ${bookingCode}\n\nFitur ini akan menampilkan:\n- Data penumpang lengkap\n- Status pembayaran\n- Riwayat perubahan\n- QR Code tiket`);
}

function editTicket(bookingCode) {
    alert(`Edit tiket: ${bookingCode}\n\nFitur ini akan membuka form untuk:\n- Mengubah data penumpang\n- Update status tiket\n- Mengubah kursi`);
}

function cancelTicket(bookingCode) {
    if (confirm(`Batalkan tiket ${bookingCode}?`)) {
        showNotification(`Tiket ${bookingCode} berhasil dibatalkan`, 'success');
        const selectJadwal = document.getElementById('selectJadwal');
        if (selectJadwal.value) {
            loadTiketData(selectJadwal.value);
        }
    }
}

function bookSeat(seatNumber) {
    alert(`Book kursi nomor ${seatNumber}\n\nFitur ini akan membuka form untuk:\n- Input data penumpang\n- Konfirmasi booking\n- Generate kode booking`);
}

function showNotification(message, type = 'info') {
    // Simple notification system
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check' : type === 'warning' ? 'exclamation' : 'info'}-circle me-2"></i>
        ${message}
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
}

function showTiketModal(idJadwal) {
    // Switch to tiket tab using Bootstrap Tab API
    const tiketTab = document.getElementById('tiket-tab');
    if (tiketTab) {
        const tab = new bootstrap.Tab(tiketTab);
        tab.show();
    }
    
    // Select the jadwal in dropdown
    const selectJadwal = document.getElementById('selectJadwal');
    if (selectJadwal) {
        selectJadwal.value = idJadwal;
        // Trigger change event
        selectJadwal.dispatchEvent(new Event('change'));
    }
    
    // Load tiket data
    loadTiketData(idJadwal);
}
</script>
