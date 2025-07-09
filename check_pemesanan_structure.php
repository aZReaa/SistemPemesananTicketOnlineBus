<?php
require_once 'config/database.php';

try {
    $pdo = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Struktur tabel pemesanan:\n";
    $stmt = $pdo->query('DESCRIBE pemesanan');
    $columns = $stmt->fetchAll();
    
    foreach ($columns as $col) {
        echo $col['Field'] . ' - ' . $col['Type'] . ' - ' . $col['Null'] . ' - ' . $col['Key'] . ' - ' . ($col['Default'] ?? 'NULL') . "\n";
    }
    
    echo "\nContoh data pemesanan yang ada:\n";
    $stmt = $pdo->query('SELECT * FROM pemesanan LIMIT 3');
    $data = $stmt->fetchAll();
    
    if (!empty($data)) {
        // Print column headers
        echo implode(' | ', array_keys($data[0])) . "\n";
        echo str_repeat('-', 80) . "\n";
        
        foreach ($data as $row) {
            echo implode(' | ', $row) . "\n";
        }
    } else {
        echo "Tidak ada data pemesanan.\n";
    }
    
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>
