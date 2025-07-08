<?php
require_once 'config/database.php';

echo "=== CEK DATA PELANGGAN DAN PEMESANAN ===\n\n";

try {
    // Cek struktur tabel pelanggan
    echo "Struktur tabel pelanggan:\n";
    $stmt = $pdo->query('DESCRIBE pelanggan');
    while ($row = $stmt->fetch()) {
        echo "- {$row['Field']} ({$row['Type']})\n";
    }
    echo "\n";
    
    // Tambahkan join dengan pelanggan
    echo "Sample pemesanan dengan nama pelanggan:\n";
    $stmt = $pdo->query('SELECT p.id_pemesanan, pel.nama, p.total_harga, p.status 
                         FROM pemesanan p 
                         JOIN pelanggan pel ON p.id_pelanggan = pel.id_pelanggan 
                         LIMIT 5');
    while ($row = $stmt->fetch()) {
        echo "- ID: {$row['id_pemesanan']}, Nama: {$row['nama']}, Status: {$row['status']}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
