<?php

// File: models/Admin.php
// Deskripsi: Kelas untuk entitas Admin, turunan dari User.

require_once 'User.php';

class Admin extends User {
    /**
     * Constructor untuk inisialisasi.
     * @param PDO $pdo Objek koneksi database.
     */
    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->role = 'admin';
    }

    /**
     * Method untuk mengelola jadwal (CRUD)
     * @param string $action Aksi yang akan dilakukan (create, read, update, delete)
     * @param array $data Data jadwal yang akan diproses
     * @return mixed Hasil operasi (array untuk read, bool untuk lainnya)
     */
    public function kelolaJadwal($action = 'read', $data = null) {
        try {
            switch ($action) {
                case 'create':
                    $stmt = $this->pdo->prepare(
                        "INSERT INTO jadwal (id_bus, waktu_berangkat, waktu_tiba, harga) 
                        VALUES (?, ?, ?, ?)"
                    );
                    return $stmt->execute([
                        $data['id_bus'],
                        $data['waktu_berangkat'],
                        $data['waktu_tiba'],
                        $data['harga']
                    ]);
                    
                case 'read':
                    $sql = "SELECT j.*, b.nama_po, b.kelas 
                            FROM jadwal j 
                            JOIN bus b ON j.id_bus = b.id_bus";
                    
                    // Jika ada parameter id, ambil data spesifik
                    if (isset($data['id_jadwal'])) {
                        $sql .= " WHERE j.id_jadwal = ?";
                        $stmt = $this->pdo->prepare($sql);
                        $stmt->execute([$data['id_jadwal']]);
                        return $stmt->fetch(PDO::FETCH_ASSOC);
                    }
                    
                    // Jika tidak ada parameter, ambil semua data
                    $stmt = $this->pdo->query($sql);
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                case 'update':
                    $stmt = $this->pdo->prepare(
                        "UPDATE jadwal SET 
                            id_bus = ?, 
                            waktu_berangkat = ?, 
                            waktu_tiba = ?, 
                            harga = ?
                        WHERE id_jadwal = ?"
                    );
                    return $stmt->execute([
                        $data['id_bus'],
                        $data['waktu_berangkat'],
                        $data['waktu_tiba'],
                        $data['harga'],
                        $data['id_jadwal']
                    ]);
                    
                case 'delete':
                    $stmt = $this->pdo->prepare("DELETE FROM jadwal WHERE id_jadwal = ?");
                    return $stmt->execute([$data['id_jadwal']]);
                    
                default:
                    return false;
            }
        } catch (Exception $e) {
            // error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Method untuk mengelola data tiket
     * @param string $action Aksi yang akan dilakukan (read, update_status)
     * @param array $data Data tiket yang akan diproses
     * @return mixed Hasil operasi
     */
    public function kelolaTiket($action = 'read', $data = null) {
        try {
            switch ($action) {
                case 'read':
                    $sql = "SELECT t.*, j.waktu_berangkat, j.waktu_tiba, b.nama_po, 
                                   p.kode_booking, u.username as nama_pelanggan
                            FROM tiket t
                            JOIN jadwal j ON t.id_jadwal = j.id_jadwal
                            JOIN bus b ON j.id_bus = b.id_bus
                            JOIN pemesanan p ON t.id_pemesanan = p.id_pemesanan
                            JOIN users u ON p.id_pelanggan = u.id_user";
                    
                    // Jika ada parameter id_pemesanan, filter berdasarkan pemesanan
                    if (isset($data['id_pemesanan'])) {
                        $sql .= " WHERE t.id_pemesanan = ?";
                        $stmt = $this->pdo->prepare($sql);
                        $stmt->execute([$data['id_pemesanan']]);
                    } else {
                        $stmt = $this->pdo->query($sql);
                    }
                    
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                case 'update_status':
                    $stmt = $this->pdo->prepare(
                        "UPDATE tiket SET status = ? WHERE id_tiket = ?"
                    );
                    return $stmt->execute([$data['status'], $data['id_tiket']]);
                    
                default:
                    return false;
            }
        } catch (Exception $e) {
            // error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Method untuk mengonfirmasi pembayaran yang diunggah pelanggan
     * @param int $id_pembayaran ID pembayaran yang akan dikonfirmasi
     * @param bool $diterima True untuk menerima, false untuk menolak
     * @param string $catatan Catatan tambahan (opsional)
     * @return bool True jika berhasil, false jika gagal
     */
    public function konfirmasiPembayaran($id_pembayaran, $diterima = true, $catatan = '') {
        try {
            $this->pdo->beginTransaction();
            
            // 1. Update status pembayaran
            $status = $diterima ? 'success' : 'rejected';
            $stmtPembayaran = $this->pdo->prepare(
                "UPDATE pembayaran 
                SET status = ?, catatan = ?, waktu_verifikasi = NOW() 
                WHERE id_pembayaran = ?"
            );
            $stmtPembayaran->execute([$status, $catatan, $id_pembayaran]);
            
            if ($diterima) {
                // 2. Jika diterima, update status pemesanan
                $stmtPemesanan = $this->pdo->prepare(
                    "UPDATE pemesanan p
                    JOIN pembayaran pb ON p.id_pemesanan = pb.id_pemesanan
                    SET p.status = 'paid'
                    WHERE pb.id_pembayaran = ?"
                );
                $stmtPemesanan->execute([$id_pembayaran]);
                
                // 3. Update status tiket menjadi 'confirmed'
                $stmtTiket = $this->pdo->prepare(
                    "UPDATE tiket t
                    JOIN pemesanan p ON t.id_pemesanan = p.id_pemesanan
                    JOIN pembayaran pb ON p.id_pemesanan = pb.id_pemesanan
                    SET t.status = 'confirmed'
                    WHERE pb.id_pembayaran = ?"
                );
                $stmtTiket->execute([$id_pembayaran]);
            }
            
            $this->pdo->commit();
            return true;
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            // error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Method untuk menghasilkan laporan penjualan
     * @param string $periode Format: 'YYYY-MM' atau 'YYYY' atau 'all' untuk semua data
     * @param string $format Format laporan: 'array' atau 'pdf' (opsional)
     * @return mixed Array data laporan atau path file PDF
     */
    public function generateLaporan($periode, $format = 'array') {
        try {
            // Bangun query berdasarkan periode
            $sql = "SELECT 
                        p.id_pemesanan, p.kode_booking, p.tgl_pesan, p.total_harga,
                        COUNT(t.id_tiket) as jumlah_tiket,
                        u.username as nama_pelanggan,
                        j.waktu_berangkat, j.waktu_tiba,
                        b.nama_po, b.kelas
                    FROM pemesanan p
                    JOIN tiket t ON p.id_pemesanan = t.id_pemesanan
                    JOIN users u ON p.id_pelanggan = u.id_user
                    JOIN jadwal j ON p.id_jadwal = j.id_jadwal
                    JOIN bus b ON j.id_bus = b.id_bus
                    WHERE p.status = 'paid' AND p.tgl_pesan IS NOT NULL";
            
            $params = [];
            
            // Tambahkan filter periode jika bukan 'all'
            if ($periode !== 'all') {
                if (strlen($periode) === 7) { // Format YYYY-MM
                    $sql .= " AND DATE_FORMAT(p.tgl_pesan, '%Y-%m') = ?";
                    $params[] = $periode;
                } elseif (strlen($periode) === 4) { // Format YYYY
                    $sql .= " AND YEAR(p.tgl_pesan) = ?";
                    $params[] = $periode;
                }
            }
            
            $sql .= " GROUP BY p.id_pemesanan ORDER BY p.tgl_pesan DESC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Hitung total pendapatan
            $totalPendapatan = array_sum(array_column($data, 'total_harga'));
            
            $result = [
                'periode' => $periode,
                'total_transaksi' => count($data),
                'total_tiket_terjual' => array_sum(array_column($data, 'jumlah_tiket')),
                'total_pendapatan' => $totalPendapatan,
                'data_transaksi' => $data
            ];
            
            // Jika format yang diminta adalah PDF, generate PDF (implementasi bisa ditambahkan kemudian)
            if ($format === 'pdf') {
                // Kode untuk generate PDF
                // $pdf = new PdfGenerator();
                // return $pdf->generate('laporan_penjualan', $result);
                // Untuk saat ini kita kembalikan array dulu
                return $result;
            }
            
            return $result;
            
        } catch (Exception $e) {
            // error_log($e->getMessage());
            return [
                'error' => true,
                'message' => 'Gagal menghasilkan laporan: ' . $e->getMessage()
            ];
        }
    }
}

?>
