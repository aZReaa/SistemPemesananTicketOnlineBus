<?php
// Load Sulawesi cities data
$cities_data = include __DIR__ . '/../config/sulawesi_cities.php';
$popular_routes = $cities_data['popular_routes'];
?>

<div class="container py-5">
    <!-- Hero Section -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Temukan Perjalanan Anda</h1>
        <p class="lead text-muted">Pesan tiket bus dengan mudah dan cepat ke seluruh Sulawesi.</p>
    </div>

    <!-- Search Form Card -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4 p-md-5">
                    <h5 class="card-title text-center mb-4">Cari Jadwal Keberangkatan</h5>
                    <p class="text-center text-muted">Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
                    <form action="index.php?page=search_results" method="POST">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-map-marker-alt"></i></span>
                                    <select class="form-select form-select-lg border-light" id="rute_awal" name="rute_awal" required>
                                        <option value="">Kota Asal</option>
                                        <?php foreach ($popular_routes as $code => $name): ?>
                                            <option value="<?php echo htmlspecialchars($code); ?>"><?php echo htmlspecialchars($name); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-map-marker-alt"></i></span>
                                    <select class="form-select form-select-lg border-light" id="rute_akhir" name="rute_akhir" required>
                                        <option value="">Kota Tujuan</option>
                                        <?php foreach ($popular_routes as $code => $name): ?>
                                            <option value="<?php echo htmlspecialchars($code); ?>"><?php echo htmlspecialchars($name); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-calendar-day"></i></span>
                                    <input type="date" class="form-control form-control-lg border-light" id="tanggal" name="tanggal" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-search me-1"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="search-results" class="mt-5">
        <!-- Hasil pencarian akan ditampilkan di sini -->
    </div>
</div>
