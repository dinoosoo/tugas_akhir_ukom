-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2025 at 11:55 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tugas_digital`
--

-- --------------------------------------------------------

--
-- Table structure for table `form_tugas`
--

CREATE TABLE `form_tugas` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `mata_pelajaran` varchar(100) NOT NULL,
  `kelas` varchar(200) NOT NULL,
  `file_upload` varchar(255) DEFAULT NULL,
  `status` enum('belum','terkumpul','','') NOT NULL,
  `waktu_pengumpulan` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `form_tugas`
--

INSERT INTO `form_tugas` (`id`, `judul`, `deskripsi`, `mata_pelajaran`, `kelas`, `file_upload`, `status`, `waktu_pengumpulan`) VALUES
(9, 'algoritma', 'halaman 1', 'mtk', 'xii rpl 2', 'rs_1-removebg-preview.png', 'belum', '2025-02-21 18:05:00'),
(10, 'observasi', 'halaman 33', 'BIN', 'xii ki', 'rs_1-removebg-preview.png', 'belum', '2025-02-23 14:14:00'),
(11, 'ngoding', 'bergerak', 'mtk', 'xii rpl 2', 'cv aku.pdf', 'belum', '2025-02-17 08:28:00'),
(13, 'penelitian', 'penelitian website', 'Basis Data', 'xii rpl 2', NULL, 'belum', '2025-03-19 14:15:00'),
(14, 'Pemograman', 'Ngoding', 'Basis Data', 'XI RPL 2', NULL, 'belum', '2025-04-11 11:20:00'),
(15, 'observasi', 'mbfkjdshf', 'Pendidikan Pancasila', 'xii ki', NULL, 'belum', '2025-04-25 08:58:00'),
(16, 'penelitian', 'jdbsd', 'biologi', 'xii rpl 2', NULL, 'belum', '2025-04-29 08:59:00'),
(17, 'hgj', 'jhghj', 'Pendidikan Pancasila', 'xii rpl 2', NULL, 'belum', '2025-04-20 09:00:00'),
(18, 'penelitian', 'hjgffh', 'Pendidikan Pancasila', 'xii ki', NULL, 'belum', '2025-04-15 09:00:00'),
(19, 'bhv', 'nvnbv', 'Pendidikan Pancasila', 'xii ki', NULL, 'belum', '2025-04-16 09:00:00'),
(20, 'mnvhv', ' nbvh', 'PJOK', 'xii rpl 1', NULL, 'belum', '2025-04-10 09:01:00'),
(21, 'observasi', 'hjjhgjhg', 'Pendidikan Pancasila', 'xii ki', NULL, 'belum', '2025-05-23 09:09:00');

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id` int(11) NOT NULL,
  `nama_guru` varchar(200) NOT NULL,
  `mapel` varchar(100) NOT NULL,
  `jenis_kelamin` enum('Laki - laki','Perempuan','','') NOT NULL,
  `nip` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`id`, `nama_guru`, `mapel`, `jenis_kelamin`, `nip`) VALUES
(8, 'tania', 'biologi', 'Perempuan', '09345812876'),
(9, 'Meriam Bellina', 'Pendidikan Pancasila', 'Perempuan', '092345678'),
(10, 'Rina Yuliani S.pd', 'Basis Data', 'Perempuan', '56778688'),
(11, 'ayu lestari', 'sejarah', 'Perempuan', '0001476463'),
(12, 'ridha deshintiyana rahma ', 'Biology', 'Perempuan', '0980834567'),
(13, 'Agus', 'PJOK', 'Laki - laki', '097845678'),
(14, 'risma', 'Basis Data', 'Perempuan', '778996435');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` int(11) NOT NULL,
  `kelas` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `kelas`) VALUES
(3, 'xii ki'),
(2, 'xii rpl 1'),
(1, 'xii rpl 2');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(225) NOT NULL,
  `role` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '123', 'admin'),
(11, 'ridha', '123', 'siswa'),
(13, 'ridha deshintiyana rahma', '1234', 'siswa'),
(14, 'rahma', '1234', 'guru'),
(15, 'saniah', '1234', 'siswa'),
(16, 'tania', '4321', 'guru'),
(17, 'Rina Yuliani S.pd', '1234', 'guru'),
(18, 'Joko', '0000', 'siswa');

-- --------------------------------------------------------

--
-- Table structure for table `mapel`
--

CREATE TABLE `mapel` (
  `id` int(11) NOT NULL,
  `mapel` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mapel`
--

INSERT INTO `mapel` (`id`, `mapel`) VALUES
(1, 'bahasa'),
(2, 'Basis Data');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id` int(11) NOT NULL,
  `nama_siswa` varchar(200) NOT NULL,
  `kelas` varchar(200) NOT NULL,
  `jenis_kelamin` enum('Laki - laki','Perempuan') NOT NULL,
  `nisn` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id`, `nama_siswa`, `kelas`, `jenis_kelamin`, `nisn`) VALUES
(3, 'bila', 'xii rpl 1', 'Perempuan', '0005689');

-- --------------------------------------------------------

--
-- Table structure for table `tugas_terkumpul`
--

CREATE TABLE `tugas_terkumpul` (
  `id` int(11) NOT NULL,
  `id_tugas` int(100) DEFAULT NULL,
  `nama_siswa` varchar(100) NOT NULL,
  `file_upload` varchar(225) DEFAULT NULL,
  `waktu_pengumpulan` datetime DEFAULT NULL,
  `feedback` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tugas_terkumpul`
--

INSERT INTO `tugas_terkumpul` (`id`, `id_tugas`, `nama_siswa`, `file_upload`, `waktu_pengumpulan`, `feedback`) VALUES
(8, 9, 'ridha', 'logo syamrabu.jpeg', '2025-03-09 16:22:23', NULL),
(9, 12, 'ridha', 'logo syamrabu.jpeg', '2025-03-09 16:23:43', NULL),
(10, 10, 'ridha', 'logo syamrabu (1).jpeg', '2025-03-09 23:28:42', NULL),
(11, 11, 'ridha', 'logo syamrabu (1).jpeg', '2025-03-09 23:29:09', 'hayolo telat '),
(12, 12, 'saniah', 'logo syamrabu (1).jpeg', '2025-03-11 08:01:07', NULL),
(13, 11, 'saniah', 'logo syamrabu (1) (1).jpeg', '2025-03-11 08:16:09', NULL),
(14, 10, 'saniah', 'logo syamrabu (1) (1).jpeg', '2025-03-11 09:57:04', NULL),
(15, 9, 'saniah', 'Modern Minimalis Surat Permohonan Lamaran Kerja Profesional untuk Profesi Digital Marketer.jpg', '2025-03-18 14:12:28', NULL),
(16, 13, 'saniah', 'logo syamrabu (1) (1).jpeg', '2025-03-18 14:16:38', 'Bagus'),
(17, 10, '', 'WhatsApp Image 2025-04-04 at 11.48.33.jpeg', '2025-04-09 14:34:57', NULL),
(18, 14, 'saniah', 'WhatsApp Image 2025-04-09 at 19.09.40.jpeg', '2025-04-10 11:28:04', 'revisi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `form_tugas`
--
ALTER TABLE `form_tugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_upload` (`file_upload`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas` (`kelas`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mapel`
--
ALTER TABLE `mapel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nisn` (`nisn`);

--
-- Indexes for table `tugas_terkumpul`
--
ALTER TABLE `tugas_terkumpul`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `form_tugas`
--
ALTER TABLE `form_tugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `mapel`
--
ALTER TABLE `mapel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tugas_terkumpul`
--
ALTER TABLE `tugas_terkumpul`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
