<?php

// File: config/database.php
// Deskripsi: Konfigurasi dan koneksi ke database menggunakan PDO.

// --- Pengaturan Kredensial Database ---
// Ganti nilai-nilai ini sesuai dengan konfigurasi server database Anda.
$host = '127.0.0.1';       // atau 'localhost'
$dbname = 'db_tiket_bus';  // Nama database yang Anda buat dengan skrip SQL
$username = 'root';        // Username database Anda
$password = '';            // Password database Anda

// --- Opsi Koneksi PDO ---
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Mengaktifkan mode error untuk menampilkan pengecualian
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Mengatur mode fetch default ke array asosiatif
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Menonaktifkan emulasi prepared statements untuk keamanan
];

// --- Membuat Koneksi PDO ---
try {
    // Membuat instance baru dari PDO untuk koneksi database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, $options);
} catch (PDOException $e) {
    // Menangani error jika koneksi gagal
    // Pesan ini sebaiknya tidak ditampilkan di lingkungan produksi untuk alasan keamanan
    die("Koneksi ke database gagal: " . $e->getMessage());
}

// Koneksi $pdo sekarang siap digunakan di seluruh aplikasi.
// Anda dapat meng-include file ini di mana pun Anda membutuhkan akses database.

?>
