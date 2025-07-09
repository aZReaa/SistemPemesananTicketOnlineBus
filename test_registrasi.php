<?php
session_start();
require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>🔧 Test Registrasi</h1>";
    
    // Cek struktur tabel users
    echo "<h2>📋 Struktur Tabel Users</h2>";
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll();
    
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>{$col['Field']}</td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Key']}</td>";
        echo "<td>" . ($col['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table><br>";
    
    // Cek struktur tabel pelanggan
    echo "<h2>📋 Struktur Tabel Pelanggan</h2>";
    $stmt = $pdo->query("DESCRIBE pelanggan");
    $columns = $stmt->fetchAll();
    
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>{$col['Field']}</td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Key']}</td>";
        echo "<td>" . ($col['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table><br>";
    
    // Test registrasi manual
    echo "<h2>🧪 Test Registrasi Model</h2>";
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
    
    if ($pelanggan->create($testData)) {
        echo "<p style='color: green;'>✅ Registrasi berhasil!</p>";
        
        // Cek data yang baru dibuat
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
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
            echo "</ul>";
        }
    } else {
        echo "<p style='color: red;'>❌ Registrasi gagal!</p>";
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
}
?>
