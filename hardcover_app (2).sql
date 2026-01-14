-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 14, 2026 at 02:50 PM
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
  `jumlah_halaman` int(11) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `catatan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dokumen`
--

INSERT INTO `dokumen` (`id`, `mahasiswa_id`, `file_path`, `judul`, `jumlah_halaman`, `uploaded_at`, `catatan`) VALUES
(9, 23, '23_1767711034.pdf', 'Analisis Pengaruh Kualitas Produk, Layanan dan Promosi terhadap Keputusan Pembelian', 70, '2026-01-06 14:50:35', ''),
(10, 24, '24_1767766153.pdf', 'Analisis Pengaruh Kualitas Produk, Layanan dan Promosi terhadap Keputusan Pembelian', 75, '2026-01-07 06:09:13', ''),
(11, 25, '25_1767766689.pdf', 'Keterikatan Pameran terhadap Motivasi, Kepuasan, dan Loyalitas Pengunjung pada Artjog', 78, '2026-01-07 06:18:09', ''),
(12, 26, '26_1767773578.pdf', 'dua keputusan', 71, '2026-01-07 08:12:58', ''),
(13, 27, '27_1767939934.pdf', 'peran guru', 77, '2026-01-09 06:25:34', ''),
(14, 28, '28_1767958242.pdf', 'Website Penilaian Kinerja', 84, '2026-01-09 11:30:42', ''),
(15, 29, '29_1768062604.pdf', 'investasi saham', 80, '2026-01-10 16:30:04', ''),
(16, 30, '30_1768066213.pdf', 'Investasi Jangka Panjang', 83, '2026-01-10 17:30:13', 'perhatikan gambar yang dilandscape terima kasih'),
(17, 31, '31_1768105062.pdf', 'Perubahan Hukum', 78, '2026-01-11 04:17:42', ''),
(18, 33, '33_1768177974.pdf', 'Aplikasi Pengelolaan Hardcover', 96, '2026-01-12 00:32:54', ''),
(19, 34, '34_1768180198.pdf', 'Aplikasi Kerja Praktik', 97, '2026-01-12 01:09:58', ''),
(20, 36, '36_1768397890.pdf', 'Aplikasi Sehub', 90, '2026-01-14 13:38:10', '');

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
  `jenis_laporan` enum('KP','SKRIPSI') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `fakultas_id` int(11) DEFAULT NULL,
  `status_registrasi` enum('PENDING','VERIFIED') DEFAULT 'PENDING'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `nim`, `nama`, `prodi`, `no_wa`, `jenis_laporan`, `created_at`, `fakultas_id`, `status_registrasi`) VALUES
(23, '22011001', 'John Doe', 'Teknik Elektro', '0812345678910', 'KP', '2026-01-06 14:49:40', 7, 'PENDING'),
(24, '22011002', 'Park Chanyeol', 'Agribisnis', '0812345678910', 'KP', '2026-01-07 06:06:57', 1, 'PENDING'),
(25, '22013170', 'Agnes Monica', 'Hospitality dan Pariwisata', '0812345678911', 'SKRIPSI', '2026-01-07 06:12:28', 4, 'PENDING'),
(26, '22013171', 'John Doe', 'Teknik Elektro', '0812345678911', 'KP', '2026-01-07 08:09:59', 7, 'PENDING'),
(27, '22013173', 'jane', 'Pendidikan Guru Sekolah Dasar', '0812345678912', 'KP', '2026-01-09 06:08:13', 6, 'PENDING'),
(28, '22013050', 'Jeni', 'Teknik Informatika', '0812345678900', 'KP', '2026-01-09 10:48:30', 7, 'PENDING'),
(29, '21003004', 'wijaya dodo', 'Akuntansi', '0812345678900', 'SKRIPSI', '2026-01-10 14:51:34', 2, 'PENDING'),
(30, '22001001', 'Andi', 'Manajemen', '0812345678901', 'KP', '2026-01-10 17:26:25', 2, 'PENDING'),
(31, '21005005', 'Nina Marina', 'Ilmu Hukum', '0812345678913', 'KP', '2026-01-11 03:58:25', 5, 'PENDING'),
(32, '21005006', 'Gracia', 'Ilmu Hukum', '0812345678914', 'SKRIPSI', '2026-01-11 04:33:36', 5, 'PENDING'),
(33, '22013017', 'Janehfers Mandagi', 'Teknik Informatika', '0812345678914', 'KP', '2026-01-12 00:30:36', 7, 'PENDING'),
(34, '22013022', 'Monica Pandeiroth', 'Teknik Informatika', '0812345678915', 'KP', '2026-01-12 01:00:25', 7, 'PENDING'),
(35, '22013002', 'Melia Kuntono', 'Teknik Informatika', '0812345678987', 'KP', '2026-01-14 13:03:25', 7, 'PENDING'),
(36, '22013006', 'Mutiara Pandejlaki', 'Teknik Informatika', '0812345678916', 'KP', '2026-01-14 13:36:10', 7, 'PENDING');

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
(29, 23, 'Pesanan #10 berhasil disubmit! Silakan tunggu proses fotokopi.', '2026-01-06 14:50:35'),
(30, 24, 'Pendaftaran berhasil! Data Anda sedang menunggu verifikasi dari Fakultas dan Keuangan.', '2026-01-07 06:06:57'),
(31, 24, 'Validasi lengkap. Silakan melakukan pemesanan hardcover.', '2026-01-07 06:08:29'),
(32, 24, 'Pesanan #11 berhasil disubmit! Silakan tunggu proses fotokopi.', '2026-01-07 06:09:13'),
(33, 25, 'Pendaftaran berhasil! Data Anda sedang menunggu verifikasi dari Fakultas dan Keuangan.', '2026-01-07 06:12:28'),
(34, 25, 'Validasi lengkap. Silakan melakukan pemesanan hardcover.', '2026-01-07 06:14:02'),
(35, 25, 'Pesanan #12 berhasil disubmit! Silakan tunggu proses fotokopi.', '2026-01-07 06:18:09'),
(36, 26, 'Pendaftaran berhasil! Data Anda sedang menunggu verifikasi dari Fakultas dan Keuangan.', '2026-01-07 08:10:00'),
(37, 26, 'Validasi lengkap. Silakan melakukan pemesanan hardcover.', '2026-01-07 08:11:18'),
(38, 26, 'Pesanan #13 berhasil disubmit! Silakan tunggu proses fotokopi.', '2026-01-07 08:12:58'),
(39, 27, 'Pendaftaran berhasil! Data Anda sedang menunggu verifikasi dari Fakultas dan Keuangan.', '2026-01-09 06:08:13'),
(40, 27, 'Validasi lengkap. Silakan melakukan pemesanan hardcover.', '2026-01-09 06:14:29'),
(41, 27, 'Pesanan #14 berhasil disubmit! Silakan tunggu proses fotokopi.', '2026-01-09 06:25:34'),
(42, 28, 'Pendaftaran berhasil! Data Anda sedang menunggu verifikasi dari Fakultas dan Keuangan.', '2026-01-09 10:48:30'),
(43, 28, 'Validasi lengkap. Silakan melakukan pemesanan hardcover.', '2026-01-09 10:49:38'),
(44, 28, 'Pesanan #15 berhasil disubmit! Silakan tunggu proses fotokopi.', '2026-01-09 11:30:42'),
(45, 29, 'Pendaftaran berhasil! Data Anda sedang menunggu verifikasi dari Fakultas dan Keuangan.', '2026-01-10 14:51:34'),
(46, 29, 'Validasi lengkap. Silakan melakukan pemesanan hardcover.', '2026-01-10 16:25:50'),
(47, 29, 'Pesanan #16 berhasil disubmit! Silakan tunggu proses fotokopi.', '2026-01-10 16:30:04'),
(48, 30, 'Pendaftaran berhasil! Data Anda sedang menunggu verifikasi dari Fakultas dan Keuangan.', '2026-01-10 17:26:25'),
(49, 30, 'Validasi lengkap. Silakan melakukan pemesanan hardcover.', '2026-01-10 17:27:18'),
(50, 30, 'Pesanan #17 berhasil disubmit! Silakan tunggu proses fotokopi.', '2026-01-10 17:30:13'),
(51, 31, 'Pendaftaran berhasil! Data Anda sedang menunggu verifikasi dari Fakultas dan Keuangan.', '2026-01-11 03:58:25'),
(52, 31, 'Validasi lengkap. Silakan melakukan pemesanan hardcover.', '2026-01-11 04:06:20'),
(53, 31, 'Pesanan #18 berhasil disubmit! Silakan tunggu proses fotokopi.', '2026-01-11 04:17:42'),
(54, 32, 'Pendaftaran berhasil! Data Anda sedang menunggu verifikasi dari Fakultas dan Keuangan.', '2026-01-11 04:33:36'),
(55, 33, 'Pendaftaran berhasil! Data Anda sedang menunggu verifikasi dari Fakultas dan Keuangan.', '2026-01-12 00:30:36'),
(56, 33, 'Validasi lengkap. Silakan melakukan pemesanan hardcover.', '2026-01-12 00:31:56'),
(57, 33, 'Pesanan #19 berhasil disubmit! Silakan tunggu proses fotokopi.', '2026-01-12 00:32:54'),
(58, 34, 'Pendaftaran berhasil! Data Anda sedang menunggu verifikasi dari Fakultas dan Keuangan.', '2026-01-12 01:00:25'),
(59, 34, 'Validasi lengkap. Silakan melakukan pemesanan hardcover.', '2026-01-12 01:05:35'),
(60, 34, 'Pesanan #20 berhasil disubmit! Silakan tunggu proses fotokopi.', '2026-01-12 01:09:58'),
(61, 32, 'Validasi lengkap. Silakan melakukan pemesanan hardcover.', '2026-01-14 12:59:54'),
(62, 35, 'Pendaftaran berhasil! Data Anda sedang menunggu verifikasi dari Fakultas dan Keuangan.', '2026-01-14 13:03:25'),
(63, 36, 'Pendaftaran berhasil! Data Anda sedang menunggu verifikasi dari Fakultas dan Keuangan.', '2026-01-14 13:36:10'),
(64, 36, 'Validasi lengkap. Silakan melakukan pemesanan hardcover.', '2026-01-14 13:37:18'),
(65, 36, 'Pesanan #21 berhasil disubmit! Silakan tunggu proses fotokopi.', '2026-01-14 13:38:10');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `mahasiswa_id` int(11) NOT NULL,
  `tanggal_order` date NOT NULL,
  `tanggal_selesai` datetime DEFAULT NULL,
  `status` enum('MENUNGGU_PROSES','MENUNGGU_VALIDASI','DIPROSES_FOTOKOPI','SELESAI','SUDAH_DIAMBIL') NOT NULL,
  `semester` varchar(20) DEFAULT NULL,
  `tahun_ajaran` varchar(20) DEFAULT NULL,
  `estimasi_selesai` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `mahasiswa_id`, `tanggal_order`, `tanggal_selesai`, `status`, `semester`, `tahun_ajaran`, `estimasi_selesai`, `created_at`) VALUES
(10, 23, '2026-01-06', NULL, 'DIPROSES_FOTOKOPI', NULL, NULL, NULL, '2026-01-06 14:50:34'),
(11, 24, '2026-01-07', NULL, 'SELESAI', NULL, NULL, NULL, '2026-01-07 06:09:13'),
(12, 25, '2026-01-07', NULL, 'DIPROSES_FOTOKOPI', NULL, NULL, NULL, '2026-01-07 06:18:09'),
(13, 26, '2026-01-07', NULL, 'MENUNGGU_PROSES', NULL, NULL, NULL, '2026-01-07 08:12:58'),
(14, 27, '2026-01-09', NULL, 'SELESAI', NULL, NULL, NULL, '2026-01-09 06:25:34'),
(15, 28, '2026-01-09', NULL, 'MENUNGGU_PROSES', NULL, NULL, NULL, '2026-01-09 11:30:42'),
(16, 29, '2026-01-11', NULL, 'MENUNGGU_PROSES', NULL, NULL, NULL, '2026-01-10 16:30:04'),
(17, 30, '2026-01-11', NULL, 'MENUNGGU_PROSES', NULL, NULL, NULL, '2026-01-10 17:30:13'),
(18, 31, '2026-01-11', NULL, 'DIPROSES_FOTOKOPI', NULL, NULL, NULL, '2026-01-11 04:17:42'),
(19, 33, '2026-01-12', NULL, 'DIPROSES_FOTOKOPI', NULL, NULL, NULL, '2026-01-12 00:32:54'),
(20, 34, '2026-01-12', NULL, 'MENUNGGU_PROSES', NULL, NULL, NULL, '2026-01-12 01:09:58'),
(21, 36, '2026-01-14', NULL, 'MENUNGGU_PROSES', 'Genap', '2025/2026', NULL, '2026-01-14 13:38:10');

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
(25, 23, 1, 1, '2026-01-06 14:50:07'),
(26, 24, 1, 1, '2026-01-07 06:08:29'),
(27, 25, 1, 1, '2026-01-07 06:14:02'),
(28, 26, 1, 1, '2026-01-07 08:11:18'),
(29, 27, 1, 1, '2026-01-09 06:14:29'),
(30, 28, 1, 1, '2026-01-09 10:49:38'),
(31, 29, 1, 1, '2026-01-10 16:25:50'),
(32, 30, 1, 1, '2026-01-10 17:27:18'),
(33, 31, 1, 1, '2026-01-11 04:06:20'),
(34, 32, 1, 1, '2026-01-14 12:59:54'),
(35, 33, 1, 1, '2026-01-12 00:31:56'),
(36, 34, 1, 1, '2026-01-12 01:05:35'),
(37, 35, 0, 0, '2026-01-14 13:03:25'),
(38, 36, 1, 1, '2026-01-14 13:37:18');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `fakultas`
--
ALTER TABLE `fakultas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

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
