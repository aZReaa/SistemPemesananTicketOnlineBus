<?php

// File: models/Rute.php
// Model untuk mengelola rute perjalanan

class Rute {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Mendapatkan semua rute aktif
     */
    public function getAllActiveRutes() {
        $sql = "SELECT * FROM rute WHERE status = 'active' ORDER BY nama_rute ASC";
        try {
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Rute Model Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Mendapatkan rute berdasarkan kota asal dan tujuan
     */
    public function searchRute($kotaAsal, $kotaTujuan) {
        $sql = "SELECT * FROM rute WHERE kota_asal = ? AND kota_tujuan = ? AND status = 'active'";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$kotaAsal, $kotaTujuan]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Rute Model Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Mendapatkan semua kota unik (untuk dropdown)
     */
    public function getAllCities() {
        $sql = "SELECT DISTINCT kota_asal as kota FROM rute WHERE status = 'active' 
                UNION 
                SELECT DISTINCT kota_tujuan as kota FROM rute WHERE status = 'active' 
                ORDER BY kota ASC";
        try {
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log("Rute Model Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Menambah rute baru
     */
    public function createRute($data) {
        $sql = "INSERT INTO rute (nama_rute, nama_po, kelas, kota_asal, kota_tujuan, kapasitas, jarak_km) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $data['nama_rute'],
                $data['nama_po'],
                $data['kelas'],
                $data['kota_asal'],
                $data['kota_tujuan'],
                $data['kapasitas'],
                $data['jarak_km']
            ]);
        } catch (PDOException $e) {
            error_log("Rute Model Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update rute
     */
    public function update($id, $data) {
        $sql = "UPDATE rute SET nama_rute = ?, nama_po = ?, kelas = ?, kota_asal = ?, kota_tujuan = ?, kapasitas = ?, jarak_km = ? WHERE id_rute = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $data['nama_rute'],
                $data['nama_po'],
                $data['kelas'],
                $data['kota_asal'],
                $data['kota_tujuan'],
                $data['kapasitas'],
                $data['jarak_km'],
                $id
            ]);
        } catch (PDOException $e) {
            error_log("Rute Model Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Hapus rute (soft delete)
     */
    public function delete($id) {
        $sql = "UPDATE rute SET status = 'inactive' WHERE id_rute = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Rute Model Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mendapatkan rute berdasarkan ID
     */
    public function findById($id) {
        $sql = "SELECT * FROM rute WHERE id_rute = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Rute Model Error: " . $e->getMessage());
            return false;
        }
    }
}
