<?php
// Test database connection and check existing tickets
require_once 'config/database.php';

try {
    echo "<h3>Test Database Connection dan Data Tiket</h3>";
    
    // Test basic connection
    echo "<p><strong>Status Koneksi:</strong> ✓ Berhasil terhubung ke database</p>";
    
    // Check if there are any tickets
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM tiket");
    $totalTickets = $stmt->fetch()['total'];
    echo "<p><strong>Total Tiket dalam Database:</strong> $totalTickets</p>";
    
    if ($totalTickets > 0) {
        // Show sample tickets with booking codes
        $stmt = $pdo->query("SELECT kode_booking, status_tiket FROM tiket LIMIT 5");
        echo "<p><strong>Sample Kode Booking (5 teratas):</strong></p>";
        echo "<ul>";
        while ($row = $stmt->fetch()) {
            echo "<li>{$row['kode_booking']} (Status: {$row['status_tiket']})</li>";
        }
        echo "</ul>";
    }
    
    // Test the updated query structure
    echo "<h4>Test Query Struktur Baru:</h4>";
    $testQuery = "SELECT t.*, p.*, j.waktu_berangkat, j.waktu_tiba, j.harga,
                         r.kota_asal, r.kota_tujuan, r.nama_rute, r.nama_po, r.kelas
                  FROM tiket t
                  JOIN pemesanan p ON t.id_pemesanan = p.id_pemesanan
                  JOIN jadwal j ON p.id_jadwal = j.id_jadwal
                  JOIN rute r ON j.id_rute = r.id_rute
                  LIMIT 1";
    
    $stmt = $pdo->query($testQuery);
    if ($stmt->rowCount() > 0) {
        echo "<p>✓ Query struktur baru berfungsi dengan baik</p>";
        $sample = $stmt->fetch();
        echo "<p><strong>Sample data:</strong></p>";
        echo "<ul>";
        echo "<li>Kode Booking: {$sample['kode_booking']}</li>";
        echo "<li>Rute: {$sample['kota_asal']} → {$sample['kota_tujuan']}</li>";
        echo "<li>PO: {$sample['nama_po']} ({$sample['kelas']})</li>";
        echo "<li>Waktu: " . date('d M Y H:i', strtotime($sample['waktu_berangkat'])) . "</li>";
        echo "</ul>";
    } else {
        echo "<p>⚠ Tidak ada data lengkap untuk testing</p>";
    }
    
} catch (Exception $e) {
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
}
?>
