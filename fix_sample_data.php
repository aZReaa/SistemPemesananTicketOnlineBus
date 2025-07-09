<?php
// File: fix_sample_data.php
// Script untuk memperbaiki dan menambahkan sample data yang konsisten

require_once 'config/database.php';

// Koneksi database
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "=== MEMPERBAIKI DATA SAMPLE ===\n\n";

try {
    // 1. Hapus data jadwal yang tidak valid
    echo "1. Menghapus data jadwal yang tidak valid...\n";
    $pdo->exec("DELETE FROM jadwal WHERE id_rute NOT IN (SELECT id_rute FROM rute)");
    
    // 2. Tambah jadwal baru dengan rute yang valid
    echo "2. Menambahkan jadwal sample yang valid...\n";
    
    $jadwalSample = [
        [
            'id_rute' => 1, // PO Litha (Makassar - Manado)
            'waktu_berangkat' => '2025-07-09 20:30:00',
            'waktu_tiba' => '2025-07-10 08:30:00',
            'harga' => 350000
        ],
        [
            'id_rute' => 2, // PO Bintang Prima (Makassar - Kendari)
            'waktu_berangkat' => '2025-07-09 22:00:00',
            'waktu_tiba' => '2025-07-10 06:00:00',
            'harga' => 200000
        ],
        [
            'id_rute' => 3, // PO Arma Jaya (Makassar - Palu)
            'waktu_berangkat' => '2025-07-10 07:00:00',
            'waktu_tiba' => '2025-07-10 15:00:00',
            'harga' => 250000
        ],
        [
            'id_rute' => 6, // PO Panorama (Makassar - Parepare)
            'waktu_berangkat' => '2025-07-09 14:00:00',
            'waktu_tiba' => '2025-07-09 17:00:00',
            'harga' => 75000
        ],
        [
            'id_rute' => 11, // PO Tana Toraja (Makassar - Rantepao)
            'waktu_berangkat' => '2025-07-09 19:00:00',
            'waktu_tiba' => '2025-07-10 05:00:00',
            'harga' => 180000
        ]
    ];
    
    // Insert jadwal sample
    $stmt = $pdo->prepare("INSERT INTO jadwal (id_rute, waktu_berangkat, waktu_tiba, harga) VALUES (?, ?, ?, ?)");
    
    foreach ($jadwalSample as $jadwal) {
        $stmt->execute([
            $jadwal['id_rute'],
            $jadwal['waktu_berangkat'],
            $jadwal['waktu_tiba'],
            $jadwal['harga']
        ]);
        echo "   - Jadwal ditambahkan untuk rute ID: {$jadwal['id_rute']}\n";
    }
    
    // 3. Ambil ID jadwal yang baru ditambahkan untuk membuat tiket
    echo "\n3. Membuat tiket untuk setiap jadwal...\n";
    
    $jadwalResult = $pdo->query("SELECT j.id_jadwal, r.kapasitas, r.nama_po, r.kota_asal, r.kota_tujuan 
                                 FROM jadwal j 
                                 JOIN rute r ON j.id_rute = r.id_rute 
                                 WHERE j.id_jadwal > 2")->fetchAll();
    
    $stmt = $pdo->prepare("INSERT INTO tiket (id_jadwal, nomor_kursi, status, kode_booking) VALUES (?, ?, ?, ?)");
    
    foreach ($jadwalResult as $jadwal) {
        echo "   - Membuat tiket untuk {$jadwal['nama_po']} ({$jadwal['kota_asal']} → {$jadwal['kota_tujuan']})\n";
        
        // Buat tiket sesuai kapasitas
        for ($i = 1; $i <= $jadwal['kapasitas']; $i++) {
            $nomorKursi = str_pad($i, 2, '0', STR_PAD_LEFT); // Format: 01, 02, 03, dst
            $status = 'available';
            $kodeBooking = null;
            
            // Simulasikan beberapa kursi sudah dibooking
            if ($i <= 3) {
                $status = 'booked';
                $kodeBooking = 'TRV' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            }
            
            $stmt->execute([
                $jadwal['id_jadwal'],
                $nomorKursi,
                $status,
                $kodeBooking
            ]);
        }
        echo "     → {$jadwal['kapasitas']} tiket dibuat\n";
    }
    
    // 4. Tampilkan ringkasan data
    echo "\n=== RINGKASAN DATA ===\n";
    
    $jumlahRute = $pdo->query("SELECT COUNT(*) FROM rute WHERE status = 'active'")->fetchColumn();
    $jumlahJadwal = $pdo->query("SELECT COUNT(*) FROM jadwal")->fetchColumn();
    $jumlahTiket = $pdo->query("SELECT COUNT(*) FROM tiket")->fetchColumn();
    $jumlahTiketTersedia = $pdo->query("SELECT COUNT(*) FROM tiket WHERE status = 'available'")->fetchColumn();
    $jumlahTiketDibooking = $pdo->query("SELECT COUNT(*) FROM tiket WHERE status = 'booked'")->fetchColumn();
    
    echo "Rute aktif: {$jumlahRute}\n";
    echo "Jadwal: {$jumlahJadwal}\n";
    echo "Total tiket: {$jumlahTiket}\n";
    echo "Tiket tersedia: {$jumlahTiketTersedia}\n";
    echo "Tiket dibooking: {$jumlahTiketDibooking}\n";
    
    echo "\n✅ Data sample berhasil diperbaiki dan ditambahkan!\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
