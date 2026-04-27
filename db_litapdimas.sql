-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 27, 2026 at 02:49 PM
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
-- Table structure for table `bidang_ilmu`
--

CREATE TABLE `bidang_ilmu` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Active, 0=Inactive',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `updated_by` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bidang_ilmu`
--

INSERT INTO `bidang_ilmu` (`id`, `uuid`, `nama`, `keterangan`, `is_active`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`) VALUES
(1, '1c7f976d-29d3-4a4c-a754-1473e8fc7987', 'Studi Islam/Dirasat Islamiyah/Islamic Studies', NULL, 1, '2026-04-26 11:03:08', '2026-04-26 11:28:05', NULL, NULL, 7),
(2, 'c9bd3179-a809-4cd3-8ce1-a6a761da7fc5', 'Ekonomi dan Bisnis Islam', NULL, 1, '2026-04-26 11:28:22', '2026-04-26 11:28:22', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan_mandiri`
--

CREATE TABLE `kegiatan_mandiri` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `tahun` year NOT NULL,
  `jenis_kegiatan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `klaster_skala_kegiatan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `judul_kegiatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `anggota_terlibat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `resume_kegiatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `unit_pelaksana_kegiatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mitra_kolaborasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sumber_dana` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `besaran_dana` bigint UNSIGNED DEFAULT NULL,
  `tautan_bukti_dukung` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
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
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `jenis_dokumen` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Sertifikat Dosen, SK Jabatan Fungsional, Kartu NIDN',
  `dokumen_file` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Path ke file dokumen',
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
-- Table structure for table `klaster_bantuan`
--

CREATE TABLE `klaster_bantuan` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Active, 0=Inactive',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `updated_by` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `klaster_bantuan`
--

INSERT INTO `klaster_bantuan` (`id`, `uuid`, `nama`, `keterangan`, `is_active`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`) VALUES
(1, 'b61b1dff-17cf-4ff4-a8e8-4602b45573ba', 'BOPTN', NULL, 1, '2026-04-26 13:52:04', '2026-04-26 13:52:04', NULL, NULL, NULL),
(2, 'b98217c6-5a75-4a02-b4c6-679cb9802c30', 'Dana Hibah ', NULL, 1, '2026-04-26 13:52:11', '2026-04-26 13:52:11', NULL, NULL, NULL);

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
(20, '2026-04-26-100000', 'App\\Database\\Migrations\\CreateSintaProfilesTable', 'default', 'App', 1777166648, 11),
(21, '2026-04-26-084109', 'App\\Database\\Migrations\\CreateBidangIlmuTable', 'default', 'App', 1777193239, 12),
(22, '2026-04-26-084118', 'App\\Database\\Migrations\\CreateKlasterBantuanTable', 'default', 'App', 1777193239, 12),
(23, '2026-04-26-084129', 'App\\Database\\Migrations\\CreateTemaPenelitianTable', 'default', 'App', 1777193239, 12),
(24, '2026-04-26-090000', 'App\\Database\\Migrations\\AddDeletedAtToProposalTables', 'default', 'App', 1777200599, 13),
(25, '2026-04-26-100000', 'App\\Database\\Migrations\\AddUserstampsToProposalTables', 'default', 'App', 1777202824, 14),
(27, '2026-04-27-080000', 'App\\Database\\Migrations\\CreateProposalMasterTables', 'default', 'App', 1777249271, 15),
(28, '2026-04-27-090000', 'App\\Database\\Migrations\\CreateProposalTransactionTables', 'default', 'App', 1777249271, 15),
(29, '2026-04-27-095000', 'App\\Database\\Migrations\\AddMissingProposalMasterTables', 'default', 'App', 1777249548, 16),
(30, '2026-04-27-130000', 'App\\Database\\Migrations\\CreateProposalReviewerAssignmentsTable', 'default', 'App', 1777301195, 17);

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
-- Table structure for table `proposal_anggota_eksternal`
--

CREATE TABLE `proposal_anggota_eksternal` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `proposal_id` int UNSIGNED NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `institusi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `posisi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tipe` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'PTU, Profesional, Other',
  `order_position` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proposal_bidang_ilmu`
--

CREATE TABLE `proposal_bidang_ilmu` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `updated_by` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proposal_bidang_ilmu`
--

INSERT INTO `proposal_bidang_ilmu` (`id`, `uuid`, `nama`, `keterangan`, `is_active`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`) VALUES
(1, '550e8400-e29b-41d4-a716-446655440031', 'Teknologi Informasi', 'Bidang ilmu yang fokus pada teknologi informasi', 1, NULL, NULL, NULL, 1, 1),
(2, '550e8400-e29b-41d4-a716-446655440032', 'Sains', 'Bidang ilmu sains alam', 1, NULL, NULL, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `proposal_dokumen`
--

CREATE TABLE `proposal_dokumen` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `proposal_id` int UNSIGNED NOT NULL,
  `tipe_dokumen` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'proposal, rab, similarity, pendukung',
  `nama_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `path_file` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'writable/uploads/proposal/{uuid}/{file}',
  `file_size` bigint UNSIGNED DEFAULT NULL COMMENT 'File size in bytes',
  `mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `order_position` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proposal_dokumen`
--

INSERT INTO `proposal_dokumen` (`id`, `uuid`, `proposal_id`, `tipe_dokumen`, `nama_file`, `path_file`, `file_size`, `mime_type`, `keterangan`, `order_position`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '19c18602-e3ce-4e7e-a198-d6593a0b59b0', 6, 'proposal', 'proposal_1777260897_a098a7661303c39f.pdf', 'writable/uploads/proposal/b4f90cc9-63e5-4e3a-bda2-327fe5caecff/proposal_1777260897_a098a7661303c39f.pdf', 138764, 'application/pdf', NULL, 0, '2026-04-27 03:34:57', '2026-04-27 03:34:57', NULL),
(2, '2f3b86ca-638b-4e63-b67d-fa1e2d4f72a4', 6, 'rab', 'rab_1777260897_2ffa982f22a2fee0.pdf', 'writable/uploads/proposal/b4f90cc9-63e5-4e3a-bda2-327fe5caecff/rab_1777260897_2ffa982f22a2fee0.pdf', 138764, 'application/pdf', NULL, 0, '2026-04-27 03:34:57', '2026-04-27 03:34:57', NULL),
(3, 'b3412036-5468-4ef8-a3b1-40a57bee81c3', 6, 'similarity', 'similarity_1777260897_a26ee540eee252be.pdf', 'writable/uploads/proposal/b4f90cc9-63e5-4e3a-bda2-327fe5caecff/similarity_1777260897_a26ee540eee252be.pdf', 138764, 'application/pdf', NULL, 0, '2026-04-27 03:34:57', '2026-04-27 03:34:57', NULL),
(4, 'bccb25b5-b1cc-498d-b1e3-847141f18794', 6, 'pendukung', 'pendukung_0_1777260897_ab6dbb37d5018356.pdf', 'writable/uploads/proposal/b4f90cc9-63e5-4e3a-bda2-327fe5caecff/pendukung_0_1777260897_ab6dbb37d5018356.pdf', 138764, 'application/pdf', NULL, 0, '2026-04-27 03:34:57', '2026-04-27 03:34:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `proposal_jenis_penelitian`
--

CREATE TABLE `proposal_jenis_penelitian` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Active, 0=Inactive',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `updated_by` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proposal_jenis_penelitian`
--

INSERT INTO `proposal_jenis_penelitian` (`id`, `uuid`, `nama`, `keterangan`, `is_active`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`) VALUES
(1, '550e8400-e29b-41d4-a716-446655440011', 'Penelitian Dasar', 'Penelitian yang dilakukan tanpa tujuan aplikasi langsung', 1, NULL, NULL, NULL, 1, 1),
(2, '550e8400-e29b-41d4-a716-446655440012', 'Penelitian Terapan', 'Penelitian dengan hasil yang dapat diterapkan', 1, NULL, NULL, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `proposal_jurnal`
--

CREATE TABLE `proposal_jurnal` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `proposal_id` int UNSIGNED NOT NULL,
  `issn` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_jurnal` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `profil_jurnal` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Rich text (Quill HTML)',
  `url_website` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `url_scopus_wos` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `url_surat_rekomendasi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `total_pengajuan_dana` bigint UNSIGNED DEFAULT NULL COMMENT 'Max 100000000 (Rp 100 juta)',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proposal_jurnal`
--

INSERT INTO `proposal_jurnal` (`id`, `uuid`, `proposal_id`, `issn`, `nama_jurnal`, `profil_jurnal`, `url_website`, `url_scopus_wos`, `url_surat_rekomendasi`, `total_pengajuan_dana`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '824feaa4-22ae-4316-82f4-82a886e049d0', 6, '2087-1221', 'PDF', '<p>Mungkin bagi sebagian orang akan heran kenapa hallo Potterhead? Kenapa bukan hallo guys atau yang lainnya?. Karena sapaan tersebut hanya akan dimengerti oleh para penggemar film Harry Potter.</p><p><br></p><p>Yup Potterhead adalah sebutan bagi orang yang sangat menyukai film Harry Potter atau bisa dibilang fansnya Harry Potter. Film Harry Potter sendiri diadaptasi dari novel yang berjudul sama yaitu Harry Potter novel ini ditulis oleh J.K Rowling. Novel Harry Potter merupakan novel terpopuler yang ditulis oleh JK Rowling bahkan sudah diterjemahkan ke dalam 80 bahasa yang ada di dunia termasuk Indonesia. Novel Harry Potter sendiri terbagi menjadi 7 bagian yang masing-masing saling terhubung dan terkait satu sama lain.&nbsp;</p><p><br></p><p>Kali ini kita akan membahas hal-hal menarik seputar film Harry Potter Yang pastinya para Potterhead wajib tahu!!</p>', 'https://www.kompasiana.com/hildarahmah9638/62c2dd0302c50e06d70c7e22/potterhead-sejati-pasti-tahu-10-hal-hal-menarik-film-harry-potter', 'https://www.kompasiana.com/hildarahmah9638/62c2dd0302c50e06d70c7e22/potterhead-sejati-pasti-tahu-10-hal-hal-menarik-film-harry-potter', 'https://www.kompasiana.com/hildarahmah9638/62c2dd0302c50e06d70c7e22/potterhead-sejati-pasti-tahu-10-hal-hal-menarik-film-harry-potter', 1000000, '2026-04-27 02:13:19', '2026-04-27 14:14:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `proposal_klaster_bantuan`
--

CREATE TABLE `proposal_klaster_bantuan` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `updated_by` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proposal_klaster_bantuan`
--

INSERT INTO `proposal_klaster_bantuan` (`id`, `uuid`, `nama`, `keterangan`, `is_active`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`) VALUES
(1, '550e8400-e29b-41d4-a716-446655440041', 'Penelitian Unggulan', 'Klaster penelitian unggulan universitas', 1, NULL, NULL, NULL, 1, 1),
(2, '550e8400-e29b-41d4-a716-446655440042', 'Penelitian Reguler', 'Klaster penelitian reguler', 1, NULL, NULL, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `proposal_kontribusi_prodi`
--

CREATE TABLE `proposal_kontribusi_prodi` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Active, 0=Inactive',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `updated_by` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proposal_kontribusi_prodi`
--

INSERT INTO `proposal_kontribusi_prodi` (`id`, `uuid`, `nama`, `keterangan`, `is_active`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`) VALUES
(1, '550e8400-e29b-41d4-a716-446655440021', 'Berkontribusi pada Program Studi', 'Hasil penelitian berkontribusi pada pengembangan program studi', 1, NULL, NULL, NULL, 1, 1),
(2, '550e8400-e29b-41d4-a716-446655440022', 'Tidak Berkontribusi pada Program Studi', 'Hasil penelitian tidak berkontribusi pada program studi', 1, NULL, NULL, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `proposal_mahasiswa`
--

CREATE TABLE `proposal_mahasiswa` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `proposal_id` int UNSIGNED NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nim` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `program_studi_id` int UNSIGNED DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `order_position` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proposal_peneliti`
--

CREATE TABLE `proposal_peneliti` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `proposal_id` int UNSIGNED NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `asal_instansi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `posisi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_internal` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Internal, 0=External',
  `is_ketua` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=Lead researcher, 0=Member',
  `order_position` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proposal_peneliti`
--

INSERT INTO `proposal_peneliti` (`id`, `uuid`, `proposal_id`, `nama`, `nip`, `email`, `asal_instansi`, `posisi`, `is_internal`, `is_ketua`, `order_position`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'cb8b02ec-c962-4734-8255-24c8426f4385', 6, 'Udin', '123456', 'udin@gmail.com', 'UINSI', 'Ketua', 1, 1, 0, '2026-04-27 01:40:59', '2026-04-27 01:41:05', '2026-04-27 01:41:05'),
(2, '9104f5a8-f6ee-46df-a8d2-553f44fe5ab3', 6, 'Udin', '123456', 'udin@gmail.com', 'UINSI', 'Ketua', 1, 1, 0, '2026-04-27 01:41:05', '2026-04-27 01:43:54', '2026-04-27 01:43:54'),
(3, 'd7105ded-c76b-44f6-afa1-8ac190a8a965', 6, 'Udin', '123456', 'udin@gmail.com', 'UINSI', 'Ketua', 1, 1, 0, '2026-04-27 01:43:54', '2026-04-27 01:54:35', '2026-04-27 01:54:35'),
(4, 'c6995c78-b40c-42d8-aa8f-2099cb405e1f', 6, 'Udin', '123456', 'udin@gmail.com', 'UINSI', 'Ketua', 1, 1, 0, '2026-04-27 01:54:35', '2026-04-27 02:13:58', '2026-04-27 02:13:58'),
(5, 'b7b50778-7725-4ca6-98b6-73a1ebdf5855', 6, 'Udin', '123456', 'udin@gmail.com', 'UINSI', 'Ketua', 1, 1, 0, '2026-04-27 02:13:58', '2026-04-27 02:56:05', '2026-04-27 02:56:05'),
(6, 'b2128281-0fcd-4dfd-9910-6a8788817dad', 6, 'Udin', '123456', 'udin@gmail.com', 'UINSI', 'Ketua', 1, 1, 0, '2026-04-27 02:56:05', '2026-04-27 02:56:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `proposal_pengajuan`
--

CREATE TABLE `proposal_pengajuan` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kata_kunci` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Comma-separated keywords',
  `pengelola_bantuan_id` int UNSIGNED DEFAULT NULL,
  `klaster_bantuan_id` int UNSIGNED DEFAULT NULL,
  `bidang_ilmu_id` int UNSIGNED DEFAULT NULL,
  `tema_penelitian_id` int UNSIGNED DEFAULT NULL,
  `jenis_penelitian_id` int UNSIGNED DEFAULT NULL,
  `kontribusi_prodi_id` int UNSIGNED DEFAULT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'draft' COMMENT 'draft, submitted, reviewed, approved, rejected',
  `current_step` int NOT NULL DEFAULT '1' COMMENT '1-5 for wizard steps',
  `step_1_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'JSON: Step 1 draft data',
  `step_2_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'JSON: Step 2 draft data',
  `step_3_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'JSON: Step 3 draft data',
  `step_4_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'JSON: Step 4 draft data',
  `step_5_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'JSON: Step 5 draft data',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `updated_by` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proposal_pengajuan`
--

INSERT INTO `proposal_pengajuan` (`id`, `uuid`, `user_id`, `judul`, `kata_kunci`, `pengelola_bantuan_id`, `klaster_bantuan_id`, `bidang_ilmu_id`, `tema_penelitian_id`, `jenis_penelitian_id`, `kontribusi_prodi_id`, `status`, `current_step`, `step_1_data`, `step_2_data`, `step_3_data`, `step_4_data`, `step_5_data`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`) VALUES
(6, 'b4f90cc9-63e5-4e3a-bda2-327fe5caecff', 9, 'Eksplorasi Penggunaan Artificial intelligence dalam Membentuk integritas Akademik dan Praktik diskursus Akademik: Studi Mixed-methods di Program Studi Bahasa inggris Ptkin', 'pendidikan, agama, kunci', 1, 1, 2, 1, 2, 1, 'submitted', 5, '{\"csrf_test_name\":\"e9efe0668e4d0f3b12f1e2fb6786fcce\",\"judul\":\"Eksplorasi Penggunaan Artificial intelligence dalam Membentuk integritas Akademik dan Praktik diskursus Akademik: Studi Mixed-methods di Program Studi Bahasa inggris Ptkin\",\"kata_kunci\":\"pendidikan, agama, kunci\",\"pengelola_bantuan_id\":\"1\",\"klaster_bantuan_id\":\"1\",\"bidang_ilmu_id\":\"2\",\"tema_penelitian_id\":\"1\",\"jenis_penelitian_id\":\"2\",\"kontribusi_prodi_id\":\"1\",\"statement_1\":\"1\",\"statement_2\":\"1\",\"statement_3\":\"1\"}', '{\"csrf_test_name\":\"e9efe0668e4d0f3b12f1e2fb6786fcce\",\"peneliti_internal\":[{\"nama\":\"Udin\",\"nip\":\"123456\",\"email\":\"udin@gmail.com\",\"asal_instansi\":\"UINSI\",\"posisi\":\"Ketua\"}]}', '{\"csrf_test_name\":\"e9efe0668e4d0f3b12f1e2fb6786fcce\",\"abstrak\":\"<p>Potterhead adalah sebutan atau julukan bagi penggemar berat seri buku dan film Harry Potter karya J.K. Rowling. Mereka biasanya memiliki dedikasi tinggi, mengenali detail dunia sihir (Potterverse) secara mendalam, dan sering kali menganggap seri ini sebagai bagian penting dari masa kecil atau budaya populer.<\\/p>\",\"substansi_bagian\":[{\"judul_bagian\":\"Latar Belakang\",\"isi_bagian\":\"<p>Potterhead adalah sebutan atau julukan bagi penggemar berat seri buku dan film Harry Potter karya J.K. Rowling. Mereka biasanya memiliki dedikasi tinggi, mengenali detail dunia sihir (Potterverse) secara mendalam, dan sering kali menganggap seri ini sebagai bagian penting dari masa kecil atau budaya populer.<\\/p>\"}]}', '{\"csrf_test_name\":\"e9efe0668e4d0f3b12f1e2fb6786fcce\"}', '{\"csrf_test_name\":\"4b8e6d7ffd73ecbb98cc896e240305fb\",\"issn\":\"2087-1221\",\"nama_jurnal\":\"PDF\",\"profil_jurnal\":\"<p>Mungkin bagi sebagian orang akan heran kenapa hallo Potterhead? Kenapa bukan hallo guys atau yang lainnya?. Karena sapaan tersebut hanya akan dimengerti oleh para penggemar film Harry Potter.<\\/p><p><br><\\/p><p>Yup Potterhead adalah sebutan bagi orang yang sangat menyukai film Harry Potter atau bisa dibilang fansnya Harry Potter. Film Harry Potter sendiri diadaptasi dari novel yang berjudul sama yaitu Harry Potter novel ini ditulis oleh J.K Rowling. Novel Harry Potter merupakan novel terpopuler yang ditulis oleh JK Rowling bahkan sudah diterjemahkan ke dalam 80 bahasa yang ada di dunia termasuk Indonesia. Novel Harry Potter sendiri terbagi menjadi 7 bagian yang masing-masing saling terhubung dan terkait satu sama lain.&nbsp;<\\/p><p><br><\\/p><p>Kali ini kita akan membahas hal-hal menarik seputar film Harry Potter Yang pastinya para Potterhead wajib tahu!!<\\/p>\",\"url_website\":\"https:\\/\\/www.kompasiana.com\\/hildarahmah9638\\/62c2dd0302c50e06d70c7e22\\/potterhead-sejati-pasti-tahu-10-hal-hal-menarik-film-harry-potter\",\"url_scopus_wos\":\"https:\\/\\/www.kompasiana.com\\/hildarahmah9638\\/62c2dd0302c50e06d70c7e22\\/potterhead-sejati-pasti-tahu-10-hal-hal-menarik-film-harry-potter\",\"url_surat_rekomendasi\":\"https:\\/\\/www.kompasiana.com\\/hildarahmah9638\\/62c2dd0302c50e06d70c7e22\\/potterhead-sejati-pasti-tahu-10-hal-hal-menarik-film-harry-potter\",\"total_pengajuan_dana\":\"1000000\"}', '2026-04-27 01:08:03', '2026-04-27 14:14:09', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `proposal_pengelola_bantuan`
--

CREATE TABLE `proposal_pengelola_bantuan` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Active, 0=Inactive',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `updated_by` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proposal_pengelola_bantuan`
--

INSERT INTO `proposal_pengelola_bantuan` (`id`, `uuid`, `nama`, `keterangan`, `is_active`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`) VALUES
(1, '550e8400-e29b-41d4-a716-446655440001', 'Ditjen Dikti', 'Direktorat Jenderal Pendidikan Tinggi', 1, NULL, NULL, NULL, 1, 1),
(2, '550e8400-e29b-41d4-a716-446655440002', 'LPPM Universitas', 'Lembaga Penelitian dan Pengabdian kepada Masyarakat', 1, NULL, NULL, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `proposal_reviewer_assignments`
--

CREATE TABLE `proposal_reviewer_assignments` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(36) COLLATE utf8mb4_general_ci NOT NULL,
  `proposal_id` int UNSIGNED NOT NULL,
  `reviewer_user_id` int UNSIGNED NOT NULL,
  `assigned_by` int UNSIGNED DEFAULT NULL,
  `assignment_notes` text COLLATE utf8mb4_general_ci,
  `status` varchar(30) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'assigned' COMMENT 'assigned, reviewed, declined',
  `recommendation` varchar(30) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending' COMMENT 'pending, recommended, revision, rejected',
  `review_notes` longtext COLLATE utf8mb4_general_ci,
  `reviewed_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proposal_substansi_bagian`
--

CREATE TABLE `proposal_substansi_bagian` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `proposal_id` int UNSIGNED NOT NULL,
  `abstrak` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Rich text (Quill HTML)',
  `judul_bagian` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `isi_bagian` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Rich text (Quill HTML)',
  `order_position` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proposal_substansi_bagian`
--

INSERT INTO `proposal_substansi_bagian` (`id`, `uuid`, `proposal_id`, `abstrak`, `judul_bagian`, `isi_bagian`, `order_position`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '76e6492b-1ca3-4a16-bbc9-1991fc80c8bc', 6, '', 'Latar Belakang', '<p>Potterhead adalah sebutan atau julukan bagi penggemar berat seri buku dan film Harry Potter karya J.K. Rowling. Mereka biasanya memiliki dedikasi tinggi, mengenali detail dunia sihir (Potterverse) secara mendalam, dan sering kali menganggap seri ini sebagai bagian penting dari masa kecil atau budaya populer.</p>', 1, '2026-04-27 02:00:00', '2026-04-27 02:04:14', '2026-04-27 02:04:14'),
(2, 'c6f3513f-4a62-43b5-a1e7-e868c9cdb77d', 6, '', 'Latar Belakang', '<p>Potterhead adalah sebutan atau julukan bagi penggemar berat seri buku dan film Harry Potter karya J.K. Rowling. Mereka biasanya memiliki dedikasi tinggi, mengenali detail dunia sihir (Potterverse) secara mendalam, dan sering kali menganggap seri ini sebagai bagian penting dari masa kecil atau budaya populer.</p>', 1, '2026-04-27 02:04:14', '2026-04-27 02:14:01', '2026-04-27 02:14:01'),
(3, '7428ed40-2aff-406a-8ffa-d36208c0dea1', 6, '', 'Latar Belakang', '<p>Potterhead adalah sebutan atau julukan bagi penggemar berat seri buku dan film Harry Potter karya J.K. Rowling. Mereka biasanya memiliki dedikasi tinggi, mengenali detail dunia sihir (Potterverse) secara mendalam, dan sering kali menganggap seri ini sebagai bagian penting dari masa kecil atau budaya populer.</p>', 1, '2026-04-27 02:14:01', '2026-04-27 02:56:07', '2026-04-27 02:56:07'),
(4, '45dce494-9b63-4dea-a752-defaca8fb572', 6, '', 'Latar Belakang', '<p>Potterhead adalah sebutan atau julukan bagi penggemar berat seri buku dan film Harry Potter karya J.K. Rowling. Mereka biasanya memiliki dedikasi tinggi, mengenali detail dunia sihir (Potterverse) secara mendalam, dan sering kali menganggap seri ini sebagai bagian penting dari masa kecil atau budaya populer.</p>', 1, '2026-04-27 02:56:07', '2026-04-27 02:56:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `proposal_tema_penelitian`
--

CREATE TABLE `proposal_tema_penelitian` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `updated_by` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proposal_tema_penelitian`
--

INSERT INTO `proposal_tema_penelitian` (`id`, `uuid`, `nama`, `keterangan`, `is_active`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`) VALUES
(1, '550e8400-e29b-41d4-a716-446655440051', 'Transformasi Digital', 'Tema penelitian seputar transformasi digital', 1, NULL, NULL, NULL, 1, 1),
(2, '550e8400-e29b-41d4-a716-446655440052', 'Sustainability', 'Tema penelitian seputar keberlanjutan', 1, NULL, NULL, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `publikasi`
--

CREATE TABLE `publikasi` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `penulis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_publikasi` enum('Jurnal','HKI','Prosiding','Buku') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tahun` year NOT NULL,
  `klaster` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sumber_pembiayaan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `jenjang_pendidikan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `program_studi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `institusi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tahun_masuk` year NOT NULL,
  `tahun_lulus` year NOT NULL,
  `ipk` decimal(3,2) DEFAULT NULL,
  `dokumen_ijazah` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `dokumen_tipe` enum('url','file') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'url',
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
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `id_sinta` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_sinta` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sinta_score_all_years` decimal(10,2) DEFAULT NULL,
  `sinta_score_3_years` decimal(10,2) DEFAULT NULL,
  `sinta_profile_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_validasi_sinta` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Belum Sinkron',
  `sync_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'never',
  `sync_error_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `raw_payload_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `last_synced_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sinta_profiles`
--

INSERT INTO `sinta_profiles` (`id`, `uuid`, `user_id`, `id_sinta`, `nama_sinta`, `sinta_score_all_years`, `sinta_score_3_years`, `sinta_profile_url`, `status_validasi_sinta`, `sync_status`, `sync_error_message`, `raw_payload_json`, `last_synced_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '0136d0d1-0028-4ae6-a821-7e2b80be1a57', 9, '6824588', 'SUMARNO', 43.00, 13.00, 'https://sinta.kemdiktisaintek.go.id/authors/profile/6824588', 'Tersinkronisasi', 'success', NULL, '{\"nama_sinta\":\"SUMARNO\",\"id_sinta\":\"6824588\",\"sinta_score_all_years\":43,\"sinta_score_3_years\":13,\"status_validasi_sinta\":\"Tersinkronisasi\",\"sinta_profile_url\":\"https:\\/\\/sinta.kemdiktisaintek.go.id\\/authors\\/profile\\/6824588\"}', '2026-04-26 03:45:05', '2026-04-26 01:29:51', '2026-04-26 03:45:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tema_penelitian`
--

CREATE TABLE `tema_penelitian` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Active, 0=Inactive',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `updated_by` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tema_penelitian`
--

INSERT INTO `tema_penelitian` (`id`, `uuid`, `nama`, `keterangan`, `is_active`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`) VALUES
(1, '7a7bba99-d340-488f-b608-7545d2fc7438', 'Agama dan Keagamaan', NULL, 1, '2026-04-26 13:52:36', '2026-04-26 13:52:36', NULL, NULL, NULL);

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
(6, 'ab6c83dd-45b1-483a-9ffb-ee6bfec1cecd', 'admin', 'admin@litapdimas.ac.id', '$2y$10$UJwZjl9OWBEaHy98AxkJDOvcZX9Y2AhHxHvSwkYBPoaKEAUkJXmDO', 'Administrator', 1, '2026-04-24 01:50:52', '2026-04-26 23:47:26', NULL, NULL, NULL),
(7, 'df0fb90a-8922-4f0c-8eca-f282fce592aa', 'rizqi', 'rizqi@uinsi.ac.id', '$2y$10$kYYwrLGvQox5kifhY.KLu.fgUni.ULDeK3EzvyfT29dJ7juFIjpse', 'Admin LPPM 2', 1, '2026-04-24 03:00:57', '2026-04-26 23:47:30', NULL, NULL, NULL),
(8, '95ae4ea2-83d7-4233-8002-93a9391a2ca1', 'amru', 'amru@uinsi.ac.id', '$2y$10$oDJUEFgmZNt8HmEEPAEzkO4bKbUOwx87spxSNY4LCYbCDCv3UB15m', 'Amirul Hadi', 1, '2026-04-24 06:46:37', '2026-04-24 06:46:37', NULL, NULL, NULL),
(9, 'a892502a-e93b-46b9-a6d5-109425776c85', 'Hernan', 'hernan@uinsi.ac.id', '$2y$10$iEf73ZnIKiPe2YF9TXUCNeKq9n/a4rKkiouBtjqOdYN45.jl8u8kC', 'Hernansyah', 1, '2026-04-24 10:35:53', '2026-04-26 06:38:15', NULL, NULL, NULL),
(16, '550e8400-e29b-41d4-a716-446655550001', 'dosen1', 'dosen1@example.com', '$2y$10$W2bQEfcWSzQiQyZj7Vq1WuL6bNUBfcTGUqYWFpAQcBsgdTMtA9Tj6', 'Dr. Ahmad Wijaya', 1, NULL, NULL, NULL, 1, 1),
(17, '550e8400-e29b-41d4-a716-446655550002', 'dosen2', 'dosen2@example.com', '$2y$10$W2bQEfcWSzQiQyZj7Vq1WuL6bNUBfcTGUqYWFpAQcBsgdTMtA9Tj6', 'Prof. Siti Nurhaliza', 1, NULL, NULL, NULL, 1, 1);

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
(16, '92578a3f-5721-4d9d-a8f5-7c645620588e', 7, NULL, NULL, NULL, NULL, 'Samarinda', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-24 03:24:53', '2026-04-26 23:47:30', NULL, NULL, NULL),
(17, 'd291e3e5-0c68-4f9c-aae0-24aad1f31806', 9, 'profile/1777168410_c334984727e874276299.png', 'Dr', 'S.Kom', 'L', 'Muara Muntai', '2026-04-24', NULL, NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, '2026-04-24 10:48:00', '2026-04-26 06:38:15', NULL, NULL, NULL);

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
(9, 7, NULL),
(9, 8, NULL),
(16, 7, '2026-04-27 00:43:38'),
(17, 7, '2026-04-27 00:43:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bidang_ilmu`
--
ALTER TABLE `bidang_ilmu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`);

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
-- Indexes for table `klaster_bantuan`
--
ALTER TABLE `klaster_bantuan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`);

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
-- Indexes for table `proposal_anggota_eksternal`
--
ALTER TABLE `proposal_anggota_eksternal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `proposal_id` (`proposal_id`);

--
-- Indexes for table `proposal_bidang_ilmu`
--
ALTER TABLE `proposal_bidang_ilmu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Indexes for table `proposal_dokumen`
--
ALTER TABLE `proposal_dokumen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `proposal_id` (`proposal_id`);

--
-- Indexes for table `proposal_jenis_penelitian`
--
ALTER TABLE `proposal_jenis_penelitian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Indexes for table `proposal_jurnal`
--
ALTER TABLE `proposal_jurnal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD UNIQUE KEY `proposal_id` (`proposal_id`);

--
-- Indexes for table `proposal_klaster_bantuan`
--
ALTER TABLE `proposal_klaster_bantuan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Indexes for table `proposal_kontribusi_prodi`
--
ALTER TABLE `proposal_kontribusi_prodi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Indexes for table `proposal_mahasiswa`
--
ALTER TABLE `proposal_mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `proposal_id` (`proposal_id`);

--
-- Indexes for table `proposal_peneliti`
--
ALTER TABLE `proposal_peneliti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `proposal_id` (`proposal_id`);

--
-- Indexes for table `proposal_pengajuan`
--
ALTER TABLE `proposal_pengajuan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `proposal_pengelola_bantuan`
--
ALTER TABLE `proposal_pengelola_bantuan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Indexes for table `proposal_reviewer_assignments`
--
ALTER TABLE `proposal_reviewer_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD UNIQUE KEY `proposal_id_reviewer_user_id` (`proposal_id`,`reviewer_user_id`),
  ADD KEY `proposal_id` (`proposal_id`),
  ADD KEY `reviewer_user_id` (`reviewer_user_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `proposal_substansi_bagian`
--
ALTER TABLE `proposal_substansi_bagian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `proposal_id` (`proposal_id`);

--
-- Indexes for table `proposal_tema_penelitian`
--
ALTER TABLE `proposal_tema_penelitian`
  ADD PRIMARY KEY (`id`),
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
-- Indexes for table `tema_penelitian`
--
ALTER TABLE `tema_penelitian`
  ADD PRIMARY KEY (`id`),
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
-- AUTO_INCREMENT for table `bidang_ilmu`
--
ALTER TABLE `bidang_ilmu`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- AUTO_INCREMENT for table `klaster_bantuan`
--
ALTER TABLE `klaster_bantuan`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `proposal_anggota_eksternal`
--
ALTER TABLE `proposal_anggota_eksternal`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proposal_bidang_ilmu`
--
ALTER TABLE `proposal_bidang_ilmu`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `proposal_dokumen`
--
ALTER TABLE `proposal_dokumen`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `proposal_jenis_penelitian`
--
ALTER TABLE `proposal_jenis_penelitian`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `proposal_jurnal`
--
ALTER TABLE `proposal_jurnal`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `proposal_klaster_bantuan`
--
ALTER TABLE `proposal_klaster_bantuan`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `proposal_kontribusi_prodi`
--
ALTER TABLE `proposal_kontribusi_prodi`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `proposal_mahasiswa`
--
ALTER TABLE `proposal_mahasiswa`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proposal_peneliti`
--
ALTER TABLE `proposal_peneliti`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `proposal_pengajuan`
--
ALTER TABLE `proposal_pengajuan`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `proposal_pengelola_bantuan`
--
ALTER TABLE `proposal_pengelola_bantuan`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `proposal_reviewer_assignments`
--
ALTER TABLE `proposal_reviewer_assignments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proposal_substansi_bagian`
--
ALTER TABLE `proposal_substansi_bagian`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `proposal_tema_penelitian`
--
ALTER TABLE `proposal_tema_penelitian`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- AUTO_INCREMENT for table `tema_penelitian`
--
ALTER TABLE `tema_penelitian`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
