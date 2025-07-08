<?php
require_once 'config/database.php';

echo "=== DEBUG DATABASE ===\n";

try {
    // Cek struktur tabel jadwal
    $stmt = $pdo->query('DESCRIBE jadwal');
    echo "Tabel jadwal:\n";
    while ($row = $stmt->fetch()) {
        echo "- {$row['Field']} ({$row['Type']})\n";
    }
    echo "\n";
    
    // Cek struktur tabel rute
    $stmt = $pdo->query('DESCRIBE rute');
    echo "Tabel rute:\n";
    while ($row = $stmt->fetch()) {
        echo "- {$row['Field']} ({$row['Type']})\n";
    }
    echo "\n";
    
    // Cek jumlah data
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM jadwal');
    echo "Jumlah data jadwal: " . $stmt->fetchColumn() . "\n";
    
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM rute');
    echo "Jumlah data rute: " . $stmt->fetchColumn() . "\n";
    
    // Cek data jadwal dengan id_jadwal = 3
    $stmt = $pdo->prepare('SELECT * FROM jadwal WHERE id_jadwal = ?');
    $stmt->execute([3]);
    $jadwal = $stmt->fetch();
    
    echo "\nData jadwal dengan id_jadwal = 3:\n";
    if ($jadwal) {
        print_r($jadwal);
    } else {
        echo "Tidak ditemukan\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
