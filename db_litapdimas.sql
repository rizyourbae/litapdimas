-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 26, 2026 at 02:01 AM
-- Server version: 8.0.45-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_litapdimas`
--

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan_mandiri`
--

CREATE TABLE `kegiatan_mandiri` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(36) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `tahun` year NOT NULL,
  `jenis_kegiatan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `klaster_skala_kegiatan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `judul_kegiatan` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `anggota_terlibat` text COLLATE utf8mb4_general_ci,
  `resume_kegiatan` text COLLATE utf8mb4_general_ci,
  `unit_pelaksana_kegiatan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mitra_kolaborasi` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sumber_dana` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `besaran_dana` bigint UNSIGNED DEFAULT NULL,
  `tautan_bukti_dukung` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kegiatan_mandiri`
--

INSERT INTO `kegiatan_mandiri` (`id`, `uuid`, `user_id`, `tahun`, `jenis_kegiatan`, `klaster_skala_kegiatan`, `judul_kegiatan`, `anggota_terlibat`, `resume_kegiatan`, `unit_pelaksana_kegiatan`, `mitra_kolaborasi`, `sumber_dana`, `besaran_dana`, `tautan_bukti_dukung`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '8997472d-29e7-4562-b055-607efcf30a85', 9, '2025', 'Penelitian Mandiri', 'Regional', 'Kegiatan', 'Oke', 'oke', 'UPT', 'Mitra', 'Sumber', 7387383, 'drive.google.com', '2026-04-25 05:16:36', '2026-04-25 05:16:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kelengkapan_dokumen`
--

CREATE TABLE `kelengkapan_dokumen` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(36) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `jenis_dokumen` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Sertifikat Dosen, SK Jabatan Fungsional, Kartu NIDN',
  `dokumen_file` text COLLATE utf8mb4_general_ci COMMENT 'Path ke file dokumen',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelengkapan_dokumen`
--

INSERT INTO `kelengkapan_dokumen` (`id`, `uuid`, `user_id`, `jenis_dokumen`, `dokumen_file`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'b6c4300e-a516-4941-b512-6c1c5e8737a5', 9, 'Sertifikat Dosen', 'uploads/kelengkapan_dokumen/1777102063_a1db932a98b789ac13d6.pdf', '2026-04-25 07:06:01', '2026-04-25 07:27:43', NULL),
(2, 'bdba5dc9-c8d1-41c4-8f1f-5c9a20eebd3b', 9, 'SK Jabatan Fungsional', NULL, '2026-04-25 07:06:01', '2026-04-25 07:06:01', NULL),
(3, '1dea311a-ecbe-4b9f-aa4d-04791490f0bf', 9, 'Kartu NIDN', NULL, '2026-04-25 07:06:01', '2026-04-25 07:06:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `master_bidang_ilmu`
--

CREATE TABLE `master_bidang_ilmu` (
  `id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `updated_by` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_bidang_ilmu`
--

INSERT INTO `master_bidang_ilmu` (`id`, `uuid`, `nama`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`) VALUES
(5, '997ac988-5ce6-4ec0-8ea3-857822f98d9f', 'Teknik Informatika', '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(6, 'b2267cc6-21d4-42fb-8691-fe6d37482c16', 'Sistem Informasi', '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(7, '0295c6bb-bfdb-4ecb-b045-cddd23b7b47c', 'Manajemen', '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(8, '534ff3a4-4c31-4c08-a587-2c14b7d281e3', 'Akuntansi', '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(9, '493c40ac-59f5-4596-a485-3667a38b8d3e', 'Ilmu Hukum', '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(10, '27f8b745-1a2a-40b7-951c-afec08570af5', 'Pendidikan Agama Islam', '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(11, '249bb89d-e09b-4f18-9eea-7100c0aae4f4', 'Ekonomi Syariah', '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(12, '58d6d83d-0aa8-4bff-b66e-3ffc55ec17d4', 'Studi Al-Quran dan Tafsir', '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(13, 'ccadfa93-3cb2-4f86-bb9c-89f6b35c3437', 'Teknik Informatika', '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(14, 'ff3bed71-df1e-4338-b996-7d833cbd207f', 'Sistem Informasi', '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(15, '5a9bb710-0494-4728-821a-f2e349230905', 'Manajemen', '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(16, '3de1114b-4213-4bf2-941d-6bf6479cece7', 'Akuntansi', '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(17, '9090afd9-da34-4b41-b8e0-069920fd8b12', 'Ilmu Hukum', '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(18, '43478c4b-d872-4067-9a7a-7f3b11af8509', 'Pendidikan Agama Islam', '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(19, '51f16391-1512-45ba-ab9a-8f33465fb7fd', 'Ekonomi Syariah', '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(20, '9cf85b2c-2816-4d45-9f9a-89f64dd4eb15', 'Studi Al-Quran dan Tafsir', '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `master_fakultas`
--

CREATE TABLE `master_fakultas` (
  `id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `updated_by` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_fakultas`
--

INSERT INTO `master_fakultas` (`id`, `uuid`, `nama`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`) VALUES
(4, '71ececa0-684c-4649-bc11-81413bea5aa7', 'Fakultas Teknik', '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(5, 'c481f503-228b-43dc-aba0-afb95c57afe2', 'Fakultas Ekonomi dan Bisnis', '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(6, 'c9444e6c-a07b-4a10-b7c9-6be2c0175116', 'Fakultas Ilmu Komputer', '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(7, 'ee37afe1-da74-490e-816a-0adebf9516ae', 'Fakultas Hukum', '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(8, '7f16cde7-f1cc-458a-9037-1b24f2df0fe1', 'Fakultas Ushuluddin dan Studi Agama', '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(9, '3988bb3d-aff5-4956-ac86-e948c35eec1d', 'Fakultas Teknik', '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(10, '1c4c4993-fbc2-4404-9a76-62f56d9d9d00', 'Fakultas Ekonomi dan Bisnis', '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(11, '1f37ea66-a7e7-4b3b-9535-a80b3995ca0c', 'Fakultas Ilmu Komputer', '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(12, '51430965-2e04-47ee-a7e0-072093b47ace', 'Fakultas Hukum', '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(13, 'fd4f8f32-3477-4583-bb0f-a1aaaed1ca62', 'Fakultas Ushuluddin dan Studi Agama', '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `master_jabatan_fungsional`
--

CREATE TABLE `master_jabatan_fungsional` (
  `id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `updated_by` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_jabatan_fungsional`
--

INSERT INTO `master_jabatan_fungsional` (`id`, `uuid`, `nama`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`) VALUES
(5, '23a2491d-4f87-4853-8145-85c33dbc982a', 'Asisten Ahli', '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(6, 'b2ae509b-7319-4513-bb9a-5a32fe8e1fda', 'Lektor', '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(7, '4c6fd89a-db19-4822-addb-49f2ed310af4', 'Lektor Kepala', '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(8, '0e83805a-adcb-4149-85d5-df79666b6ae8', 'Guru Besar', '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(9, 'da3e591e-662a-4990-80dd-9930c289d5d7', 'Pranata Komputer Ahli Pertama', '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(10, '28c3b9e6-a57b-4ec1-8bdf-e6eb71760734', 'Pranata Komputer Ahli Muda', '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(11, '5843c3fd-ec74-4a79-bca4-30017eb214ff', 'Asisten Ahli', '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(12, 'f6e55e53-b915-495c-a584-4b65d7555d22', 'Lektor', '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(13, 'f3f4de11-da00-49c0-a8d5-77da72bce8d6', 'Lektor Kepala', '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(14, 'fa12d8ea-a5ed-42e4-b0de-0a8b35321c65', 'Guru Besar', '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(15, '6de5f588-c43c-426a-9738-aa72cad0fb3b', 'Pranata Komputer Ahli Pertama', '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(16, 'eb813200-c38b-4662-81c4-3b3af24bfd09', 'Pranata Komputer Ahli Muda', '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `master_profesi`
--

CREATE TABLE `master_profesi` (
  `id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `updated_by` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_profesi`
--

INSERT INTO `master_profesi` (`id`, `uuid`, `nama`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`) VALUES
(5, '1ae71369-976b-425f-9245-1cee3f52c7ac', 'Dosenn', '2026-04-24 01:46:01', '2026-04-24 08:27:08', NULL, NULL, NULL),
(6, '403f0ed3-c95d-4beb-9979-f9f63a2b7280', 'Pranata Komputer', '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(7, '3b7f5614-f7d5-4340-bb1c-3d2786e2dd4a', 'Pengembang TP', '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(8, '46e0c464-76ac-423a-97a3-8666713bd18f', 'Pranata Keuangan', '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(9, 'c10e61d6-b606-4f2d-97d2-bd1431c30220', 'Perancang UU', '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(10, 'b981f8d2-0368-45bd-b5cc-ef074d85874d', 'Dosen', '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(11, '2ea0901f-ca38-4e07-b22f-c66b3b18b7a4', 'Pranata Komputer', '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(12, 'c4454c7b-edfd-480c-bdac-7315bc2be102', 'Pengembang TP', '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(13, 'a5ef0227-0acb-49a3-8a13-ce37116a9493', 'Pranata Keuangan', '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(14, 'f835a3bf-352f-4ba6-8d13-5b89f3aa8517', 'Perancang UU', '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(15, 'b85b06db-9363-441b-8301-8d3f25f7a446', 'Guru', '2026-04-24 07:50:18', '2026-04-24 07:50:18', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `master_program_studi`
--

CREATE TABLE `master_program_studi` (
  `id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fakultas_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `updated_by` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_program_studi`
--

INSERT INTO `master_program_studi` (`id`, `uuid`, `nama`, `fakultas_id`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`) VALUES
(3, '25664b1f-8008-4417-aeaa-aed535c11354', 'Teknik Informatika', 4, '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(4, '73549701-f348-4d96-900f-8e2470d4e037', 'Sistem Informasi', 6, '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(5, '141d0f99-4936-449f-b6ec-a4f0c141fcce', 'Manajemen', 5, '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(6, '20998403-70fa-4795-8032-f68c6b7ee580', 'Akuntansi', 5, '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(7, '15740115-b634-4230-8d41-95a51ddac094', 'Ilmu Hukum', 7, '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(8, '641b8e53-7143-43b9-84aa-0a96f2328330', 'Studi Agama-Agama', 8, '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(9, 'bc340293-6e6e-4abe-a7a6-259a1e690e7c', 'Teknik Informatika', 9, '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(10, '3c819fb8-efb4-4ea6-b619-05ba0be70e05', 'Sistem Informasi', 11, '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(11, '1c575fe2-4ceb-4952-a028-22787b2a14cd', 'Manajemen', 10, '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(12, 'e6f06127-717e-40a5-adef-7448c8533a52', 'Akuntansi', 10, '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(13, 'd7d71048-38d2-45b8-873b-68e0734a85c8', 'Ilmu Hukum', 12, '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(14, '76343733-cb98-4e35-a35b-4a9cbcb71934', 'Studi Agama-Agama', 13, '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `master_unit_kerja`
--

CREATE TABLE `master_unit_kerja` (
  `id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `parent_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `updated_by` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_unit_kerja`
--

INSERT INTO `master_unit_kerja` (`id`, `uuid`, `nama`, `parent_id`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`) VALUES
(5, 'eb4c1141-b1e5-47fd-9b2d-8533b0d0a06c', 'LPPM', NULL, '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(6, '0f7a4630-3eb3-4edc-b9ae-007e71e1a778', 'Lembaga Penelitian dan Pengabdian', 5, '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(7, 'd141510b-8ccf-470f-954d-f00a636af247', 'Lembaga Penelitian', 5, '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(8, '73eacd13-336d-489f-99b3-4eb3221a6893', 'Lembaga Pengabdian', 5, '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(9, '23dae520-6080-4f6b-9d31-e4a24da0f10e', 'Perpustakaan', NULL, '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(10, '935e5b19-75c2-4479-92f8-71ab887b7536', 'Biro Akademik dan Kemahasiswaan', NULL, '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(11, 'f72a0297-37c7-44dd-bf1a-5c637dd0ed27', 'Biro Keuangan', NULL, '2026-04-24 01:46:01', '2026-04-24 01:46:01', NULL, NULL, NULL),
(12, '3ebc874a-6d27-41ab-99c3-a0e8f5910760', 'LPPM', NULL, '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(13, 'e1417f76-2f4d-4760-a493-919aad24959e', 'Lembaga Penelitian dan Pengabdian', 12, '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(14, '92a29b6f-ac03-4f3a-a2bd-2e9bc8ab6ac3', 'Lembaga Penelitian', 12, '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(15, '9cd9bdc1-3159-46e6-bad3-6a7edaee3ebf', 'Lembaga Pengabdian', 12, '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(16, '3128685c-49ae-44ac-90e3-fa2e6f4c6788', 'Perpustakaan', NULL, '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(17, '8756944f-3748-43b7-bfd0-910fc5fef49f', 'Biro Akademik dan Kemahasiswaan', NULL, '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL),
(18, '10ff5471-8fcc-4890-9ea6-2dbc6d962557', 'Biro Keuangan', NULL, '2026-04-24 01:50:41', '2026-04-24 01:50:41', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-04-23-125302', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', 1776948913, 1),
(2, '2026-04-23-125322', 'App\\Database\\Migrations\\CreateRolesTable', 'default', 'App', 1776948913, 1),
(3, '2026-04-23-125331', 'App\\Database\\Migrations\\CreatePermissionsTable', 'default', 'App', 1776948913, 1),
(4, '2026-04-23-125340', 'App\\Database\\Migrations\\CreateUserRolesTable', 'default', 'App', 1776948913, 1),
(5, '2026-04-23-125353', 'App\\Database\\Migrations\\CreateRolePermissionsTable', 'default', 'App', 1776948913, 1),
(6, '2026-04-23-143318', 'App\\Database\\Migrations\\CreateMasterProfesi', 'default', 'App', 1776955123, 2),
(7, '2026-04-23-143326', 'App\\Database\\Migrations\\CreateMasterBidangIlmu', 'default', 'App', 1776955123, 2),
(8, '2026-04-23-143334', 'App\\Database\\Migrations\\CreateMasterJabatanFungsional', 'default', 'App', 1776955123, 2),
(9, '2026-04-23-143346', 'App\\Database\\Migrations\\CreateMasterFakultas', 'default', 'App', 1776955123, 2),
(10, '2026-04-23-143354', 'App\\Database\\Migrations\\CreateMasterUnitKerja', 'default', 'App', 1776955123, 2),
(11, '2026-04-23-143411', 'App\\Database\\Migrations\\CreateMasterProgramStudi', 'default', 'App', 1776955123, 2),
(12, '2026-04-24-020623', 'App\\Database\\Migrations\\CreateUserProfiles', 'default', 'App', 1776996420, 3),
(13, '2026-04-24-100000', 'App\\Database\\Migrations\\AddDosenReviewerRoles', 'default', 'App', 1777025755, 4),
(14, '2026-04-24-110000', 'App\\Database\\Migrations\\AddProfileManageToAdmin', 'default', 'App', 1777027394, 5),
(15, '2026-04-24-133044', 'App\\Database\\Migrations\\CreatePublikasiTable', 'default', 'App', 1777043179, 6),
(16, '2026-04-25-091200', 'App\\Database\\Migrations\\AddPenulisKlasterToPublikasi', 'default', 'App', 1777084056, 7),
(17, '2026-04-25-103000', 'App\\Database\\Migrations\\CreateKegiatanMandiriTable', 'default', 'App', 1777093726, 8),
(18, '2026-04-25-140000', 'App\\Database\\Migrations\\CreateRiwayatPendidikanTable', 'default', 'App', 1777098895, 9),
(19, '2026-04-25-150000', 'App\\Database\\Migrations\\CreateKelengkapanDokumenTable', 'default', 'App', 1777099905, 10),
(20, '2026-04-26-100000', 'App\\Database\\Migrations\\CreateSintaProfilesTable', 'default', 'App', 1777166648, 11);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `uuid`, `name`, `description`, `created_at`, `updated_at`) VALUES
(18, 'd43c944a-1fe0-42a3-857d-aeac99296b6a', 'admin.access', 'Akses panel admin', '2026-04-24 01:50:52', '2026-04-24 01:50:52'),
(19, '5611ab16-b685-4591-a2de-494f4b57c31b', 'users.manage', 'Manajemen user', '2026-04-24 01:50:52', '2026-04-24 01:50:52'),
(20, 'daf2ddff-031c-49e6-bcfb-d91658ee874f', 'proposals.view', 'Lihat proposal', '2026-04-24 01:50:52', '2026-04-24 01:50:52'),
(21, 'e20f2b17-ac60-4cf3-ab91-8d5b1bd6906c', 'master.manage', 'Mengelola Data Master', '2026-04-24 01:50:52', '2026-04-24 01:50:52'),
(22, 'f8b1503e-18a6-4851-a845-01fa01e958b2', 'dashboard.access', 'Akses dashboard', '2026-04-24 10:15:55', '2026-04-24 10:15:55'),
(23, '7e905e48-fc7c-4fa2-b0c6-b3524bf120b1', 'dosen.access', 'Akses panel dosen', '2026-04-24 10:15:55', '2026-04-24 10:15:55'),
(24, '818a6503-1f48-484a-9f2a-ab8076fa1dec', 'profile.manage', 'Kelola profil sendiri', '2026-04-24 10:15:55', '2026-04-24 10:15:55'),
(25, '3ab26355-8ca7-4de9-9126-23b54255c493', 'reviewer.access', 'Akses panel reviewer', '2026-04-24 10:15:55', '2026-04-24 10:15:55'),
(26, 'f2b73f0c-84cb-4782-bfd1-5bcbfabc6ba6', 'reviews.manage', 'Mengelola review proposal', '2026-04-24 10:15:55', '2026-04-24 10:15:55');

-- --------------------------------------------------------

--
-- Table structure for table `publikasi`
--

CREATE TABLE `publikasi` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(36) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `penulis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_publikasi` enum('Jurnal','HKI','Prosiding','Buku') COLLATE utf8mb4_general_ci NOT NULL,
  `tahun` year NOT NULL,
  `klaster` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sumber_pembiayaan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `publikasi`
--

INSERT INTO `publikasi` (`id`, `uuid`, `user_id`, `judul`, `penulis`, `jenis_publikasi`, `tahun`, `klaster`, `sumber_pembiayaan`, `metadata`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '97fc65ba-489f-4494-8b98-8de7c093035c', 9, 'Kocak', 'Udin', 'HKI', '2025', 'Nasional', 'Mandiri', '{\"url\": \"www.google.com\", \"no_hki\": \"HKI\"}', '2026-04-25 02:35:09', '2026-04-25 02:35:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_pendidikan`
--

CREATE TABLE `riwayat_pendidikan` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(36) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `jenjang_pendidikan` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `program_studi` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `institusi` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tahun_masuk` year NOT NULL,
  `tahun_lulus` year NOT NULL,
  `ipk` decimal(3,2) DEFAULT NULL,
  `dokumen_ijazah` text COLLATE utf8mb4_general_ci,
  `dokumen_tipe` enum('url','file') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'url',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riwayat_pendidikan`
--

INSERT INTO `riwayat_pendidikan` (`id`, `uuid`, `user_id`, `jenjang_pendidikan`, `program_studi`, `institusi`, `tahun_masuk`, `tahun_lulus`, `ipk`, `dokumen_ijazah`, `dokumen_tipe`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'b7d6c39f-5c24-454b-ad8d-db498ee41e70', 9, 'S1', 'Ilmu Komputer', 'UGM', '2017', '2021', 3.50, 'uploads/riwayat_pendidikan/1777102208_13adad45e5d109b36ac8.pdf', 'file', '2026-04-25 06:41:07', '2026-04-25 07:30:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `uuid`, `name`, `description`, `created_at`, `updated_at`) VALUES
(6, '1add0d81-83cc-4ddf-a3b1-e800aa8c4964', 'admin', 'Administrator', '2026-04-24 01:50:52', '2026-04-24 01:50:52'),
(7, 'ac9151c8-c201-4dde-acf0-729ccfd8f19b', 'dosen', 'Dosen / Lecturer', '2026-04-24 10:15:55', '2026-04-24 10:15:55'),
(8, '362ba455-0e32-48fd-8d33-9a314cb934aa', 'reviewer', 'Reviewer Proposal', '2026-04-24 10:15:55', '2026-04-24 10:15:55');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` int UNSIGNED NOT NULL,
  `permission_id` int UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES
(6, 18, NULL),
(6, 19, NULL),
(6, 20, NULL),
(6, 21, NULL),
(6, 22, NULL),
(6, 24, NULL),
(7, 22, NULL),
(7, 23, NULL),
(7, 24, NULL),
(8, 22, NULL),
(8, 24, NULL),
(8, 25, NULL),
(8, 26, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sinta_profiles`
--

CREATE TABLE `sinta_profiles` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(36) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `id_sinta` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_sinta` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sinta_score_all_years` decimal(10,2) DEFAULT NULL,
  `sinta_score_3_years` decimal(10,2) DEFAULT NULL,
  `sinta_profile_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_validasi_sinta` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Belum Sinkron',
  `sync_status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'never',
  `sync_error_message` text COLLATE utf8mb4_general_ci,
  `raw_payload_json` longtext COLLATE utf8mb4_general_ci,
  `last_synced_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sinta_profiles`
--

INSERT INTO `sinta_profiles` (`id`, `uuid`, `user_id`, `id_sinta`, `nama_sinta`, `sinta_score_all_years`, `sinta_score_3_years`, `sinta_profile_url`, `status_validasi_sinta`, `sync_status`, `sync_error_message`, `raw_payload_json`, `last_synced_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '0136d0d1-0028-4ae6-a821-7e2b80be1a57', 9, '6824588', 'SUMARNO', 43.00, 13.00, 'https://sinta.kemdiktisaintek.go.id/authors/profile/6824588', 'Tersinkronisasi', 'success', NULL, '{\"nama_sinta\":\"SUMARNO\",\"id_sinta\":\"6824588\",\"sinta_score_all_years\":43,\"sinta_score_3_years\":13,\"status_validasi_sinta\":\"Tersinkronisasi\",\"sinta_profile_url\":\"https:\\/\\/sinta.kemdiktisaintek.go.id\\/authors\\/profile\\/6824588\"}', '2026-04-26 01:35:00', '2026-04-26 01:29:51', '2026-04-26 01:35:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_lengkap` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `updated_by` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uuid`, `username`, `email`, `password`, `nama_lengkap`, `aktif`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`) VALUES
(6, 'ab6c83dd-45b1-483a-9ffb-ee6bfec1cecd', 'ADMIN LP2M', 'admin@litapdimas.ac.id', '$2y$10$4dbqI40cMgV7DrlMiJXp.O32lGMFa948hsaz8TKpLM44Nf2rUH5yO', 'Administrator', 1, '2026-04-24 01:50:52', '2026-04-24 11:40:40', NULL, NULL, NULL),
(7, 'df0fb90a-8922-4f0c-8eca-f282fce592aa', 'rizqi', 'rizqi@uinsi.ac.id', '$2y$10$kYYwrLGvQox5kifhY.KLu.fgUni.ULDeK3EzvyfT29dJ7juFIjpse', 'Admin LPPM 2', 1, '2026-04-24 03:00:57', '2026-04-24 12:55:11', NULL, NULL, NULL),
(8, '95ae4ea2-83d7-4233-8002-93a9391a2ca1', 'amru', 'amru@uinsi.ac.id', '$2y$10$oDJUEFgmZNt8HmEEPAEzkO4bKbUOwx87spxSNY4LCYbCDCv3UB15m', 'Amirul Hadi', 1, '2026-04-24 06:46:37', '2026-04-24 06:46:37', NULL, NULL, NULL),
(9, 'a892502a-e93b-46b9-a6d5-109425776c85', 'Hernan', 'hernan@uinsi.ac.id', '$2y$10$iEf73ZnIKiPe2YF9TXUCNeKq9n/a4rKkiouBtjqOdYN45.jl8u8kC', 'Hernansyah', 1, '2026-04-24 10:35:53', '2026-04-26 01:53:30', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` int UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gelar_depan` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gelar_belakang` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_kelamin` enum('L','P') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tempat_lahir` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `no_hp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nik` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nidn` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nip` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `profesi_id` int UNSIGNED DEFAULT NULL,
  `bidang_ilmu_id` int UNSIGNED DEFAULT NULL,
  `fakultas_id` int UNSIGNED DEFAULT NULL,
  `program_studi_id` int UNSIGNED DEFAULT NULL,
  `jabatan_fungsional_id` int UNSIGNED DEFAULT NULL,
  `id_sinta` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `updated_by` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `uuid`, `user_id`, `foto`, `gelar_depan`, `gelar_belakang`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_hp`, `nik`, `nidn`, `nip`, `profesi_id`, `bidang_ilmu_id`, `fakultas_id`, `program_studi_id`, `jabatan_fungsional_id`, `id_sinta`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`) VALUES
(16, '92578a3f-5721-4d9d-a8f5-7c645620588e', 7, NULL, NULL, NULL, NULL, 'Samarinda', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-24 03:24:53', '2026-04-24 12:55:11', NULL, NULL, NULL),
(17, 'd291e3e5-0c68-4f9c-aae0-24aad1f31806', 9, 'profile/1777168410_c334984727e874276299.png', 'Dr', 'S.Kom', 'L', 'Muara Muntai', '2026-04-24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-24 10:48:00', '2026-04-26 01:53:30', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` int UNSIGNED NOT NULL,
  `role_id` int UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`user_id`, `role_id`, `created_at`) VALUES
(6, 6, NULL),
(7, 6, NULL),
(8, 6, NULL),
(9, 7, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kegiatan_mandiri`
--
ALTER TABLE `kegiatan_mandiri`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `kegiatan_mandiri_user_id_foreign` (`user_id`);

--
-- Indexes for table `kelengkapan_dokumen`
--
ALTER TABLE `kelengkapan_dokumen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `kelengkapan_dokumen_user_id_foreign` (`user_id`);

--
-- Indexes for table `master_bidang_ilmu`
--
ALTER TABLE `master_bidang_ilmu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Indexes for table `master_fakultas`
--
ALTER TABLE `master_fakultas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Indexes for table `master_jabatan_fungsional`
--
ALTER TABLE `master_jabatan_fungsional`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Indexes for table `master_profesi`
--
ALTER TABLE `master_profesi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Indexes for table `master_program_studi`
--
ALTER TABLE `master_program_studi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `master_program_studi_fakultas_id_foreign` (`fakultas_id`);

--
-- Indexes for table `master_unit_kerja`
--
ALTER TABLE `master_unit_kerja`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `master_unit_kerja_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Indexes for table `publikasi`
--
ALTER TABLE `publikasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `publikasi_user_id_foreign` (`user_id`);

--
-- Indexes for table `riwayat_pendidikan`
--
ALTER TABLE `riwayat_pendidikan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `riwayat_pendidikan_user_id_foreign` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `role_permissions_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `sinta_profiles`
--
ALTER TABLE `sinta_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `user_profiles_profesi_id_foreign` (`profesi_id`),
  ADD KEY `user_profiles_bidang_ilmu_id_foreign` (`bidang_ilmu_id`),
  ADD KEY `user_profiles_fakultas_id_foreign` (`fakultas_id`),
  ADD KEY `user_profiles_program_studi_id_foreign` (`program_studi_id`),
  ADD KEY `user_profiles_jabatan_fungsional_id_foreign` (`jabatan_fungsional_id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `user_roles_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kegiatan_mandiri`
--
ALTER TABLE `kegiatan_mandiri`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kelengkapan_dokumen`
--
ALTER TABLE `kelengkapan_dokumen`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `master_bidang_ilmu`
--
ALTER TABLE `master_bidang_ilmu`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `master_fakultas`
--
ALTER TABLE `master_fakultas`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `master_jabatan_fungsional`
--
ALTER TABLE `master_jabatan_fungsional`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `master_profesi`
--
ALTER TABLE `master_profesi`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `master_program_studi`
--
ALTER TABLE `master_program_studi`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `master_unit_kerja`
--
ALTER TABLE `master_unit_kerja`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `publikasi`
--
ALTER TABLE `publikasi`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `riwayat_pendidikan`
--
ALTER TABLE `riwayat_pendidikan`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sinta_profiles`
--
ALTER TABLE `sinta_profiles`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kegiatan_mandiri`
--
ALTER TABLE `kegiatan_mandiri`
  ADD CONSTRAINT `kegiatan_mandiri_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kelengkapan_dokumen`
--
ALTER TABLE `kelengkapan_dokumen`
  ADD CONSTRAINT `kelengkapan_dokumen_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `master_program_studi`
--
ALTER TABLE `master_program_studi`
  ADD CONSTRAINT `master_program_studi_fakultas_id_foreign` FOREIGN KEY (`fakultas_id`) REFERENCES `master_fakultas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `master_unit_kerja`
--
ALTER TABLE `master_unit_kerja`
  ADD CONSTRAINT `master_unit_kerja_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `master_unit_kerja` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `publikasi`
--
ALTER TABLE `publikasi`
  ADD CONSTRAINT `publikasi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `riwayat_pendidikan`
--
ALTER TABLE `riwayat_pendidikan`
  ADD CONSTRAINT `riwayat_pendidikan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sinta_profiles`
--
ALTER TABLE `sinta_profiles`
  ADD CONSTRAINT `sinta_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_bidang_ilmu_id_foreign` FOREIGN KEY (`bidang_ilmu_id`) REFERENCES `master_bidang_ilmu` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `user_profiles_fakultas_id_foreign` FOREIGN KEY (`fakultas_id`) REFERENCES `master_fakultas` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `user_profiles_jabatan_fungsional_id_foreign` FOREIGN KEY (`jabatan_fungsional_id`) REFERENCES `master_jabatan_fungsional` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `user_profiles_profesi_id_foreign` FOREIGN KEY (`profesi_id`) REFERENCES `master_profesi` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `user_profiles_program_studi_id_foreign` FOREIGN KEY (`program_studi_id`) REFERENCES `master_program_studi` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `user_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
