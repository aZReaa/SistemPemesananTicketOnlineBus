<?php
require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>🔧 Perbaikan Database - Tabel Pelanggan</h1>";
    
    // 1. Cek semua tabel yang ada
    echo "<h2>📋 Tabel yang Ada di Database</h2>";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>{$table}</li>";
    }
    echo "</ul>";
    
    // 2. Cek apakah tabel pelanggan ada
    $pelangganExists = in_array('pelanggan', $tables);
    
    if (!$pelangganExists) {
        echo "<h2>⚠️ Tabel Pelanggan Tidak Ada - Membuat Tabel Baru</h2>";
        
        // Buat tabel pelanggan
        $createTableSQL = "
            CREATE TABLE `pelanggan` (
                `id_pelanggan` int NOT NULL,
                `alamat` text,
                PRIMARY KEY (`id_pelanggan`),
                FOREIGN KEY (`id_pelanggan`) REFERENCES `users`(`id_user`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        
        $pdo->exec($createTableSQL);
        echo "<p style='color: green;'>✅ Tabel pelanggan berhasil dibuat!</p>";
        
        // Verifikasi tabel yang baru dibuat
        echo "<h3>📋 Struktur Tabel Pelanggan yang Baru</h3>";
        $stmt = $pdo->query("DESCRIBE pelanggan");
        $columns = $stmt->fetchAll();
        
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        foreach ($columns as $col) {
            echo "<tr>";
            echo "<td>{$col['Field']}</td>";
            echo "<td>{$col['Type']}</td>";
            echo "<td>{$col['Null']}</td>";
            echo "<td>{$col['Key']}</td>";
            echo "<td>" . ($col['Default'] ?? 'NULL') . "</td>";
            echo "<td>{$col['Extra']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
    } else {
        echo "<h2>✅ Tabel Pelanggan Sudah Ada</h2>";
        
        // Tampilkan struktur
        $stmt = $pdo->query("DESCRIBE pelanggan");
        $columns = $stmt->fetchAll();
        
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        foreach ($columns as $col) {
            echo "<tr>";
            echo "<td>{$col['Field']}</td>";
            echo "<td>{$col['Type']}</td>";
            echo "<td>{$col['Null']}</td>";
            echo "<td>{$col['Key']}</td>";
            echo "<td>" . ($col['Default'] ?? 'NULL') . "</td>";
            echo "<td>{$col['Extra']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // 3. Test registrasi manual
    echo "<h2>🧪 Test Registrasi Manual</h2>";
    require_once 'models/Pelanggan.php';
    
    $pelanggan = new Pelanggan($pdo);
    
    $testData = [
        'username' => 'testuser_' . time(),
        'email' => 'test' . time() . '@example.com',
        'password' => 'testpassword123',
        'no_telp' => '081234567890',
        'alamat' => 'Jl. Test No. 123'
    ];
    
    echo "<p>Mencoba registrasi dengan data:</p>";
    echo "<ul>";
    foreach ($testData as $key => $value) {
        if ($key !== 'password') {
            echo "<li><strong>{$key}:</strong> {$value}</li>";
        } else {
            echo "<li><strong>{$key}:</strong> [HIDDEN]</li>";
        }
    }
    echo "</ul>";
    
    try {
        if ($pelanggan->create($testData)) {
            echo "<p style='color: green;'>✅ Registrasi berhasil!</p>";
            
            // Cek data yang baru dibuat
            $stmt = $pdo->prepare("
                SELECT u.*, p.alamat 
                FROM users u 
                LEFT JOIN pelanggan p ON u.id_user = p.id_pelanggan 
                WHERE u.username = ?
            ");
            $stmt->execute([$testData['username']]);
            $user = $stmt->fetch();
            
            if ($user) {
                echo "<p>Data user yang dibuat:</p>";
                echo "<ul>";
                echo "<li>ID: {$user['id_user']}</li>";
                echo "<li>Username: {$user['username']}</li>";
                echo "<li>Email: {$user['email']}</li>";
                echo "<li>Role: {$user['role']}</li>";
                echo "<li>No Telp: {$user['no_telp']}</li>";
                echo "<li>Alamat: " . ($user['alamat'] ?? 'NULL') . "</li>";
                echo "</ul>";
                
                // Hapus test data
                $stmt = $pdo->prepare("DELETE FROM users WHERE username = ?");
                $stmt->execute([$testData['username']]);
                echo "<p style='color: orange;'>🗑️ Test data telah dihapus</p>";
            }
        } else {
            echo "<p style='color: red;'>❌ Registrasi gagal!</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    }
    
    echo "<hr>";
    echo "<h2>🔗 Test Links</h2>";
    echo "<ul>";
    echo "<li><a href='public/index.php?page=register' target='_blank'><strong>Test Form Registrasi</strong></a></li>";
    echo "<li><a href='public/index.php?page=login' target='_blank'>Test Form Login</a></li>";
    echo "<li><a href='public/index.php' target='_blank'>Halaman Utama</a></li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
