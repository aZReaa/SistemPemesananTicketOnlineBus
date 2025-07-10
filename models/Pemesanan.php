<?php

// File: models/Pemesanan.php
// Deskripsi: Kelas untuk entitas Pemesanan.

class Pemesanan {
    // Properti
    public $id_pemesanan;
    public $id_pelanggan;
    public $tgl_pesan;
    public $status; // enum: 'pending', 'paid', 'cancelled'
    public $total_harga;

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Membuat pesanan baru beserta tiketnya dalam satu transaksi.
     * @param int $id_pelanggan
     * @param int $id_jadwal
     * @param array $seats
     * @param float $total_harga
     * @return string|false Kode booking jika berhasil, false jika gagal.
     */
    public function createPemesanan($id_pelanggan, $id_jadwal, $seats, $total_harga) {
        $kode_booking = 'BOOK-' . strtoupper(substr(md5(time() . $id_pelanggan), 0, 8));
        
        try {
            $this->pdo->beginTransaction();

            // 1. Masukkan data ke tabel pemesanan (sesuai struktur database)
            $sql_pemesanan = "INSERT INTO pemesanan (id_pelanggan, tgl_pesan, status, total_harga) VALUES (?, NOW(), 'pending', ?)";
            $stmt_pemesanan = $this->pdo->prepare($sql_pemesanan);
            $stmt_pemesanan->execute([$id_pelanggan, $total_harga]);
            $id_pemesanan = $this->pdo->lastInsertId();

            // 2. Update tiket yang tersedia menjadi booked untuk setiap kursi
            $sql_tiket = "UPDATE tiket SET id_pemesanan = ?, kode_booking = ?, status = 'booked' WHERE id_jadwal = ? AND nomor_kursi = ? AND status = 'available'";
            $stmt_tiket = $this->pdo->prepare($sql_tiket);

            foreach ($seats as $seat) {
                // Validasi bahwa kursi tersedia
                $check_sql = "SELECT COUNT(*) FROM tiket WHERE id_jadwal = ? AND nomor_kursi = ? AND status = 'available'";
                $check_stmt = $this->pdo->prepare($check_sql);
                $check_stmt->execute([$id_jadwal, $seat]);
                if ($check_stmt->fetchColumn() == 0) {
                    throw new Exception("Kursi $seat sudah tidak tersedia.");
                }
                
                // Generate unique kode_booking per tiket (booking_code + seat number)
                $unique_kode_booking = $kode_booking . '-' . str_pad($seat, 2, '0', STR_PAD_LEFT);
                
                // Update tiket menjadi booked dengan kode booking unik
                $stmt_tiket->execute([$id_pemesanan, $unique_kode_booking, $id_jadwal, $seat]);
                if ($stmt_tiket->rowCount() == 0) {
                    throw new Exception("Gagal memesan kursi $seat - kursi mungkin sudah dipesan orang lain.");
                }
            }

            $this->pdo->commit();
            return $kode_booking;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Error creating pemesanan: " . $e->getMessage());
            return false;
        }
    }

    public function konfirmasiPembayaran($id_pemesanan) {
        // Logika untuk mengubah status pemesanan menjadi 'confirmed'
        // setelah pembayaran diverifikasi oleh Admin.
    }

    /**
     * Menghitung total jumlah pemesanan.
     * @return int
     */
    public function getTotalCount() {
        $query = "SELECT COUNT(*) as total FROM pemesanan";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function getDetailById($id_pemesanan) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, 
                   MAX(j.waktu_berangkat) as waktu_berangkat, 
                   MAX(j.waktu_tiba) as waktu_tiba, 
                   MAX(j.harga) as harga, 
                   MAX(r.nama_po) as nama_po, 
                   MAX(r.kelas) as kelas, 
                   MAX(r.nama_rute) as nama_rute, 
                   MAX(r.kota_asal) as asal, 
                   MAX(r.kota_tujuan) as tujuan, 
                   MAX(r.kapasitas) as kapasitas,
                   MAX(t.kode_booking) as kode_booking,
                   GROUP_CONCAT(t.nomor_kursi ORDER BY t.nomor_kursi) as nomor_kursi,
                   COUNT(t.id_tiket) as jumlah_tiket,
                   MAX(u.username) as nama_pelanggan, 
                   MAX(u.email) as email_pelanggan,
                   MAX(pb.status) as status_pembayaran
            FROM pemesanan p
            LEFT JOIN tiket t ON p.id_pemesanan = t.id_pemesanan
            LEFT JOIN jadwal j ON t.id_jadwal = j.id_jadwal
            LEFT JOIN rute r ON j.id_rute = r.id_rute
            LEFT JOIN users u ON p.id_pelanggan = u.id_user
            LEFT JOIN pembayaran pb ON p.id_pemesanan = pb.id_pemesanan
            WHERE p.id_pemesanan = ?
            GROUP BY p.id_pemesanan, p.id_pelanggan, p.tgl_pesan, p.status, p.total_harga
            LIMIT 1
        ");
        $stmt->execute([$id_pemesanan]);
        $pemesanan = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($pemesanan) {
            require_once __DIR__ . '/Tiket.php';
            $tiketModel = new Tiket($this->pdo);
            $pemesanan['tiket'] = $tiketModel->getByPemesanan($id_pemesanan);
        }

        return $pemesanan;
    }

    /**
     * Mendapatkan semua pemesanan untuk pelanggan tertentu.
     * @param int $id_pelanggan
     * @return array
     */
    public function getAllByPelanggan($id_pelanggan) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, 
                   MAX(j.waktu_berangkat) as waktu_berangkat, 
                   MAX(j.waktu_tiba) as waktu_tiba, 
                   MAX(j.harga) as harga,
                   MAX(r.nama_po) as nama_po, 
                   MAX(r.kelas) as kelas, 
                   MAX(r.kota_asal) as asal, 
                   MAX(r.kota_tujuan) as tujuan,
                   MAX(pb.status) as status_pembayaran,
                   MAX(t.kode_booking) as kode_booking,
                   COUNT(t.id_tiket) as jumlah_tiket
            FROM pemesanan p
            LEFT JOIN tiket t ON p.id_pemesanan = t.id_pemesanan
            LEFT JOIN jadwal j ON t.id_jadwal = j.id_jadwal
            LEFT JOIN rute r ON j.id_rute = r.id_rute
            LEFT JOIN pembayaran pb ON p.id_pemesanan = pb.id_pemesanan
            WHERE p.id_pelanggan = ?
            GROUP BY p.id_pemesanan, p.id_pelanggan, p.tgl_pesan, p.status, p.total_harga
            ORDER BY p.tgl_pesan DESC
        ");
        $stmt->execute([$id_pelanggan]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Alias untuk getAllByPelanggan untuk kompatibilitas
     */
    public function getByPelanggan($id_pelanggan) {
        return $this->getAllByPelanggan($id_pelanggan);
    }

    // Metode lain seperti membuat pemesanan baru, membatalkan, dll.
    // akan ditambahkan di sini.
}

?>
