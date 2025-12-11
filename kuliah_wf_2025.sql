-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 11, 2025 at 05:22 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kuliah_wf_2025`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_rekam_medis`
--

CREATE TABLE `detail_rekam_medis` (
  `iddetail_rekam_medis` int NOT NULL,
  `idrekam_medis` int NOT NULL,
  `idkode_tindakan_terapi` int NOT NULL,
  `detail` varchar(1000) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_rekam_medis`
--

INSERT INTO `detail_rekam_medis` (`iddetail_rekam_medis`, `idrekam_medis`, `idkode_tindakan_terapi`, `detail`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 1, '', NULL, NULL),
(2, 2, 20, '', NULL, NULL),
(3, 3, 39, '', NULL, NULL),
(4, 4, 1, NULL, NULL, NULL),
(7, 4, 2, NULL, NULL, NULL),
(9, 4, 4, NULL, NULL, NULL),
(10, 4, 5, NULL, NULL, NULL),
(11, 4, 6, NULL, NULL, NULL),
(12, 4, 13, NULL, NULL, NULL),
(13, 5, 43, NULL, NULL, NULL),
(14, 6, 5, NULL, NULL, NULL),
(15, 7, 5, NULL, NULL, NULL),
(16, 8, 39, NULL, NULL, NULL),
(17, 9, 42, NULL, NULL, NULL),
(18, 9, 42, NULL, NULL, NULL),
(19, 9, 42, NULL, NULL, NULL),
(20, 9, 42, NULL, NULL, NULL),
(21, 9, 42, NULL, NULL, NULL),
(22, 9, 45, 'hiii', '2025-12-09 09:55:07', 6);

-- --------------------------------------------------------

--
-- Table structure for table `dokter`
--

CREATE TABLE `dokter` (
  `id_dokter` int NOT NULL,
  `alamat` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_hp` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bidang_dokter` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_kelamin` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_user` bigint NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_hewan`
--

CREATE TABLE `jenis_hewan` (
  `idjenis_hewan` int NOT NULL,
  `nama_jenis_hewan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis_hewan`
--

INSERT INTO `jenis_hewan` (`idjenis_hewan`, `nama_jenis_hewan`, `deleted_at`, `deleted_by`) VALUES
(1, 'Anjing (Canis lupus familiaris)', NULL, NULL),
(2, 'Kucing (Felis catus)', NULL, NULL),
(3, 'Kelinci (Oryctolagus cuniculus)', NULL, NULL),
(4, 'Burung', NULL, NULL),
(5, 'Reptil', NULL, NULL),
(6, 'Rodent / Hewan Kecil', NULL, NULL),
(8, 'Alpha MiawAugggg', NULL, NULL),
(15, 'Ambakerad', NULL, NULL),
(16, 'Ryan Miaw', '2025-12-05 02:00:09', 6);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `idkategori` int NOT NULL,
  `nama_kategori` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`idkategori`, `nama_kategori`, `deleted_at`, `deleted_by`) VALUES
(1, 'Vaksinasi', NULL, NULL),
(2, 'Bedah / Operasi', NULL, NULL),
(3, 'Cairan infus', NULL, NULL),
(4, 'Terapi Injeksi', NULL, NULL),
(5, 'Terapi Oral', NULL, NULL),
(6, 'Diagnostik', NULL, NULL),
(7, 'Rawat Inap', NULL, NULL),
(8, 'Lain-lain', NULL, NULL),
(11, 'Lemahhhh', NULL, NULL),
(12, 'Terapi Rebahan', NULL, NULL),
(15, 'Vaksin Dewa', NULL, NULL),
(16, 'Rawat Selamanya', NULL, NULL),
(17, 'Terapi Pijat', NULL, NULL),
(18, 'Pegel Linuuu', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kategori_klinis`
--

CREATE TABLE `kategori_klinis` (
  `idkategori_klinis` int NOT NULL,
  `nama_kategori_klinis` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori_klinis`
--

INSERT INTO `kategori_klinis` (`idkategori_klinis`, `nama_kategori_klinis`, `deleted_at`, `deleted_by`) VALUES
(1, 'Terapi', NULL, NULL),
(2, 'Tindakan', NULL, NULL),
(4, 'Berdarah - Darahhh', NULL, NULL),
(5, 'Pendarahan Sadis', NULL, NULL),
(8, 'Pendarahan Besarrr', NULL, NULL),
(9, 'Tindakan Berat', NULL, NULL),
(10, 'Samdis Banged', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kode_tindakan_terapi`
--

CREATE TABLE `kode_tindakan_terapi` (
  `idkode_tindakan_terapi` int NOT NULL,
  `kode` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deskripsi_tindakan_terapi` varchar(1000) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `idkategori` int NOT NULL,
  `idkategori_klinis` int NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kode_tindakan_terapi`
--

INSERT INTO `kode_tindakan_terapi` (`idkode_tindakan_terapi`, `kode`, `deskripsi_tindakan_terapi`, `idkategori`, `idkategori_klinis`, `deleted_at`, `deleted_by`) VALUES
(1, 'T01', 'Vaksinasi Rabies', 1, 1, NULL, NULL),
(2, 'T02', 'Vaksinasi Polivalen (DHPPi/L untuk anjing)', 1, 1, NULL, NULL),
(3, 'T03', 'Vaksinasi Panleukopenia / Tricat kucing', 1, 1, NULL, NULL),
(4, 'T04', 'Vaksinasi lainnya (bordetella, influenza, dsb.)', 1, 1, NULL, NULL),
(5, 'T05', 'Sterilisasi jantan', 2, 2, NULL, NULL),
(6, 'T06', 'Sterilisasi betina', 2, 2, NULL, NULL),
(9, 'T07', 'Minor surgery (luka, abses)', 2, 2, NULL, NULL),
(10, 'T08', 'Major surgery (laparotomi, tumor)', 2, 2, NULL, NULL),
(11, 'T09', 'Infus intravena cairan kristaloid', 3, 1, NULL, NULL),
(12, 'T10', 'Infus intravena cairan koloid', 3, 1, NULL, NULL),
(13, 'T11', 'Antibiotik injeksi', 4, 1, NULL, NULL),
(14, 'T12', 'Antiparasit injeksi', 4, 1, NULL, NULL),
(15, 'T13', 'Antiemetik / gastroprotektor', 4, 1, NULL, NULL),
(16, 'T14', 'Analgesik / antiinflamasi', 4, 1, NULL, NULL),
(17, 'T15', 'Kortikosteroid', 4, 1, NULL, NULL),
(18, 'T16', 'Antibiotik oral', 5, 1, NULL, NULL),
(19, 'T17', 'Antiparasit oral', 5, 1, NULL, NULL),
(20, 'T18', 'Vitamin / suplemen', 5, 1, NULL, NULL),
(21, 'T19', 'Diet khusus', 5, 1, NULL, NULL),
(22, 'T20', 'Pemeriksaan darah rutin', 6, 2, NULL, NULL),
(23, 'T21', 'Pemeriksaan kimia darah', 6, 2, NULL, NULL),
(24, 'T22', 'Pemeriksaan feses / parasitologi', 6, 2, NULL, NULL),
(25, 'T23', 'Pemeriksaan urin', 6, 2, NULL, NULL),
(26, 'T24', 'Radiografi (rontgen)', 6, 2, NULL, NULL),
(27, 'T25', 'USG Abdomen', 6, 2, NULL, NULL),
(28, 'T26', 'Sitologi / biopsi', 6, 2, NULL, NULL),
(29, 'T27', 'Rapid test penyakit infeksi', 6, 2, NULL, NULL),
(30, 'T28', 'Observasi sehari', 7, 2, NULL, NULL),
(31, 'T29', 'Observasi lebih dari 1 hari', 7, 2, NULL, NULL),
(32, 'T30', 'Grooming medis', 8, 2, NULL, NULL),
(33, 'T31', 'Deworming', 8, 1, NULL, NULL),
(34, 'T32', 'Ektoparasit control', 8, 1, NULL, NULL),
(39, 'T777', 'Tindakan KriminaLLL', 3, 5, NULL, NULL),
(42, 'T1', 'Sterilisasi', 2, 2, NULL, NULL),
(43, 'T2', 'Vaksinasi Urban', 1, 2, NULL, NULL),
(44, 'T701', 'Rawat Inap Beraddd', 16, 9, NULL, NULL),
(45, 'T707', 'James Bond', 11, 4, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemilik`
--

CREATE TABLE `pemilik` (
  `idpemilik` int NOT NULL,
  `alamat` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_wa` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `iduser` bigint NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemilik`
--

INSERT INTO `pemilik` (`idpemilik`, `alamat`, `no_wa`, `iduser`, `deleted_at`, `deleted_by`) VALUES
(19, 'Surabaya', '08123456789', 23, NULL, NULL),
(20, 'Bukit Palma Blok B1', '08123456789', 24, NULL, NULL),
(22, 'Manukan Wetan', '08123456789', 31, NULL, NULL),
(23, 'Pasuruan', '08123456789', 32, NULL, NULL),
(24, 'Surabaya Barat', '08123456789', 33, NULL, NULL),
(25, 'Manukan', '08123456789', 36, NULL, NULL),
(26, 'Menganti', '08123456789', 37, NULL, NULL),
(30, 'Bandung', '08123456789', 43, NULL, NULL),
(31, 'BanjarSugihannn', '08123456789', 44, NULL, NULL),
(33, 'Tenggilis Utara', '+628123456789', 16, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `perawat`
--

CREATE TABLE `perawat` (
  `id_perawat` int NOT NULL,
  `alamat` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_hp` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_kelamin` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pendidikan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_user` bigint NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pet`
--

CREATE TABLE `pet` (
  `idpet` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `warna_tanda` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_kelamin` char(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `idpemilik` int NOT NULL,
  `idras_hewan` int NOT NULL,
  `nama_pemilik` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_ras` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_jenis_hewan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pet`
--

INSERT INTO `pet` (`idpet`, `nama`, `tanggal_lahir`, `warna_tanda`, `jenis_kelamin`, `idpemilik`, `idras_hewan`, `nama_pemilik`, `nama_ras`, `nama_jenis_hewan`, `deleted_at`, `deleted_by`) VALUES
(1, 'Ihyaaa', '2025-09-20', 'Coklat', 'M', 24, 21, NULL, NULL, NULL, NULL, NULL),
(3, 'Kucing Anggoraaa', '2025-09-20', 'Putih Oren', 'F', 24, 28, NULL, NULL, NULL, NULL, NULL),
(10, 'Gajah', '2025-09-25', 'Abu-abu', 'F', 20, 42, NULL, NULL, NULL, NULL, NULL),
(11, 'Ginooo', '2025-09-25', 'Putih Oren', 'F', 20, 11, NULL, NULL, NULL, NULL, NULL),
(13, 'Luthfi', '2025-09-05', 'Orenn', 'M', 19, 26, NULL, NULL, NULL, NULL, NULL),
(15, 'Zoyaa', '2025-09-12', 'Kuning', 'M', 19, 28, NULL, NULL, NULL, NULL, NULL),
(17, 'Kim', '2025-09-26', 'IHIjau', 'M', 19, 28, NULL, NULL, NULL, NULL, NULL),
(18, 'Marco', '2025-09-26', 'Hijau', 'M', 19, 14, NULL, NULL, NULL, NULL, NULL),
(19, 'Raka', '2025-09-30', 'Hytam Legam', 'M', 24, 38, NULL, NULL, NULL, NULL, NULL),
(21, 'Ryan', '2025-09-05', 'Hytam Legam', 'F', 24, 28, NULL, NULL, NULL, NULL, NULL),
(22, 'Nelsen', '2025-10-04', 'Hytam Legam', 'M', 20, 28, NULL, NULL, NULL, NULL, NULL),
(25, 'Lee Jaemin', '2025-11-01', 'Kuning Oren', 'M', 31, 10, NULL, NULL, NULL, NULL, NULL),
(26, 'Pureum', '2025-11-01', 'Abu-abu', 'F', 31, 11, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ras_hewan`
--

CREATE TABLE `ras_hewan` (
  `idras_hewan` int NOT NULL,
  `nama_ras` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `idjenis_hewan` int NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ras_hewan`
--

INSERT INTO `ras_hewan` (`idras_hewan`, `nama_ras`, `idjenis_hewan`, `deleted_at`, `deleted_by`) VALUES
(1, 'Golden Retriever', 1, NULL, NULL),
(2, 'Labrador Retriever', 1, NULL, NULL),
(3, 'German Shepherd', 1, NULL, NULL),
(4, 'Bulldog (English, French)', 1, NULL, NULL),
(5, 'Poodle (Toy, Miniature, Standard)', 1, NULL, NULL),
(6, 'Beagle', 1, NULL, NULL),
(7, 'Siberian Husky', 1, NULL, NULL),
(8, 'Shih Tzu', 1, NULL, NULL),
(9, 'Dachshund', 1, NULL, NULL),
(10, 'Chihuahua', 1, NULL, NULL),
(11, 'Persia', 2, NULL, NULL),
(12, 'Maine Coon', 2, NULL, NULL),
(13, 'Siamese', 2, NULL, NULL),
(14, 'Bengal', 2, NULL, NULL),
(15, 'Sphynx', 2, NULL, NULL),
(16, 'Scottish Fold', 2, NULL, NULL),
(17, 'British Shorthair', 2, NULL, NULL),
(18, 'Anggora', 2, NULL, NULL),
(19, 'Domestic Shorthair (kampung)', 2, NULL, NULL),
(20, 'Ragdoll', 2, NULL, NULL),
(21, 'Holland Lop', 3, NULL, NULL),
(22, 'Netherland Dwarf', 3, NULL, NULL),
(23, 'Flemish Giant', 3, NULL, NULL),
(24, 'Lionhead', 3, NULL, NULL),
(26, 'Angora Rabbit', 3, NULL, NULL),
(27, 'Mini Lop', 3, NULL, NULL),
(28, 'Lovebird (Agapornis sp.)', 4, NULL, NULL),
(29, 'Kakatua (Cockatoo)', 4, NULL, NULL),
(30, 'Parrot / Nuri (Macaw, African Grey, Amazon Parrot)', 4, NULL, NULL),
(31, 'Kenari (Serinus canaria)', 4, NULL, NULL),
(32, 'Merpati (Columba livia)', 4, NULL, NULL),
(33, 'Parkit (Budgerigar / Melopsittacus undulatus)', 4, NULL, NULL),
(34, 'Jalak (Sturnus sp.)', 4, NULL, NULL),
(35, 'Kura-kura Sulcata (African spurred tortoise)', 5, NULL, NULL),
(36, 'Red-Eared Slider (Trachemys scripta elegans)', 5, NULL, NULL),
(37, 'Leopard Gecko', 5, NULL, NULL),
(38, 'Iguana hijau', 5, NULL, NULL),
(39, 'Ball Python', 5, NULL, NULL),
(40, 'Corn Snake', 5, NULL, NULL),
(41, 'Hamster (Syrian, Roborovski, Campbell, Winter White)', 6, NULL, NULL),
(42, 'Guinea Pig (Abyssinian, Peruvian, American Shorthair)', 6, NULL, NULL),
(43, 'Gerbil', 6, NULL, NULL),
(44, 'Chinchilla', 6, NULL, NULL),
(45, 'Kucing Oren Penguasa Langit', 8, NULL, NULL),
(46, 'Koceng Hytam Legam', 8, NULL, NULL),
(47, 'Koceng', 2, NULL, NULL),
(49, 'Kepiting Alaska', 5, NULL, NULL),
(51, 'Origin', 2, NULL, NULL),
(52, 'Ambacuuuyyyylahhh', 15, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rekam_medis`
--

CREATE TABLE `rekam_medis` (
  `idrekam_medis` int NOT NULL,
  `idreservasi_dokter` int DEFAULT NULL,
  `idpet` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `anamnesa` varchar(1000) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `temuan_klinis` varchar(1000) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `diagnosa` varchar(1000) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dokter_pemeriksa` int NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rekam_medis`
--

INSERT INTO `rekam_medis` (`idrekam_medis`, `idreservasi_dokter`, `idpet`, `created_at`, `anamnesa`, `temuan_klinis`, `diagnosa`, `dokter_pemeriksa`, `deleted_at`, `deleted_by`) VALUES
(1, 9, 1, '2025-10-05 14:34:57', 'Tralala', 'TungTungSahur', 'Penyakit Brainrot', 5, NULL, NULL),
(2, 11, 21, '2025-10-05 14:42:28', 'Tralala', 'Sedikit Pusing Bagian Kiri', 'Aman aja selama masih minum teh', 5, NULL, NULL),
(3, 8, 19, '2025-10-05 15:09:37', 'Aku gatau', 'coba2 aja doc', 'Penyakit bruntusan', 5, NULL, NULL),
(4, 4, 1, '2025-10-07 05:06:34', 'LAALA', 'LILI', 'LULULULULU', 5, NULL, NULL),
(5, 1, 3, '2025-11-19 04:22:21', 'Batuk-batuk', 'Flu tiap hari tiap jam tiap menit', 'Demam tinggi yg tak kunjung turun', 5, NULL, NULL),
(6, 2, 22, '2025-11-19 04:44:22', 'YAYAYA', 'YIYIYI', 'YUYUYU', 5, NULL, NULL),
(7, 3, 11, '2025-11-19 04:47:29', 'SASASA', 'SISISI', 'SUSUSU', 5, NULL, NULL),
(8, 15, 25, '2025-11-22 04:33:24', 'PAPAPAPA', 'PIPIPIPIPI', 'PUPUPUPU', 5, NULL, NULL),
(9, 16, 21, '2025-12-09 09:34:55', 'kit heart', 'nangid tiap malam', 'sakit dadanyaaa', 5, '2025-12-09 09:55:31', 6);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `idrole` int NOT NULL,
  `nama_role` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`idrole`, `nama_role`, `deleted_at`, `deleted_by`) VALUES
(1, 'Administrator', NULL, NULL),
(2, 'Dokter', NULL, NULL),
(3, 'Perawat', NULL, NULL),
(4, 'Resepsionis', NULL, NULL),
(5, 'Pemilik', NULL, NULL),
(6, 'Psikologgg', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `idrole_user` int NOT NULL,
  `iduser` bigint NOT NULL,
  `idrole` int NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`idrole_user`, `iduser`, `idrole`, `status`, `deleted_at`, `deleted_by`) VALUES
(1, 6, 1, 1, NULL, NULL),
(2, 12, 4, 1, NULL, NULL),
(5, 34, 2, 1, NULL, NULL),
(6, 35, 3, 1, NULL, NULL),
(7, 37, 5, 1, NULL, NULL),
(8, 36, 2, 1, NULL, NULL),
(9, 13, 2, 1, NULL, NULL),
(10, 16, 3, 1, NULL, NULL),
(11, 13, 3, 0, NULL, NULL),
(12, 16, 4, 1, NULL, NULL),
(13, 26, 1, 1, NULL, NULL),
(14, 26, 2, 0, NULL, NULL),
(15, 26, 5, 0, NULL, NULL),
(16, 26, 3, 0, NULL, NULL),
(17, 26, 4, 0, NULL, NULL),
(18, 18, 1, 1, NULL, NULL),
(19, 32, 1, 1, NULL, NULL),
(20, 19, 5, 1, NULL, NULL),
(21, 40, 5, 1, NULL, NULL),
(22, 22, 5, 0, NULL, NULL),
(23, 33, 5, 1, NULL, NULL),
(24, 23, 5, 1, NULL, NULL),
(25, 24, 5, 1, NULL, NULL),
(42, 45, 5, 1, NULL, NULL),
(43, 45, 3, 1, NULL, NULL),
(44, 45, 6, 1, NULL, NULL),
(45, 45, 4, 1, NULL, NULL),
(46, 20, 2, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('38AZubr6fcmeFXMnOAckrOB4gjgUTrihbhnHXDOS', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWXZ4MUQyYWN5dEg4QU5JQ1ZRcWNYTTBYQ214cEFGa0VUS2pzZ0N4TyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fX0=', 1765300772);

-- --------------------------------------------------------

--
-- Table structure for table `temu_dokter`
--

CREATE TABLE `temu_dokter` (
  `idreservasi_dokter` int NOT NULL,
  `no_urut` int NOT NULL,
  `waktu_daftar` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint NOT NULL DEFAULT '0',
  `idpet` int NOT NULL,
  `idrole_user` int NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `temu_dokter`
--

INSERT INTO `temu_dokter` (`idreservasi_dokter`, `no_urut`, `waktu_daftar`, `status`, `idpet`, `idrole_user`, `deleted_at`, `deleted_by`) VALUES
(1, 1, '2025-10-04 23:17:19', 1, 3, 5, NULL, NULL),
(2, 2, '2025-10-04 23:18:36', 1, 22, 5, NULL, NULL),
(3, 3, '2025-10-04 23:23:26', 1, 11, 5, NULL, NULL),
(4, 1, '2025-10-10 00:00:00', 1, 1, 5, NULL, NULL),
(5, 2, '2025-10-10 00:00:00', 0, 13, 5, NULL, NULL),
(6, 3, '2025-10-10 00:00:00', 0, 15, 5, NULL, NULL),
(7, 4, '2025-10-10 00:00:00', 0, 18, 5, NULL, NULL),
(8, 5, '2025-10-10 16:41:30', 1, 19, 5, NULL, NULL),
(9, 4, '2025-10-04 16:41:49', 1, 1, 5, NULL, NULL),
(10, 6, '2025-10-10 16:56:39', 0, 10, 5, NULL, NULL),
(11, 7, '2025-10-10 23:58:49', 1, 21, 5, NULL, NULL),
(12, 1, '2025-10-05 00:04:00', 1, 17, 5, NULL, NULL),
(13, 2, '2025-10-05 14:53:49', 0, 10, 5, NULL, NULL),
(14, 1, '2025-10-07 12:05:46', 2, 1, 5, '2025-12-09 10:17:52', 6),
(15, 1, '2025-11-22 09:22:34', 1, 25, 5, NULL, NULL),
(16, 2, '2025-11-22 16:23:00', 1, 21, 5, NULL, NULL),
(17, 3, '2025-11-22 10:36:03', 1, 1, 5, NULL, NULL),
(18, 4, '2025-11-22 10:36:35', 1, 19, 5, NULL, NULL),
(19, 1, '2025-12-09 17:18:23', 0, 1, 5, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `iduser` bigint NOT NULL,
  `nama` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`iduser`, `nama`, `email`, `password`, `deleted_at`, `deleted_by`) VALUES
(6, 'ADMINNN', 'admin@mail.com', '$2y$12$EJ.YUWYiDY5YrlUHtAW1xOzEJjvIJUdNIX3FDxNv2D5W8it0ofvAO', NULL, NULL),
(12, 'Resepsionis', 'resepsionis@mail.com', '$2y$12$RO.BZ/4ItpTWkpvofXffguZbARfnQpxReEyROXLWqAgooehewR4NW', NULL, NULL),
(13, 'Ammariah', 'ammar@mail.com', '$2y$10$YYIudzvPSZFCYovLzSQ/8Oe9kfqSekkh.kWNympJHNvlcHHfusAzC', NULL, NULL),
(16, 'fachriAJAH', 'fachri@mail.com', '$2y$10$B.hij7snmgjNyZ1/vPlTFObbSc3VGDAP2ykPNnRmcc1foujCLTcnO', NULL, NULL),
(18, 'Farellas', 'farellas@mail.com', '$2y$10$M1qGrXvVzsIr2J0q2aJlAuShC9JVrrRBlBeotkabhy.EKg08mrcMu', NULL, NULL),
(19, 'Rafi', 'rafi@mail.com', '$2y$10$cS6sxQieXiP3djhyaStgFeIGMCaweZvkT1I62bi3a6uRbE4KXnLHK', NULL, NULL),
(20, 'Ryan', 'ryan@mail.com', '$2y$10$4xdm.V9H4GSVqeTrVflPyefCYbouF6KYs2vgzJhoaL7EEVz2XApSu', NULL, NULL),
(21, 'Ihya', 'ihya@mail.com', '$2y$10$2hLaonjG37D7L8WgA7vwveApEPUyapwXet33mo2HResmDgCzaebhu', '2025-12-05 05:11:45', 6),
(22, 'Ihya', 'ihya2@mail.com', '$2y$10$d6hQeJ8WAlsjr96DI9jyBeQOFb/zc0YxwJk2zBU2i80qWMPHxseSa', NULL, NULL),
(23, 'Inu', 'inu@mail.com', '$2y$12$5KP.d5R5BXJl/nn1ON25VurCTXmhyjRCTd6rvitziTQdmGAfVV7pe', NULL, NULL),
(24, 'Satria', 'satria111@mail.com', '$2y$10$Diy0l5ozsgcqwEzbr7N.Y.iFp9aw.Ofi4WtoDsNKpqjce97GUthfe', NULL, NULL),
(26, 'Mas Amba', 'amba@mail.com', '$2y$10$8NdjGkk1o3/r8mkVVCpnzumx/NR/C1ohixOieU9DK7OgrQolkcvf.', NULL, NULL),
(28, 'Pemilik 1', 'pemilik1@mail.com', '123', '2025-12-05 05:11:32', 6),
(29, 'Pemilik 2', 'pemilik2@mail.com', '123', '2025-12-05 05:11:41', 6),
(30, 'Pemilik 3', 'pemilik3@mail.com', '123', NULL, NULL),
(31, 'Naufal', 'naufal@mail.com', '$2y$10$fX9/lfnH9k7qMYUVfvIFsefTjgxwnurY2X28nJU8B7rUanDyCxaaS', NULL, NULL),
(32, 'Adit', 'adit@mail.com', '$2y$10$KOrLoMOeqmtjBtXdsL3YsuKZaOx0iLon7YyPWwga9afLy1epXjcXG', NULL, NULL),
(33, 'Rizkimba', 'rizkimba@mail.com', '$2y$12$Ko4Wwg8rquS3aSKVjhHJ9epY1vbxN5D2gVmWgDeXJJCH8uy2Lqhhy', NULL, NULL),
(34, 'doc', 'dokter@mail.com', '$2y$12$2GVRXRFp0L7n2XfkrVUbquBe3BMjIFIrnRI4AtYZvmdHdGt1vAWvK', NULL, NULL),
(35, 'Perawat', 'perawat@mail.com', '$2y$12$kgxdPn2FhU448NObgTtw5uiTo316t3xl49cMJu.nhE6OsRrpWVkPC', NULL, NULL),
(36, 'Nelsen', 'nelsen@mail.com', '$2y$10$WSH8jG0rK/8NSh5ZUb0at.0e8rN3Dyvl/wZ7Z3l1bR3wzRU9yeBji', NULL, NULL),
(37, 'fadhil', 'fadhil@mail.com', '$2y$10$iIJi3u5JH4b1vhdq4xCGuu9vjxKLi51QbeIJSLWfO4dlBKxil9vDC', NULL, NULL),
(40, 'Pemilik', 'pemilik@mail.com', '$2y$12$2m8pXk344c3PNovtcNFhAeAoaCsQWtU8Tt/WFIWc7U/q6M9KQnBgm', NULL, NULL),
(41, 'ganyu', 'ganyu@mail.com', '$2y$10$H4pFk7HqNaVczybJ/Yu4fuUbR3ZBxuSrTDuJRP82bc51oAZlIvseC', NULL, NULL),
(43, 'lutpi', 'lutpi@mail.com', '$2y$10$zHcMIs3Lok7C8e4UgtGKtuTfstkx6QT85fxTOtCJTUUcA8MkUPPey', NULL, NULL),
(44, 'Klein Moretti', 'klein@mail.com', '$2y$12$mmoVzW5BFetyrd6i5QKkhOC7mFjoHriH9ykLd.7tOjqJ0lPjS1Stq', NULL, NULL),
(45, 'Habib Anwash', 'habib@mail.com', '$2y$12$vbs..kI8MI5yJJCJoQF8i.sUPCwAVhWhGe1ej8eA5namhx/RhC7Ey', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `detail_rekam_medis`
--
ALTER TABLE `detail_rekam_medis`
  ADD PRIMARY KEY (`iddetail_rekam_medis`),
  ADD KEY `fk_detail_rekam_medis_rekam_medis1_idx` (`idrekam_medis`),
  ADD KEY `idkode_tindakan_terapi` (`idkode_tindakan_terapi`),
  ADD KEY `fk_detail_rekam_medis_deleted_by` (`deleted_by`);

--
-- Indexes for table `dokter`
--
ALTER TABLE `dokter`
  ADD PRIMARY KEY (`id_dokter`),
  ADD KEY `fk_dokter_user_idx` (`id_user`),
  ADD KEY `fk_dokter_deleted_by` (`deleted_by`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jenis_hewan`
--
ALTER TABLE `jenis_hewan`
  ADD PRIMARY KEY (`idjenis_hewan`),
  ADD KEY `fk_jenis_hewan_deleted_by` (`deleted_by`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`idkategori`),
  ADD KEY `fk_kategori_deleted_by` (`deleted_by`);

--
-- Indexes for table `kategori_klinis`
--
ALTER TABLE `kategori_klinis`
  ADD PRIMARY KEY (`idkategori_klinis`),
  ADD KEY `fk_kategori_klinis_deleted_by` (`deleted_by`);

--
-- Indexes for table `kode_tindakan_terapi`
--
ALTER TABLE `kode_tindakan_terapi`
  ADD PRIMARY KEY (`idkode_tindakan_terapi`),
  ADD KEY `fk_kode_tindakan_terapi_kategori1_idx` (`idkategori`),
  ADD KEY `fk_kode_tindakan_terapi_kategori_klinis1_idx` (`idkategori_klinis`),
  ADD KEY `fk_kode_tindakan_terapi_deleted_by` (`deleted_by`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pemilik`
--
ALTER TABLE `pemilik`
  ADD PRIMARY KEY (`idpemilik`),
  ADD KEY `fk_pemilik_user1` (`iduser`),
  ADD KEY `fk_pemilik_deleted_by` (`deleted_by`);

--
-- Indexes for table `perawat`
--
ALTER TABLE `perawat`
  ADD PRIMARY KEY (`id_perawat`),
  ADD KEY `fk_perawat_user_idx` (`id_user`),
  ADD KEY `fk_perawat_deleted_by` (`deleted_by`);

--
-- Indexes for table `pet`
--
ALTER TABLE `pet`
  ADD PRIMARY KEY (`idpet`),
  ADD KEY `fk_pet_pemilik1_idx` (`idpemilik`),
  ADD KEY `fk_pet_ras_hewan1_idx` (`idras_hewan`),
  ADD KEY `fk_pet_deleted_by` (`deleted_by`);

--
-- Indexes for table `ras_hewan`
--
ALTER TABLE `ras_hewan`
  ADD PRIMARY KEY (`idras_hewan`),
  ADD KEY `fk_ras_hewan_jenis_hewan1_idx` (`idjenis_hewan`),
  ADD KEY `fk_ras_hewan_deleted_by` (`deleted_by`);

--
-- Indexes for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  ADD PRIMARY KEY (`idrekam_medis`),
  ADD KEY `fk_rekam_medis_role_user1_idx` (`dokter_pemeriksa`),
  ADD KEY `fk_rekam_temu_idx` (`idreservasi_dokter`),
  ADD KEY `fk_rekam_medis_pet` (`idpet`),
  ADD KEY `fk_rekam_medis_deleted_by` (`deleted_by`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`idrole`),
  ADD KEY `fk_role_deleted_by` (`deleted_by`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`idrole_user`),
  ADD KEY `fk_role_user_user_idx` (`iduser`),
  ADD KEY `fk_role_user_role1_idx` (`idrole`),
  ADD KEY `fk_role_user_deleted_by` (`deleted_by`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `temu_dokter`
--
ALTER TABLE `temu_dokter`
  ADD PRIMARY KEY (`idreservasi_dokter`),
  ADD KEY `fk_temu_dokter_pet_idx` (`idpet`),
  ADD KEY `fk_temu_dokter_role_user_idx` (`idrole_user`),
  ADD KEY `fk_temu_dokter_deleted_by` (`deleted_by`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`iduser`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_user_deleted_by` (`deleted_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_rekam_medis`
--
ALTER TABLE `detail_rekam_medis`
  MODIFY `iddetail_rekam_medis` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `dokter`
--
ALTER TABLE `dokter`
  MODIFY `id_dokter` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_hewan`
--
ALTER TABLE `jenis_hewan`
  MODIFY `idjenis_hewan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `idkategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `kategori_klinis`
--
ALTER TABLE `kategori_klinis`
  MODIFY `idkategori_klinis` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `kode_tindakan_terapi`
--
ALTER TABLE `kode_tindakan_terapi`
  MODIFY `idkode_tindakan_terapi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pemilik`
--
ALTER TABLE `pemilik`
  MODIFY `idpemilik` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `perawat`
--
ALTER TABLE `perawat`
  MODIFY `id_perawat` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pet`
--
ALTER TABLE `pet`
  MODIFY `idpet` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `ras_hewan`
--
ALTER TABLE `ras_hewan`
  MODIFY `idras_hewan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  MODIFY `idrekam_medis` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `idrole` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `idrole_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `temu_dokter`
--
ALTER TABLE `temu_dokter`
  MODIFY `idreservasi_dokter` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `iduser` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_rekam_medis`
--
ALTER TABLE `detail_rekam_medis`
  ADD CONSTRAINT `detail_rekam_medis_ibfk_1` FOREIGN KEY (`idkode_tindakan_terapi`) REFERENCES `kode_tindakan_terapi` (`idkode_tindakan_terapi`),
  ADD CONSTRAINT `fk_detail_rekam_medis_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `user` (`iduser`),
  ADD CONSTRAINT `fk_detail_rekam_medis_rekam_medis1` FOREIGN KEY (`idrekam_medis`) REFERENCES `rekam_medis` (`idrekam_medis`);

--
-- Constraints for table `dokter`
--
ALTER TABLE `dokter`
  ADD CONSTRAINT `fk_dokter_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `user` (`iduser`),
  ADD CONSTRAINT `fk_dokter_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`iduser`);

--
-- Constraints for table `jenis_hewan`
--
ALTER TABLE `jenis_hewan`
  ADD CONSTRAINT `fk_jenis_hewan_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `user` (`iduser`);

--
-- Constraints for table `kategori`
--
ALTER TABLE `kategori`
  ADD CONSTRAINT `fk_kategori_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `user` (`iduser`);

--
-- Constraints for table `kategori_klinis`
--
ALTER TABLE `kategori_klinis`
  ADD CONSTRAINT `fk_kategori_klinis_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `user` (`iduser`);

--
-- Constraints for table `kode_tindakan_terapi`
--
ALTER TABLE `kode_tindakan_terapi`
  ADD CONSTRAINT `fk_kode_tindakan_terapi_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `user` (`iduser`),
  ADD CONSTRAINT `fk_kode_tindakan_terapi_kategori1` FOREIGN KEY (`idkategori`) REFERENCES `kategori` (`idkategori`),
  ADD CONSTRAINT `fk_kode_tindakan_terapi_kategori_klinis1` FOREIGN KEY (`idkategori_klinis`) REFERENCES `kategori_klinis` (`idkategori_klinis`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `pemilik`
--
ALTER TABLE `pemilik`
  ADD CONSTRAINT `fk_pemilik_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `user` (`iduser`),
  ADD CONSTRAINT `fk_pemilik_user1` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`);

--
-- Constraints for table `perawat`
--
ALTER TABLE `perawat`
  ADD CONSTRAINT `fk_perawat_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `user` (`iduser`),
  ADD CONSTRAINT `fk_perawat_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`iduser`);

--
-- Constraints for table `pet`
--
ALTER TABLE `pet`
  ADD CONSTRAINT `fk_pet_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `user` (`iduser`),
  ADD CONSTRAINT `fk_pet_ras_hewan1` FOREIGN KEY (`idras_hewan`) REFERENCES `ras_hewan` (`idras_hewan`);

--
-- Constraints for table `ras_hewan`
--
ALTER TABLE `ras_hewan`
  ADD CONSTRAINT `fk_ras_hewan_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `user` (`iduser`),
  ADD CONSTRAINT `fk_ras_hewan_jenis_hewan1` FOREIGN KEY (`idjenis_hewan`) REFERENCES `jenis_hewan` (`idjenis_hewan`);

--
-- Constraints for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  ADD CONSTRAINT `fk_rekam_medis_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `user` (`iduser`),
  ADD CONSTRAINT `fk_rekam_medis_pet` FOREIGN KEY (`idpet`) REFERENCES `pet` (`idpet`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rekam_reservasi` FOREIGN KEY (`idreservasi_dokter`) REFERENCES `temu_dokter` (`idreservasi_dokter`),
  ADD CONSTRAINT `fk_rekam_reservasi_correct` FOREIGN KEY (`idreservasi_dokter`) REFERENCES `temu_dokter` (`idreservasi_dokter`),
  ADD CONSTRAINT `rekam_medis_ibfk_1` FOREIGN KEY (`dokter_pemeriksa`) REFERENCES `role_user` (`idrole_user`);

--
-- Constraints for table `role`
--
ALTER TABLE `role`
  ADD CONSTRAINT `fk_role_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `user` (`iduser`);

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `fk_role_user_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `user` (`iduser`),
  ADD CONSTRAINT `fk_role_user_role1` FOREIGN KEY (`idrole`) REFERENCES `role` (`idrole`),
  ADD CONSTRAINT `fk_role_user_user` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`);

--
-- Constraints for table `temu_dokter`
--
ALTER TABLE `temu_dokter`
  ADD CONSTRAINT `fk_temu_dokter_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `user` (`iduser`),
  ADD CONSTRAINT `fk_temu_dokter_pet` FOREIGN KEY (`idpet`) REFERENCES `pet` (`idpet`),
  ADD CONSTRAINT `fk_temu_dokter_role_user` FOREIGN KEY (`idrole_user`) REFERENCES `role_user` (`idrole_user`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `user` (`iduser`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
