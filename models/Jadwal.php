<?php

// File: models/Jadwal.php
// Deskripsi: Kelas untuk entitas Jadwal.

class Jadwal {
    // Properti
    public $id_jadwal;
    public $id_rute;
    public $waktu_berangkat;
    public $waktu_tiba;
    public $harga;

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Mencari jadwal berdasarkan tanggal keberangkatan.
     * @param string $tanggal Tanggal dalam format Y-m-d.
     * @return array Daftar jadwal yang ditemukan.
     */
    public function searchByDate($tanggal) {
        // Query untuk mengambil data jadwal dengan join ke tabel rute
        // untuk mendapatkan nama_po, kelas, dan informasi rute.
        $sql = "SELECT 
                    j.id_jadwal, 
                    j.waktu_berangkat, 
                    j.waktu_tiba, 
                    j.harga, 
                    r.nama_po, 
                    r.kelas,
                    r.kota_asal,
                    r.kota_tujuan,
                    r.kapasitas
                FROM jadwal j
                JOIN rute r ON j.id_rute = r.id_rute
                WHERE DATE(j.waktu_berangkat) = ? AND r.status = 'active'
                ORDER BY j.waktu_berangkat ASC";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$tanggal]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // Handle error, misalnya dengan logging
            // error_log($e->getMessage());
            return [];
        }
    }

    /**
     * Menemukan jadwal berdasarkan ID-nya.
     * @param int $id_jadwal ID Jadwal.
     * @return array|false Data jadwal atau false jika tidak ditemukan.
     */
    public function findById($id_jadwal) {
        $sql = "SELECT 
                    j.id_jadwal, 
                    j.waktu_berangkat, 
                    j.waktu_tiba, 
                    j.harga, 
                    j.id_rute,
                    r.nama_rute,
                    r.nama_po,
                    r.kelas,
                    r.kota_asal,
                    r.kota_tujuan,
                    r.kapasitas,
                    r.jarak_km,
                    r.status
                FROM jadwal j
                JOIN rute r ON j.id_rute = r.id_rute
                WHERE j.id_jadwal = ? AND r.status = 'active'";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_jadwal]);
            $result = $stmt->fetch();
            
            if ($result) {
                // Tambahkan informasi asal dan tujuan ke hasil untuk kompatibilitas
                $result['asal'] = $result['kota_asal'];
                $result['tujuan'] = $result['kota_tujuan'];
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error in findById: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mengambil semua data jadwal dari database.
     * @return array Daftar semua jadwal.
     */
    public function getAll() {
        $sql = "SELECT 
                    j.id_jadwal, 
                    j.waktu_berangkat, 
                    j.waktu_tiba, 
                    j.harga, 
                    r.nama_po, 
                    r.kelas,
                    r.kota_asal,
                    r.kota_tujuan,
                    r.kapasitas
                FROM jadwal j
                JOIN rute r ON j.id_rute = r.id_rute
                WHERE r.status = 'active'
                ORDER BY j.waktu_berangkat DESC";

        try {
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // error_log($e->getMessage());
            return [];
        }
    }

    /**
     * Menyimpan jadwal baru ke database.
     * @param array $data Data jadwal dari form.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function store($data) {
        $sql = "INSERT INTO jadwal (id_rute, waktu_berangkat, waktu_tiba, harga) VALUES (?, ?, ?, ?)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                $data['id_rute'],
                $data['waktu_berangkat'],
                $data['waktu_tiba'],
                $data['harga']
            ]);
            
            if (!$result) {
                error_log("Jadwal store failed - SQL error: " . print_r($stmt->errorInfo(), true));
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("PDO Exception in Jadwal store: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Memperbarui data jadwal yang ada di database.
     * @param int $id ID jadwal yang akan diupdate.
     * @param array $data Data jadwal baru dari form.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function update($id, $data) {
        $sql = "UPDATE jadwal SET id_rute = ?, waktu_berangkat = ?, waktu_tiba = ?, harga = ? WHERE id_jadwal = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $data['id_rute'],
                $data['waktu_berangkat'],
                $data['waktu_tiba'],
                $data['harga'],
                $id
            ]);
        } catch (PDOException $e) {
            // error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Menghapus data jadwal dari database.
     * @param int $id ID jadwal yang akan dihapus.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function delete($id) {
        $sql = "DELETE FROM jadwal WHERE id_jadwal = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            // error_log($e->getMessage());
            // Sebaiknya tangani foreign key constraint violation di sini jika ada
            return false;
        }
    }

    /**
     * Menghitung jumlah total jadwal.
     *
     * @return int Jumlah total jadwal.
     */
    public function getTotalCount() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM jadwal");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    /**
     * Mencari jadwal berdasarkan kota asal dan tujuan
     * @param string $kotaAsal
     * @param string $kotaTujuan
     * @param string $tanggal (optional)
     * @return array
     */
    public function searchByRoute($kotaAsal, $kotaTujuan, $tanggal = null) {
        $sql = "SELECT 
                    j.id_jadwal, 
                    j.waktu_berangkat, 
                    j.waktu_tiba, 
                    j.harga, 
                    r.nama_po, 
                    r.kelas,
                    r.kota_asal,
                    r.kota_tujuan,
                    r.kapasitas,
                    r.jarak_km
                FROM jadwal j
                JOIN rute r ON j.id_rute = r.id_rute
                WHERE r.kota_asal = ? AND r.kota_tujuan = ? AND r.status = 'active'";
        
        $params = [$kotaAsal, $kotaTujuan];
        
        if ($tanggal) {
            $sql .= " AND DATE(j.waktu_berangkat) = ?";
            $params[] = $tanggal;
        }
        
        $sql .= " ORDER BY j.waktu_berangkat ASC";

        try {
            error_log("Search query: $sql");
            error_log("Search params: " . print_r($params, true));
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $results = $stmt->fetchAll();
            
            error_log("Search results count: " . count($results));
            
            return $results;
        } catch (PDOException $e) {
            error_log("Search error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Mendapatkan semua kota untuk dropdown
     * @return array
     */
    public function getAllCities() {
        $sql = "SELECT DISTINCT kota_asal as kota FROM rute WHERE status = 'active' 
                UNION 
                SELECT DISTINCT kota_tujuan as kota FROM rute WHERE status = 'active' 
                ORDER BY kota ASC";
        
        try {
            $stmt = $this->pdo->query($sql);
            $cities = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            error_log("Cities query: $sql");
            error_log("Cities found: " . count($cities));
            error_log("Cities list: " . print_r($cities, true));
            
            return $cities;
        } catch (PDOException $e) {
            error_log("Cities error: " . $e->getMessage());
            return [];
        }
    }

    // Metode-metode lain untuk CRUD (Create, Read, Update, Delete) data jadwal
    // akan ditambahkan di sini, biasanya dikelola oleh Admin.
}

?>
