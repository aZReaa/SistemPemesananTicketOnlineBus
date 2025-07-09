<?php
require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>🔧 Quick Fix & Test</h1>";
    
    // Reset tiket untuk jadwal 4 sebagai test
    $id_jadwal = 4;
    
    echo "<h2>Sebelum Reset - Jadwal {$id_jadwal}:</h2>";
    $stmt = $pdo->prepare("SELECT status, COUNT(*) as jumlah FROM tiket WHERE id_jadwal = ? GROUP BY status");
    $stmt->execute([$id_jadwal]);
    $before = $stmt->fetchAll();
    
    foreach ($before as $status) {
        echo "<p>{$status['status']}: {$status['jumlah']}</p>";
    }
    
    // Reset semua tiket menjadi available dengan pemesanan dummy
    $stmt = $pdo->query("SELECT id_pemesanan FROM pemesanan WHERE status = 'pending' AND total_harga = 0 LIMIT 1");
    $dummy = $stmt->fetch();
    
    if ($dummy) {
        $dummy_id = $dummy['id_pemesanan'];
        
        $stmt = $pdo->prepare("
            UPDATE tiket 
            SET status = 'available', 
                id_pemesanan = ?, 
                kode_booking = CONCAT('AVAIL_', id_jadwal, '_', nomor_kursi)
            WHERE id_jadwal = ?
        ");
        $stmt->execute([$dummy_id, $id_jadwal]);
        
        echo "<h2>Setelah Reset:</h2>";
        $stmt = $pdo->prepare("SELECT status, COUNT(*) as jumlah FROM tiket WHERE id_jadwal = ? GROUP BY status");
        $stmt->execute([$id_jadwal]);
        $after = $stmt->fetchAll();
        
        foreach ($after as $status) {
            echo "<p>{$status['status']}: {$status['jumlah']}</p>";
        }
        
        echo "<p style='color: green;'>✅ Reset berhasil!</p>";
        echo "<p><a href='public/index.php?page=select_seat&id_jadwal={$id_jadwal}' target='_blank'><strong>Test Booking Sekarang</strong></a></p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
