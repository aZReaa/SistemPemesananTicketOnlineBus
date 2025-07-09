<?php
require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>🔧 Perbaikan Foreign Key Constraint</h1>";
    
    // 1. Cek foreign key yang bermasalah
    echo "<h2>1. 📋 Analisis Foreign Key</h2>";
    $stmt = $pdo->query("
        SELECT 
            CONSTRAINT_NAME,
            TABLE_NAME,
            COLUMN_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE REFERENCED_TABLE_SCHEMA = '$dbname' 
        AND TABLE_NAME = 'tiket'
    ");
    $constraints = $stmt->fetchAll();
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Constraint</th><th>Table</th><th>Column</th><th>Referenced Table</th><th>Referenced Column</th></tr>";
    foreach ($constraints as $constraint) {
        echo "<tr>";
        echo "<td>{$constraint['CONSTRAINT_NAME']}</td>";
        echo "<td>{$constraint['TABLE_NAME']}</td>";
        echo "<td>{$constraint['COLUMN_NAME']}</td>";
        echo "<td style='" . ($constraint['REFERENCED_TABLE_NAME'] == 'jadwal_backup' ? "color: red;" : "") . "'>{$constraint['REFERENCED_TABLE_NAME']}</td>";
        echo "<td>{$constraint['REFERENCED_COLUMN_NAME']}</td>";
        echo "</tr>";
    }
    echo "</table><br>";
    
    // 2. Cek apakah ada tabel jadwal_backup
    echo "<h2>2. 🔍 Cek Tabel yang Ada</h2>";
    $stmt = $pdo->query("SHOW TABLES LIKE '%jadwal%'");
    $tables = $stmt->fetchAll();
    
    echo "<ul>";
    foreach ($tables as $table) {
        $tableName = array_values($table)[0];
        echo "<li>{$tableName}";
        
        // Cek jumlah record
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM `{$tableName}`");
        $count = $stmt->fetch()['count'];
        echo " (records: {$count})";
        
        echo "</li>";
    }
    echo "</ul>";
    
    // 3. Perbaiki foreign key constraint
    echo "<h2>3. 🔧 Perbaikan Foreign Key</h2>";
    
    try {
        // Drop foreign key yang salah
        echo "<p>Menghapus foreign key constraint yang salah...</p>";
        $pdo->exec("ALTER TABLE tiket DROP FOREIGN KEY tiket_ibfk_2");
        echo "<p style='color: green;'>✅ Foreign key tiket_ibfk_2 berhasil dihapus</p>";
    } catch (PDOException $e) {
        echo "<p style='color: orange;'>⚠️ Tidak dapat menghapus foreign key: " . $e->getMessage() . "</p>";
    }
    
    try {
        // Tambah foreign key yang benar
        echo "<p>Menambahkan foreign key constraint yang benar...</p>";
        $pdo->exec("ALTER TABLE tiket ADD CONSTRAINT tiket_jadwal_fk FOREIGN KEY (id_jadwal) REFERENCES jadwal(id_jadwal) ON DELETE CASCADE");
        echo "<p style='color: green;'>✅ Foreign key baru berhasil ditambahkan</p>";
    } catch (PDOException $e) {
        echo "<p style='color: red;'>❌ Gagal menambahkan foreign key: " . $e->getMessage() . "</p>";
        
        // Cek data yang tidak konsisten
        echo "<p>Mengecek data yang tidak konsisten...</p>";
        $stmt = $pdo->query("
            SELECT t.id_jadwal, COUNT(*) as count
            FROM tiket t 
            LEFT JOIN jadwal j ON t.id_jadwal = j.id_jadwal 
            WHERE j.id_jadwal IS NULL 
            GROUP BY t.id_jadwal
        ");
        $inconsistent = $stmt->fetchAll();
        
        if (!empty($inconsistent)) {
            echo "<p style='color: red;'>Ditemukan data tiket dengan id_jadwal yang tidak valid:</p>";
            echo "<ul>";
            foreach ($inconsistent as $inc) {
                echo "<li>ID Jadwal: {$inc['id_jadwal']} (Tiket: {$inc['count']})</li>";
            }
            echo "</ul>";
            
            echo "<p>Menghapus tiket dengan id_jadwal yang tidak valid...</p>";
            $pdo->exec("DELETE t FROM tiket t LEFT JOIN jadwal j ON t.id_jadwal = j.id_jadwal WHERE j.id_jadwal IS NULL");
            echo "<p style='color: green;'>✅ Tiket tidak valid berhasil dihapus</p>";
            
            // Coba lagi tambah foreign key
            try {
                $pdo->exec("ALTER TABLE tiket ADD CONSTRAINT tiket_jadwal_fk FOREIGN KEY (id_jadwal) REFERENCES jadwal(id_jadwal) ON DELETE CASCADE");
                echo "<p style='color: green;'>✅ Foreign key baru berhasil ditambahkan setelah cleanup</p>";
            } catch (PDOException $e) {
                echo "<p style='color: red;'>❌ Masih gagal menambahkan foreign key: " . $e->getMessage() . "</p>";
            }
        }
    }
    
    // 4. Verifikasi foreign key baru
    echo "<h2>4. ✅ Verifikasi Foreign Key Baru</h2>";
    $stmt = $pdo->query("
        SELECT 
            CONSTRAINT_NAME,
            TABLE_NAME,
            COLUMN_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE REFERENCED_TABLE_SCHEMA = '$dbname' 
        AND TABLE_NAME = 'tiket'
    ");
    $new_constraints = $stmt->fetchAll();
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Constraint</th><th>Table</th><th>Column</th><th>Referenced Table</th><th>Referenced Column</th></tr>";
    foreach ($new_constraints as $constraint) {
        echo "<tr>";
        echo "<td>{$constraint['CONSTRAINT_NAME']}</td>";
        echo "<td>{$constraint['TABLE_NAME']}</td>";
        echo "<td>{$constraint['COLUMN_NAME']}</td>";
        echo "<td style='" . ($constraint['REFERENCED_TABLE_NAME'] == 'jadwal' ? "color: green;" : "color: red;") . "'>{$constraint['REFERENCED_TABLE_NAME']}</td>";
        echo "<td>{$constraint['REFERENCED_COLUMN_NAME']}</td>";
        echo "</tr>";
    }
    echo "</table><br>";
    
    echo "<hr>";
    echo "<h2>🔗 Lanjutan</h2>";
    echo "<p><a href='fix_tiket_complete.php'>Jalankan Ulang Perbaikan Tiket</a></p>";
    echo "<p><a href='public/index.php'>Kembali ke Halaman Utama</a></p>";
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>❌ Database Error</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Code:</strong> " . $e->getCode() . "</p>";
}
?>
