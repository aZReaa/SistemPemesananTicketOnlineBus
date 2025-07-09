<?php
require_once 'config/database.php';

// Koneksi database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<h2>✅ Koneksi Database Berhasil</h2>";
} catch(PDOException $e) {
    die("<h2>❌ Koneksi Database Gagal: " . $e->getMessage() . "</h2>");
}

echo "<h1>🔧 Database Repair Tool</h1>";
echo "<hr>";

// Cek data rute (ambil beberapa saja untuk tampilan)
echo "<h3>� Data Rute (Sample 10 Data):</h3>";
$stmt = $pdo->query("SELECT id_rute, nama_rute, nama_po, kelas, kota_asal, kota_tujuan, kapasitas, jarak_km, status FROM rute ORDER BY id_rute LIMIT 10");
$rutes = $stmt->fetchAll();
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>ID</th><th>Nama Rute</th><th>PO</th><th>Kota Asal</th><th>Kota Tujuan</th><th>Kapasitas</th><th>Jarak</th><th>Status</th></tr>";
foreach($rutes as $rute) {
    echo "<tr>";
    echo "<td>{$rute['id_rute']}</td>";
    echo "<td>{$rute['nama_rute']}</td>";
    echo "<td>{$rute['nama_po']}</td>";
    echo "<td>{$rute['kota_asal']}</td>";
    echo "<td>{$rute['kota_tujuan']}</td>";
    echo "<td>{$rute['kapasitas']}</td>";
    echo "<td>{$rute['jarak_km']} km</td>";
    echo "<td>{$rute['status']}</td>";
    echo "</tr>";
}
echo "</table>";

// Hitung total rute
$stmt = $pdo->query("SELECT COUNT(*) as total FROM rute");
$total_rute = $stmt->fetch()['total'];
echo "<p><strong>Total Rute: $total_rute</strong></p>";

// Cek data jadwal
echo "<h3>🚌 Data Jadwal:</h3>";
$stmt = $pdo->query("SELECT j.*, r.nama_po, r.kota_asal, r.kota_tujuan, r.kapasitas 
                     FROM jadwal j 
                     LEFT JOIN rute r ON j.id_rute = r.id_rute 
                     ORDER BY j.id_jadwal");
$jadwals = $stmt->fetchAll();
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>ID</th><th>ID Rute</th><th>Rute</th><th>PO</th><th>Waktu Berangkat</th><th>Waktu Tiba</th><th>Harga</th><th>Status</th></tr>";
foreach($jadwals as $jadwal) {
    $rute_text = $jadwal['kota_asal'] ? $jadwal['kota_asal'] . ' → ' . $jadwal['kota_tujuan'] : 'RUTE TIDAK DITEMUKAN';
    $style = $jadwal['kota_asal'] ? '' : 'background-color: #ffcccc;';
    echo "<tr style='$style'>";
    echo "<td>{$jadwal['id_jadwal']}</td>";
    echo "<td>{$jadwal['id_rute']}</td>";
    echo "<td>$rute_text</td>";
    echo "<td>" . ($jadwal['nama_po'] ?? '-') . "</td>";
    echo "<td>{$jadwal['waktu_berangkat']}</td>";
    echo "<td>{$jadwal['waktu_tiba']}</td>";
    echo "<td>Rp " . number_format($jadwal['harga'], 0, ',', '.') . "</td>";
    echo "<td>" . ($jadwal['kota_asal'] ? 'Valid' : 'ID Rute Tidak Valid') . "</td>";
    echo "</tr>";
}
echo "</table>";
echo "<p><strong>Total Jadwal: " . count($jadwals) . "</strong></p>";

// Cek data tiket
echo "<h3>🎫 Data Tiket:</h3>";
$stmt = $pdo->query("SELECT COUNT(*) as total FROM tiket");
$tiket_count = $stmt->fetch()['total'];
echo "<p><strong>Total Tiket: $tiket_count</strong></p>";

if($tiket_count > 0) {
    $stmt = $pdo->query("SELECT t.*, 
                                r.nama_po, 
                                r.kota_asal, 
                                r.kota_tujuan,
                                j.waktu_berangkat,
                                j.harga
                         FROM tiket t 
                         LEFT JOIN jadwal j ON t.id_jadwal = j.id_jadwal 
                         LEFT JOIN rute r ON j.id_rute = r.id_rute 
                         ORDER BY t.id_tiket
                         LIMIT 20");
    $tikets = $stmt->fetchAll();
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID Tiket</th><th>ID Jadwal</th><th>No Kursi</th><th>Status</th><th>Rute</th><th>Kode Booking</th></tr>";
    foreach($tikets as $tiket) {
        $rute_info = ($tiket['kota_asal'] && $tiket['kota_tujuan']) ? 
                     $tiket['kota_asal'] . ' → ' . $tiket['kota_tujuan'] : 
                     'Rute tidak valid';
        echo "<tr>";
        echo "<td>{$tiket['id_tiket']}</td>";
        echo "<td>{$tiket['id_jadwal']}</td>";
        echo "<td>{$tiket['nomor_kursi']}</td>";
        echo "<td>{$tiket['status']}</td>";
        echo "<td>$rute_info</td>";
        echo "<td>" . ($tiket['kode_booking'] ?? '-') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<p><em>Menampilkan maksimal 20 tiket pertama</em></p>";
}

echo "<hr>";
echo "<h3>🔨 Aksi Perbaikan:</h3>";

// Form untuk fix data
if(isset($_POST['fix_data'])) {
    echo "<div style='background: #e8f5e8; padding: 10px; border: 1px solid #4CAF50; margin: 10px 0;'>";
    echo "<h4>🔄 Memulai perbaikan data...</h4>";
    
    // 1. Hapus jadwal yang id_rute tidak valid
    $stmt = $pdo->prepare("DELETE FROM jadwal WHERE id_rute NOT IN (SELECT id_rute FROM rute)");
    $stmt->execute();
    $affected = $stmt->rowCount();
    echo "<p>✅ Menghapus $affected jadwal dengan id_rute tidak valid</p>";
    
    // 2. Buat jadwal baru dengan rute yang valid (5 rute pertama)
    $valid_rutes = $pdo->query("SELECT id_rute, nama_po, kapasitas FROM rute WHERE status = 'active' ORDER BY id_rute LIMIT 5")->fetchAll();
    
    $jadwal_count = 0;
    foreach($valid_rutes as $rute) {
        // Tambah jadwal untuk hari ini dan besok
        $dates = [
            date('Y-m-d'),
            date('Y-m-d', strtotime('+1 day'))
        ];
        
        foreach($dates as $date) {
            $waktu_berangkat = $date . ' ' . sprintf('%02d:00:00', 8 + ($jadwal_count % 12));
            $waktu_tiba = $date . ' ' . sprintf('%02d:00:00', 12 + ($jadwal_count % 12));
            $harga = 50000 + ($jadwal_count * 25000); // Harga bervariasi
            
            $stmt = $pdo->prepare("INSERT INTO jadwal (id_rute, waktu_berangkat, waktu_tiba, harga) VALUES (?, ?, ?, ?)");
            $stmt->execute([$rute['id_rute'], $waktu_berangkat, $waktu_tiba, $harga]);
            $jadwal_count++;
        }
    }
    echo "<p>✅ Menambahkan $jadwal_count jadwal baru</p>";
    
    // 3. Hapus tiket lama yang tidak valid
    $stmt = $pdo->prepare("DELETE FROM tiket WHERE id_jadwal NOT IN (SELECT id_jadwal FROM jadwal)");
    $stmt->execute();
    $deleted_tickets = $stmt->rowCount();
    echo "<p>✅ Menghapus $deleted_tickets tiket yang tidak valid</p>";
    
    // 4. Generate tiket untuk setiap jadwal baru
    $stmt = $pdo->query("SELECT j.id_jadwal, r.kapasitas 
                         FROM jadwal j 
                         JOIN rute r ON j.id_rute = r.id_rute");
    $jadwals_for_tickets = $stmt->fetchAll();
    $total_tickets = 0;
    
    foreach($jadwals_for_tickets as $jadwal) {
        for($i = 1; $i <= $jadwal['kapasitas']; $i++) {
            $nomor_kursi = str_pad($i, 2, '0', STR_PAD_LEFT);
            $status = 'available';
            $kode_booking = null;
            
            // Simulasikan beberapa kursi sudah dibooking (kursi 1-3)
            if($i <= 3) {
                $status = 'booked';
                $kode_booking = 'TRV' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            }
            
            $stmt = $pdo->prepare("INSERT INTO tiket (id_jadwal, nomor_kursi, status, kode_booking, id_pemesanan) VALUES (?, ?, ?, ?, NULL)");
            $stmt->execute([$jadwal['id_jadwal'], $nomor_kursi, $status, $kode_booking]);
            $total_tickets++;
        }
    }
    echo "<p>✅ Membuat $total_tickets tiket baru</p>";
    
    echo "<h4>✅ Perbaikan selesai! <a href='fix_database_web.php'>Refresh halaman</a></h4>";
    echo "</div>";
}

if(isset($_POST['clear_all'])) {
    echo "<div style='background: #ffe8e8; padding: 10px; border: 1px solid #f44336; margin: 10px 0;'>";
    echo "<h4>🗑️ Menghapus semua data...</h4>";
    
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("TRUNCATE TABLE tiket");
    $pdo->exec("DELETE FROM jadwal WHERE id_jadwal > 0");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    echo "<p>✅ Semua data jadwal dan tiket dihapus</p>";
    echo "<h4>✅ Penghapusan selesai! <a href='fix_database_web.php'>Refresh halaman</a></h4>";
    echo "</div>";
}

echo "<form method='POST' style='margin: 10px 0;'>";
echo "<button type='submit' name='fix_data' style='background: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; margin-right: 10px;'>🔧 Perbaiki & Buat Data Sample</button>";
echo "<button type='submit' name='clear_all' style='background: #f44336; color: white; padding: 10px 20px; border: none; cursor: pointer;' onclick='return confirm(\"Yakin ingin menghapus semua data jadwal & tiket?\")'>🗑️ Hapus Semua Data</button>";
echo "</form>";

echo "<hr>";
echo "<p><a href='index.php?page=admin_schedule'>← Kembali ke Halaman Admin</a></p>";
?>

// Cek data rute
echo "<h3>📋 Data Rute:</h3>";
$stmt = $pdo->query("SELECT * FROM rute ORDER BY id_rute LIMIT 10");
$rutes = $stmt->fetchAll();
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>ID</th><th>Nama Rute</th><th>PO</th><th>Kota Asal</th><th>Kota Tujuan</th><th>Kapasitas</th><th>Jarak</th><th>Status</th></tr>";
foreach($rutes as $rute) {
    echo "<tr>";
    echo "<td>{$rute['id_rute']}</td>";
    echo "<td>{$rute['nama_rute']}</td>";
    echo "<td>{$rute['nama_po']}</td>";
    echo "<td>{$rute['kota_asal']}</td>";
    echo "<td>{$rute['kota_tujuan']}</td>";
    echo "<td>{$rute['kapasitas']}</td>";
    echo "<td>{$rute['jarak_km']} km</td>";
    echo "<td>{$rute['status']}</td>";
    echo "</tr>";
}
echo "</table>";

$total_rute = $pdo->query("SELECT COUNT(*) FROM rute")->fetchColumn();
echo "<p><strong>Total Rute: $total_rute</strong></p>";

// Cek data jadwal
echo "<h3>🚌 Data Jadwal:</h3>";
$stmt = $pdo->query("SELECT j.*, r.nama_po, r.kota_asal, r.kota_tujuan, r.kapasitas 
                     FROM jadwal j 
                     LEFT JOIN rute r ON j.id_rute = r.id_rute 
                     ORDER BY j.id_jadwal");
$jadwals = $stmt->fetchAll();
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>ID</th><th>ID Rute</th><th>Rute</th><th>PO</th><th>Waktu Berangkat</th><th>Waktu Tiba</th><th>Harga</th><th>Status</th></tr>";
foreach($jadwals as $jadwal) {
    $rute_text = $jadwal['kota_asal'] ? $jadwal['kota_asal'] . ' → ' . $jadwal['kota_tujuan'] : 'RUTE TIDAK DITEMUKAN';
    $style = $jadwal['kota_asal'] ? '' : 'background-color: #ffcccc;';
    echo "<tr style='$style'>";
    echo "<td>{$jadwal['id_jadwal']}</td>";
    echo "<td>{$jadwal['id_rute']}</td>";
    echo "<td>$rute_text</td>";
    echo "<td>" . ($jadwal['nama_po'] ?? '-') . "</td>";
    echo "<td>{$jadwal['waktu_berangkat']}</td>";
    echo "<td>{$jadwal['waktu_tiba']}</td>";
    echo "<td>Rp " . number_format($jadwal['harga'], 0, ',', '.') . "</td>";
    echo "<td>" . ($jadwal['kota_asal'] ? 'Valid' : 'Invalid - Rute tidak ditemukan') . "</td>";
    echo "</tr>";
}
echo "</table>";
echo "<p><strong>Total Jadwal: " . count($jadwals) . "</strong></p>";

// Cek data tiket
echo "<h3>🎫 Data Tiket:</h3>";
$stmt = $pdo->query("SELECT COUNT(*) as total FROM tiket");
$tiket_count = $stmt->fetch()['total'];
echo "<p><strong>Total Tiket: $tiket_count</strong></p>";

if($tiket_count > 0) {
    $stmt = $pdo->query("SELECT t.*, r.kota_asal, r.kota_tujuan, r.nama_po
                         FROM tiket t 
                         LEFT JOIN jadwal j ON t.id_jadwal = j.id_jadwal 
                         LEFT JOIN rute r ON j.id_rute = r.id_rute 
                         LIMIT 10");
    $tikets = $stmt->fetchAll();
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>ID Jadwal</th><th>No Kursi</th><th>Status</th><th>Kode Booking</th><th>Rute</th></tr>";
    foreach($tikets as $tiket) {
        echo "<tr>";
        echo "<td>{$tiket['id_tiket']}</td>";
        echo "<td>{$tiket['id_jadwal']}</td>";
        echo "<td>{$tiket['nomor_kursi']}</td>";
        echo "<td>{$tiket['status']}</td>";
        echo "<td>" . ($tiket['kode_booking'] ?? '-') . "</td>";
        echo "<td>" . ($tiket['kota_asal'] ? $tiket['kota_asal'] . ' → ' . $tiket['kota_tujuan'] : 'Rute tidak valid') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<hr>";
echo "<h3>🔨 Aksi Perbaikan:</h3>";

// Form untuk fix data
if(isset($_POST['fix_data'])) {
    echo "<div style='background: #e8f5e8; padding: 10px; border: 1px solid #4CAF50; margin: 10px 0;'>";
    echo "<h4>🔄 Memulai perbaikan data...</h4>";
    
    // 1. Hapus jadwal yang id_rute tidak valid
    $stmt = $pdo->prepare("DELETE FROM jadwal WHERE id_rute NOT IN (SELECT id_rute FROM rute)");
    $deleted = $stmt->execute();
    $affected = $stmt->rowCount();
    echo "<p>✅ Menghapus $affected jadwal dengan id_rute tidak valid</p>";
    
    // 2. Buat jadwal baru dengan rute yang valid
    $new_jadwals = [
        [1, 'Litha & Co', '2025-07-10 08:00:00', 40, 'aktif'],
        [2, 'Litha & Co', '2025-07-10 14:00:00', 40, 'aktif'],
        [3, 'Putra Jaya', '2025-07-10 10:00:00', 35, 'aktif'],
        [5, 'Bintang Prima', '2025-07-10 16:00:00', 45, 'aktif'],
        [7, 'Anugrah', '2025-07-11 07:00:00', 40, 'aktif']
    ];
    
    foreach($new_jadwals as $jadwal) {
        $stmt = $pdo->prepare("INSERT INTO jadwal (id_rute, nama_po, waktu_berangkat, kapasitas, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute($jadwal);
    }
    echo "<p>✅ Menambahkan " . count($new_jadwals) . " jadwal baru</p>";
    
    // 3. Generate tiket untuk setiap jadwal
    $stmt = $pdo->query("SELECT id_jadwal, kapasitas FROM jadwal");
    $jadwals_for_tickets = $stmt->fetchAll();
    $total_tickets = 0;
    
    foreach($jadwals_for_tickets as $jadwal) {
        for($i = 1; $i <= $jadwal['kapasitas']; $i++) {
            $stmt = $pdo->prepare("INSERT INTO tiket (id_jadwal, no_kursi, status) VALUES (?, ?, 'tersedia')");
            $stmt->execute([$jadwal['id_jadwal'], $i]);
            $total_tickets++;
        }
    }
    echo "<p>✅ Membuat $total_tickets tiket</p>";
    
    echo "<h4>✅ Perbaikan selesai! <a href='fix_database_web.php'>Refresh halaman</a></h4>";
    echo "</div>";
}

if(isset($_POST['clear_all'])) {
    echo "<div style='background: #ffe8e8; padding: 10px; border: 1px solid #f44336; margin: 10px 0;'>";
    echo "<h4>🗑️ Menghapus semua data...</h4>";
    
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("TRUNCATE TABLE tiket");
    $pdo->exec("TRUNCATE TABLE jadwal");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    echo "<p>✅ Semua data jadwal dan tiket dihapus</p>";
    echo "<h4>✅ Penghapusan selesai! <a href='fix_database_web.php'>Refresh halaman</a></h4>";
    echo "</div>";
}

echo "<form method='POST' style='margin: 10px 0;'>";
echo "<button type='submit' name='fix_data' style='background: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; margin-right: 10px;'>🔧 Perbaiki Data</button>";
echo "<button type='submit' name='clear_all' style='background: #f44336; color: white; padding: 10px 20px; border: none; cursor: pointer;' onclick='return confirm(\"Yakin ingin menghapus semua data?\")'>🗑️ Hapus Semua Data</button>";
echo "</form>";

echo "<hr>";
echo "<p><a href='index.php?page=admin_schedule'>← Kembali ke Halaman Admin</a></p>";
?>
