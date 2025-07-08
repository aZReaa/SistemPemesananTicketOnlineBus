<?php

// File: models/PetugasLoket.php
// Deskripsi: Kelas untuk entitas Petugas Loket, turunan dari User.

require_once 'User.php';

class PetugasLoket extends User {
    private $id_petugas;
    private $shift;
    private $lokasi_loket;
    
    /**
     * Constructor untuk inisialisasi.
     * @param PDO $pdo Objek koneksi database.
     */
    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->role = 'petugas_loket';
    }

    /**
     * Cari dan validasi tiket berdasarkan kode booking sesuai UML
     * Hanya untuk pencarian, tidak mengupdate status
     * @param string $kode_booking Kode booking tiket
     * @return array Result dengan success status dan data
     */
    public function cariTiket($kode_booking) {
        try {
            // Ambil data tiket berdasarkan kode booking
            $query = "SELECT t.*, p.*, j.waktu_berangkat, j.waktu_tiba, j.harga,
                            r.kota_asal, r.kota_tujuan, r.nama_rute, r.nama_po, r.kelas,
                            CASE 
                                WHEN p.status = 'paid' THEN 'lunas'
                                WHEN p.status = 'pending' THEN 'pending'
                                WHEN p.status = 'cancelled' THEN 'dibatalkan'
                                ELSE p.status
                            END as status_pembayaran,
                            CASE 
                                WHEN t.status = 'used' THEN 'digunakan'
                                WHEN t.status = 'booked' THEN 'aktif'
                                WHEN t.status = 'available' THEN 'tersedia'
                                ELSE t.status
                            END as status_tiket
                     FROM tiket t
                     JOIN pemesanan p ON t.id_pemesanan = p.id_pemesanan
                     JOIN jadwal j ON t.id_jadwal = j.id_jadwal
                     JOIN rute r ON j.id_rute = r.id_rute
                     WHERE t.kode_booking = :kode_booking";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':kode_booking', $kode_booking);
            $stmt->execute();
            
            $tiket = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$tiket) {
                return [
                    'success' => false,
                    'message' => 'Tiket tidak ditemukan',
                    'data' => null
                ];
            }
            
            return [
                'success' => true,
                'message' => 'Tiket ditemukan',
                'data' => $tiket
            ];
            
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Verifikasi dan validasi tiket (update status menjadi digunakan) sesuai UML
     * @param string $kode_booking Kode booking tiket
     * @return array Result dengan success status dan message
     */
    public function verifikasiTiket($kode_booking) {
        try {
            $this->pdo->beginTransaction();
            
            // Cari tiket terlebih dahulu
            $result = $this->cariTiket($kode_booking);
            
            if (!$result['success']) {
                $this->pdo->rollBack();
                return $result;
            }
            
            $tiket = $result['data'];
            
            // Validasi status tiket - menggunakan status asli dari database
            if ($tiket['status'] == 'used') {
                $this->pdo->rollBack();
                return [
                    'success' => false,
                    'message' => 'Tiket sudah pernah digunakan'
                ];
            }
            
            // Validasi status pembayaran
            if ($tiket['status'] != 'paid' && $tiket['status'] != 'booked') {
                $this->pdo->rollBack();
                return [
                    'success' => false,
                    'message' => 'Tiket belum lunas, tidak dapat divalidasi'
                ];
            }
            
            // Update status tiket menjadi used (digunakan)
            $updateQuery = "UPDATE tiket SET status = 'used' WHERE kode_booking = :kode_booking";
            
            $updateStmt = $this->pdo->prepare($updateQuery);
            $updateStmt->bindParam(':kode_booking', $kode_booking);
            $updateStmt->execute();
            
            $this->pdo->commit();
            
            return [
                'success' => true,
                'message' => 'Tiket berhasil diverifikasi dan ditandai sebagai telah digunakan'
            ];
            
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Terima pembayaran offline sesuai UML
     * @param int $id_pemesanan ID pemesanan
     * @param float $jumlah_bayar Jumlah pembayaran
     * @return array Result dengan success status
     */
    public function terimaPembayaranOffline($id_pemesanan, $jumlah_bayar) {
        try {
            $this->pdo->beginTransaction();
            
            // Ambil data pemesanan
            $query = "SELECT * FROM pemesanan WHERE id_pemesanan = :id_pemesanan";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id_pemesanan', $id_pemesanan);
            $stmt->execute();
            
            $pemesanan = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$pemesanan) {
                $this->pdo->rollback();
                return [
                    'success' => false,
                    'message' => 'Pemesanan tidak ditemukan'
                ];
            }
            
            // Cek apakah jumlah bayar sesuai
            if ($jumlah_bayar < $pemesanan['total_harga']) {
                $this->pdo->rollback();
                return [
                    'success' => false,
                    'message' => 'Jumlah pembayaran kurang'
                ];
            }
            
            // Insert ke tabel pembayaran
            $insertPembayaran = "INSERT INTO pembayaran (id_pemesanan, metode_pembayaran, 
                                jumlah_bayar, tanggal_pembayaran, status_pembayaran, 
                                id_petugas_konfirmasi) 
                                VALUES (:id_pemesanan, 'offline', :jumlah_bayar, NOW(), 
                                'lunas', :id_petugas)";
            
            $stmt = $this->pdo->prepare($insertPembayaran);
            $stmt->bindParam(':id_pemesanan', $id_pemesanan);
            $stmt->bindParam(':jumlah_bayar', $jumlah_bayar);
            $stmt->bindParam(':id_petugas', $this->id_petugas);
            $stmt->execute();
            
            // Update status pemesanan
            $updatePemesanan = "UPDATE pemesanan SET status_pembayaran = 'lunas' 
                               WHERE id_pemesanan = :id_pemesanan";
            
            $stmt = $this->pdo->prepare($updatePemesanan);
            $stmt->bindParam(':id_pemesanan', $id_pemesanan);
            $stmt->execute();
            
            // Update status tiket
            $updateTiket = "UPDATE tiket SET status_tiket = 'aktif' 
                           WHERE id_pemesanan = :id_pemesanan";
            
            $stmt = $this->pdo->prepare($updateTiket);
            $stmt->bindParam(':id_pemesanan', $id_pemesanan);
            $stmt->execute();
            
            $this->pdo->commit();
            
            return [
                'success' => true,
                'message' => 'Pembayaran berhasil diterima',
                'kembalian' => $jumlah_bayar - $pemesanan['total_harga']
            ];
            
        } catch (PDOException $e) {
            $this->pdo->rollback();
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lihat data pengunjung sesuai UML
     * @param string $tanggal_mulai Filter tanggal mulai
     * @param string $tanggal_selesai Filter tanggal selesai
     * @param string $filter_nama Filter nama pengunjung
     * @return array Data pengunjung
     */
    public function lihatDataPelanggan($tanggal_mulai = null, $tanggal_selesai = null, $filter_nama = null) {
        try {
            // Query yang disesuaikan dengan struktur tabel yang benar
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
            
            $params = [];
            
            if ($tanggal_mulai && $tanggal_selesai) {
                $query .= " AND DATE(j.waktu_berangkat) BETWEEN :tanggal_mulai AND :tanggal_selesai";
                $params['tanggal_mulai'] = $tanggal_mulai;
                $params['tanggal_selesai'] = $tanggal_selesai;
            }
            
            if ($filter_nama) {
                $query .= " AND u.username LIKE :nama";
                $params['nama'] = '%' . $filter_nama . '%';
            }
            
            $query .= " ORDER BY j.waktu_berangkat DESC";
            
            $stmt = $this->pdo->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bindParam(':' . $key, $value);
            }
            
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'success' => true,
                'data' => $result,
                'total' => count($result)
            ];
            
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => [],
                'total' => 0
            ];
        }
    }
    
    /**
     * Ambil data pemesanan yang belum dibayar untuk pembayaran offline
     * @return array
     */
    public function getPendingPayments() {
        try {
            $query = "SELECT p.*, u.nama, j.asal, j.tujuan, j.tanggal_berangkat, j.jam_berangkat
                      FROM pemesanan p
                      JOIN users u ON p.id_pelanggan = u.id_user
                      JOIN jadwal j ON p.id_jadwal = j.id_jadwal
                      WHERE p.status_pembayaran = 'pending'
                      ORDER BY p.tanggal_pemesanan DESC";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    
    // Getter dan Setter sesuai UML
    public function getIdPetugas() {
        return $this->id_petugas;
    }
    
    public function setIdPetugas($id_petugas) {
        $this->id_petugas = $id_petugas;
    }
    
    public function getShift() {
        return $this->shift;
    }
    
    public function setShift($shift) {
        $this->shift = $shift;
    }
    
    public function getLokasiLoket() {
        return $this->lokasi_loket;
    }
    
    public function setLokasiLoket($lokasi_loket) {
        $this->lokasi_loket = $lokasi_loket;
    }
}

?>
