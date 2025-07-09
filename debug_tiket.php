<?php
require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🔍 Debug Data Tiket untuk Jadwal ID: 4</h2>";
    
    // Cek jadwal ID 4
    $stmt = $pdo->prepare("SELECT j.*, r.nama_po, r.kota_asal, r.kota_tujuan, r.kapasitas 
                           FROM jadwal j 
                           LEFT JOIN rute r ON j.id_rute = r.id_rute 
                           WHERE j.id_jadwal = ?");
    $stmt->execute([4]);
    $jadwal = $stmt->fetch();
    
    if ($jadwal) {
        echo "<h3>📅 Data Jadwal:</h3>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Rute</th><th>PO</th><th>Waktu</th><th>Harga</th><th>Kapasitas</th></tr>";
        echo "<tr>";
        echo "<td>{$jadwal['id_jadwal']}</td>";
        echo "<td>{$jadwal['kota_asal']} → {$jadwal['kota_tujuan']}</td>";
        echo "<td>{$jadwal['nama_po']}</td>";
        echo "<td>{$jadwal['waktu_berangkat']}</td>";
        echo "<td>Rp " . number_format($jadwal['harga'], 0, ',', '.') . "</td>";
        echo "<td>{$jadwal['kapasitas']}</td>";
        echo "</tr></table>";
        
        // Cek tiket untuk jadwal ini
        echo "<h3>🎫 Data Tiket:</h3>";
        $stmt = $pdo->prepare("SELECT * FROM tiket WHERE id_jadwal = ? ORDER BY nomor_kursi");
        $stmt->execute([4]);
        $tikets = $stmt->fetchAll();
        
        if (count($tikets) > 0) {
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>ID Tiket</th><th>No Kursi</th><th>Status</th><th>Kode Booking</th><th>ID Pemesanan</th></tr>";
            foreach ($tikets as $tiket) {
                echo "<tr>";
                echo "<td>{$tiket['id_tiket']}</td>";
                echo "<td>{$tiket['nomor_kursi']}</td>";
                echo "<td>{$tiket['status']}</td>";
                echo "<td>" . ($tiket['kode_booking'] ?? '-') . "</td>";
                echo "<td>" . ($tiket['id_pemesanan'] ?? '-') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: red;'><strong>❌ TIDAK ADA TIKET untuk jadwal ID: 4</strong></p>";
            echo "<p>Ini adalah penyebab error! Sistem tidak dapat menemukan kursi untuk dipilih.</p>";
            
            // Buat tiket untuk jadwal ini
            echo "<h3>🔧 Membuat Tiket:</h3>";
            for ($i = 1; $i <= $jadwal['kapasitas']; $i++) {
                $nomor_kursi = str_pad($i, 2, '0', STR_PAD_LEFT);
                $status = 'available';
                
                $stmt = $pdo->prepare("INSERT INTO tiket (id_jadwal, nomor_kursi, status, id_pemesanan) VALUES (?, ?, ?, NULL)");
                $stmt->execute([4, $nomor_kursi, $status]);
            }
            echo "<p style='color: green;'>✅ Berhasil membuat {$jadwal['kapasitas']} tiket untuk jadwal ID: 4</p>";
            echo "<p><a href='debug_tiket.php'>Refresh halaman</a> | <a href='public/index.php?page=select_seat&id_jadwal=4'>Coba booking lagi</a></p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Jadwal dengan ID: 4 tidak ditemukan!</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database Error: " . $e->getMessage() . "</p>";
}
?>
