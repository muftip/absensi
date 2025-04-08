-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 14 Nov 2024 pada 07.12
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absensi2`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi`
--

CREATE TABLE `absensi` (
  `id` int(10) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `absensi`
--

INSERT INTO `absensi` (`id`, `tanggal`, `created_at`, `updated_at`) VALUES
(1, '2024-07-01', NULL, NULL),
(2, '2024-07-02', NULL, NULL),
(3, '2024-07-03', NULL, NULL),
(4, '2024-07-04', NULL, NULL),
(5, '2024-07-05', NULL, NULL),
(6, '2024-07-06', NULL, NULL),
(7, '2024-07-07', NULL, NULL),
(8, '2024-07-08', NULL, NULL),
(9, '2024-07-09', NULL, NULL),
(10, '2024-07-10', NULL, NULL),
(11, '2024-07-11', NULL, NULL),
(12, '2024-07-12', NULL, NULL),
(13, '2024-07-13', NULL, NULL),
(14, '2024-07-14', NULL, NULL),
(15, '2024-11-14', '2024-11-14 06:11:37', '2024-11-14 06:11:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `log_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `subject_id` varchar(255) NOT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` varchar(255) NOT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('id_absensi', 'i:15;', 2046924697);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `jabatan`
--

CREATE TABLE `jabatan` (
  `id` varchar(2) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jabatan`
--

INSERT INTO `jabatan` (`id`, `nama`, `created_at`, `updated_at`) VALUES
('AA', 'Director', NULL, NULL),
('AB', 'General Manager', NULL, NULL),
('AC', 'Staff Administration', NULL, NULL),
('AD', 'Accounting and Finance', NULL, NULL),
('AE', 'Sales Manager', NULL, NULL),
('AF', 'Warehouse Manager', NULL, NULL),
('AG', 'Sales Supervisor', NULL, NULL),
('AH', 'Salesman', NULL, NULL),
('AI', 'Driver', NULL, NULL),
('AJ', 'Helper', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `karyawan`
--

CREATE TABLE `karyawan` (
  `id` varchar(5) NOT NULL,
  `id_jabatan` varchar(2) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `tempat_lahir` varchar(20) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `foto` varchar(50) DEFAULT NULL,
  `agama` enum('Islam','Katolik','Hindu','Kristen','Buddha','Konghucu') NOT NULL,
  `no_telp` varchar(13) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `karyawan`
--

INSERT INTO `karyawan` (`id`, `id_jabatan`, `nama`, `email`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `foto`, `agama`, `no_telp`, `deleted_at`, `created_at`, `updated_at`) VALUES
('001', 'AA', 'Nicholas', 'nicholas@gmail.com', 'Laki-laki', 'Lubuklinggau', '2003-11-09', 'Jalan Letda A.Rozak ', '', 'Buddha', '081271590161', NULL, NULL, NULL),
('002', 'AB', 'Thomas Setiawan', 'thomas@gmail.com', 'Laki-laki', 'Palembang', '2003-10-08', 'KM.9', '', 'Kristen', '082134682309', NULL, NULL, NULL),
('003', 'AC', 'Margaretha', 'margaretha@gmail.com', 'Perempuan', 'Palembang', '1990-12-14', 'Alang Alang Lebar', '', 'Katolik', '081234567890', NULL, NULL, NULL),
('004', 'AD', 'Karyawan-1', 'karyawan1@gmail.com', 'Perempuan', 'Palembang', '2000-10-10', 'Jl. Buah', '', 'Islam', '089987612342', NULL, NULL, NULL),
('005', 'AE', 'Karyawan-2', 'karyawan2@gmail.com', 'Laki-laki', 'Padang', '1999-03-09', 'Jl. Sayur', '', 'Islam', '085366768891', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `karyawan_absensi`
--

CREATE TABLE `karyawan_absensi` (
  `id_karyawan` varchar(5) NOT NULL,
  `id_absensi` int(10) UNSIGNED NOT NULL,
  `waktu_masuk` time NOT NULL,
  `waktu_keluar` time DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `karyawan_absensi`
--

INSERT INTO `karyawan_absensi` (`id_karyawan`, `id_absensi`, `waktu_masuk`, `waktu_keluar`, `deleted_at`, `created_at`, `updated_at`) VALUES
('001', 1, '07:50:00', '16:45:00', NULL, NULL, NULL),
('002', 1, '07:30:00', '16:40:30', NULL, NULL, NULL),
('003', 1, '09:10:20', '16:55:40', NULL, NULL, NULL),
('004', 1, '10:20:45', '18:30:30', NULL, NULL, NULL),
('005', 1, '07:30:45', '17:10:50', NULL, NULL, NULL),
('003', 2, '07:36:40', '16:56:00', NULL, NULL, NULL),
('005', 2, '08:30:00', '18:10:00', NULL, NULL, NULL),
('002', 3, '11:20:30', '19:35:20', NULL, NULL, NULL),
('004', 3, '10:00:00', '17:00:00', NULL, NULL, NULL),
('003', 4, '10:00:00', '17:00:00', NULL, NULL, NULL),
('004', 4, '10:00:00', '17:00:00', NULL, NULL, NULL),
('005', 4, '10:00:00', '17:00:00', NULL, NULL, NULL),
('001', 5, '10:00:00', '17:00:00', NULL, NULL, NULL),
('002', 5, '10:00:00', '17:00:00', NULL, NULL, NULL),
('003', 5, '10:00:00', '17:00:00', NULL, NULL, NULL),
('004', 5, '10:00:00', '17:00:00', NULL, NULL, NULL),
('005', 5, '10:00:00', '17:00:00', NULL, NULL, NULL),
('001', 15, '13:11:39', NULL, NULL, '2024-11-14 06:11:39', '2024-11-14 06:11:39'),
('002', 15, '13:11:38', NULL, NULL, '2024-11-14 06:11:39', '2024-11-14 06:11:39'),
('003', 15, '13:11:38', NULL, NULL, '2024-11-14 06:11:38', '2024-11-14 06:11:38'),
('004', 15, '13:11:37', NULL, NULL, '2024-11-14 06:11:38', '2024-11-14 06:11:38'),
('005', 15, '13:11:36', NULL, NULL, '2024-11-14 06:11:37', '2024-11-14 06:11:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `karyawan_izin`
--

CREATE TABLE `karyawan_izin` (
  `id_karyawan` varchar(5) NOT NULL,
  `id_absensi` int(10) UNSIGNED NOT NULL,
  `izin` tinyint(1) NOT NULL,
  `keterangan` varchar(50) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `karyawan_izin`
--

INSERT INTO `karyawan_izin` (`id_karyawan`, `id_absensi`, `izin`, `keterangan`, `deleted_at`, `created_at`, `updated_at`) VALUES
('001', 2, 0, '', NULL, NULL, NULL),
('002', 2, 1, 'Sakit', NULL, NULL, NULL),
('004', 2, 1, 'Sakit', NULL, NULL, NULL),
('001', 3, 0, '', NULL, NULL, NULL),
('003', 3, 0, '', NULL, NULL, NULL),
('005', 3, 1, 'Sakit', NULL, NULL, NULL),
('001', 4, 1, 'Sakit', NULL, NULL, NULL),
('002', 4, 0, '', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `libur`
--

CREATE TABLE `libur` (
  `id` int(10) UNSIGNED NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `libur`
--

INSERT INTO `libur` (`id`, `keterangan`, `tanggal_mulai`, `tanggal_selesai`, `created_at`, `updated_at`) VALUES
(1, 'Hari Buruh Internasional', '2024-05-01', '2024-05-01', NULL, NULL),
(2, 'Hari Raya Idul Adha 1445 Hijriah', '2024-06-17', '2024-06-18', NULL, NULL),
(3, 'Hari Kemerdekaan Indonesia', '2024-08-17', '2024-08-17', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2024_05_18_010604_create_jabatan_table', 1),
(4, '2024_05_18_010605_create_karyawan_table', 1),
(5, '2024_06_25_184542_create_libur_table', 1),
(6, '2024_06_25_184556_create_absensi_table', 1),
(7, '2024_06_25_185052_create_karyawan_absensi_table', 1),
(8, '2024_06_25_185100_create_karyawan_izin_table', 1),
(9, '2024_06_25_190013_create_user_table', 1),
(10, '2024_07_14_084823_create_activity_log_table', 1),
(11, '2024_07_14_084824_add_event_column_to_activity_log_table', 1),
(12, '2024_07_14_084825_add_batch_uuid_column_to_activity_log_table', 1),
(13, '2024_07_14_094418_alter_subject_id_in_activity_log', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('HKQTjbSIcw01850UqddP0WkhLSc1wDl1aORyh72z', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNVFERXhKUXJoRmt1aEJockVMMm1MRVBDNXJPOThlUUQxZXBOaW42WSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1731564604),
('VGqVKak2EwcY2IbFgxeaqmyrJdZYvh8sepd3Wr9R', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRksyc08zMzhUeTh2TW5ydjB4UkZ0S1N1YVlRUXhIQjlEcDdyNkZwRyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czoxMToiaWRfa2FyeWF3YW4iO3M6MzoiMDAxIjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9fQ==', 1731564709);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_karyawan` varchar(5) NOT NULL,
  `username` varchar(10) NOT NULL,
  `password` varchar(100) NOT NULL,
  `hak_akses` enum('Director','General Manager','Admin') NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_karyawan`, `username`, `password`, `hak_akses`, `remember_token`, `created_at`, `updated_at`) VALUES
('001', '123', '$2y$12$Pd600h3L6UzhRfglSBpwJOPihskzGzs/JzKqIgwVxC8EEAVCdP./K', 'Director', NULL, NULL, NULL),
('002', '1234', '$2y$12$wed7ynQ/JUsJSd45O2aJXO5Uya8apJJtgr/6QDa.7TYhhF3U3mkFa', 'General Manager', NULL, NULL, NULL),
('003', '12345', '$2y$12$nFPrygW5.t2Eg7JZ1Co3neWr7E9VsnkAZlkv6PTgA0Rj6M1rlMkt6', 'Admin', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `absensi_tanggal_unique` (`tanggal`);

--
-- Indeks untuk tabel `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject` (`subject_type`,`subject_id`),
  ADD KEY `causer` (`causer_type`,`causer_id`),
  ADD KEY `activity_log_log_name_index` (`log_name`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `karyawan_email_unique` (`email`),
  ADD UNIQUE KEY `karyawan_no_telp_unique` (`no_telp`),
  ADD KEY `karyawan_id_jabatan_foreign` (`id_jabatan`);

--
-- Indeks untuk tabel `karyawan_absensi`
--
ALTER TABLE `karyawan_absensi`
  ADD PRIMARY KEY (`id_absensi`,`id_karyawan`),
  ADD KEY `karyawan_absensi_id_karyawan_foreign` (`id_karyawan`);

--
-- Indeks untuk tabel `karyawan_izin`
--
ALTER TABLE `karyawan_izin`
  ADD PRIMARY KEY (`id_absensi`,`id_karyawan`),
  ADD KEY `karyawan_izin_id_karyawan_foreign` (`id_karyawan`);

--
-- Indeks untuk tabel `libur`
--
ALTER TABLE `libur`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_karyawan`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `libur`
--
ALTER TABLE `libur`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  ADD CONSTRAINT `karyawan_id_jabatan_foreign` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatan` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `karyawan_absensi`
--
ALTER TABLE `karyawan_absensi`
  ADD CONSTRAINT `karyawan_absensi_id_absensi_foreign` FOREIGN KEY (`id_absensi`) REFERENCES `absensi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `karyawan_absensi_id_karyawan_foreign` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `karyawan_izin`
--
ALTER TABLE `karyawan_izin`
  ADD CONSTRAINT `karyawan_izin_id_absensi_foreign` FOREIGN KEY (`id_absensi`) REFERENCES `absensi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `karyawan_izin_id_karyawan_foreign` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_id_karyawan_foreign` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
