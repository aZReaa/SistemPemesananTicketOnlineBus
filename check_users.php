<?php
require_once 'config/database.php';

echo "=== CEK STRUKTUR TABEL USERS ===\n\n";

try {
    // Cek tabel yang tersedia
    echo "Tabel dalam database:\n";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = [];
    while ($row = $stmt->fetch()) {
        $table = $row[0];
        $tables[] = $table;
        echo "- $table\n";
    }
    echo "\n";
    
    // Cek users
    if (in_array('users', $tables)) {
        echo "Struktur tabel users:\n";
        $stmt = $pdo->query('DESCRIBE users');
        while ($row = $stmt->fetch()) {
            echo "- {$row['Field']} ({$row['Type']})\n";
        }
        echo "\n";
        
        // Cek sample data
        echo "Sample data users (5 baris teratas):\n";
        $stmt = $pdo->query('SELECT * FROM users LIMIT 5');
        while ($row = $stmt->fetch()) {
            $role = $row['role'] ?? 'tidak ada';
            $name = $row['nama'] ?? $row['name'] ?? $row['username'] ?? 'tidak ada';
            echo "- ID: {$row['id']}, Nama: {$name}, Role: {$role}\n";
        }
    }
    
    // Cek apakah ada tabel yang memiliki field 'nama'
    echo "\n\nTabel dengan field 'nama':\n";
    foreach ($tables as $table) {
        $stmt = $pdo->query("DESCRIBE $table");
        $fields = [];
        while ($row = $stmt->fetch()) {
            $fields[] = $row['Field'];
        }
        
        if (in_array('nama', $fields)) {
            echo "- Tabel $table memiliki field 'nama'\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
