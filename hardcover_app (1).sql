-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2026 at 04:23 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hardcover_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `dokumen`
--

CREATE TABLE `dokumen` (
  `id` int(11) NOT NULL,
  `mahasiswa_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `catatan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dokumen`
--

INSERT INTO `dokumen` (`id`, `mahasiswa_id`, `file_path`, `judul`, `uploaded_at`, `catatan`) VALUES
(9, 23, '23_1767711034.pdf', 'Analisis Pengaruh Kualitas Produk, Layanan dan Promosi terhadap Keputusan Pembelian', '2026-01-06 14:50:35', '');

-- --------------------------------------------------------

--
-- Table structure for table `fakultas`
--

CREATE TABLE `fakultas` (
  `id` int(11) NOT NULL,
  `kode` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fakultas`
--

INSERT INTO `fakultas` (`id`, `kode`, `nama`) VALUES
(1, 'pertanian', 'Fakultas Pertanian'),
(2, 'ekonomi', 'Fakultas Ekonomi dan Bisnis'),
(3, 'keperawatan', 'Fakultas Keperawatan'),
(4, 'pariwisata', 'Fakultas Pariwisata'),
(5, 'hukum', 'Fakultas Hukum'),
(6, 'pendidikan', 'Fakultas Ilmu Pendidikan'),
(7, 'teknik', 'Fakultas Teknik');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` int(11) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `prodi` varchar(100) NOT NULL,
  `no_wa` varchar(20) DEFAULT NULL,
  `jenis_laporan` enum('KP','Skripsi') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `fakultas_id` int(11) DEFAULT NULL,
  `status_registrasi` enum('PENDING','VERIFIED') DEFAULT 'PENDING'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `nim`, `nama`, `prodi`, `no_wa`, `jenis_laporan`, `created_at`, `fakultas_id`, `status_registrasi`) VALUES
(23, '22011001', 'John Doe', 'Teknik Elektro', '0812345678910', 'KP', '2026-01-06 14:49:40', 7, 'PENDING');

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL,
  `mahasiswa_id` int(11) NOT NULL,
  `pesan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifikasi`
--

INSERT INTO `notifikasi` (`id`, `mahasiswa_id`, `pesan`, `created_at`) VALUES
(27, 23, 'Pendaftaran berhasil! Data Anda sedang menunggu verifikasi dari Fakultas dan Keuangan.', '2026-01-06 14:49:40'),
(28, 23, 'Validasi lengkap. Silakan melakukan pemesanan hardcover.', '2026-01-06 14:50:07'),
(29, 23, 'Pesanan #10 berhasil disubmit! Silakan tunggu proses fotokopi.', '2026-01-06 14:50:35');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `mahasiswa_id` int(11) NOT NULL,
  `tanggal_order` date NOT NULL,
  `status` enum('MENUNGGU_VALIDASI','DIPROSES_FOTOKOPI','SELESAI','SUDAH_DIAMBIL') NOT NULL,
  `estimasi_selesai` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `mahasiswa_id`, `tanggal_order`, `status`, `estimasi_selesai`, `created_at`) VALUES
(10, 23, '2026-01-06', 'MENUNGGU_VALIDASI', NULL, '2026-01-06 14:50:34');

-- --------------------------------------------------------

--
-- Table structure for table `prodi`
--

CREATE TABLE `prodi` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `fakultas_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prodi`
--

INSERT INTO `prodi` (`id`, `nama`, `fakultas_id`) VALUES
(1, 'Agribisnis', 1),
(2, 'Akuntansi', 2),
(3, 'Manajemen', 2),
(4, 'Fisioterapi', 3),
(5, 'Ilmu Keperawatan', 3),
(6, 'Profesi Ners', 3),
(7, 'Hospitality dan Pariwisata', 4),
(8, 'Ilmu Hukum', 5),
(9, 'Pendidikan Guru Sekolah Dasar', 6),
(10, 'Teknik Elektro', 7),
(11, 'Teknik Informatika', 7),
(12, 'Teknik Industri', 7),
(13, 'Teknik Sipil', 7);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('fakultas','keuangan','yayasan','fotokopi','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `fakultas_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `role`, `created_at`, `fakultas_id`) VALUES
(1, '', 'fotokopi', '$2a$12$4IO6Pa6fwR.85c2HPjQqf.aH1w9pNS/34u8GCA2VUzd9.OcG4AmqW', 'fotokopi', '2026-01-05 12:33:34', NULL),
(3, '', 'keuangan', '$2a$12$jc.fzn/9JZC99S3j0VRGSODUI7fs3QYA.tN5P5X4corg9u/gIUapK', 'keuangan', '2026-01-05 12:33:34', NULL),
(4, '', 'yayasan', '$2a$12$6RdAhA654CPHtIkGpYcJdewRe1ZWP3RRGnBUpgn25Je/z5FGvdltK', 'yayasan', '2026-01-05 12:33:34', NULL),
(5, '', 'pertanian', '$2a$12$5jWxc0HNfGnX.SuHRtDIoua.IeHEPCdR0L92H4qsZZpTkIHxcAdbe', 'fakultas', '2026-01-06 03:39:34', 1),
(6, '', 'ekonomi', '$2a$12$QdzRO8/W1nio2Hlp5KFcAOQnuGJ54puOauz/4H/IFL9yE8nJ4wYEO', 'fakultas', '2026-01-06 03:39:34', 2),
(7, '', 'keperawatan', '$2a$12$CmJKQo8EOiPrsXT6LgqNsuysrOt3G5LMyOg.NCq0ErHIjVD.X7il.', 'fakultas', '2026-01-06 03:39:34', 3),
(8, '', 'pariwisata', '$2a$12$OmnK2r6b8P3Rj.01z24fLu6rANSLCz8mELygX.4RApqTmR2onzmku', 'fakultas', '2026-01-06 03:39:34', 4),
(9, '', 'hukum', '$2a$12$iSVAFJtudcpJqmULy58rxetKubZ9A5GE6DrAp7OSTmHD9Bc5RAOiy', 'fakultas', '2026-01-06 03:39:34', 5),
(10, '', 'pendidikan', '$2a$12$Vme69TGdiqkDCQoG8iyUoOCvUJY0DT9uZ1Bi5zJ0jDbREJBezM9Hi', 'fakultas', '2026-01-06 03:39:34', 6),
(11, '', 'teknik', '$2a$12$YYhIEN9.k//wzNySO0D4SesugsKZhU9jj5AvuZqS6mkX7bN4ELWau', 'fakultas', '2026-01-06 03:39:34', 7);

-- --------------------------------------------------------

--
-- Table structure for table `validasi`
--

CREATE TABLE `validasi` (
  `id` int(11) NOT NULL,
  `mahasiswa_id` int(11) NOT NULL,
  `valid_fakultas` tinyint(1) DEFAULT 0,
  `valid_keuangan` tinyint(1) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `validasi`
--

INSERT INTO `validasi` (`id`, `mahasiswa_id`, `valid_fakultas`, `valid_keuangan`, `updated_at`) VALUES
(25, 23, 1, 1, '2026-01-06 14:50:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dokumen`
--
ALTER TABLE `dokumen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`);

--
-- Indexes for table `fakultas`
--
ALTER TABLE `fakultas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nim` (`nim`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`);

--
-- Indexes for table `prodi`
--
ALTER TABLE `prodi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fakultas_id` (`fakultas_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `validasi`
--
ALTER TABLE `validasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dokumen`
--
ALTER TABLE `dokumen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `fakultas`
--
ALTER TABLE `fakultas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `prodi`
--
ALTER TABLE `prodi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `validasi`
--
ALTER TABLE `validasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dokumen`
--
ALTER TABLE `dokumen`
  ADD CONSTRAINT `dokumen_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`);

--
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`);

--
-- Constraints for table `prodi`
--
ALTER TABLE `prodi`
  ADD CONSTRAINT `prodi_ibfk_1` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`);

--
-- Constraints for table `validasi`
--
ALTER TABLE `validasi`
  ADD CONSTRAINT `validasi_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
