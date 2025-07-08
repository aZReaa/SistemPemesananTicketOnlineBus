<?php
require_once 'config/database.php';

echo "=== DEBUG JADWAL SPESIFIK ===\n";

try {
    // Ambil semua data jadwal untuk melihat ID yang ada
    echo "1. Semua data jadwal:\n";
    $stmt = $pdo->query('SELECT id_jadwal, id_rute, id_bus, waktu_berangkat, harga FROM jadwal');
    while ($row = $stmt->fetch()) {
        echo "ID: {$row['id_jadwal']}, Rute: {$row['id_rute']}, Bus: {$row['id_bus']}, Berangkat: {$row['waktu_berangkat']}\n";
    }
    
    echo "\n2. Data yang digunakan di pencarian (dengan JOIN):\n";
    $searchQuery = "SELECT 
                        j.id_jadwal, 
                        j.waktu_berangkat, 
                        j.waktu_tiba, 
                        j.harga,
                        r.nama_rute,
                        r.kota_asal, 
                        r.kota_tujuan, 
                        r.jarak_km,
                        b.nama_po, 
                        b.kelas, 
                        b.kapasitas
                    FROM jadwal j
                    LEFT JOIN rute r ON j.id_rute = r.id_rute
                    LEFT JOIN bus b ON j.id_bus = b.id_bus
                    ORDER BY j.id_jadwal";
    
    $stmt = $pdo->query($searchQuery);
    while ($row = $stmt->fetch()) {
        echo "ID: {$row['id_jadwal']}, PO: {$row['nama_po']}, Rute: {$row['kota_asal']} → {$row['kota_tujuan']}\n";
    }
    
    echo "\n3. Test query findById untuk id_jadwal tertentu:\n";
    $findQuery = "SELECT 
                    j.id_jadwal, 
                    j.waktu_berangkat, 
                    j.waktu_tiba, 
                    j.harga, 
                    j.id_rute,
                    r.nama_rute,
                    r.kota_asal,
                    r.kota_tujuan,
                    r.jarak_km,
                    r.estimasi_waktu,
                    b.nama_po, 
                    b.kelas,
                    b.kapasitas
                FROM jadwal j
                LEFT JOIN rute r ON j.id_rute = r.id_rute
                LEFT JOIN bus b ON j.id_bus = b.id_bus
                WHERE j.id_jadwal = ?";
    
    // Test dengan beberapa ID
    foreach ([1, 2, 3] as $test_id) {
        $stmt = $pdo->prepare($findQuery);
        $stmt->execute([$test_id]);
        $result = $stmt->fetch();
        
        echo "ID $test_id: ";
        if ($result) {
            echo "DITEMUKAN - PO: {$result['nama_po']}, Rute: {$result['kota_asal']} → {$result['kota_tujuan']}\n";
        } else {
            echo "TIDAK DITEMUKAN\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
