<?php
require_once 'config/database.php';

echo "=== DEBUG USER DATA ===\n\n";

try {
    // Cek tabel users
    echo "1. Struktur tabel users:\n";
    $stmt = $pdo->query("DESCRIBE users");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- {$row['Field']} ({$row['Type']})\n";
    }
    echo "\n";
    
    // Cek data pada tabel users
    echo "2. Data tabel users:\n";
    $stmt = $pdo->query("SELECT * FROM users LIMIT 5");
    $count = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $count++;
        $id = $row['id'] ?? 'N/A';
        $username = $row['username'] ?? $row['nama'] ?? 'N/A';
        $role = $row['role'] ?? 'N/A';
        echo "- User #{$count}: ID={$id}, Username={$username}, Role={$role}\n";
    }
    if ($count == 0) {
        echo "Tidak ada data user\n";
    }
    echo "\n";
    
    // Cek query data pelanggan
    echo "3. Test query data pelanggan:\n";
    $query = "SELECT u.username, u.email, u.role, u.no_telp,
               t.kode_booking, t.status,
               p.*,
               j.waktu_berangkat, j.waktu_tiba, j.harga,
               r.nama_rute, r.nama_po, r.kelas, r.kota_asal, r.kota_tujuan, r.kapasitas
        FROM tiket t
        JOIN pemesanan p ON t.id_pemesanan = p.id_pemesanan
        JOIN jadwal j ON t.id_jadwal = j.id_jadwal
        JOIN rute r ON j.id_rute = r.id_rute
        JOIN users u ON p.id_pelanggan = u.id_user
        WHERE p.status = 'paid'";
    
    $stmt = $pdo->query($query);
    $count = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $count++;
        $username = $row['username'] ?? 'N/A';
        $email = $row['email'] ?? 'N/A';
        $kode_booking = $row['kode_booking'] ?? 'N/A';
        echo "- Record #{$count}: Username={$username}, Email={$email}, Booking={$kode_booking}\n";
    }
    if ($count == 0) {
        echo "Query tidak mengembalikan data apa pun.\n\n";
        
        // Cek setiap tabel secara terpisah untuk memahami struktur data
        echo "4. Cek data di setiap tabel:\n";
        
        echo "4.1 Tabel pemesanan (5 baris pertama):\n";
        $stmt = $pdo->query("SELECT * FROM pemesanan LIMIT 5");
        $count = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $count++;
            $id = $row['id_pemesanan'] ?? 'N/A';
            $id_pelanggan = $row['id_pelanggan'] ?? 'N/A';
            $status = $row['status'] ?? 'N/A';
            echo "- Pemesanan #{$count}: ID={$id}, ID_Pelanggan={$id_pelanggan}, Status={$status}\n";
        }
        if ($count == 0) {
            echo "Tidak ada data pemesanan\n";
        }
        echo "\n";
        
        echo "4.2 Tabel tiket (5 baris pertama):\n";
        $stmt = $pdo->query("SELECT * FROM tiket LIMIT 5");
        $count = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $count++;
            $id = $row['id_tiket'] ?? 'N/A';
            $id_pemesanan = $row['id_pemesanan'] ?? 'N/A';
            $kode_booking = $row['kode_booking'] ?? 'N/A';
            $status = $row['status'] ?? 'N/A';
            echo "- Tiket #{$count}: ID={$id}, ID_Pemesanan={$id_pemesanan}, Kode={$kode_booking}, Status={$status}\n";
        }
        if ($count == 0) {
            echo "Tidak ada data tiket\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
