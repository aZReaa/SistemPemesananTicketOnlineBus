<?php
require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>🔍 Debug Masalah Pemesanan</h1>";
    
    // 1. Cek jadwal yang sedang ditest (misalnya jadwal 4)
    $id_jadwal = 4; // Sesuaikan dengan jadwal yang error
    
    echo "<h2>1. 📅 Info Jadwal ID: {$id_jadwal}</h2>";
    $stmt = $pdo->prepare("
        SELECT j.*, r.nama_po, r.kota_asal, r.kota_tujuan, r.kapasitas 
        FROM jadwal j 
        LEFT JOIN rute r ON j.id_rute = r.id_rute 
        WHERE j.id_jadwal = ?
    ");
    $stmt->execute([$id_jadwal]);
    $jadwal = $stmt->fetch();
    
    if ($jadwal) {
        echo "<table border='1'>";
        echo "<tr><th>Field</th><th>Value</th></tr>";
        foreach ($jadwal as $key => $value) {
            if (!is_numeric($key)) {
                echo "<tr><td>{$key}</td><td>{$value}</td></tr>";
            }
        }
        echo "</table>";
    }
    
    // 2. Cek status tiket untuk jadwal ini
    echo "<h2>2. 🎫 Status Tiket Jadwal {$id_jadwal}</h2>";
    $stmt = $pdo->prepare("
        SELECT 
            status,
            COUNT(*) as jumlah,
            GROUP_CONCAT(nomor_kursi ORDER BY nomor_kursi) as kursi_list
        FROM tiket 
        WHERE id_jadwal = ? 
        GROUP BY status
    ");
    $stmt->execute([$id_jadwal]);
    $status_tiket = $stmt->fetchAll();
    
    echo "<table border='1'>";
    echo "<tr><th>Status</th><th>Jumlah</th><th>Nomor Kursi</th></tr>";
    foreach ($status_tiket as $status) {
        $color = $status['status'] == 'available' ? 'green' : ($status['status'] == 'booked' ? 'orange' : 'red');
        echo "<tr style='color: {$color};'>";
        echo "<td><strong>{$status['status']}</strong></td>";
        echo "<td>{$status['jumlah']}</td>";
        echo "<td>" . substr($status['kursi_list'], 0, 100) . (strlen($status['kursi_list']) > 100 ? '...' : '') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 3. Cek tiket yang bermasalah (id_pemesanan dummy)
    echo "<h2>3. 🔍 Tiket dengan ID Pemesanan Dummy</h2>";
    $stmt = $pdo->prepare("
        SELECT t.*, p.status as pemesanan_status
        FROM tiket t
        LEFT JOIN pemesanan p ON t.id_pemesanan = p.id_pemesanan
        WHERE t.id_jadwal = ? 
        AND (p.id_pemesanan IS NULL OR p.status = 'pending')
        LIMIT 10
    ");
    $stmt->execute([$id_jadwal]);
    $tiket_dummy = $stmt->fetchAll();
    
    if (count($tiket_dummy) > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID Tiket</th><th>Nomor Kursi</th><th>Status</th><th>ID Pemesanan</th><th>Kode Booking</th><th>Status Pemesanan</th></tr>";
        foreach ($tiket_dummy as $tiket) {
            echo "<tr>";
            echo "<td>{$tiket['id_tiket']}</td>";
            echo "<td>{$tiket['nomor_kursi']}</td>";
            echo "<td>{$tiket['status']}</td>";
            echo "<td>{$tiket['id_pemesanan']}</td>";
            echo "<td>{$tiket['kode_booking']}</td>";
            echo "<td>" . ($tiket['pemesanan_status'] ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: green;'>✅ Tidak ada tiket dengan pemesanan dummy</p>";
    }
    
    // 4. Reset tiket yang menggunakan pemesanan dummy
    echo "<h2>4. 🔧 Reset Tiket Bermasalah</h2>";
    
    // Cari pemesanan dummy
    $stmt = $pdo->query("
        SELECT id_pemesanan 
        FROM pemesanan 
        WHERE status = 'pending' 
        AND (total_harga = 0 OR total_harga IS NULL)
        ORDER BY id_pemesanan
        LIMIT 1
    ");
    $dummy_pemesanan = $stmt->fetch();
    
    if ($dummy_pemesanan) {
        $dummy_id = $dummy_pemesanan['id_pemesanan'];
        echo "<p>Menggunakan pemesanan dummy ID: {$dummy_id}</p>";
        
        // Update tiket yang menggunakan pemesanan ini menjadi available
        $stmt = $pdo->prepare("
            UPDATE tiket 
            SET status = 'available', 
                kode_booking = CONCAT('AVAIL_', id_jadwal, '_', nomor_kursi)
            WHERE id_pemesanan = ? 
            AND status != 'available'
        ");
        $stmt->execute([$dummy_id]);
        $updated = $stmt->rowCount();
        
        echo "<p style='color: green;'>✅ Berhasil reset {$updated} tiket menjadi available</p>";
        
        // Pastikan semua tiket available untuk jadwal ini menggunakan dummy_id
        $stmt = $pdo->prepare("
            UPDATE tiket 
            SET id_pemesanan = ?,
                kode_booking = CONCAT('AVAIL_', id_jadwal, '_', nomor_kursi)
            WHERE id_jadwal = ? 
            AND status = 'available'
        ");
        $stmt->execute([$dummy_id, $id_jadwal]);
        $updated2 = $stmt->rowCount();
        
        echo "<p style='color: green;'>✅ Berhasil standardisasi {$updated2} tiket available</p>";
        
    } else {
        echo "<p style='color: red;'>❌ Tidak ditemukan pemesanan dummy</p>";
    }
    
    // 5. Verifikasi hasil
    echo "<h2>5. ✅ Verifikasi Hasil</h2>";
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(*) as total_tiket,
            COUNT(CASE WHEN status = 'available' THEN 1 END) as available,
            COUNT(CASE WHEN status = 'booked' THEN 1 END) as booked,
            COUNT(CASE WHEN status = 'used' THEN 1 END) as used
        FROM tiket 
        WHERE id_jadwal = ?
    ");
    $stmt->execute([$id_jadwal]);
    $summary = $stmt->fetch();
    
    echo "<table border='1'>";
    echo "<tr><th>Status</th><th>Jumlah</th></tr>";
    echo "<tr style='color: green;'><td>Total Tiket</td><td>{$summary['total_tiket']}</td></tr>";
    echo "<tr style='color: blue;'><td>Available</td><td>{$summary['available']}</td></tr>";
    echo "<tr style='color: orange;'><td>Booked</td><td>{$summary['booked']}</td></tr>";
    echo "<tr style='color: red;'><td>Used</td><td>{$summary['used']}</td></tr>";
    echo "</table>";
    
    // 6. Test kursi available
    echo "<h2>6. 🪑 Sample Kursi Available</h2>";
    $stmt = $pdo->prepare("
        SELECT nomor_kursi, kode_booking, id_pemesanan
        FROM tiket 
        WHERE id_jadwal = ? 
        AND status = 'available'
        ORDER BY nomor_kursi
        LIMIT 10
    ");
    $stmt->execute([$id_jadwal]);
    $available_seats = $stmt->fetchAll();
    
    if (count($available_seats) > 0) {
        echo "<p style='color: green;'>✅ Ditemukan " . count($available_seats) . " kursi available (menampilkan 10 pertama):</p>";
        echo "<table border='1'>";
        echo "<tr><th>Nomor Kursi</th><th>Kode Booking</th><th>ID Pemesanan</th></tr>";
        foreach ($available_seats as $seat) {
            echo "<tr>";
            echo "<td>{$seat['nomor_kursi']}</td>";
            echo "<td>{$seat['kode_booking']}</td>";
            echo "<td>{$seat['id_pemesanan']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: red;'>❌ TIDAK ADA KURSI AVAILABLE!</p>";
    }
    
    echo "<hr>";
    echo "<h2>🔗 Test Links</h2>";
    echo "<ul>";
    echo "<li><a href='public/index.php?page=select_seat&id_jadwal={$id_jadwal}' target='_blank'><strong>Test Booking Jadwal {$id_jadwal}</strong></a></li>";
    echo "<li><a href='debug_pemesanan.php'>Debug Proses Pemesanan Detail</a></li>";
    echo "<li><a href='public/index.php'>Kembali ke Halaman Utama</a></li>";
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>❌ Database Error</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Code:</strong> " . $e->getCode() . "</p>";
}
?>
