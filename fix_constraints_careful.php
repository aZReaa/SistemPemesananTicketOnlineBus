<?php
require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>🔧 Perbaikan Foreign Key dengan Hati-hati</h1>";
    
    // 1. Cek semua foreign key yang ada
    echo "<h2>1. 📋 Foreign Key yang Ada Saat Ini</h2>";
    $stmt = $pdo->query("
        SELECT 
            CONSTRAINT_NAME,
            TABLE_NAME,
            COLUMN_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
        WHERE CONSTRAINT_SCHEMA = '$dbname' 
        AND REFERENCED_TABLE_NAME IS NOT NULL
        AND TABLE_NAME = 'tiket'
        ORDER BY TABLE_NAME, CONSTRAINT_NAME
    ");
    $constraints = $stmt->fetchAll();
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Constraint</th><th>Table</th><th>Column</th><th>Referenced Table</th><th>Referenced Column</th></tr>";
    foreach ($constraints as $constraint) {
        echo "<tr>";
        echo "<td>{$constraint['CONSTRAINT_NAME']}</td>";
        echo "<td>{$constraint['TABLE_NAME']}</td>";
        echo "<td>{$constraint['COLUMN_NAME']}</td>";
        echo "<td>{$constraint['REFERENCED_TABLE_NAME']}</td>";
        echo "<td>{$constraint['REFERENCED_COLUMN_NAME']}</td>";
        echo "</tr>";
    }
    echo "</table><br>";
    
    // 2. Cek data yang tidak konsisten
    echo "<h2>2. 🔍 Cek Data Inconsistency</h2>";
    
    // Cek tiket yang mereferensi jadwal yang tidak ada
    $stmt = $pdo->query("
        SELECT t.id_tiket, t.id_jadwal, t.nomor_kursi
        FROM tiket t
        LEFT JOIN jadwal j ON t.id_jadwal = j.id_jadwal
        WHERE j.id_jadwal IS NULL
        LIMIT 10
    ");
    $orphaned_tikets = $stmt->fetchAll();
    
    if (count($orphaned_tikets) > 0) {
        echo "<p style='color: red;'><strong>❌ Ditemukan tiket yang mereferensi jadwal yang tidak ada:</strong></p>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>ID Tiket</th><th>ID Jadwal (Invalid)</th><th>Nomor Kursi</th></tr>";
        foreach ($orphaned_tikets as $tiket) {
            echo "<tr>";
            echo "<td>{$tiket['id_tiket']}</td>";
            echo "<td>{$tiket['id_jadwal']}</td>";
            echo "<td>{$tiket['nomor_kursi']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Hapus tiket yang orphaned
        echo "<p>Menghapus tiket yang orphaned...</p>";
        $stmt = $pdo->query("
            DELETE t FROM tiket t
            LEFT JOIN jadwal j ON t.id_jadwal = j.id_jadwal
            WHERE j.id_jadwal IS NULL
        ");
        $deleted = $stmt->rowCount();
        echo "<p style='color: green;'>✅ Berhasil menghapus {$deleted} tiket orphaned</p>";
    } else {
        echo "<p style='color: green;'>✅ Tidak ada tiket orphaned</p>";
    }
    
    // 3. Drop constraint yang bermasalah jika ada
    echo "<h2>3. 🔧 Memperbaiki Foreign Key</h2>";
    
    $problematic_constraints = [];
    foreach ($constraints as $constraint) {
        if ($constraint['REFERENCED_TABLE_NAME'] == 'jadwal_backup') {
            $problematic_constraints[] = $constraint['CONSTRAINT_NAME'];
        }
    }
    
    if (!empty($problematic_constraints)) {
        foreach ($problematic_constraints as $constraint_name) {
            try {
                $pdo->exec("ALTER TABLE tiket DROP FOREIGN KEY `{$constraint_name}`");
                echo "<p style='color: green;'>✅ Berhasil menghapus constraint: {$constraint_name}</p>";
            } catch (PDOException $e) {
                echo "<p style='color: orange;'>⚠️ Constraint {$constraint_name} sudah tidak ada atau tidak bisa dihapus</p>";
            }
        }
    } else {
        echo "<p style='color: blue;'>ℹ️ Tidak ada constraint bermasalah yang perlu dihapus</p>";
    }
    
    // 4. Pastikan constraint yang benar ada
    $correct_constraint_exists = false;
    foreach ($constraints as $constraint) {
        if ($constraint['COLUMN_NAME'] == 'id_jadwal' && $constraint['REFERENCED_TABLE_NAME'] == 'jadwal') {
            $correct_constraint_exists = true;
            echo "<p style='color: green;'>✅ Constraint yang benar sudah ada: {$constraint['CONSTRAINT_NAME']}</p>";
            break;
        }
    }
    
    if (!$correct_constraint_exists) {
        try {
            $pdo->exec("
                ALTER TABLE tiket 
                ADD CONSTRAINT tiket_jadwal_fk_new 
                FOREIGN KEY (id_jadwal) 
                REFERENCES jadwal(id_jadwal) 
                ON DELETE CASCADE
            ");
            echo "<p style='color: green;'>✅ Berhasil menambahkan constraint baru: tiket_jadwal_fk_new</p>";
        } catch (PDOException $e) {
            echo "<p style='color: red;'>❌ Gagal menambahkan constraint: " . $e->getMessage() . "</p>";
        }
    }
    
    // 5. Verifikasi final
    echo "<h2>4. ✅ Verifikasi Final</h2>";
    $stmt = $pdo->query("
        SELECT 
            CONSTRAINT_NAME,
            COLUMN_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
        WHERE CONSTRAINT_SCHEMA = '$dbname' 
        AND REFERENCED_TABLE_NAME IS NOT NULL
        AND TABLE_NAME = 'tiket'
        ORDER BY CONSTRAINT_NAME
    ");
    $final_constraints = $stmt->fetchAll();
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Constraint</th><th>Column</th><th>Referenced Table</th><th>Referenced Column</th></tr>";
    foreach ($final_constraints as $constraint) {
        $color = ($constraint['REFERENCED_TABLE_NAME'] == 'jadwal') ? 'green' : 'red';
        echo "<tr style='color: {$color};'>";
        echo "<td>{$constraint['CONSTRAINT_NAME']}</td>";
        echo "<td>{$constraint['COLUMN_NAME']}</td>";
        echo "<td>{$constraint['REFERENCED_TABLE_NAME']}</td>";
        echo "<td>{$constraint['REFERENCED_COLUMN_NAME']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<hr>";
    echo "<h2>🔗 Lanjutan</h2>";
    echo "<ul>";
    echo "<li><a href='fix_tiket_complete.php'><strong>Jalankan Perbaikan Tiket</strong></a></li>";
    echo "<li><a href='debug_tiket.php'>Debug Tiket Spesifik</a></li>";
    echo "<li><a href='public/index.php'>Kembali ke Halaman Utama</a></li>";
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>❌ Database Error</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Code:</strong> " . $e->getCode() . "</p>";
}
?>
