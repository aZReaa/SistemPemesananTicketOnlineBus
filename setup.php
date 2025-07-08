<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Default Users - Travel Bus System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .success {
            color: #28a745;
            background: #d4edda;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .error {
            color: #dc3545;
            background: #f8d7da;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .info {
            color: #0c5460;
            background: #d1ecf1;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .credentials {
            background: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .btn {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background: #0056b3;
        }
        ul {
            padding-left: 20px;
        }
        li {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚌 Create Default Users - Travel Bus System</h1>
        
        <?php
        if (isset($_GET['run'])) {
            require_once __DIR__ . '/config/database.php';
            
            function createDefaultUsers($pdo) {
                try {
                    // Mulai transaction
                    $pdo->beginTransaction();
                    
                    // Data user default
                    $defaultUsers = [
                        [
                            'username' => 'admin',
                            'email' => 'admin@travel.com',
                            'password' => password_hash('admin123', PASSWORD_DEFAULT),
                            'role' => 'admin',
                            'no_telepon' => '081234567890'
                        ],
                        [
                            'username' => 'petugas1',
                            'email' => 'petugas1@travel.com',
                            'password' => password_hash('petugas123', PASSWORD_DEFAULT),
                            'role' => 'petugas_loket',
                            'no_telepon' => '081234567891'
                        ],
                        [
                            'username' => 'petugas2',
                            'email' => 'petugas2@travel.com',
                            'password' => password_hash('petugas123', PASSWORD_DEFAULT),
                            'role' => 'petugas_loket',
                            'no_telepon' => '081234567892'
                        ]
                    ];
                    
                    // Cek apakah user sudah ada
                    $checkQuery = "SELECT COUNT(*) FROM users WHERE email = :email OR username = :username";
                    $checkStmt = $pdo->prepare($checkQuery);
                    
                    // Query untuk insert user
                    $insertQuery = "INSERT INTO users (username, password, email, no_telp, role, created_at) 
                                    VALUES (:username, :password, :email, :no_telp, :role, NOW())";
                    $insertStmt = $pdo->prepare($insertQuery);
                    
                    // Query untuk insert pelanggan details (untuk role pelanggan)
                    $insertPelangganQuery = "INSERT INTO pelanggan_details (id_user, alamat) VALUES (:id_user, :alamat)";
                    $insertPelangganStmt = $pdo->prepare($insertPelangganQuery);
                    
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
                            ':username' => $user['username'],
                            ':password' => $user['password'],
                            ':email' => $user['email'],
                            ':no_telp' => $user['no_telepon'],
                            ':role' => $user['role']
                        ]);
                        
                        // Jika role adalah pelanggan, tambahkan detail pelanggan
                        if ($user['role'] === 'pelanggan') {
                            $userId = $pdo->lastInsertId();
                            $insertPelangganStmt->execute([
                                ':id_user' => $userId,
                                ':alamat' => $user['alamat']
                            ]);
                        }
                        
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
                            'nama_po' => 'Sinar Jaya Express',
                            'kelas' => 'Executive',
                            'kapasitas' => 40
                        ],
                        [
                            'nama_po' => 'Harapan Jaya',
                            'kelas' => 'Bisnis',
                            'kapasitas' => 35
                        ],
                        [
                            'nama_po' => 'Maju Bersama',
                            'kelas' => 'Ekonomi',
                            'kapasitas' => 45
                        ]
                    ];
                    
                    // Cek apakah bus sudah ada
                    $checkBusQuery = "SELECT COUNT(*) FROM bus WHERE nama_po = :nama_po";
                    $checkBusStmt = $pdo->prepare($checkBusQuery);
                    
                    // Query untuk insert bus
                    $insertBusQuery = "INSERT INTO bus (nama_po, kelas, kapasitas) 
                                      VALUES (:nama_po, :kelas, :kapasitas)";
                    $insertBusStmt = $pdo->prepare($insertBusQuery);
                    
                    $createdBuses = [];
                    
                    foreach ($busData as $bus) {
                        // Cek apakah bus sudah ada
                        $checkBusStmt->execute([':nama_po' => $bus['nama_po']]);
                        
                        if ($checkBusStmt->fetchColumn() > 0) {
                            continue; // Skip jika sudah ada
                        }
                        
                        // Insert bus baru
                        $insertBusStmt->execute($bus);
                        $createdBuses[] = $bus['nama_po'] . ' (' . $bus['kelas'] . ')';
                    }
                    
                    // Buat data jadwal sample
                    $jadwalData = [
                        [
                            'id_bus' => 1,
                            'waktu_berangkat' => date('Y-m-d', strtotime('+1 day')) . ' 08:00:00',
                            'waktu_tiba' => date('Y-m-d', strtotime('+1 day')) . ' 11:00:00',
                            'harga' => 150000
                        ],
                        [
                            'id_bus' => 2,
                            'waktu_berangkat' => date('Y-m-d', strtotime('+1 day')) . ' 14:00:00',
                            'waktu_tiba' => date('Y-m-d', strtotime('+1 day')) . ' 20:00:00',
                            'harga' => 200000
                        ],
                        [
                            'id_bus' => 3,
                            'waktu_berangkat' => date('Y-m-d', strtotime('+2 days')) . ' 06:00:00',
                            'waktu_tiba' => date('Y-m-d', strtotime('+2 days')) . ' 12:00:00',
                            'harga' => 180000
                        ]
                    ];
                    
                    // Query untuk insert jadwal
                    $insertJadwalQuery = "INSERT INTO jadwal (id_bus, waktu_berangkat, waktu_tiba, harga) 
                                         VALUES (:id_bus, :waktu_berangkat, :waktu_tiba, :harga)";
                    $insertJadwalStmt = $pdo->prepare($insertJadwalQuery);
                    
                    $createdJadwal = [];
                    
                    foreach ($jadwalData as $jadwal) {
                        try {
                            $insertJadwalStmt->execute($jadwal);
                            $createdJadwal[] = 'Bus ID ' . $jadwal['id_bus'] . ' (' . $jadwal['waktu_berangkat'] . ')';
                        } catch (PDOException $e) {
                            // Skip jika bus ID tidak ada atau jadwal sudah ada
                            continue;
                        }
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
            echo "<h2>📝 Creating Default Users</h2>";
            $userResult = createDefaultUsers($pdo);
            
            if ($userResult['success']) {
                echo "<div class='success'>✅ User creation successful!</div>";
                
                if (!empty($userResult['created'])) {
                    echo "<div class='info'><strong>Created Users:</strong><ul>";
                    foreach ($userResult['created'] as $user) {
                        echo "<li>" . htmlspecialchars($user) . "</li>";
                    }
                    echo "</ul></div>";
                }
                
                if (!empty($userResult['skipped'])) {
                    echo "<div class='info'><strong>Skipped Users (already exist):</strong><ul>";
                    foreach ($userResult['skipped'] as $user) {
                        echo "<li>" . htmlspecialchars($user) . "</li>";
                    }
                    echo "</ul></div>";
                }
            } else {
                echo "<div class='error'>❌ ERROR: " . htmlspecialchars($userResult['error']) . "</div>";
            }
            
            echo "<h2>🚌 Creating Sample Data</h2>";
            $sampleResult = createSampleData($pdo);
            
            if ($sampleResult['success']) {
                echo "<div class='success'>✅ Sample data creation successful!</div>";
                
                if (!empty($sampleResult['buses'])) {
                    echo "<div class='info'><strong>Created Buses:</strong><ul>";
                    foreach ($sampleResult['buses'] as $bus) {
                        echo "<li>" . htmlspecialchars($bus) . "</li>";
                    }
                    echo "</ul></div>";
                }
                
                if (!empty($sampleResult['jadwal'])) {
                    echo "<div class='info'><strong>Created Schedules:</strong><ul>";
                    foreach ($sampleResult['jadwal'] as $jadwal) {
                        echo "<li>" . htmlspecialchars($jadwal) . "</li>";
                    }
                    echo "</ul></div>";
                }
            } else {
                echo "<div class='error'>❌ ERROR: " . htmlspecialchars($sampleResult['error']) . "</div>";
            }
        ?>
        
        <div class="credentials">
            <h3>🔐 Default Login Credentials</h3>
            <p><strong>ADMIN:</strong><br>
               Email: admin@travel.com<br>
               Username: admin<br>
               Password: admin123</p>
            
            <p><strong>PETUGAS LOKET 1:</strong><br>
               Email: petugas1@travel.com<br>
               Username: petugas1<br>
               Password: petugas123</p>
            
            <p><strong>PETUGAS LOKET 2:</strong><br>
               Email: petugas2@travel.com<br>
               Username: petugas2<br>
               Password: petugas123</p>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="public/index.php" class="btn">🏠 Go to Main Site</a>
            <a href="public/index.php?page=login" class="btn">🔐 Login</a>
        </div>
        
        <?php } else { ?>
            <div class="info">
                <p>Script ini akan membuat user default untuk sistem:</p>
                <ul>
                    <li><strong>1 Admin</strong> dengan username: admin</li>
                    <li><strong>2 Petugas Loket</strong> dengan username: petugas1 dan petugas2</li>
                    <li><strong>Data sample</strong> berupa bus dan jadwal</li>
                </ul>
                <p>Klik tombol di bawah untuk menjalankan:</p>
            </div>
            
            <div style="text-align: center;">
                <a href="?run=1" class="btn">🚀 Create Default Users & Sample Data</a>
            </div>
        <?php } ?>
    </div>
</body>
</html>
