<?php
// File: create_default_users.php
// Deskripsi: Script untuk membuat user default (admin dan petugas loket)

require_once __DIR__ . '/config/database.php';

function createDefaultUsers($pdo) {
    try {
        // Mulai transaction
        $pdo->beginTransaction();
        
        // Data user default
        $defaultUsers = [
            [
                'nama' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@travel.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'no_telepon' => '081234567890',
                'alamat' => 'Jl. Admin No. 1'
            ],
            [
                'nama' => 'Petugas Loket 1',
                'username' => 'petugas1',
                'email' => 'petugas1@travel.com',
                'password' => password_hash('petugas123', PASSWORD_DEFAULT),
                'role' => 'petugas_loket',
                'no_telepon' => '081234567891',
                'alamat' => 'Jl. Loket No. 1'
            ],
            [
                'nama' => 'Petugas Loket 2',
                'username' => 'petugas2',
                'email' => 'petugas2@travel.com',
                'password' => password_hash('petugas123', PASSWORD_DEFAULT),
                'role' => 'petugas_loket',
                'no_telepon' => '081234567892',
                'alamat' => 'Jl. Loket No. 2'
            ]
        ];
        
        // Cek apakah user sudah ada
        $checkQuery = "SELECT COUNT(*) FROM user WHERE email = :email OR username = :username";
        $checkStmt = $pdo->prepare($checkQuery);
        
        // Query untuk insert user
        $insertQuery = "INSERT INTO user (nama, username, email, password, role, no_telepon, alamat, tanggal_daftar) 
                        VALUES (:nama, :username, :email, :password, :role, :no_telepon, :alamat, NOW())";
        $insertStmt = $pdo->prepare($insertQuery);
        
        $createdUsers = [];
        $skippedUsers = [];
        
        foreach ($defaultUsers as $user) {
            // Cek apakah user sudah ada
            $checkStmt->execute([
                ':email' => $user['email'],
                ':username' => $user['username']
            ]);
            
            if ($checkStmt->fetchColumn() > 0) {
                $skippedUsers[] = $user['username'] . ' (' . $user['email'] . ')';
                continue;
            }
            
            // Insert user baru
            $insertStmt->execute([
                ':nama' => $user['nama'],
                ':username' => $user['username'],
                ':email' => $user['email'],
                ':password' => $user['password'],
                ':role' => $user['role'],
                ':no_telepon' => $user['no_telepon'],
                ':alamat' => $user['alamat']
            ]);
            
            $createdUsers[] = $user['username'] . ' (' . $user['email'] . ')';
        }
        
        // Commit transaction
        $pdo->commit();
        
        return [
            'success' => true,
            'created' => $createdUsers,
            'skipped' => $skippedUsers
        ];
        
    } catch (PDOException $e) {
        // Rollback transaction
        $pdo->rollback();
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}

function createSampleData($pdo) {
    try {
        // Mulai transaction
        $pdo->beginTransaction();
        
        // Buat data bus sample
        $busData = [
            [
                'nama_bus' => 'Sinar Jaya Express',
                'nomor_bus' => 'SJ001',
                'kapasitas' => 40,
                'kelas' => 'Executive'
            ],
            [
                'nama_bus' => 'Harapan Jaya',
                'nomor_bus' => 'HJ002',
                'kapasitas' => 35,
                'kelas' => 'Bisnis'
            ],
            [
                'nama_bus' => 'Maju Bersama',
                'nomor_bus' => 'MB003',
                'kapasitas' => 45,
                'kelas' => 'Ekonomi'
            ]
        ];
        
        // Cek apakah bus sudah ada
        $checkBusQuery = "SELECT COUNT(*) FROM bus WHERE nomor_bus = :nomor_bus";
        $checkBusStmt = $pdo->prepare($checkBusQuery);
        
        // Query untuk insert bus
        $insertBusQuery = "INSERT INTO bus (nama_bus, nomor_bus, kapasitas, kelas) 
                          VALUES (:nama_bus, :nomor_bus, :kapasitas, :kelas)";
        $insertBusStmt = $pdo->prepare($insertBusQuery);
        
        $createdBuses = [];
        
        foreach ($busData as $bus) {
            // Cek apakah bus sudah ada
            $checkBusStmt->execute([':nomor_bus' => $bus['nomor_bus']]);
            
            if ($checkBusStmt->fetchColumn() > 0) {
                continue; // Skip jika sudah ada
            }
            
            // Insert bus baru
            $insertBusStmt->execute($bus);
            $createdBuses[] = $bus['nama_bus'] . ' (' . $bus['nomor_bus'] . ')';
        }
        
        // Buat data jadwal sample
        $jadwalData = [
            [
                'id_bus' => 1,
                'asal' => 'Jakarta',
                'tujuan' => 'Bandung',
                'tanggal_berangkat' => date('Y-m-d', strtotime('+1 day')),
                'jam_berangkat' => '08:00:00',
                'jam_tiba' => '11:00:00',
                'harga' => 150000
            ],
            [
                'id_bus' => 2,
                'asal' => 'Bandung',
                'tujuan' => 'Yogyakarta',
                'tanggal_berangkat' => date('Y-m-d', strtotime('+1 day')),
                'jam_berangkat' => '14:00:00',
                'jam_tiba' => '20:00:00',
                'harga' => 200000
            ],
            [
                'id_bus' => 3,
                'asal' => 'Yogyakarta',
                'tujuan' => 'Surabaya',
                'tanggal_berangkat' => date('Y-m-d', strtotime('+2 days')),
                'jam_berangkat' => '06:00:00',
                'jam_tiba' => '12:00:00',
                'harga' => 180000
            ]
        ];
        
        // Query untuk insert jadwal
        $insertJadwalQuery = "INSERT INTO jadwal (id_bus, asal, tujuan, tanggal_berangkat, jam_berangkat, jam_tiba, harga) 
                             VALUES (:id_bus, :asal, :tujuan, :tanggal_berangkat, :jam_berangkat, :jam_tiba, :harga)";
        $insertJadwalStmt = $pdo->prepare($insertJadwalQuery);
        
        $createdJadwal = [];
        
        foreach ($jadwalData as $jadwal) {
            $insertJadwalStmt->execute($jadwal);
            $createdJadwal[] = $jadwal['asal'] . ' → ' . $jadwal['tujuan'] . ' (' . $jadwal['tanggal_berangkat'] . ')';
        }
        
        // Commit transaction
        $pdo->commit();
        
        return [
            'success' => true,
            'buses' => $createdBuses,
            'jadwal' => $createdJadwal
        ];
        
    } catch (PDOException $e) {
        // Rollback transaction
        $pdo->rollback();
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}

// Jalankan script
echo "=== CREATING DEFAULT USERS ===\n";
$userResult = createDefaultUsers($pdo);

if ($userResult['success']) {
    echo "✅ SUCCESS!\n";
    
    if (!empty($userResult['created'])) {
        echo "\n📝 Created Users:\n";
        foreach ($userResult['created'] as $user) {
            echo "   • " . $user . "\n";
        }
    }
    
    if (!empty($userResult['skipped'])) {
        echo "\n⏭️ Skipped Users (already exist):\n";
        foreach ($userResult['skipped'] as $user) {
            echo "   • " . $user . "\n";
        }
    }
} else {
    echo "❌ ERROR: " . $userResult['error'] . "\n";
}

echo "\n=== CREATING SAMPLE DATA ===\n";
$sampleResult = createSampleData($pdo);

if ($sampleResult['success']) {
    echo "✅ SUCCESS!\n";
    
    if (!empty($sampleResult['buses'])) {
        echo "\n🚌 Created Buses:\n";
        foreach ($sampleResult['buses'] as $bus) {
            echo "   • " . $bus . "\n";
        }
    }
    
    if (!empty($sampleResult['jadwal'])) {
        echo "\n📅 Created Jadwal:\n";
        foreach ($sampleResult['jadwal'] as $jadwal) {
            echo "   • " . $jadwal . "\n";
        }
    }
} else {
    echo "❌ ERROR: " . $sampleResult['error'] . "\n";
}

echo "\n=== DEFAULT LOGIN CREDENTIALS ===\n";
echo "👤 ADMIN:\n";
echo "   Email: admin@travel.com\n";
echo "   Username: admin\n";
echo "   Password: admin123\n";
echo "\n👤 PETUGAS LOKET 1:\n";
echo "   Email: petugas1@travel.com\n";
echo "   Username: petugas1\n";
echo "   Password: petugas123\n";
echo "\n👤 PETUGAS LOKET 2:\n";
echo "   Email: petugas2@travel.com\n";
echo "   Username: petugas2\n";
echo "   Password: petugas123\n";

echo "\n=== DONE ===\n";
echo "🎉 Script selesai! Silakan login dengan kredensial di atas.\n";
?>
