<?php
require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>🔍 Debug Registrasi Backend</h1>";
    
    // 1. Cek struktur tabel users
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
    
    // 2. Cek struktur tabel pelanggan
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
    
    // 3. Cek data yang ada di tabel users
    echo "<h2>3. 📊 Data Users yang Ada (10 terakhir)</h2>";
    $stmt = $pdo->query("SELECT id_user, username, email, role, created_at FROM users ORDER BY id_user DESC LIMIT 10");
    $users = $stmt->fetchAll();
    
    if (count($users) > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Created</th></tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>{$user['id_user']}</td>";
            echo "<td>{$user['username']}</td>";
            echo "<td>{$user['email']}</td>";
            echo "<td>{$user['role']}</td>";
            echo "<td>" . ($user['created_at'] ?? 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Tidak ada data users</p>";
    }
    echo "<br>";
    
    // 4. Test registrasi manual dengan error handling yang detail
    echo "<h2>4. 🧪 Test Registrasi Manual Step by Step</h2>";
    require_once 'models/Pelanggan.php';
    
    // Enable error reporting untuk debugging
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    
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
    
    // Test step by step
    echo "<h3>🔧 Test Step by Step:</h3>";
    
    // Step 1: Test connection
    echo "<p>✅ Database connection: OK</p>";
    
    // Step 2: Test hash password
    $hashedPassword = password_hash($testData['password'], PASSWORD_DEFAULT);
    echo "<p>✅ Password hashing: OK (length: " . strlen($hashedPassword) . ")</p>";
    
    // Step 3: Test duplicate check
    $stmtCheck = $pdo->prepare("SELECT id_user FROM users WHERE username = ? OR email = ?");
    $stmtCheck->execute([$testData['username'], $testData['email']]);
    $existing = $stmtCheck->fetch();
    if ($existing) {
        echo "<p>❌ User sudah ada: ID {$existing['id_user']}</p>";
    } else {
        echo "<p>✅ Username dan email tersedia</p>";
    }
    
    // Step 4: Test user creation
    try {
        $pdo->beginTransaction();
        
        $stmtUser = $pdo->prepare(
            "INSERT INTO users (username, email, password, no_telp, role) VALUES (?, ?, ?, ?, ?)"
        );
        $result = $stmtUser->execute([
            $testData['username'],
            $testData['email'],
            $hashedPassword,
            $testData['no_telp'],
            'pelanggan'
        ]);
        
        if ($result) {
            $id_user = $pdo->lastInsertId();
            echo "<p>✅ User created: ID {$id_user}</p>";
            
            // Step 5: Test pelanggan creation
            $stmtPelanggan = $pdo->prepare(
                "INSERT INTO pelanggan (id_pelanggan, alamat) VALUES (?, ?)"
            );
            $result2 = $stmtPelanggan->execute([$id_user, $testData['alamat']]);
            
            if ($result2) {
                echo "<p>✅ Pelanggan record created</p>";
                $pdo->commit();
                echo "<p style='color: green;'><strong>🎉 REGISTRASI BERHASIL!</strong></p>";
                
                // Verify data
                $stmt = $pdo->prepare("SELECT * FROM users WHERE id_user = ?");
                $stmt->execute([$id_user]);
                $userData = $stmt->fetch();
                
                echo "<h4>Data yang berhasil disimpan:</h4>";
                echo "<ul>";
                echo "<li>ID: {$userData['id_user']}</li>";
                echo "<li>Username: {$userData['username']}</li>";
                echo "<li>Email: {$userData['email']}</li>";
                echo "<li>Role: {$userData['role']}</li>";
                echo "<li>No Telp: {$userData['no_telp']}</li>";
                echo "</ul>";
                
            } else {
                echo "<p>❌ Gagal membuat record pelanggan</p>";
                $pdo->rollBack();
            }
            
        } else {
            echo "<p>❌ Gagal membuat user</p>";
            $pdo->rollBack();
        }
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
        echo "<p><strong>Error Code:</strong> " . $e->getCode() . "</p>";
    }
    
    echo "<hr>";
    echo "<h2>🔗 Test Links</h2>";
    echo "<ul>";
    echo "<li><a href='public/index.php?page=register' target='_blank'><strong>Test Form Registrasi</strong></a></li>";
    echo "<li><a href='public/index.php?page=login' target='_blank'>Test Form Login</a></li>";
    echo "<li><a href='public/index.php' target='_blank'>Halaman Utama</a></li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Fatal Error: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace: " . $e->getTraceAsString() . "</p>";
}
?>
