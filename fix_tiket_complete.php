<?php
require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>🔧 Perbaikan Komprehensif Data Tiket</h1>";
    
    // 1. Cek struktur tabel tiket
    echo "<h2>1. 📋 Struktur Tabel Tiket</h2>";
    $stmt = $pdo->query("DESCRIBE tiket");
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
    echo "</table><br>";
    
    // 2. Cek semua jadwal yang valid dan tiketnya
    echo "<h2>2. 📅 Analisis Jadwal dan Tiket</h2>";
    $stmt = $pdo->query("
        SELECT j.id_jadwal, j.waktu_berangkat, j.harga, 
               r.nama_po, r.kota_asal, r.kota_tujuan, r.kapasitas,
               COUNT(t.id_tiket) as jumlah_tiket
        FROM jadwal j 
        LEFT JOIN rute r ON j.id_rute = r.id_rute 
        LEFT JOIN tiket t ON j.id_jadwal = t.id_jadwal
        WHERE r.id_rute IS NOT NULL
        GROUP BY j.id_jadwal
        ORDER BY j.id_jadwal
    ");
    $jadwals = $stmt->fetchAll();
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID Jadwal</th><th>Rute</th><th>PO</th><th>Kapasitas</th><th>Tiket Tersedia</th><th>Status</th><th>Aksi</th></tr>";
    
    $jadwal_tanpa_tiket = [];
    
    foreach ($jadwals as $jadwal) {
        echo "<tr>";
        echo "<td>{$jadwal['id_jadwal']}</td>";
        echo "<td>{$jadwal['kota_asal']} → {$jadwal['kota_tujuan']}</td>";
        echo "<td>{$jadwal['nama_po']}</td>";
        echo "<td>{$jadwal['kapasitas']}</td>";
        echo "<td>{$jadwal['jumlah_tiket']}</td>";
        
        if ($jadwal['jumlah_tiket'] == 0) {
            echo "<td style='color: red;'><strong>❌ TIDAK ADA TIKET</strong></td>";
            echo "<td>PERLU DIPERBAIKI</td>";
            $jadwal_tanpa_tiket[] = $jadwal;
        } else if ($jadwal['jumlah_tiket'] != $jadwal['kapasitas']) {
            echo "<td style='color: orange;'><strong>⚠️ TIKET TIDAK LENGKAP</strong></td>";
            echo "<td>PERLU DIPERBAIKI</td>";
            $jadwal_tanpa_tiket[] = $jadwal;
        } else {
            echo "<td style='color: green;'><strong>✅ LENGKAP</strong></td>";
            echo "<td>OK</td>";
        }
        echo "</tr>";
    }
    echo "</table><br>";
    
    // 3. Buat pemesanan dummy jika belum ada
    echo "<h2>3. 🔧 Membuat Pemesanan Dummy</h2>";
    
    // Cek struktur tabel pemesanan terlebih dahulu
    echo "<h3>📋 Struktur Tabel Pemesanan</h3>";
    $stmt = $pdo->query("DESCRIBE pemesanan");
    $pemesanan_columns = $stmt->fetchAll();
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($pemesanan_columns as $col) {
        echo "<tr>";
        echo "<td>{$col['Field']}</td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Key']}</td>";
        echo "<td>" . ($col['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table><br>";
    
    // Cek apakah ada pemesanan dummy berdasarkan nama
    $stmt = $pdo->query("SELECT id_pemesanan FROM pemesanan WHERE status = 'dummy' OR total_harga = 0 LIMIT 1");
    $dummy_pemesanan = $stmt->fetch();
    
    if (!$dummy_pemesanan) {
        echo "<p>Membuat pemesanan dummy untuk tiket yang belum dipesan...</p>";
        
        // Dapatkan kolom yang ada di tabel pemesanan
        $kolom_pemesanan = array_column($pemesanan_columns, 'Field');
        
        // Buat query INSERT berdasarkan kolom yang ada
        if (in_array('id_pelanggan', $kolom_pemesanan)) {
            $stmt = $pdo->prepare("
                INSERT INTO pemesanan (id_pelanggan, total_harga, status, tgl_pesan) 
                VALUES (1, 0, 'dummy', NOW())
            ");
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO pemesanan (total_harga, status, tgl_pesan) 
                VALUES (0, 'dummy', NOW())
            ");
        }
        
        try {
            $stmt->execute();
            $dummy_id = $pdo->lastInsertId();
            echo "<p style='color: green;'>✅ Pemesanan dummy dibuat dengan ID: {$dummy_id}</p>";
        } catch (PDOException $e) {
            // Jika gagal, coba cara lain
            echo "<p style='color: orange;'>⚠️ Gagal membuat pemesanan dummy, mencoba alternatif...</p>";
            echo "<p>Error: " . $e->getMessage() . "</p>";
            
            // Gunakan ID pemesanan yang sudah ada atau buat manual
            $stmt = $pdo->query("SELECT id_pemesanan FROM pemesanan ORDER BY id_pemesanan LIMIT 1");
            $existing = $stmt->fetch();
            
            if ($existing) {
                $dummy_id = $existing['id_pemesanan'];
                echo "<p style='color: blue;'>ℹ️ Menggunakan pemesanan yang sudah ada: ID {$dummy_id}</p>";
            } else {
                // Insert dengan nilai minimal
                $stmt = $pdo->prepare("INSERT INTO pemesanan (total_harga) VALUES (0)");
                $stmt->execute();
                $dummy_id = $pdo->lastInsertId();
                echo "<p style='color: green;'>✅ Pemesanan minimal dibuat dengan ID: {$dummy_id}</p>";
            }
        }
    } else {
        $dummy_id = $dummy_pemesanan['id_pemesanan'];
        echo "<p style='color: blue;'>ℹ️ Menggunakan pemesanan dummy yang sudah ada: ID {$dummy_id}</p>";
    }
    
    // 4. Perbaiki jadwal yang tidak memiliki tiket lengkap
    if (!empty($jadwal_tanpa_tiket)) {
        echo "<h2>4. 🎫 Membuat/Melengkapi Tiket</h2>";
        
        foreach ($jadwal_tanpa_tiket as $jadwal) {
            echo "<h3>Jadwal ID: {$jadwal['id_jadwal']} ({$jadwal['kota_asal']} → {$jadwal['kota_tujuan']})</h3>";
            
            // Hapus tiket yang ada (kalau ada tapi tidak lengkap)
            $stmt = $pdo->prepare("DELETE FROM tiket WHERE id_jadwal = ?");
            $stmt->execute([$jadwal['id_jadwal']]);
            
            // Buat tiket baru untuk semua kursi
            $berhasil = 0;
            for ($i = 1; $i <= $jadwal['kapasitas']; $i++) {
                $nomor_kursi = str_pad($i, 2, '0', STR_PAD_LEFT);
                $kode_booking = 'SEAT-' . $jadwal['id_jadwal'] . '-' . $nomor_kursi;
                
                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO tiket (id_jadwal, nomor_kursi, status, id_pemesanan, kode_booking) 
                        VALUES (?, ?, 'available', ?, ?)
                    ");
                    $stmt->execute([$jadwal['id_jadwal'], $nomor_kursi, $dummy_id, $kode_booking]);
                    $berhasil++;
                } catch (PDOException $e) {
                    echo "<p style='color: red;'>❌ Error kursi {$nomor_kursi}: " . $e->getMessage() . "</p>";
                }
            }
            
            echo "<p style='color: green;'>✅ Berhasil membuat {$berhasil} tiket dari {$jadwal['kapasitas']} kursi</p>";
        }
        
        echo "<hr>";
        echo "<h2>5. ✅ Verifikasi Hasil Perbaikan</h2>";
        
        // Verifikasi ulang
        $stmt = $pdo->query("
            SELECT j.id_jadwal, 
                   r.kota_asal, r.kota_tujuan, r.kapasitas,
                   COUNT(t.id_tiket) as jumlah_tiket,
                   COUNT(CASE WHEN t.status = 'available' THEN 1 END) as tiket_available
            FROM jadwal j 
            LEFT JOIN rute r ON j.id_rute = r.id_rute 
            LEFT JOIN tiket t ON j.id_jadwal = t.id_jadwal
            WHERE r.id_rute IS NOT NULL
            GROUP BY j.id_jadwal
            ORDER BY j.id_jadwal
        ");
        $verifikasi = $stmt->fetchAll();
        
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>ID Jadwal</th><th>Rute</th><th>Kapasitas</th><th>Total Tiket</th><th>Tiket Available</th><th>Status</th></tr>";
        
        foreach ($verifikasi as $v) {
            echo "<tr>";
            echo "<td>{$v['id_jadwal']}</td>";
            echo "<td>{$v['kota_asal']} → {$v['kota_tujuan']}</td>";
            echo "<td>{$v['kapasitas']}</td>";
            echo "<td>{$v['jumlah_tiket']}</td>";
            echo "<td>{$v['tiket_available']}</td>";
            
            if ($v['jumlah_tiket'] == $v['kapasitas'] && $v['tiket_available'] > 0) {
                echo "<td style='color: green;'><strong>✅ SEMPURNA</strong></td>";
            } else {
                echo "<td style='color: red;'><strong>❌ MASIH BERMASALAH</strong></td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        
    } else {
        echo "<h2>4. ✅ Semua Jadwal Sudah Memiliki Tiket Lengkap</h2>";
        echo "<p style='color: green;'>Tidak ada perbaikan yang diperlukan!</p>";
    }
    
    echo "<hr>";
    echo "<h2>🔗 Link Test</h2>";
    echo "<ul>";
    echo "<li><a href='public/index.php'>Kembali ke Halaman Utama</a></li>";
    echo "<li><a href='public/index.php?page=admin&tab=jadwal'>Admin - Kelola Jadwal</a></li>";
    echo "<li><a href='debug_tiket.php'>Debug Tiket Spesifik</a></li>";
    foreach ($verifikasi ?? $jadwals as $j) {
        echo "<li><a href='public/index.php?page=select_seat&id_jadwal={$j['id_jadwal']}'>Test Booking Jadwal {$j['id_jadwal']}</a></li>";
    }
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>❌ Database Error</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Code:</strong> " . $e->getCode() . "</p>";
}
?>
