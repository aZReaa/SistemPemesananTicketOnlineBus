-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 08, 2025 at 05:08 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_tiket_bus`
--

-- --------------------------------------------------------

--
-- Table structure for table `bus`
--

CREATE TABLE `bus` (
  `id_bus` int NOT NULL,
  `nama_po` varchar(100) NOT NULL,
  `kelas` varchar(50) NOT NULL,
  `kapasitas` int NOT NULL,
  `rute_awal` varchar(100) NOT NULL,
  `rute_akhir` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bus`
--

INSERT INTO `bus` (`id_bus`, `nama_po`, `kelas`, `kapasitas`, `rute_awal`, `rute_akhir`) VALUES
(1, 'Sinar Jaya Express', 'Executive', 40, 'Makassar', 'Manado'),
(2, 'Harapan Jaya', 'Bisnis', 35, 'Makassar', 'Manado'),
(3, 'Maju Bersama', 'Ekonomi', 45, 'Makassar', 'Manado');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id_jadwal` int NOT NULL,
  `id_rute` int NOT NULL,
  `waktu_berangkat` datetime NOT NULL,
  `waktu_tiba` datetime NOT NULL,
  `harga` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id_jadwal`, `id_rute`, `waktu_berangkat`, `waktu_tiba`, `harga`) VALUES
(1, 31, '2025-07-09 12:45:00', '2025-07-09 12:45:00', '100000.00'),
(2, 81, '2025-07-08 12:48:00', '2025-07-09 12:48:00', '150000.00');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_backup`
--

CREATE TABLE `jadwal_backup` (
  `id_jadwal` int NOT NULL,
  `id_rute` int DEFAULT NULL,
  `id_bus` int NOT NULL,
  `waktu_berangkat` datetime NOT NULL,
  `waktu_tiba` datetime NOT NULL,
  `harga` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jadwal_backup`
--

INSERT INTO `jadwal_backup` (`id_jadwal`, `id_rute`, `id_bus`, `waktu_berangkat`, `waktu_tiba`, `harga`) VALUES
(1, NULL, 1, '2025-07-08 08:00:00', '2025-07-08 11:00:00', '150000.00'),
(2, NULL, 2, '2025-07-08 14:00:00', '2025-07-08 20:00:00', '200000.00'),
(3, NULL, 3, '2025-07-09 06:00:00', '2025-07-09 12:00:00', '180000.00');

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id_laporan` int NOT NULL,
  `periode` varchar(50) NOT NULL,
  `total_penjualan` decimal(15,2) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan_details`
--

CREATE TABLE `pelanggan_details` (
  `id_user` int NOT NULL,
  `alamat` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pelanggan_details`
--

INSERT INTO `pelanggan_details` (`id_user`, `alamat`) VALUES
(2, 'palopo');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int NOT NULL,
  `id_pemesanan` int NOT NULL,
  `metode` enum('transfer','tunai') NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `status` enum('pending','success','failed') NOT NULL DEFAULT 'pending',
  `waktu_pembayaran` datetime DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id_pemesanan` int NOT NULL,
  `id_pelanggan` int NOT NULL,
  `tgl_pesan` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','paid','cancelled') NOT NULL DEFAULT 'pending',
  `total_harga` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rute`
--

CREATE TABLE `rute` (
  `id_rute` int NOT NULL,
  `nama_rute` varchar(200) NOT NULL,
  `nama_po` varchar(100) NOT NULL,
  `kelas` enum('Ekonomi','Bisnis','Eksekutif','Super Eksekutif') NOT NULL,
  `kota_asal` varchar(100) NOT NULL,
  `kota_tujuan` varchar(100) NOT NULL,
  `kapasitas` int NOT NULL DEFAULT '40',
  `jarak_km` int NOT NULL DEFAULT '0',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rute`
--

INSERT INTO `rute` (`id_rute`, `nama_rute`, `nama_po`, `kelas`, `kota_asal`, `kota_tujuan`, `kapasitas`, `jarak_km`, `status`, `created_at`) VALUES
(1, 'PO Litha (Makassar - Manado)', 'PO Litha', 'Eksekutif', 'Makassar', 'Manado', 40, 680, 'active', '2025-07-08 04:30:02'),
(2, 'PO Bintang Prima (Makassar - Kendari)', 'PO Bintang Prima', 'Bisnis', 'Makassar', 'Kendari', 35, 350, 'active', '2025-07-08 04:30:02'),
(3, 'PO Arma Jaya (Makassar - Palu)', 'PO Arma Jaya', 'Ekonomi', 'Makassar', 'Palu', 50, 420, 'active', '2025-07-08 04:30:02'),
(4, 'PO Celebes (Manado - Gorontalo)', 'PO Celebes', 'Bisnis', 'Manado', 'Gorontalo', 30, 280, 'active', '2025-07-08 04:30:02'),
(5, 'PO Sultra Express (Kendari - Bau-Bau)', 'PO Sultra Express', 'Ekonomi', 'Kendari', 'Bau-Bau', 45, 180, 'active', '2025-07-08 04:30:02'),
(6, 'PO Panorama (Makassar - Parepare)', 'PO Panorama', 'Ekonomi', 'Makassar', 'Parepare', 50, 155, 'active', '2025-07-08 04:30:20'),
(7, 'PO Sejahtera (Parepare - Makassar)', 'PO Sejahtera', 'Bisnis', 'Parepare', 'Makassar', 40, 155, 'active', '2025-07-08 04:30:20'),
(8, 'PO Mandala (Bone - Makassar)', 'PO Mandala', 'Ekonomi', 'Bone', 'Makassar', 45, 174, 'active', '2025-07-08 04:30:20'),
(9, 'PO Citra (Makassar - Bone)', 'PO Citra', 'Bisnis', 'Makassar', 'Bone', 38, 174, 'active', '2025-07-08 04:30:20'),
(10, 'PO Lokal Express (Makassar - Bulukumba)', 'PO Lokal Express', 'Ekonomi', 'Makassar', 'Bulukumba', 42, 153, 'active', '2025-07-08 04:30:20'),
(11, 'PO Tana Toraja (Makassar - Rantepao)', 'PO Tana Toraja', 'Bisnis', 'Makassar', 'Rantepao', 35, 328, 'active', '2025-07-08 04:30:20'),
(12, 'PO Bantaeng Indah (Makassar - Bantaeng)', 'PO Bantaeng Indah', 'Ekonomi', 'Makassar', 'Bantaeng', 48, 125, 'active', '2025-07-08 04:30:20'),
(13, 'PO Palu Raya (Palu - Donggala)', 'PO Palu Raya', 'Ekonomi', 'Palu', 'Donggala', 40, 27, 'active', '2025-07-08 04:30:20'),
(14, 'PO Sulteng (Palu - Poso)', 'PO Sulteng', 'Bisnis', 'Palu', 'Poso', 35, 218, 'active', '2025-07-08 04:30:20'),
(15, 'PO Morowali (Palu - Bungku)', 'PO Morowali', 'Ekonomi', 'Palu', 'Bungku', 38, 350, 'active', '2025-07-08 04:30:20'),
(16, 'PO Minahasa (Manado - Tomohon)', 'PO Minahasa', 'Ekonomi', 'Manado', 'Tomohon', 30, 25, 'active', '2025-07-08 04:30:20'),
(17, 'PO Bitung Express (Manado - Bitung)', 'PO Bitung Express', 'Bisnis', 'Manado', 'Bitung', 32, 45, 'active', '2025-07-08 04:30:20'),
(18, 'PO Sangihe (Manado - Tahuna)', 'PO Sangihe', 'Eksekutif', 'Manado', 'Tahuna', 28, 260, 'active', '2025-07-08 04:30:20'),
(19, 'PO Kolaka Trans (Kendari - Kolaka)', 'PO Kolaka Trans', 'Ekonomi', 'Kendari', 'Kolaka', 40, 150, 'active', '2025-07-08 04:30:20'),
(20, 'PO Buton (Kendari - Batauga)', 'PO Buton', 'Bisnis', 'Kendari', 'Batauga', 35, 280, 'active', '2025-07-08 04:30:20'),
(21, 'PO Wakatobi (Kendari - Wangi-Wangi)', 'PO Wakatobi', 'Eksekutif', 'Kendari', 'Wangi-Wangi', 30, 320, 'active', '2025-07-08 04:30:20'),
(22, 'PO Trans Sulawesi (Makassar - Gorontalo)', 'PO Trans Sulawesi', 'Super Eksekutif', 'Makassar', 'Gorontalo', 30, 850, 'active', '2025-07-08 04:30:20'),
(23, 'PO Lintas Pulau (Palu - Manado)', 'PO Lintas Pulau', 'Eksekutif', 'Palu', 'Manado', 32, 620, 'active', '2025-07-08 04:30:20'),
(24, 'PO Nusantara Express (Kendari - Manado)', 'PO Nusantara Express', 'Super Eksekutif', 'Kendari', 'Manado', 28, 900, 'active', '2025-07-08 04:30:20'),
(25, 'Litha & Co (Makassar - Mamuju)', 'Litha & Co', 'Eksekutif', 'Makassar', 'Mamuju', 40, 230, 'active', '2025-07-08 04:30:20'),
(26, 'Litha & Co (Makassar - Palopo)', 'Litha & Co', 'Bisnis', 'Makassar', 'Palopo', 45, 348, 'active', '2025-07-08 04:30:20'),
(27, 'Litha & Co (Makassar - Tana Toraja)', 'Litha & Co', 'Super Eksekutif', 'Makassar', 'Tana Toraja', 35, 328, 'active', '2025-07-08 04:30:20'),
(28, 'Litha & Co (Makassar - Majene)', 'Litha & Co', 'Bisnis', 'Makassar', 'Majene', 42, 285, 'active', '2025-07-08 04:30:20'),
(29, 'Litha & Co (Makassar - Parepare)', 'Litha & Co', 'Ekonomi', 'Makassar', 'Parepare', 50, 155, 'active', '2025-07-08 04:30:20'),
(30, 'Litha & Co (Makassar - Kendari)', 'Litha & Co', 'Eksekutif', 'Makassar', 'Kendari', 38, 565, 'active', '2025-07-08 04:30:20'),
(31, 'Bintang Prima (Makassar - Mamuju)', 'Bintang Prima', 'Bisnis', 'Makassar', 'Mamuju', 40, 230, 'active', '2025-07-08 04:30:20'),
(32, 'Bintang Prima (Makassar - Toraja)', 'Bintang Prima', 'Eksekutif', 'Makassar', 'Toraja', 35, 328, 'active', '2025-07-08 04:30:20'),
(33, 'Bintang Prima (Makassar - Majene)', 'Bintang Prima', 'Ekonomi', 'Makassar', 'Majene', 45, 285, 'active', '2025-07-08 04:30:20'),
(34, 'Bintang Prima (Makassar - Topoyo)', 'Bintang Prima', 'Bisnis', 'Makassar', 'Topoyo', 38, 315, 'active', '2025-07-08 04:30:20'),
(35, 'Bintang Prima (Palopo - Makassar)', 'Bintang Prima', 'Bisnis', 'Palopo', 'Makassar', 42, 348, 'active', '2025-07-08 04:30:20'),
(36, 'Bintang Prima (Toraja - Makassar)', 'Bintang Prima', 'Eksekutif', 'Toraja', 'Makassar', 35, 328, 'active', '2025-07-08 04:30:20'),
(37, 'Borlindo (Makassar - Morowali)', 'Borlindo Mandiri Jaya', 'Super Eksekutif', 'Makassar', 'Morowali', 30, 750, 'active', '2025-07-08 04:30:20'),
(38, 'Borlindo (Makassar - Palu)', 'Borlindo Mandiri Jaya', 'Super Eksekutif', 'Makassar', 'Palu', 32, 420, 'active', '2025-07-08 04:30:20'),
(39, 'Borlindo (Makassar - Tana Toraja)', 'Borlindo Mandiri Jaya', 'Super Eksekutif', 'Makassar', 'Tana Toraja', 28, 328, 'active', '2025-07-08 04:30:20'),
(40, 'Borlindo (Makassar - Mamuju)', 'Borlindo Mandiri Jaya', 'Eksekutif', 'Makassar', 'Mamuju', 35, 230, 'active', '2025-07-08 04:30:20'),
(41, 'Borlindo (Makassar - Palopo)', 'Borlindo Mandiri Jaya', 'Eksekutif', 'Makassar', 'Palopo', 38, 348, 'active', '2025-07-08 04:30:20'),
(42, 'Borlindo (Makassar - Poso)', 'Borlindo Mandiri Jaya', 'Super Eksekutif', 'Makassar', 'Poso', 30, 638, 'active', '2025-07-08 04:30:20'),
(43, 'Bintang Timur (Makassar - Luwu Timur)', 'Bintang Timur', 'Bisnis', 'Makassar', 'Luwu Timur', 40, 420, 'active', '2025-07-08 04:30:20'),
(44, 'Bintang Timur (Makassar - Wawondula)', 'Bintang Timur', 'Eksekutif', 'Makassar', 'Wawondula', 35, 385, 'active', '2025-07-08 04:30:20'),
(45, 'Bintang Timur (Makassar - Masamba)', 'Bintang Timur', 'Bisnis', 'Makassar', 'Masamba', 42, 395, 'active', '2025-07-08 04:30:20'),
(46, 'Primadona (Makassar - Toraja)', 'Primadona', 'Eksekutif', 'Makassar', 'Toraja', 35, 328, 'active', '2025-07-08 04:30:20'),
(47, 'Primadona (Makassar - Palopo)', 'Primadona', 'Bisnis', 'Makassar', 'Palopo', 40, 348, 'active', '2025-07-08 04:30:20'),
(48, 'Metro Permai (Makassar - Tana Toraja)', 'Metro Permai', 'Bisnis', 'Makassar', 'Tana Toraja', 38, 328, 'active', '2025-07-08 04:30:20'),
(49, 'Litha & Co (Mamuju - Makassar)', 'Litha & Co', 'Eksekutif', 'Mamuju', 'Makassar', 40, 230, 'active', '2025-07-08 04:30:20'),
(50, 'Litha & Co (Palopo - Makassar)', 'Litha & Co', 'Bisnis', 'Palopo', 'Makassar', 45, 348, 'active', '2025-07-08 04:30:20'),
(51, 'Litha & Co (Tana Toraja - Makassar)', 'Litha & Co', 'Super Eksekutif', 'Tana Toraja', 'Makassar', 35, 328, 'active', '2025-07-08 04:30:20'),
(52, 'Bintang Prima (Mamuju - Makassar)', 'Bintang Prima', 'Bisnis', 'Mamuju', 'Makassar', 40, 230, 'active', '2025-07-08 04:30:20'),
(53, 'Borlindo (Palu - Makassar)', 'Borlindo Mandiri Jaya', 'Super Eksekutif', 'Palu', 'Makassar', 32, 420, 'active', '2025-07-08 04:30:20'),
(54, 'Borlindo (Morowali - Makassar)', 'Borlindo Mandiri Jaya', 'Super Eksekutif', 'Morowali', 'Makassar', 30, 750, 'active', '2025-07-08 04:30:20'),
(55, 'PO Panorama (Makassar - Parepare)', 'PO Panorama', 'Ekonomi', 'Makassar', 'Parepare', 50, 155, 'active', '2025-07-08 04:47:20'),
(56, 'PO Sejahtera (Parepare - Makassar)', 'PO Sejahtera', 'Bisnis', 'Parepare', 'Makassar', 40, 155, 'active', '2025-07-08 04:47:20'),
(57, 'PO Mandala (Bone - Makassar)', 'PO Mandala', 'Ekonomi', 'Bone', 'Makassar', 45, 174, 'active', '2025-07-08 04:47:20'),
(58, 'PO Citra (Makassar - Bone)', 'PO Citra', 'Bisnis', 'Makassar', 'Bone', 38, 174, 'active', '2025-07-08 04:47:20'),
(59, 'PO Lokal Express (Makassar - Bulukumba)', 'PO Lokal Express', 'Ekonomi', 'Makassar', 'Bulukumba', 42, 153, 'active', '2025-07-08 04:47:20'),
(60, 'PO Tana Toraja (Makassar - Rantepao)', 'PO Tana Toraja', 'Bisnis', 'Makassar', 'Rantepao', 35, 328, 'active', '2025-07-08 04:47:20'),
(61, 'PO Bantaeng Indah (Makassar - Bantaeng)', 'PO Bantaeng Indah', 'Ekonomi', 'Makassar', 'Bantaeng', 48, 125, 'active', '2025-07-08 04:47:20'),
(62, 'PO Palu Raya (Palu - Donggala)', 'PO Palu Raya', 'Ekonomi', 'Palu', 'Donggala', 40, 27, 'active', '2025-07-08 04:47:20'),
(63, 'PO Sulteng (Palu - Poso)', 'PO Sulteng', 'Bisnis', 'Palu', 'Poso', 35, 218, 'active', '2025-07-08 04:47:20'),
(64, 'PO Morowali (Palu - Bungku)', 'PO Morowali', 'Ekonomi', 'Palu', 'Bungku', 38, 350, 'active', '2025-07-08 04:47:20'),
(65, 'PO Minahasa (Manado - Tomohon)', 'PO Minahasa', 'Ekonomi', 'Manado', 'Tomohon', 30, 25, 'active', '2025-07-08 04:47:20'),
(66, 'PO Bitung Express (Manado - Bitung)', 'PO Bitung Express', 'Bisnis', 'Manado', 'Bitung', 32, 45, 'active', '2025-07-08 04:47:20'),
(67, 'PO Sangihe (Manado - Tahuna)', 'PO Sangihe', 'Eksekutif', 'Manado', 'Tahuna', 28, 260, 'active', '2025-07-08 04:47:20'),
(68, 'PO Kolaka Trans (Kendari - Kolaka)', 'PO Kolaka Trans', 'Ekonomi', 'Kendari', 'Kolaka', 40, 150, 'active', '2025-07-08 04:47:20'),
(69, 'PO Buton (Kendari - Batauga)', 'PO Buton', 'Bisnis', 'Kendari', 'Batauga', 35, 280, 'active', '2025-07-08 04:47:20'),
(70, 'PO Wakatobi (Kendari - Wangi-Wangi)', 'PO Wakatobi', 'Eksekutif', 'Kendari', 'Wangi-Wangi', 30, 320, 'active', '2025-07-08 04:47:20'),
(71, 'PO Trans Sulawesi (Makassar - Gorontalo)', 'PO Trans Sulawesi', 'Super Eksekutif', 'Makassar', 'Gorontalo', 30, 850, 'active', '2025-07-08 04:47:20'),
(72, 'PO Lintas Pulau (Palu - Manado)', 'PO Lintas Pulau', 'Eksekutif', 'Palu', 'Manado', 32, 620, 'active', '2025-07-08 04:47:20'),
(73, 'PO Nusantara Express (Kendari - Manado)', 'PO Nusantara Express', 'Super Eksekutif', 'Kendari', 'Manado', 28, 900, 'active', '2025-07-08 04:47:20'),
(74, 'Litha & Co (Makassar - Mamuju)', 'Litha & Co', 'Eksekutif', 'Makassar', 'Mamuju', 40, 230, 'active', '2025-07-08 04:47:20'),
(75, 'Litha & Co (Makassar - Palopo)', 'Litha & Co', 'Bisnis', 'Makassar', 'Palopo', 45, 348, 'active', '2025-07-08 04:47:20'),
(76, 'Litha & Co (Makassar - Tana Toraja)', 'Litha & Co', 'Super Eksekutif', 'Makassar', 'Tana Toraja', 35, 328, 'active', '2025-07-08 04:47:20'),
(77, 'Litha & Co (Makassar - Majene)', 'Litha & Co', 'Bisnis', 'Makassar', 'Majene', 42, 285, 'active', '2025-07-08 04:47:20'),
(78, 'Litha & Co (Makassar - Parepare)', 'Litha & Co', 'Ekonomi', 'Makassar', 'Parepare', 50, 155, 'active', '2025-07-08 04:47:20'),
(79, 'Litha & Co (Makassar - Kendari)', 'Litha & Co', 'Eksekutif', 'Makassar', 'Kendari', 38, 565, 'active', '2025-07-08 04:47:20'),
(80, 'Bintang Prima (Makassar - Mamuju)', 'Bintang Prima', 'Bisnis', 'Makassar', 'Mamuju', 40, 230, 'active', '2025-07-08 04:47:20'),
(81, 'Bintang Prima (Makassar - Toraja)', 'Bintang Prima', 'Eksekutif', 'Makassar', 'Toraja', 35, 328, 'active', '2025-07-08 04:47:20'),
(82, 'Bintang Prima (Makassar - Majene)', 'Bintang Prima', 'Ekonomi', 'Makassar', 'Majene', 45, 285, 'active', '2025-07-08 04:47:20'),
(83, 'Bintang Prima (Makassar - Topoyo)', 'Bintang Prima', 'Bisnis', 'Makassar', 'Topoyo', 38, 315, 'active', '2025-07-08 04:47:20'),
(84, 'Bintang Prima (Palopo - Makassar)', 'Bintang Prima', 'Bisnis', 'Palopo', 'Makassar', 42, 348, 'active', '2025-07-08 04:47:20'),
(85, 'Bintang Prima (Toraja - Makassar)', 'Bintang Prima', 'Eksekutif', 'Toraja', 'Makassar', 35, 328, 'active', '2025-07-08 04:47:20'),
(86, 'Borlindo (Makassar - Morowali)', 'Borlindo Mandiri Jaya', 'Super Eksekutif', 'Makassar', 'Morowali', 30, 750, 'active', '2025-07-08 04:47:20'),
(87, 'Borlindo (Makassar - Palu)', 'Borlindo Mandiri Jaya', 'Super Eksekutif', 'Makassar', 'Palu', 32, 420, 'active', '2025-07-08 04:47:20'),
(88, 'Borlindo (Makassar - Tana Toraja)', 'Borlindo Mandiri Jaya', 'Super Eksekutif', 'Makassar', 'Tana Toraja', 28, 328, 'active', '2025-07-08 04:47:20'),
(89, 'Borlindo (Makassar - Mamuju)', 'Borlindo Mandiri Jaya', 'Eksekutif', 'Makassar', 'Mamuju', 35, 230, 'active', '2025-07-08 04:47:20'),
(90, 'Borlindo (Makassar - Palopo)', 'Borlindo Mandiri Jaya', 'Eksekutif', 'Makassar', 'Palopo', 38, 348, 'active', '2025-07-08 04:47:20'),
(91, 'Borlindo (Makassar - Poso)', 'Borlindo Mandiri Jaya', 'Super Eksekutif', 'Makassar', 'Poso', 30, 638, 'active', '2025-07-08 04:47:20'),
(92, 'Bintang Timur (Makassar - Luwu Timur)', 'Bintang Timur', 'Bisnis', 'Makassar', 'Luwu Timur', 40, 420, 'active', '2025-07-08 04:47:20'),
(93, 'Bintang Timur (Makassar - Wawondula)', 'Bintang Timur', 'Eksekutif', 'Makassar', 'Wawondula', 35, 385, 'active', '2025-07-08 04:47:20'),
(94, 'Bintang Timur (Makassar - Masamba)', 'Bintang Timur', 'Bisnis', 'Makassar', 'Masamba', 42, 395, 'active', '2025-07-08 04:47:20'),
(95, 'Primadona (Makassar - Toraja)', 'Primadona', 'Eksekutif', 'Makassar', 'Toraja', 35, 328, 'active', '2025-07-08 04:47:20'),
(96, 'Primadona (Makassar - Palopo)', 'Primadona', 'Bisnis', 'Makassar', 'Palopo', 40, 348, 'active', '2025-07-08 04:47:20'),
(97, 'Metro Permai (Makassar - Tana Toraja)', 'Metro Permai', 'Bisnis', 'Makassar', 'Tana Toraja', 38, 328, 'active', '2025-07-08 04:47:20'),
(98, 'Litha & Co (Mamuju - Makassar)', 'Litha & Co', 'Eksekutif', 'Mamuju', 'Makassar', 40, 230, 'active', '2025-07-08 04:47:20'),
(99, 'Litha & Co (Palopo - Makassar)', 'Litha & Co', 'Bisnis', 'Palopo', 'Makassar', 45, 348, 'active', '2025-07-08 04:47:20'),
(100, 'Litha & Co (Tana Toraja - Makassar)', 'Litha & Co', 'Super Eksekutif', 'Tana Toraja', 'Makassar', 35, 328, 'active', '2025-07-08 04:47:20'),
(101, 'Bintang Prima (Mamuju - Makassar)', 'Bintang Prima', 'Bisnis', 'Mamuju', 'Makassar', 40, 230, 'active', '2025-07-08 04:47:20'),
(102, 'Borlindo (Palu - Makassar)', 'Borlindo Mandiri Jaya', 'Super Eksekutif', 'Palu', 'Makassar', 32, 420, 'active', '2025-07-08 04:47:20'),
(103, 'Borlindo (Morowali - Makassar)', 'Borlindo Mandiri Jaya', 'Super Eksekutif', 'Morowali', 'Makassar', 30, 750, 'active', '2025-07-08 04:47:20');

-- --------------------------------------------------------

--
-- Table structure for table `tiket`
--

CREATE TABLE `tiket` (
  `id_tiket` int NOT NULL,
  `id_pemesanan` int NOT NULL,
  `id_jadwal` int NOT NULL,
  `kode_booking` varchar(20) NOT NULL,
  `nomor_kursi` varchar(5) NOT NULL,
  `status` enum('available','booked','used') NOT NULL DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `role` enum('pelanggan','admin','petugas_loket') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `email`, `no_telp`, `role`, `created_at`) VALUES
(2, 'reza123', '$2y$10$4/Nus7/e/DQv0wtfCRnKluFFdFNFGfte/7mWy0wi4t6WlRQ0XypnW', 'mynameisreza07@gmail.com', '08991754764', 'pelanggan', '2025-07-07 10:16:59'),
(3, 'admin', '$2y$10$hTtLBw/xKomv7gL29muKAuSLMGHKjgiQC5bvMP7Rkc80ueWV9I0tW', 'admin@travel.com', '081234567890', 'admin', '2025-07-07 11:31:45'),
(4, 'petugas1', '$2y$10$MRV2D4huVP079VRCECnNrOyJvq0K8EEKS9nACARFXhZgMkUwKgcpG', 'petugas1@travel.com', '081234567891', 'petugas_loket', '2025-07-07 11:31:45'),
(5, 'petugas2', '$2y$10$7NT8sJ27tsYNSuSziKNtB.aM3N6d47KRbTv6dA9AKNEm0fssbQXfu', 'petugas2@travel.com', '081234567892', 'petugas_loket', '2025-07-07 11:31:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bus`
--
ALTER TABLE `bus`
  ADD PRIMARY KEY (`id_bus`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_rute` (`id_rute`);

--
-- Indexes for table `jadwal_backup`
--
ALTER TABLE `jadwal_backup`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `fk_jadwal_rute` (`id_rute`);

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id_laporan`);

--
-- Indexes for table `pelanggan_details`
--
ALTER TABLE `pelanggan_details`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD UNIQUE KEY `id_pemesanan` (`id_pemesanan`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id_pemesanan`),
  ADD KEY `id_pelanggan` (`id_pelanggan`);

--
-- Indexes for table `rute`
--
ALTER TABLE `rute`
  ADD PRIMARY KEY (`id_rute`),
  ADD KEY `idx_rute_kota` (`kota_asal`,`kota_tujuan`),
  ADD KEY `idx_rute_po` (`nama_po`),
  ADD KEY `idx_rute_status` (`status`);

--
-- Indexes for table `tiket`
--
ALTER TABLE `tiket`
  ADD PRIMARY KEY (`id_tiket`),
  ADD UNIQUE KEY `kode_booking` (`kode_booking`),
  ADD KEY `id_pemesanan` (`id_pemesanan`),
  ADD KEY `id_jadwal` (`id_jadwal`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bus`
--
ALTER TABLE `bus`
  MODIFY `id_bus` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id_jadwal` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jadwal_backup`
--
ALTER TABLE `jadwal_backup`
  MODIFY `id_jadwal` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id_laporan` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id_pemesanan` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rute`
--
ALTER TABLE `rute`
  MODIFY `id_rute` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `tiket`
--
ALTER TABLE `tiket`
  MODIFY `id_tiket` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`id_rute`) REFERENCES `rute` (`id_rute`) ON DELETE CASCADE;

--
-- Constraints for table `jadwal_backup`
--
ALTER TABLE `jadwal_backup`
  ADD CONSTRAINT `fk_jadwal_rute` FOREIGN KEY (`id_rute`) REFERENCES `rute` (`id_rute`) ON DELETE CASCADE;

--
-- Constraints for table `pelanggan_details`
--
ALTER TABLE `pelanggan_details`
  ADD CONSTRAINT `pelanggan_details_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`) ON DELETE CASCADE;

--
-- Constraints for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `tiket`
--
ALTER TABLE `tiket`
  ADD CONSTRAINT `tiket_ibfk_1` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`) ON DELETE CASCADE,
  ADD CONSTRAINT `tiket_ibfk_2` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal_backup` (`id_jadwal`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
