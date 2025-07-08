<?php

// File: models/Laporan.php
// Deskripsi: Kelas untuk entitas Laporan.

class Laporan {
    // Properti
    public $id_laporan;
    public $periode;
    public $total_penjualan;
    public $file_path;

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Mengambil data penjualan yang berhasil dalam rentang tanggal tertentu.
     * @param string $startDate Tanggal mulai dalam format Y-m-d.
     * @param string $endDate Tanggal akhir dalam format Y-m-d.
     * @return array Data penjualan untuk laporan.
     */
    public function getSalesByDateRange($startDate, $endDate) {
        // Menambahkan waktu 23:59:59 ke tanggal akhir untuk mencakup seluruh hari itu.
        $endDate = $endDate . ' 23:59:59';

        $sql = "SELECT
                    p.id_pembayaran,
                    p.jumlah,
                    p.waktu_pembayaran,
                    pes.id_pemesanan,
                    u.username AS nama_pelanggan
                FROM
                    pembayaran p
                JOIN
                    pemesanan pes ON p.id_pemesanan = pes.id_pemesanan
                JOIN
                    users u ON pes.id_pelanggan = u.id_user
                WHERE
                    p.status = 'success'
                    AND p.waktu_pembayaran BETWEEN ? AND ?
                ORDER BY
                    p.waktu_pembayaran DESC";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$startDate, $endDate]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Sebaiknya log error di sini
            // error_log($e->getMessage());
            return [];
        }
    }

    /**
     * Generate laporan sesuai UML class diagram
     * @param string $periode Periode laporan (harian, mingguan, bulanan, tahunan)
     * @param string $tanggal_mulai Tanggal mulai laporan
     * @param string $tanggal_selesai Tanggal selesai laporan
     * @param string $format Format laporan (pdf, excel, json)
     * @return array Result dengan data laporan
     */
    public function generate($periode, $tanggal_mulai, $tanggal_selesai, $format = 'json') {
        try {
            // Validasi parameter
            if (!in_array($periode, ['harian', 'mingguan', 'bulanan', 'tahunan'])) {
                return [
                    'success' => false,
                    'message' => 'Periode laporan tidak valid'
                ];
            }

            // Query untuk mengambil data penjualan
            $query = "SELECT 
                        DATE(pb.tanggal_pembayaran) as tanggal,
                        COUNT(DISTINCT p.id_pemesanan) as total_transaksi,
                        SUM(p.total_harga) as total_pendapatan,
                        COUNT(DISTINCT t.id_tiket) as total_tiket_terjual,
                        j.asal,
                        j.tujuan,
                        b.nama_bus,
                        b.nomor_bus
                     FROM pembayaran pb
                     JOIN pemesanan p ON pb.id_pemesanan = p.id_pemesanan
                     JOIN tiket t ON p.id_pemesanan = t.id_pemesanan
                     JOIN jadwal j ON p.id_jadwal = j.id_jadwal
                     JOIN bus b ON j.id_bus = b.id_bus
                     WHERE pb.status_pembayaran = 'lunas'
                     AND pb.tanggal_pembayaran BETWEEN :tanggal_mulai AND :tanggal_selesai";

            // Grouping berdasarkan periode
            switch ($periode) {
                case 'harian':
                    $query .= " GROUP BY DATE(pb.tanggal_pembayaran), j.asal, j.tujuan";
                    break;
                case 'mingguan':
                    $query .= " GROUP BY WEEK(pb.tanggal_pembayaran), j.asal, j.tujuan";
                    break;
                case 'bulanan':
                    $query .= " GROUP BY MONTH(pb.tanggal_pembayaran), YEAR(pb.tanggal_pembayaran), j.asal, j.tujuan";
                    break;
                case 'tahunan':
                    $query .= " GROUP BY YEAR(pb.tanggal_pembayaran), j.asal, j.tujuan";
                    break;
            }

            $query .= " ORDER BY tanggal DESC";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':tanggal_mulai', $tanggal_mulai);
            $stmt->bindParam(':tanggal_selesai', $tanggal_selesai);
            $stmt->execute();
            
            $data_detail = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Hitung statistik ringkasan
            $total_pendapatan = 0;
            $total_transaksi = 0;
            $total_tiket = 0;

            foreach ($data_detail as $row) {
                $total_pendapatan += $row['total_pendapatan'];
                $total_transaksi += $row['total_transaksi'];
                $total_tiket += $row['total_tiket_terjual'];
            }

            // Simpan data laporan ke database
            $this->id_laporan = $this->saveLaporanToDatabase($periode, $tanggal_mulai, $tanggal_selesai, $total_pendapatan);

            $laporan_data = [
                'id_laporan' => $this->id_laporan,
                'periode' => $periode,
                'tanggal_mulai' => $tanggal_mulai,
                'tanggal_selesai' => $tanggal_selesai,
                'ringkasan' => [
                    'total_pendapatan' => $total_pendapatan,
                    'total_transaksi' => $total_transaksi,
                    'total_tiket_terjual' => $total_tiket
                ],
                'detail' => $data_detail,
                'format' => $format,
                'tanggal_generated' => date('Y-m-d H:i:s')
            ];

            // Set properti objek
            $this->periode = $periode;
            $this->total_penjualan = $total_pendapatan;

            // Generate file berdasarkan format
            switch ($format) {
                case 'pdf':
                    $file_path = $this->generatePDF($laporan_data);
                    break;
                case 'excel':
                    $file_path = $this->generateExcel($laporan_data);
                    break;
                default:
                    $file_path = null;
            }

            $this->file_path = $file_path;

            return [
                'success' => true,
                'data' => $laporan_data,
                'file_path' => $file_path
            ];

        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error generating report: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Simpan data laporan ke database
     * @param string $periode
     * @param string $tanggal_mulai
     * @param string $tanggal_selesai
     * @param float $total_penjualan
     * @return int ID laporan yang disimpan
     */
    private function saveLaporanToDatabase($periode, $tanggal_mulai, $tanggal_selesai, $total_penjualan) {
        try {
            $query = "INSERT INTO laporan (periode, tanggal_mulai, tanggal_selesai, total_penjualan, tanggal_dibuat) 
                      VALUES (:periode, :tanggal_mulai, :tanggal_selesai, :total_penjualan, NOW())";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':periode', $periode);
            $stmt->bindParam(':tanggal_mulai', $tanggal_mulai);
            $stmt->bindParam(':tanggal_selesai', $tanggal_selesai);
            $stmt->bindParam(':total_penjualan', $total_penjualan);
            $stmt->execute();
            
            return $this->pdo->lastInsertId();
            
        } catch (PDOException $e) {
            error_log("Error saving report: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Generate laporan dalam format PDF
     * @param array $data
     * @return string|null
     */
    private function generatePDF($data) {
        try {
            $filename = 'laporan_' . $data['periode'] . '_' . date('Y-m-d_H-i-s') . '.pdf';
            $filepath = __DIR__ . '/../public/reports/' . $filename;
            
            // Buat direktori jika belum ada
            $dir = dirname($filepath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            
            // Generate HTML content
            $html = $this->generateHTMLReport($data);
            
            // Untuk implementasi sederhana, kita bisa menggunakan library seperti DomPDF
            // Atau untuk saat ini, simpan sebagai HTML dengan extension PDF
            file_put_contents($filepath, $html);
            
            return $filepath;
            
        } catch (Exception $e) {
            error_log("Error generating PDF: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate laporan dalam format Excel
     * @param array $data
     * @return string|null
     */
    private function generateExcel($data) {
        try {
            $filename = 'laporan_' . $data['periode'] . '_' . date('Y-m-d_H-i-s') . '.xlsx';
            $filepath = __DIR__ . '/../public/reports/' . $filename;
            
            // Buat direktori jika belum ada
            $dir = dirname($filepath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            
            // Generate CSV content (simple Excel alternative)
            $csv = $this->generateCSVReport($data);
            file_put_contents($filepath, $csv);
            
            return $filepath;
            
        } catch (Exception $e) {
            error_log("Error generating Excel: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Generate HTML report template
     * @param array $data
     * @return string
     */
    private function generateHTMLReport($data) {
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan ' . ucfirst($data['periode']) . '</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .summary { background: #f5f5f5; padding: 15px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #007bff; color: white; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Penjualan ' . ucfirst($data['periode']) . '</h2>
        <p>Periode: ' . date('d/m/Y', strtotime($data['tanggal_mulai'])) . ' - ' . date('d/m/Y', strtotime($data['tanggal_selesai'])) . '</p>
        <p>Digenerate pada: ' . $data['tanggal_generated'] . '</p>
    </div>
    
    <div class="summary">
        <h3>Ringkasan</h3>
        <p><strong>Total Pendapatan:</strong> Rp ' . number_format($data['ringkasan']['total_pendapatan'], 0, ',', '.') . '</p>
        <p><strong>Total Transaksi:</strong> ' . $data['ringkasan']['total_transaksi'] . '</p>
        <p><strong>Total Tiket Terjual:</strong> ' . $data['ringkasan']['total_tiket_terjual'] . '</p>
    </div>
    
    <h3>Detail Transaksi</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Rute</th>
                <th>Bus</th>
                <th>Total Transaksi</th>
                <th>Tiket Terjual</th>
                <th>Pendapatan</th>
            </tr>
        </thead>
        <tbody>';
        
        foreach ($data['detail'] as $row) {
            $html .= '<tr>
                <td>' . date('d/m/Y', strtotime($row['tanggal'])) . '</td>
                <td>' . htmlspecialchars($row['asal'] . ' - ' . $row['tujuan']) . '</td>
                <td>' . htmlspecialchars($row['nama_bus'] . ' (' . $row['nomor_bus'] . ')') . '</td>
                <td class="text-right">' . $row['total_transaksi'] . '</td>
                <td class="text-right">' . $row['total_tiket_terjual'] . '</td>
                <td class="text-right">Rp ' . number_format($row['total_pendapatan'], 0, ',', '.') . '</td>
            </tr>';
        }
        
        $html .= '</tbody>
    </table>
</body>
</html>';
        
        return $html;
    }
    
    /**
     * Generate CSV report content
     * @param array $data
     * @return string
     */
    private function generateCSVReport($data) {
        $csv = "Laporan Penjualan " . ucfirst($data['periode']) . "\n";
        $csv .= "Periode: " . date('d/m/Y', strtotime($data['tanggal_mulai'])) . " - " . date('d/m/Y', strtotime($data['tanggal_selesai'])) . "\n";
        $csv .= "Digenerate pada: " . $data['tanggal_generated'] . "\n\n";
        
        $csv .= "RINGKASAN\n";
        $csv .= "Total Pendapatan,Rp " . number_format($data['ringkasan']['total_pendapatan'], 0, ',', '.') . "\n";
        $csv .= "Total Transaksi," . $data['ringkasan']['total_transaksi'] . "\n";
        $csv .= "Total Tiket Terjual," . $data['ringkasan']['total_tiket_terjual'] . "\n\n";
        
        $csv .= "DETAIL TRANSAKSI\n";
        $csv .= "Tanggal,Rute,Bus,Total Transaksi,Tiket Terjual,Pendapatan\n";
        
        foreach ($data['detail'] as $row) {
            $csv .= date('d/m/Y', strtotime($row['tanggal'])) . ',';
            $csv .= $row['asal'] . ' - ' . $row['tujuan'] . ',';
            $csv .= $row['nama_bus'] . ' (' . $row['nomor_bus'] . ')' . ',';
            $csv .= $row['total_transaksi'] . ',';
            $csv .= $row['total_tiket_terjual'] . ',';
            $csv .= 'Rp ' . number_format($row['total_pendapatan'], 0, ',', '.') . "\n";
        }
        
        return $csv;
    }

    // ...existing code...
}

?>
