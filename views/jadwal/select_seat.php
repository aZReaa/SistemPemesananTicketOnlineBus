<?php
// views/jadwal/select_seat.php
?>

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

    function updateSummary() {
        const selectedSeats = Array.from(document.querySelectorAll('.seat-map input[type="checkbox"]:checked'));
        const selectedSeatsInputs = document.getElementById('selected-seats-inputs');
        
        // Clear existing inputs
        selectedSeatsInputs.innerHTML = '';
        
        if (selectedSeats.length === 0) {
            bookingSummary.innerHTML = '<p class="text-muted text-center py-4">Silakan pilih kursi untuk melihat ringkasan.</p>';
            btnBookNow.disabled = true;
            return;
        }

        let summaryHtml = '<ul class="list-group list-group-flush mb-3">';
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
            
            summaryHtml += `<li class="list-group-item d-flex justify-content-between align-items-center">
                                Kursi Nomor ${seatNumber}
                                <span>Rp ${basePrice.toLocaleString('id-ID')}</span>
                           </li>`;
        });

        summaryHtml += `<li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                            Total Pembayaran
                            <span class="text-primary">Rp ${totalPrice.toLocaleString('id-ID')}</span>
                       </li>`;
        summaryHtml += '</ul>';

        bookingSummary.innerHTML = summaryHtml;
        btnBookNow.disabled = false;
    }

    seatCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSummary);
    });

    // Form validation before submit
    document.getElementById('booking-form').addEventListener('submit', function(e) {
        const selectedSeats = Array.from(document.querySelectorAll('.seat-map input[type="checkbox"]:checked'));
        
        if (selectedSeats.length === 0) {
            e.preventDefault();
            alert('Silakan pilih setidaknya satu kursi sebelum melanjutkan!');
            return false;
        }
        
        // Show loading
        const submitBtn = document.getElementById('btn-book-now');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
    });

    updateSummary(); // Initial call
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
