<?php
require_once 'config/database.php';

echo "=== MENAMBAHKAN DATA SAMPLE ===\n";

try {
    // Cek apakah ada data bus
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM bus');
    $busCount = $stmt->fetchColumn();
    
    if ($busCount == 0) {
        echo "Menambahkan data bus...\n";
        $pdo->exec("INSERT INTO bus (nama_po, kelas, kapasitas) VALUES 
            ('Sinar Jaya', 'Eksekutif', 32),
            ('Kramat Djati', 'Bisnis', 36),
            ('Haryanto', 'Ekonomi', 40)");
    }
    
    // Cek apakah ada data rute
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM rute');
    $ruteCount = $stmt->fetchColumn();
    
    if ($ruteCount == 0) {
        echo "Menambahkan data rute...\n";
        $pdo->exec("INSERT INTO rute (nama_rute, kota_asal, kota_tujuan, jarak_km, estimasi_waktu) VALUES 
            ('Jakarta - Bandung', 'Jakarta', 'Bandung', 150, '3 jam'),
            ('Jakarta - Yogyakarta', 'Jakarta', 'Yogyakarta', 430, '8 jam'),
            ('Bandung - Surabaya', 'Bandung', 'Surabaya', 550, '10 jam')");
    }
    
    // Cek apakah ada data jadwal
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM jadwal');
    $jadwalCount = $stmt->fetchColumn();
    
    if ($jadwalCount == 0) {
        echo "Menambahkan data jadwal...\n";
        $pdo->exec("INSERT INTO jadwal (id_rute, id_bus, waktu_berangkat, waktu_tiba, harga) VALUES 
            (1, 1, '2025-07-10 08:00:00', '2025-07-10 11:00:00', 75000),
            (2, 2, '2025-07-10 20:00:00', '2025-07-11 04:00:00', 150000),
            (3, 3, '2025-07-11 06:00:00', '2025-07-11 16:00:00', 200000)");
    }
    
    echo "Data sample berhasil ditambahkan!\n";
    
    // Tampilkan data yang ada
    echo "\n=== DATA YANG ADA ===\n";
    $stmt = $pdo->query('SELECT j.id_jadwal, r.nama_rute, b.nama_po, j.waktu_berangkat, j.harga 
                         FROM jadwal j 
                         JOIN rute r ON j.id_rute = r.id_rute 
                         JOIN bus b ON j.id_bus = b.id_bus');
    
    while ($row = $stmt->fetch()) {
        echo "ID: {$row['id_jadwal']}, Rute: {$row['nama_rute']}, PO: {$row['nama_po']}, Berangkat: {$row['waktu_berangkat']}, Harga: {$row['harga']}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
