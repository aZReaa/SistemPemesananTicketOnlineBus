<?php
require_once 'config/database.php';

echo "<h2>Debug Jadwal ID: 3</h2>";

try {
    // Cek data jadwal dengan ID 3
    echo "<h3>1. Data Jadwal ID 3:</h3>";
    $stmt = $pdo->prepare('SELECT * FROM jadwal WHERE id_jadwal = ?');
    $stmt->execute([3]);
    $jadwal = $stmt->fetch();
    
    if ($jadwal) {
        echo "<pre>";
        print_r($jadwal);
        echo "</pre>";
    } else {
        echo "Jadwal dengan ID 3 tidak ditemukan!<br>";
    }
    
    // Cek data rute
    echo "<h3>2. Data Rute:</h3>";
    $stmt = $pdo->query('SELECT * FROM rute');
    $rutes = $stmt->fetchAll();
    echo "<pre>";
    print_r($rutes);
    echo "</pre>";
    
    // Cek data bus
    echo "<h3>3. Data Bus:</h3>";
    $stmt = $pdo->query('SELECT * FROM bus');
    $buses = $stmt->fetchAll();
    echo "<pre>";
    print_r($buses);
    echo "</pre>";
    
    // Test query JOIN seperti di model
    echo "<h3>4. Query JOIN seperti di model:</h3>";
    $sql = "SELECT 
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
            JOIN rute r ON j.id_rute = r.id_rute
            JOIN bus b ON j.id_bus = b.id_bus
            WHERE j.id_jadwal = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([3]);
    $result = $stmt->fetch();
    
    if ($result) {
        echo "<pre>";
        print_r($result);
        echo "</pre>";
    } else {
        echo "Query JOIN tidak menghasilkan data!<br>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
