<?php
// views/jadwal/select_seat.php
?>

<!-- Enhanced CSS for Seat Selection with Animations -->
<style>
/* Seat Map Container */
.seat-map-container {
    max-width: 500px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 20px;
    padding: 30px 20px;
    position: relative;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

/* Driver Section */
.driver-seat {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 12px;
    border-radius: 10px 10px 0 0;
    text-align: center;
    font-weight: 600;
    margin-bottom: 20px;
    font-size: 0.9rem;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.driver-seat i {
    margin-right: 8px;
    font-size: 1.1rem;
}

/* Seat Map Grid */
.seat-map {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
    padding: 0 10px;
}

/* Individual Seat */
.seat {
    position: relative;
    width: 50px;
    height: 50px;
    margin: 0 auto;
}

/* Aisle Space */
.seat.aisle {
    background: none;
    pointer-events: none;
}

/* Seat Input (Hidden) */
.seat input[type="checkbox"] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    width: 100%;
    height: 100%;
    z-index: 2;
}

/* Seat Label (Visual Seat) */
.seat label {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #28a745 0%, #34ce57 100%);
    color: white;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2);
    border: 2px solid transparent;
    user-select: none;
}

/* Available Seat Hover */
.seat label:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
    background: linear-gradient(135deg, #34ce57 0%, #28a745 100%);
}

/* Selected Seat */
.seat input[type="checkbox"]:checked + label {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
    border-color: #fff;
    animation: seatSelect 0.4s ease-out;
}

/* Booked/Disabled Seat */
.seat input[type="checkbox"]:disabled + label {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    cursor: not-allowed;
    opacity: 0.7;
    transform: none;
    box-shadow: 0 2px 8px rgba(108, 117, 125, 0.3);
}

.seat input[type="checkbox"]:disabled + label:hover {
    transform: none;
    box-shadow: 0 2px 8px rgba(108, 117, 125, 0.3);
}

/* Seat Selection Animation */
@keyframes seatSelect {
    0% {
        transform: translateY(-2px) scale(1.02);
    }
    50% {
        transform: translateY(-5px) scale(1.1);
    }
    100% {
        transform: translateY(-2px) scale(1.02);
    }
}

/* Seat Legend */
.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    font-weight: 500;
}

.seat-legend {
    width: 20px;
    height: 20px;
    border-radius: 6px;
    display: inline-block;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.seat-legend.available {
    background: linear-gradient(135deg, #28a745 0%, #34ce57 100%);
}

.seat-legend.booked {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
}

.seat-legend.selected {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

/* Summary Card Enhancements */
.card.sticky-top {
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    overflow: hidden;
}

.card-body h4 {
    color: #2c3e50;
    margin-bottom: 1rem;
}

#booking-summary .list-group-item {
    border: none;
    padding: 0.75rem 0;
    background: transparent;
}

#booking-summary .list-group-item:not(:last-child) {
    border-bottom: 1px solid #e9ecef;
}

#booking-summary .list-group-item.fw-bold {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 8px;
    padding: 1rem;
    margin-top: 0.5rem;
}

/* Button Enhancements */
#btn-book-now {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
    border-radius: 12px;
    padding: 15px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
}

#btn-book-now:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
}

#btn-book-now:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .seat-map-container {
        padding: 20px 15px;
    }
    
    .seat {
        width: 40px;
        height: 40px;
    }
    
    .seat label {
        font-size: 0.75rem;
        border-radius: 8px;
    }
    
    .seat-map {
        gap: 8px;
    }
}

/* Loading Animation */
.fa-spinner.fa-spin {
    animation: fa-spin 1s infinite linear;
}

/* Enhanced Alert Styles */
.alert {
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.alert-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
}

/* Card Header Enhancement */
.card-header {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    border-radius: 12px 12px 0 0 !important;
    border: none;
}

.card-subtitle {
    opacity: 0.9;
    margin-bottom: 0;
}

/* Schedule Details Icons */
.card-body i.text-primary {
    color: #007bff !important;
    width: 20px;
    text-align: center;
}
</style>

<!-- Alert Messages -->
<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <?= htmlspecialchars($_SESSION['error_message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-couch me-2"></i>
                        Pilih Kursi Anda
                    </h4>
                    <p class="card-subtitle">Pilih kursi yang tersedia pada denah di bawah ini.</p>
                </div>
                <div class="card-body">
                    <!-- Schedule Details -->
                    <div class="mb-4">
                        <h5><?php echo htmlspecialchars($jadwal['nama_po'] ?? ''); ?></h5>
                        <p class="mb-1">
                            <i class="fas fa-bus-alt me-2 text-primary"></i>
                            <strong><?php echo htmlspecialchars($jadwal['kota_asal'] ?? ''); ?> - <?php echo htmlspecialchars($jadwal['kota_tujuan'] ?? ''); ?></strong>
                        </p>
                        <p class="mb-1">
                            <i class="fas fa-clock me-2 text-primary"></i>
                            <?php echo htmlspecialchars(date('d M Y, H:i', strtotime($jadwal['waktu_berangkat'] ?? 'now'))); ?>
                        </p>
                        <p class="mb-1">
                            <i class="fas fa-couch me-2 text-primary"></i>
                            Kelas <?php echo htmlspecialchars($jadwal['kelas'] ?? ''); ?>
                        </p>
                    </div>

                    <!-- Seat Legend -->
                    <div class="d-flex justify-content-center align-items-center mb-3 gap-3">
                        <div class="legend-item"><span class="seat-legend available"></span> Tersedia</div>
                        <div class="legend-item"><span class="seat-legend booked"></span> Terisi</div>
                        <div class="legend-item"><span class="seat-legend selected"></span> Pilihan Anda</div>
                    </div>

                    <!-- Seat Map -->
                    <div class="seat-map-container mx-auto">
                        <div class="driver-seat"><i class="fas fa-user-tie"></i> Driver</div>
                        <div class="seat-map">
                            <?php
                            $seat_number = 1;
                            $num_rows = ceil($jadwal['kapasitas'] / 4);
                            for ($i = 1; $i <= $num_rows; $i++) {
                                for ($j = 1; $j <= 5; $j++) {
                                    if ($j == 3) {
                                        echo '<div class="seat aisle"></div>';
                                    } else {
                                        if ($seat_number <= $jadwal['kapasitas']) {
                                            $seat_id = 'seat-' . $seat_number;
                                            $is_booked = in_array($seat_number, $booked_seats);
                                            echo '<div class="seat">';
                                            echo '<input type="checkbox" id="' . $seat_id . '" name="seats[]" value="' . $seat_number . '"' . ($is_booked ? ' disabled' : '') . ' data-price="' . $jadwal['harga'] . '">';
                                            echo '<label for="' . $seat_id . '">' . $seat_number . '</label>';
                                            echo '</div>';
                                            $seat_number++;
                                        }
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h4 class="fw-bold">Ringkasan Pemesanan</h4>
                    <hr>
                    <form action="index.php?page=process_booking" method="POST" id="booking-form">
                        <input type="hidden" name="id_jadwal" value="<?php echo $jadwal['id_jadwal']; ?>">
                        <div id="selected-seats-inputs"></div>
                        <div id="booking-summary">
                            <p class="text-muted text-center py-4">Silakan pilih kursi untuk melihat ringkasan.</p>
                        </div>
                        <div class="d-grid">
                            <button id="btn-book-now" type="submit" class="btn btn-primary btn-lg" disabled>Pesan Sekarang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const seatCheckboxes = document.querySelectorAll('.seat-map input[type="checkbox"]:not(:disabled)');
    const bookingSummary = document.getElementById('booking-summary');
    const btnBookNow = document.getElementById('btn-book-now');
    const basePrice = <?php echo $jadwal['harga']; ?>;
    const maxSeats = 4; // Maximum seats per booking

    // Add sound effects (optional)
    function playClickSound() {
        // You can add audio elements here for better UX
        // const audio = new Audio('click.mp3');
        // audio.play().catch(e => {}); // Ignore autoplay policy errors
    }

    function updateSummary() {
        const selectedSeats = Array.from(document.querySelectorAll('.seat-map input[type="checkbox"]:checked'));
        const selectedSeatsInputs = document.getElementById('selected-seats-inputs');
        
        // Clear existing inputs
        selectedSeatsInputs.innerHTML = '';
        
        if (selectedSeats.length === 0) {
            bookingSummary.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-couch fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Silakan pilih kursi untuk melihat ringkasan.</p>
                </div>
            `;
            btnBookNow.disabled = true;
            btnBookNow.innerHTML = 'Pilih Kursi Dulu';
            return;
        }

        // Check maximum seats limit
        if (selectedSeats.length > maxSeats) {
            // Uncheck the last selected seat
            selectedSeats[selectedSeats.length - 1].checked = false;
            showNotification(`Maksimal ${maxSeats} kursi per pemesanan`, 'warning');
            updateSummary(); // Recursive call to update
            return;
        }

        let summaryHtml = '<div class="animate__animated animate__fadeIn">';
        summaryHtml += '<ul class="list-group list-group-flush mb-3">';
        let totalPrice = 0;

        selectedSeats.forEach((seat, index) => {
            const seatNumber = seat.value;
            totalPrice += basePrice;
            
            // Add hidden input for each selected seat
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'seats[]';
            hiddenInput.value = seatNumber;
            selectedSeatsInputs.appendChild(hiddenInput);
            
            summaryHtml += `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-couch me-2 text-primary"></i>
                        <strong>Kursi ${seatNumber}</strong>
                    </div>
                    <span class="text-success fw-bold">Rp ${basePrice.toLocaleString('id-ID')}</span>
                </li>
            `;
        });

        summaryHtml += `
            <li class="list-group-item d-flex justify-content-between align-items-center fw-bold bg-light">
                <div>
                    <i class="fas fa-calculator me-2 text-primary"></i>
                    Total Pembayaran
                </div>
                <span class="text-primary fs-5">Rp ${totalPrice.toLocaleString('id-ID')}</span>
            </li>
        `;
        
        summaryHtml += '</ul>';
        
        // Add seat count info
        summaryHtml += `
            <div class="alert alert-info mb-3">
                <i class="fas fa-info-circle me-2"></i>
                ${selectedSeats.length} kursi dipilih ${maxSeats > selectedSeats.length ? `(maksimal ${maxSeats - selectedSeats.length} lagi)` : '(maksimal tercapai)'}
            </div>
        `;
        
        summaryHtml += '</div>';

        bookingSummary.innerHTML = summaryHtml;
        btnBookNow.disabled = false;
        btnBookNow.innerHTML = `<i class="fas fa-ticket-alt me-2"></i>Pesan ${selectedSeats.length} Kursi`;
    }

    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = `
            top: 20px; 
            right: 20px; 
            z-index: 9999; 
            min-width: 300px;
            animation: slideInRight 0.3s ease-out;
        `;
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 3000);
    }

    // Add click event listeners with enhanced feedback
    seatCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function(e) {
            playClickSound();
            
            // Add visual feedback
            const label = this.nextElementSibling;
            if (this.checked) {
                label.style.animation = 'seatSelect 0.4s ease-out';
                showNotification(`Kursi ${this.value} dipilih`, 'success');
            } else {
                label.style.animation = 'none';
                showNotification(`Kursi ${this.value} dibatalkan`, 'info');
            }
            
            // Reset animation after completion
            setTimeout(() => {
                label.style.animation = '';
            }, 400);
            
            updateSummary();
        });

        // Add hover effects for better UX
        checkbox.addEventListener('mouseenter', function() {
            if (!this.disabled && !this.checked) {
                const label = this.nextElementSibling;
                label.style.transform = 'translateY(-2px) scale(1.03)';
            }
        });

        checkbox.addEventListener('mouseleave', function() {
            if (!this.disabled && !this.checked) {
                const label = this.nextElementSibling;
                label.style.transform = '';
            }
        });
    });

    // Enhanced form validation before submit
    document.getElementById('booking-form').addEventListener('submit', function(e) {
        const selectedSeats = Array.from(document.querySelectorAll('.seat-map input[type="checkbox"]:checked'));
        
        if (selectedSeats.length === 0) {
            e.preventDefault();
            showNotification('Silakan pilih setidaknya satu kursi sebelum melanjutkan!', 'warning');
            return false;
        }
        
        // Show confirmation
        const seatNumbers = selectedSeats.map(seat => seat.value).join(', ');
        const totalPrice = selectedSeats.length * basePrice;
        
        if (!confirm(`Konfirmasi pemesanan:\n\nKursi: ${seatNumbers}\nTotal: Rp ${totalPrice.toLocaleString('id-ID')}\n\nLanjutkan pemesanan?`)) {
            e.preventDefault();
            return false;
        }
        
        // Show loading with enhanced animation
        const submitBtn = document.getElementById('btn-book-now');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses Pemesanan...';
        
        // Add loading overlay to prevent multiple clicks
        const overlay = document.createElement('div');
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 9998;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        `;
        overlay.innerHTML = `
            <div class="text-center">
                <i class="fas fa-spinner fa-spin fa-3x mb-3"></i>
                <div>Sedang memproses pemesanan Anda...</div>
            </div>
        `;
        document.body.appendChild(overlay);
    });

    // Initialize
    updateSummary();
    
    // Welcome message
    setTimeout(() => {
        showNotification('Pilih kursi Anda dengan mengklik nomor kursi', 'info');
    }, 500);
});

// Add CSS animation for notifications
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;
document.head.appendChild(style);
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
