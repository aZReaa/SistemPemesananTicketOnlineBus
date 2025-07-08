<?php
require_once 'config/database.php';

echo "=== CEK STRUKTUR TABEL TIKET DAN PEMESANAN ===\n\n";

try {
    // Cek struktur tabel tiket
    echo "Struktur tabel tiket:\n";
    $stmt = $pdo->query('DESCRIBE tiket');
    while ($row = $stmt->fetch()) {
        echo "- {$row['Field']} ({$row['Type']})\n";
    }
    echo "\n";
    
    // Cek struktur tabel pemesanan
    echo "Struktur tabel pemesanan:\n";
    $stmt = $pdo->query('DESCRIBE pemesanan');
    while ($row = $stmt->fetch()) {
        echo "- {$row['Field']} ({$row['Type']})\n";
    }
    echo "\n";
    
    // Cek data sample dari tiket
    echo "Sample data tiket (5 record teratas):\n";
    $stmt = $pdo->query('SELECT * FROM tiket LIMIT 5');
    while ($row = $stmt->fetch()) {
        echo "- ID: {$row['id_tiket']}, Kode: " . ($row['kode_booking'] ?? 'N/A') . "\n";
    }
    echo "\n";
    
    // Cek data sample dari pemesanan
    echo "Sample data pemesanan (5 record teratas):\n";
    $stmt = $pdo->query('SELECT * FROM pemesanan LIMIT 5');
    while ($row = $stmt->fetch()) {
        echo "- ID: {$row['id_pemesanan']}, Status: " . ($row['status_pembayaran'] ?? 'N/A') . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
