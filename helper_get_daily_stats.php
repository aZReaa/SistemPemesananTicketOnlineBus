<?php

/**
 * Temporary helper untuk membuat modifikasi metode getDailyStats di PetugasController
 */

$getDailyStatsMethod = <<<'EOD'
    /**
     * Get statistik harian
     */
    private function getDailyStats($tanggal) {
        try {
            // Query disesuaikan dengan struktur tabel yang benar
            $query = "SELECT 
                        COUNT(DISTINCT t.id_tiket) as total_tiket_verified,
                        COUNT(DISTINCT CASE WHEN t.status = 'used' THEN t.id_tiket END) as tiket_digunakan,
                        (SELECT COUNT(DISTINCT u.id) FROM users u WHERE u.role = 'pelanggan') as total_pelanggan
                      FROM tiket t
                      LEFT JOIN pemesanan p ON t.id_pemesanan = p.id_pemesanan
                      LEFT JOIN jadwal j ON t.id_jadwal = j.id_jadwal
                      WHERE DATE(j.waktu_berangkat) = :tanggal";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':tanggal', $tanggal);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                // Jika query gagal, berikan nilai default
                return [
                    'total_tiket_verified' => 0,
                    'tiket_digunakan' => 0,
                    'total_pelanggan' => 0
                ];
            }
            
            return $result;
        } catch (PDOException $e) {
            return [
                'total_tiket_verified' => 0,
                'tiket_digunakan' => 0,
                'total_pelanggan' => 0
            ];
        }
    }
EOD;

echo "Copy dan paste fungsi berikut ke controllers/PetugasController.php (ganti fungsi getDailyStats yang ada):\n\n";
echo $getDailyStatsMethod;

?>
