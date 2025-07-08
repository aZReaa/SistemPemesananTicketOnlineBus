<?php
require_once 'config/database.php';

echo "=== CEK STRUKTUR TABEL ===\n";

try {
    // Cek struktur tabel jadwal
    $stmt = $pdo->query('DESCRIBE jadwal');
    echo "Struktur tabel jadwal:\n";
    while ($row = $stmt->fetch()) {
        echo "- {$row['Field']} ({$row['Type']})\n";
    }
    echo "\n";
    
    // Cek struktur tabel rute
    $stmt = $pdo->query('DESCRIBE rute');
    echo "Struktur tabel rute:\n";
    while ($row = $stmt->fetch()) {
        echo "- {$row['Field']} ({$row['Type']})\n";
    }
    echo "\n";
    
    // Cek apakah ada tabel bus
    $stmt = $pdo->query("SHOW TABLES LIKE 'bus'");
    if ($stmt->rowCount() > 0) {
        $stmt = $pdo->query('DESCRIBE bus');
        echo "Struktur tabel bus:\n";
        while ($row = $stmt->fetch()) {
            echo "- {$row['Field']} ({$row['Type']})\n";
        }
    } else {
        echo "Tabel bus tidak ada!\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
