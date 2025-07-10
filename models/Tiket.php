<?php

// File: models/Tiket.php
// Deskripsi: Kelas untuk entitas Tiket.

require_once __DIR__ . '/QRCodeGenerator.php';

class Tiket {
    // Properti
    public $id_tiket;
    public $id_pemesanan;
    public $id_jadwal;
    public $kode_booking;
    public $nomor_kursi;
    public $status; // enum: 'available', 'booked', 'used'

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Generate QR code for ticket
     * @param string $kode_booking Booking code
     * @param array $additionalData Additional data to include in QR
     * @return string QR code image URL
     */
    public function generateQRCode($kode_booking, $additionalData = []) {
        return QRCodeGenerator::generateBookingQR($kode_booking, $additionalData);
    }

    /**
     * Validate QR code data
     * @param string $qrData QR code data (can be JSON string or plain booking code)
     * @return array|false Decoded and validated data or false on failure
     */
    public function validateQRCode($qrData) {
        // Try to decode as JSON first (new format)
        $decoded = json_decode($qrData, true);
        
        if ($decoded && isset($decoded['type']) && $decoded['type'] === 'ticket') {
            // This is a JSON format QR code
            if (QRCodeGenerator::validateQRData($decoded)) {
                return $decoded;
            }
        }
        
        // If JSON decode failed or invalid, treat as simple booking code
        $trimmed = trim($qrData);
        if (!empty($trimmed) && strlen($trimmed) >= 5) {
            // Return in expected format for compatibility
            return [
                'type' => 'ticket',
                'booking_code' => $trimmed,
                'timestamp' => time(),
                'format' => 'simple'
            ];
        }
        
        return false;
    }

    /**
     * Mendapatkan daftar kursi yang sudah dipesan untuk jadwal tertentu.
     * @param int $id_jadwal ID Jadwal.
     * @return array Daftar nomor kursi yang sudah dipesan.
     */
    public function getBookedSeatsByJadwal($id_jadwal) {
        $sql = "SELECT nomor_kursi FROM tiket WHERE id_jadwal = ? AND status != 'available'";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_jadwal]);
            // Menggunakan fetchAll dengan PDO::FETCH_COLUMN untuk mendapatkan array flat
            return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        } catch (PDOException $e) {
            // error_log($e->getMessage());
            return [];
        }
    }

    /**
     * Mendapatkan daftar kursi yang tersedia untuk jadwal tertentu.
     * @param int $id_jadwal ID Jadwal.
     * @return array Daftar nomor kursi yang tersedia.
     */
    public function getAvailableSeats($id_jadwal) {
        $sql = "SELECT nomor_kursi FROM tiket WHERE id_jadwal = ? AND status = 'available' ORDER BY nomor_kursi ASC";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_jadwal]);
            return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        } catch (PDOException $e) {
            error_log("Error getting available seats: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Mencari tiket berdasarkan kode booking unik beserta detail lengkapnya.
     * @param string $kode_tiket Kode unik tiket.
     * @return array|false Data tiket jika ditemukan, atau false jika tidak.
     */
    public function findByKode($kode_tiket) {
        $sql = "SELECT 
                    t.id_tiket, t.kode_booking AS kode_tiket, t.status,
                    p.id_pemesanan,
                    u.username AS nama_penumpang,
                    j.waktu_berangkat, j.waktu_tiba,
                    b_asal.nama_terminal AS asal,
                    b_tujuan.nama_terminal AS tujuan
                FROM tiket t
                JOIN pemesanan p ON t.id_pemesanan = p.id_pemesanan
                JOIN users u ON p.id_pelanggan = u.id_user
                JOIN jadwal j ON t.id_jadwal = j.id_jadwal
                JOIN bus b ON j.id_bus = b.id_bus
                JOIN terminal b_asal ON b.terminal_asal_id = b_asal.id_terminal
                JOIN terminal b_tujuan ON b.terminal_tujuan_id = b_tujuan.id_terminal
                WHERE t.kode_booking = ?";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$kode_tiket]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Update status tiket
     * @param int $id_tiket
     * @param string $status
     * @return bool
     */
    public function updateStatus($id_tiket, $status) {
        $sql = "UPDATE tiket SET status = ? WHERE id_tiket = ?";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$status, $id_tiket]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Mengambil semua tiket dengan detail lengkap.
     * @return array
     */
    public function getAll() {
        $sql = "SELECT 
                    t.id_tiket, 
                    t.kode_booking, 
                    t.nomor_kursi, 
                    t.status,
                    u.username AS nama_pelanggan,
                    b.nama_po,
                    b.kelas,
                    b.rute_awal,
                    b.rute_akhir,
                    j.waktu_berangkat,
                    j.waktu_tiba
                FROM tiket t
                LEFT JOIN pemesanan pe ON t.id_pemesanan = pe.id_pemesanan
                LEFT JOIN users u ON pe.id_pelanggan = u.id_user
                LEFT JOIN jadwal j ON t.id_jadwal = j.id_jadwal
                LEFT JOIN bus b ON j.id_bus = b.id_bus
                ORDER BY t.id_tiket DESC";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Menambahkan tiket baru ke dalam sistem.
     * @param int $id_pemesanan
     * @param int $id_jadwal
     * @param string $nomor_kursi
     * @param string $nama_penumpang
     * @param float $harga
     * @return bool
     */
    public function create($id_pemesanan, $id_jadwal, $nomor_kursi, $nama_penumpang, $harga) {
        $stmt = $this->pdo->prepare("INSERT INTO tiket (id_pemesanan, id_jadwal, nomor_kursi, nama_penumpang, harga) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$id_pemesanan, $id_jadwal, $nomor_kursi, $nama_penumpang, $harga]);
    }

    /**
     * Mengambil tiket berdasarkan ID pemesanan.
     * @param int $id_pemesanan
     * @return array
     */
    public function getByPemesanan($id_pemesanan) {
        $stmt = $this->pdo->prepare("SELECT * FROM tiket WHERE id_pemesanan = ?");
        $stmt->execute([$id_pemesanan]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mendapatkan total kapasitas untuk jadwal tertentu.
     * @param int $id_jadwal ID Jadwal.
     * @return int Total kapasitas.
     */
    public function getKapasitasByJadwal($id_jadwal) {
        $sql = "SELECT COUNT(*) as total FROM tiket WHERE id_jadwal = ?";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_jadwal]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error getting kapasitas: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Mendapatkan jumlah tiket yang sudah dipesan untuk jadwal tertentu.
     * @param int $id_jadwal ID Jadwal.
     * @return int Jumlah tiket yang sudah dipesan.
     */
    public function getBookedCount($id_jadwal) {
        $sql = "SELECT COUNT(*) as total FROM tiket WHERE id_jadwal = ? AND status IN ('booked', 'used')";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_jadwal]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error getting booked count: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get daftar tiket untuk jadwal tertentu dengan detail
     * @param int $id_jadwal ID jadwal
     * @return array Daftar tiket dengan detail
     */
    public function getTiketByJadwal($id_jadwal) {
        try {
            $stmt = $this->pdo->prepare(
                "SELECT t.*, p.kode_booking, p.status as status_pemesanan,
                        pel.nama as nama_penumpang, pel.no_telp,
                        pem.waktu_pembayaran
                 FROM tiket t
                 LEFT JOIN pemesanan p ON t.id_pemesanan = p.id_pemesanan
                 LEFT JOIN pelanggan pel ON p.id_pelanggan = pel.id_user
                 LEFT JOIN pembayaran pem ON p.id_pemesanan = pem.id_pemesanan
                 WHERE t.id_jadwal = ?
                 ORDER BY CAST(t.nomor_kursi AS UNSIGNED)"
            );
            $stmt->execute([$id_jadwal]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
}

?>
