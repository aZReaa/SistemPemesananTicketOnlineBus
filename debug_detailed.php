<?php
require_once 'config/database.php';

echo "<h3>DEBUG JADWAL ID: 1</h3>";

try {
    // 1. Cek data di tabel jadwal
    echo "<h4>1. Data di tabel jadwal:</h4>";
    $stmt = $pdo->prepare('SELECT * FROM jadwal WHERE id_jadwal = ?');
    $stmt->execute([1]);
    $jadwal = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($jadwal) {
        echo "<pre>";
        print_r($jadwal);
        echo "</pre>";
    } else {
        echo "Tidak ada data jadwal dengan ID 1<br>";
    }
    
    // 2. Cek data di tabel rute
    echo "<h4>2. Data di tabel rute:</h4>";
    $stmt = $pdo->query('SELECT * FROM rute LIMIT 5');
    $rutes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($rutes) {
        echo "<pre>";
        print_r($rutes);
        echo "</pre>";
    } else {
        echo "Tidak ada data rute<br>";
    }
    
    // 3. Cek data di tabel bus
    echo "<h4>3. Data di tabel bus:</h4>";
    $stmt = $pdo->query('SELECT * FROM bus LIMIT 5');
    $buses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($buses) {
        echo "<pre>";
        print_r($buses);
        echo "</pre>";
    } else {
        echo "Tidak ada data bus<br>";
    }
    
    // 4. Test query JOIN yang sama seperti di model
    echo "<h4>4. Test Query JOIN (seperti di model):</h4>";
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
            LEFT JOIN rute r ON j.id_rute = r.id_rute
            LEFT JOIN bus b ON j.id_bus = b.id_bus
            WHERE j.id_jadwal = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([1]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        echo "<pre>";
        print_r($result);
        echo "</pre>";
    } else {
        echo "Query JOIN tidak mengembalikan hasil<br>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
    echo "Trace: " . $e->getTraceAsString();
}
?>
