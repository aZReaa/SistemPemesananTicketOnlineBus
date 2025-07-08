<?php

class Pembayaran {
    private $pdo;
    private $table = 'pembayaran';

    // Properti
    public $id_pembayaran;
    public $id_pemesanan;
    public $metode;
    public $jumlah;
    public $status;
    public $waktu_pembayaran;
    public $bukti_pembayaran;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Mengambil semua pembayaran yang statusnya 'pending' beserta detail pemesan.
     * @return array
     */
    public function getAllPendingWithDetails() {
        $query = "SELECT
                    p.id_pembayaran,
                    p.id_pemesanan,
                    p.metode,
                    p.jumlah,
                    p.status,
                    p.waktu_pembayaran,
                    p.bukti_pembayaran,
                    pes.tgl_pesan,
                    u.username AS nama_pelanggan,
                    u.email AS email_pelanggan
                FROM
                    " . $this->table . " p
                JOIN
                    pemesanan pes ON p.id_pemesanan = pes.id_pemesanan
                JOIN
                    users u ON pes.id_pelanggan = u.id_user
                WHERE
                    p.status = 'pending'
                ORDER BY
                    p.waktu_pembayaran ASC";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mencari pembayaran berdasarkan ID.
     * @param int $id_pembayaran
     * @return bool
     */
    public function findById($id_pembayaran) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_pembayaran = :id_pembayaran LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id_pembayaran', $id_pembayaran);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id_pembayaran = $row['id_pembayaran'];
            $this->id_pemesanan = $row['id_pemesanan'];
            $this->metode = $row['metode'];
            $this->jumlah = $row['jumlah'];
            $this->status = $row['status'];
            $this->waktu_pembayaran = $row['waktu_pembayaran'];
            $this->bukti_pembayaran = $row['bukti_pembayaran'];
            return true;
        }
        return false;
    }

    /**
     * Memperbarui status pembayaran dan juga status pemesanan terkait.
     * Menggunakan transaksi untuk memastikan integritas data.
     * @param int $id_pembayaran
     * @param string $new_status 'success' atau 'failed'
     * @return bool
     */
    public function updateStatus($id_pembayaran, $new_status) {
        if ($new_status !== 'success' && $new_status !== 'failed') {
            return false; // Hanya status 'success' atau 'failed' yang diizinkan
        }

        $this->pdo->beginTransaction();

        try {
            // 1. Update status di tabel pembayaran
            $query_pembayaran = "UPDATE " . $this->table . " SET status = :status WHERE id_pembayaran = :id_pembayaran";
            $stmt_pembayaran = $this->pdo->prepare($query_pembayaran);
            $stmt_pembayaran->bindParam(':status', $new_status);
            $stmt_pembayaran->bindParam(':id_pembayaran', $id_pembayaran);
            $stmt_pembayaran->execute();

            // 2. Dapatkan id_pemesanan dari pembayaran
            $query_get_pemesanan = "SELECT id_pemesanan FROM " . $this->table . " WHERE id_pembayaran = :id_pembayaran";
            $stmt_get_pemesanan = $this->pdo->prepare($query_get_pemesanan);
            $stmt_get_pemesanan->bindParam(':id_pembayaran', $id_pembayaran);
            $stmt_get_pemesanan->execute();
            $pemesanan = $stmt_get_pemesanan->fetch(PDO::FETCH_ASSOC);
            
            if ($pemesanan) {
                $id_pemesanan = $pemesanan['id_pemesanan'];
                // Jika pembayaran sukses, status pemesanan menjadi 'paid'.
                // Jika pembayaran gagal/ditolak, status pemesanan tetap 'pending'.
                $status_pemesanan = ($new_status == 'success') ? 'paid' : 'pending';
                
                $query_pemesanan = "UPDATE pemesanan SET status = :status WHERE id_pemesanan = :id_pemesanan";
                $stmt_pemesanan = $this->pdo->prepare($query_pemesanan);
                $stmt_pemesanan->bindParam(':status', $status_pemesanan);
                $stmt_pemesanan->bindParam(':id_pemesanan', $id_pemesanan);
                $stmt_pemesanan->execute();
            }

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            // error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Menghitung jumlah pembayaran yang statusnya 'pending'.
     *
     * @return int Jumlah pembayaran pending.
     */
    public function getPendingCount() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM " . $this->table . " WHERE status = 'pending'");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    /**
     * Menghitung total pendapatan dari pembayaran yang berhasil.
     * @return float
     */
    public function getTotalRevenue() {
        $query = "SELECT COALESCE(SUM(jumlah), 0) as total_revenue FROM " . $this->table . " WHERE status = 'success'";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_revenue'] ?? 0;
    }

    /**
     * Mengambil semua pembayaran yang statusnya 'pending' beserta detail pemesan.
     * @return array
     */
    public function getPendingPayments() {
        $query = "SELECT
                    p.id_pembayaran,
                    p.id_pemesanan,
                    p.metode,
                    p.jumlah,
                    p.status,
                    p.waktu_pembayaran,
                    p.bukti_pembayaran,
                    pes.tgl_pesan,
                    u.username AS nama_pelanggan,
                    u.email AS email_pelanggan
                FROM
                    " . $this->table . " p
                JOIN
                    pemesanan pes ON p.id_pemesanan = pes.id_pemesanan
                JOIN
                    users u ON pes.id_pelanggan = u.id_user
                WHERE
                    p.status = 'pending'
                ORDER BY
                    p.waktu_pembayaran ASC";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Membuat pembayaran baru
     * @param array $data Data pembayaran yang akan disimpan
     * @return bool True jika berhasil, false jika gagal
     */
    public function create($data) {
        try {
            $query = "INSERT INTO " . $this->table . " 
                     (id_pemesanan, metode, jumlah, status, waktu_pembayaran, bukti_pembayaran) 
                     VALUES (?, ?, ?, ?, NOW(), ?)";
            
            $stmt = $this->pdo->prepare($query);
            $result = $stmt->execute([
                $data['id_pemesanan'],
                $data['metode_pembayaran'],
                $data['jumlah'],
                $data['status'],
                $data['bukti_pembayaran']
            ]);
            
            if ($result) {
                $this->id_pembayaran = $this->pdo->lastInsertId();
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error creating payment: " . $e->getMessage());
            return false;
        }
    }
}
