-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2026 at 03:54 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ukom_al`
--

-- --------------------------------------------------------

--
-- Table structure for table `anggota`
--

CREATE TABLE `anggota` (
  `id_anggota` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nis` varchar(255) NOT NULL,
  `nama_anggota` varchar(255) NOT NULL,
  `kelas` enum('10','11','12') NOT NULL,
  `jurusan` enum('RPL','TKJ','MM','DKV','SIJA','AKL','OTKP') NOT NULL,
  `no_telp` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `anggota`
--

INSERT INTO `anggota` (`id_anggota`, `user_id`, `nis`, `nama_anggota`, `kelas`, `jurusan`, `no_telp`, `created_at`, `updated_at`) VALUES
(2, 3, '02', 'Alfarshya', '11', 'TKJ', '0812', '2026-04-11 01:09:43', NULL),
(3, 4, '010', 'forsho', '11', 'DKV', '0876', '2026-04-14 19:27:48', '2026-04-14 19:27:48'),
(4, 5, '090', 'rindra', '11', 'DKV', '08867', '2026-04-16 18:35:19', '2026-04-16 18:35:19'),
(5, 6, '080', 'meong', '12', 'MM', '098077', '2026-04-17 00:35:39', '2026-04-17 02:46:07');

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` bigint(20) UNSIGNED NOT NULL,
  `nama_buku` varchar(255) NOT NULL,
  `penerbit` varchar(255) NOT NULL,
  `stok` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `nama_buku`, `penerbit`, `stok`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'Goodbye Eri', 'm&c', 5, NULL, NULL, NULL),
(3, 'lookback', 'm&c', 5, NULL, NULL, NULL),
(4, 'chainsawman', 'm&c', 4, NULL, NULL, NULL),
(5, 'cara jadi kaya', 'ibban book entertain', 9, NULL, NULL, NULL),
(6, 'jojo', 'm&c', 2, NULL, NULL, '2026-04-17 02:59:25'),
(7, 'cara masak', 'ibban book entertain', 5, NULL, NULL, NULL),
(8, 'invincible', 'm&c', 1, NULL, NULL, '2026-04-17 02:12:24');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2026_01_28_124013_create_anggota_table', 1),
(5, '2026_01_29_231200_create_bukus_table', 1),
(6, '2026_01_29_232233_create_transaksis_table', 1),
(7, '2026_04_09_044409_create_posts_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` bigint(20) UNSIGNED NOT NULL,
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_buku` bigint(20) UNSIGNED NOT NULL,
  `tanggal_peminjaman` datetime NOT NULL,
  `tanggal_pengembalian` datetime NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id`, `id_buku`, `tanggal_peminjaman`, `tanggal_pengembalian`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(6, 3, 2, '2026-04-15 02:04:41', '2026-04-22 02:04:41', 'Dipinjam', '2026-04-14 19:04:41', '2026-04-14 23:06:26', NULL),
(10, 4, 4, '2026-04-15 04:23:29', '2026-04-22 04:23:29', 'Dipinjam', '2026-04-14 21:23:29', '2026-04-14 21:23:29', NULL),
(11, 3, 2, '2026-04-15 05:22:42', '2026-04-22 05:22:42', 'Dipinjam', '2026-04-14 22:22:42', '2026-04-14 22:22:42', NULL),
(12, 4, 2, '2026-04-16 07:18:16', '2026-04-23 07:18:16', 'Dipinjam', '2026-04-16 00:18:16', '2026-04-16 00:18:16', NULL),
(13, 4, 2, '2026-04-17 03:52:12', '2026-04-24 03:52:12', 'Pending', '2026-04-16 20:52:12', '2026-04-17 01:32:02', NULL),
(16, 5, 3, '2026-04-17 08:36:00', '2026-04-24 08:36:00', 'Dikembalikan', '2026-04-17 01:36:00', '2026-04-17 02:02:05', '2026-04-17 02:02:18'),
(17, 6, 2, '2026-04-17 09:48:09', '2026-04-24 09:48:09', 'Pending', '2026-04-17 02:48:09', '2026-04-17 02:48:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','siswa') NOT NULL DEFAULT 'siswa',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'endmin', 'admin', '$2y$10$EcMmncFe2/gmo/WwDmY3U.Y/fS7Jrfci/qBibzLVKTLWX/g6ypfBG', 'admin', NULL, '2026-04-08 22:00:31', '2026-04-14 23:29:42'),
(3, 'Alfarshya', 'user', '$2y$10$8qCMatpW9pTE9FhrDQVpZ.QhvjT6dY6fX40jRsl88qQsJs27faIGO', 'siswa', NULL, '2026-04-11 01:09:43', NULL),
(4, 'forsho', 'budi', '$2y$10$PS8hLcIl.zqOniI4HjIEY.h5OR8vzIjafcKlwFUN9hp.iT1P3hQfu', 'siswa', NULL, '2026-04-14 19:27:48', '2026-04-16 21:17:56'),
(5, 'rindra', 'rindro', '$2y$10$NTUQOnZ9CugEOTDDemHbrOgftpdq3y1cDNAgN.ahABO47FKcF67eO', 'siswa', NULL, '2026-04-16 18:35:19', '2026-04-17 01:35:43'),
(6, 'meong', 'dummy1', '$2y$10$45woCE6dR7Bgbqi5jbmfAehVHrCeXNtIpLGoRBR.kLd9v.qWvzOWu', 'siswa', NULL, '2026-04-17 00:35:39', '2026-04-17 02:46:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id_anggota`),
  ADD UNIQUE KEY `anggota_nis_unique` (`nis`),
  ADD KEY `anggota_user_id_foreign` (`user_id`);

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `transaksi_id_foreign` (`id`),
  ADD KEY `transaksi_id_buku_foreign` (`id_buku`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id_anggota` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anggota`
--
ALTER TABLE `anggota`
  ADD CONSTRAINT `anggota_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_id_buku_foreign` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_id_foreign` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
