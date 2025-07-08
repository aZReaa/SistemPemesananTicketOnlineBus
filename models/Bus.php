<?php

// File: models/Bus.php
// Deskripsi: Kelas untuk entitas Bus.

class Bus {
    // Properti
    public $id_bus;
    public $nama_po;
    public $kelas;
    public $kapasitas;
    public $rute_awal;
    public $rute_akhir;

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Mengambil semua data bus dari database.
     * @return array Daftar semua bus.
     */
    public function getAll() {
        $sql = "SELECT id_bus, nama_po, kelas, kapasitas, rute_awal, rute_akhir FROM bus ORDER BY nama_po ASC";
        try {
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Bus Model Error: " . $e->getMessage());
            return [];
        }
    }

    // Metode-metode lain untuk CRUD (Create, Read, Update, Delete) data bus
    // akan ditambahkan di sini sesuai kebutuhan fungsionalitas Admin.
}

?>
