-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2022 at 06:04 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_absensi`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_siswa`
--

CREATE TABLE `data_siswa` (
  `id` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `jenis_kelamin` enum('laki-laki','perempuan') DEFAULT NULL,
  `tempat_lahir` varchar(20) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `berat_badan` int(3) DEFAULT NULL,
  `tinggi_badan` int(3) DEFAULT NULL,
  `gol_darah` enum('a','b','o','ab') DEFAULT NULL,
  `rencana_lulus` varchar(60) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `data_siswa`
--

INSERT INTO `data_siswa` (`id`, `id_users`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `berat_badan`, `tinggi_badan`, `gol_darah`, `rencana_lulus`, `created_at`, `updated_at`) VALUES
(1, 3, 'perempuan', 'ngawi', '2009-11-01', 'ngawi, jawa timur', 50, 160, 'a', NULL, '2022-11-24 03:01:19', '2022-11-24 03:01:19');

-- --------------------------------------------------------

--
-- Table structure for table `ekskul_siswa`
--

CREATE TABLE `ekskul_siswa` (
  `id` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `ekskul` varchar(45) DEFAULT NULL,
  `semester` tinyint(4) DEFAULT NULL,
  `keterangan` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hasil_belajar`
--

CREATE TABLE `hasil_belajar` (
  `id` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `tahun_ajaran` varchar(15) DEFAULT NULL,
  `semester` tinyint(4) DEFAULT NULL,
  `sikap` varchar(100) DEFAULT NULL,
  `catatan_wali` varchar(100) DEFAULT NULL,
  `tanggapan_ortu` varchar(100) DEFAULT NULL,
  `wali_kelas` varchar(45) DEFAULT NULL,
  `nip` varchar(18) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hasil_belajar`
--

INSERT INTO `hasil_belajar` (`id`, `id_users`, `tahun_ajaran`, `semester`, `sikap`, `catatan_wali`, `tanggapan_ortu`, `wali_kelas`, `nip`, `created_at`) VALUES
(1, 3, '2021/2022', 1, 'Baik', 'Ditingkatkan lagi belajarnya', NULL, 'Sri Juniarti Utami, ST', '123456789112345678', '2021-12-30 02:34:06');

-- --------------------------------------------------------

--
-- Table structure for table `jurusan`
--

CREATE TABLE `jurusan` (
  `id` int(11) NOT NULL,
  `jurusan` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jurusan`
--

INSERT INTO `jurusan` (`id`, `jurusan`) VALUES
(1, 'RPL'),
(2, 'TKJ');

-- --------------------------------------------------------

--
-- Table structure for table `kehadiran_siswa`
--

CREATE TABLE `kehadiran_siswa` (
  `id` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `id_mapel` int(11) NOT NULL,
  `semester` tinyint(4) DEFAULT NULL,
  `keterangan` enum('masuk','sakit','izin') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kehadiran_siswa`
--

INSERT INTO `kehadiran_siswa` (`id`, `id_users`, `id_mapel`, `semester`, `keterangan`, `created_at`) VALUES
(1, 3, 1, 2, 'masuk', '2022-11-29 11:59:36'),
(2, 3, 3, 2, 'masuk', '2022-11-29 12:57:42'),
(3, 3, 1, 2, 'izin', '2022-11-29 12:57:59'),
(4, 3, 2, 2, 'sakit', '2022-11-29 12:58:09');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` int(11) NOT NULL,
  `id_jurusan` int(11) NOT NULL,
  `kelas` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `id_jurusan`, `kelas`) VALUES
(1, 1, 'X RPL 1'),
(2, 1, 'X RPL 2'),
(3, 1, 'XI RPL 1'),
(4, 1, 'XI RPL 2'),
(5, 1, 'XII RPL 1'),
(6, 1, 'XII RPL 2'),
(7, 2, 'X TKJ 1'),
(8, 2, 'X TKJ 2'),
(9, 2, 'XI TKJ 1'),
(10, 2, 'XI TKJ 2'),
(11, 2, 'XII TKJ 1'),
(12, 2, 'XII TKJ 2');

-- --------------------------------------------------------

--
-- Table structure for table `mapel`
--

CREATE TABLE `mapel` (
  `id` int(11) NOT NULL,
  `id_jurusan` int(11) NOT NULL,
  `mapel` varchar(60) DEFAULT NULL,
  `kelompok` enum('a','b','c') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mapel`
--

INSERT INTO `mapel` (`id`, `id_jurusan`, `mapel`, `kelompok`) VALUES
(1, 1, 'Pendidikan Agama dan Budi Pekerti', 'a'),
(2, 2, 'Pendidikan Agama dan Budi Pekerti', 'a'),
(3, 1, 'Pemrograman Berorientasi Objek', 'c'),
(4, 1, 'Pemrograman Web dan Perangkat Bergerak', 'c'),
(5, 1, 'Pendidikan Jasmani Olahraga dan Kesehatan', 'b'),
(6, 2, 'Pendidikan Jasmani Olahraga dan Kesehatan', 'c'),
(7, 1, 'Bahasa Indonesia', 'a');

-- --------------------------------------------------------

--
-- Table structure for table `nilai_siswa`
--

CREATE TABLE `nilai_siswa` (
  `id` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `id_mapel` int(11) NOT NULL,
  `semester` tinyint(4) DEFAULT NULL,
  `pengetahuan_kb` varchar(15) DEFAULT NULL,
  `pengetahuan_angka` float DEFAULT NULL,
  `keterampilan_kb` varchar(15) DEFAULT NULL,
  `keterampilan_angka` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `nilai_siswa`
--

INSERT INTO `nilai_siswa` (`id`, `id_users`, `id_mapel`, `semester`, `pengetahuan_kb`, `pengetahuan_angka`, `keterampilan_kb`, `keterampilan_angka`) VALUES
(1, 3, 1, 2, NULL, 90, NULL, 90),
(2, 3, 3, 2, NULL, 90, NULL, 95),
(3, 3, 4, 2, NULL, 100, NULL, 100),
(4, 3, 5, 2, NULL, 80, NULL, 86),
(5, 3, 7, 2, NULL, 80, NULL, 80);

-- --------------------------------------------------------

--
-- Table structure for table `ortu_siswa`
--

CREATE TABLE `ortu_siswa` (
  `id` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `ket_data` enum('ayah','ibu','wali') DEFAULT NULL,
  `nama` varchar(45) DEFAULT NULL,
  `tempat_lahir` varchar(45) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `pendidikan` varchar(45) DEFAULT NULL,
  `pekerjaan` varchar(45) DEFAULT NULL,
  `penghasilan` varchar(15) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ortu_siswa`
--

INSERT INTO `ortu_siswa` (`id`, `id_users`, `ket_data`, `nama`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `pendidikan`, `pekerjaan`, `penghasilan`, `status`, `created_at`, `updated_at`) VALUES
(6, 3, 'ayah', 'father', 'ngawi', '1987-11-01', 'ngawi', 'SMA', 'supir', '1000000', NULL, '2022-11-24', '2022-11-24'),
(7, 3, 'ibu', 'ibu kiyo', 'ngawi', '1987-11-01', 'ngawi', 'SMA', 'ibu rumah tangga', NULL, NULL, '2022-11-24', '2022-11-24');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(45) NOT NULL,
  `otp` varchar(60) DEFAULT NULL,
  `expire_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `otp`, `expire_at`, `status`) VALUES
('kiyowolicious@gmail.com', '$2y$10$zt0m7eKikr1ColylY1SuluZf.O7OtLiukfe2PybVHa3lUBXswQ4J.', '2022-11-25 02:04:20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pendidikan_siswa`
--

CREATE TABLE `pendidikan_siswa` (
  `id` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `tingkat_pend` varchar(45) DEFAULT NULL,
  `nama_sekolah` varchar(45) DEFAULT NULL,
  `tahun_lulus` year(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pendidikan_siswa`
--

INSERT INTO `pendidikan_siswa` (`id`, `id_users`, `tingkat_pend`, `nama_sekolah`, `tahun_lulus`, `created_at`, `updated_at`) VALUES
(1, 3, 'SMP', 'SMP Negeri 2 Ngawi', 2019, '2022-11-24 04:16:15', '2022-11-24 04:16:15'),
(2, 3, 'SD', 'SDN Margomulyo 1', 2017, '2022-11-24 04:19:30', '2022-11-24 04:19:30');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
(6, 'App\\Models\\User', 3, 'API Token', '168cbe49b71b2139e0bb046940271a21eeed3243e672c62cc3a1988b09e827eb', '[\"*\"]', '2022-11-29 13:43:36', '2022-11-29 11:28:00', '2022-11-29 13:43:36');

-- --------------------------------------------------------

--
-- Table structure for table `pkl_siswa`
--

CREATE TABLE `pkl_siswa` (
  `id` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `semester` tinyint(4) DEFAULT NULL,
  `mitra` varchar(65) DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `lama_pkl` varchar(10) DEFAULT NULL,
  `keterangan` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `prestasi_siswa`
--

CREATE TABLE `prestasi_siswa` (
  `id` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `prestasi` varchar(60) DEFAULT NULL,
  `tingkat` varchar(60) DEFAULT NULL,
  `juara` varchar(45) DEFAULT NULL,
  `semester` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `id_kelas` int(11) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `nama_lengkap` varchar(45) DEFAULT NULL,
  `nama_kecil` varchar(10) DEFAULT NULL,
  `nis` bigint(18) DEFAULT NULL,
  `nisn` int(10) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `image` varchar(15) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `id_kelas`, `email`, `nama_lengkap`, `nama_kecil`, `nis`, `nisn`, `no_hp`, `image`, `password`, `created_at`, `updated_at`) VALUES
(3, 1, 'kiyowolicious@gmail.com', 'kiyowo', 'kiyo', 1234, 12345678, '081222333444', '1669265451.jpg', '$2y$10$cf2gWoVegmS.0QCCgAIYZukbPJRkD5Exz.4AZ448rABO6mWY.nGjm', '2022-11-23 16:08:06', '2022-11-25 01:53:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_siswa`
--
ALTER TABLE `data_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_data_siswa_users_idx` (`id_users`);

--
-- Indexes for table `ekskul_siswa`
--
ALTER TABLE `ekskul_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ekskul_siswa_users1_idx` (`id_users`);

--
-- Indexes for table `hasil_belajar`
--
ALTER TABLE `hasil_belajar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_hasil_belajar_users1_idx` (`id_users`);

--
-- Indexes for table `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kehadiran_siswa`
--
ALTER TABLE `kehadiran_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kehadiran_siswa_users1_idx` (`id_users`),
  ADD KEY `fk_kehadiran_siswa_mapel1_idx` (`id_mapel`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kelas_jurusan1_idx` (`id_jurusan`);

--
-- Indexes for table `mapel`
--
ALTER TABLE `mapel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_jurusan` (`id_jurusan`);

--
-- Indexes for table `nilai_siswa`
--
ALTER TABLE `nilai_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_nilai_siswa_users1_idx` (`id_users`),
  ADD KEY `fk_nilai_siswa_mapel1_idx` (`id_mapel`);

--
-- Indexes for table `ortu_siswa`
--
ALTER TABLE `ortu_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ortu_siswa_users1_idx` (`id_users`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pendidikan_siswa`
--
ALTER TABLE `pendidikan_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pendidikan_siswa_users1_idx` (`id_users`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `pkl_siswa`
--
ALTER TABLE `pkl_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pkl_siswa_users1_idx` (`id_users`);

--
-- Indexes for table `prestasi_siswa`
--
ALTER TABLE `prestasi_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_prestasi_siswa_users1_idx` (`id_users`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `nim_UNIQUE` (`nis`),
  ADD UNIQUE KEY `no_hp_UNIQUE` (`no_hp`),
  ADD UNIQUE KEY `nisn_UNIQUE` (`nisn`),
  ADD KEY `fk_users_kelas1_idx` (`id_kelas`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_siswa`
--
ALTER TABLE `data_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ekskul_siswa`
--
ALTER TABLE `ekskul_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hasil_belajar`
--
ALTER TABLE `hasil_belajar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kehadiran_siswa`
--
ALTER TABLE `kehadiran_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `mapel`
--
ALTER TABLE `mapel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `nilai_siswa`
--
ALTER TABLE `nilai_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ortu_siswa`
--
ALTER TABLE `ortu_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pendidikan_siswa`
--
ALTER TABLE `pendidikan_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pkl_siswa`
--
ALTER TABLE `pkl_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prestasi_siswa`
--
ALTER TABLE `prestasi_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `data_siswa`
--
ALTER TABLE `data_siswa`
  ADD CONSTRAINT `fk_data_siswa_users` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ekskul_siswa`
--
ALTER TABLE `ekskul_siswa`
  ADD CONSTRAINT `fk_ekskul_siswa_users1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hasil_belajar`
--
ALTER TABLE `hasil_belajar`
  ADD CONSTRAINT `fk_hasil_belajar_users1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kehadiran_siswa`
--
ALTER TABLE `kehadiran_siswa`
  ADD CONSTRAINT `fk_kehadiran_siswa_mapel1` FOREIGN KEY (`id_mapel`) REFERENCES `mapel` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kehadiran_siswa_users1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `fk_kelas_jurusan1` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mapel`
--
ALTER TABLE `mapel`
  ADD CONSTRAINT `id_jurusan` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `nilai_siswa`
--
ALTER TABLE `nilai_siswa`
  ADD CONSTRAINT `fk_nilai_siswa_users1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_mapel` FOREIGN KEY (`id_mapel`) REFERENCES `mapel` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `ortu_siswa`
--
ALTER TABLE `ortu_siswa`
  ADD CONSTRAINT `fk_ortu_siswa_users1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pendidikan_siswa`
--
ALTER TABLE `pendidikan_siswa`
  ADD CONSTRAINT `fk_pendidikan_siswa_users1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pkl_siswa`
--
ALTER TABLE `pkl_siswa`
  ADD CONSTRAINT `fk_pkl_siswa_users1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prestasi_siswa`
--
ALTER TABLE `prestasi_siswa`
  ADD CONSTRAINT `fk_prestasi_siswa_users1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `id_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
