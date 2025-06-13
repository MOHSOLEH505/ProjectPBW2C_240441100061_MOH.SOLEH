-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 13 Jun 2025 pada 18.36
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
-- Database: `pengelolaan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `id` int(11) NOT NULL,
  `kode` varchar(20) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `dosen_id` int(11) DEFAULT NULL,
  `nama_dosen` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`id`, `kode`, `nama`, `dosen_id`, `nama_dosen`) VALUES
(1, '', '', 2, NULL),
(8, '123', 'PAI', 3, ''),
(9, '456', 'Matematika', 3, 'Gibran '),
(10, '789', 'IPA', 3, 'Gibran'),
(11, '098', 'PJOK', 3, 'Gibran'),
(12, '765', 'IPS', 3, 'Gibran'),
(13, '432', 'Sejarah', 3, 'Gibran'),
(14, '135', 'Pancasila', 3, 'Gibran'),
(16, '246', 'Logika Engineering', 3, 'Gibran'),
(17, '357', 'Bahasa Inggris', 3, 'Gibran');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas_mahasiswa`
--

CREATE TABLE `kelas_mahasiswa` (
  `id` int(11) NOT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `mahasiswa_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kelas_mahasiswa`
--

INSERT INTO `kelas_mahasiswa` (`id`, `kelas_id`, `mahasiswa_id`) VALUES
(6, 8, 4),
(7, 9, 4),
(8, 10, 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengumpulan`
--

CREATE TABLE `pengumpulan` (
  `id` int(11) NOT NULL,
  `tugas_id` int(11) DEFAULT NULL,
  `mahasiswa_id` int(11) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `nim` varchar(20) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `waktu_submit` datetime DEFAULT current_timestamp(),
  `komentar` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengumpulan`
--

INSERT INTO `pengumpulan` (`id`, `tugas_id`, `mahasiswa_id`, `nama`, `nim`, `file`, `waktu_submit`, `komentar`) VALUES
(5, 5, 4, 'MOH. SOLEH', '240441100061', 'Cuplikan layar 2025-04-15 204159.png', '2025-06-13 20:49:41', 'mantap bgt\r\n'),
(6, 6, 4, 'MOH. SOLEH', '240441100061', 'Cuplikan layar 2025-04-11 203124.png', '2025-06-13 21:16:26', 'maaf bgt\r\n');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tugas`
--

CREATE TABLE `tugas` (
  `id` int(11) NOT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `judul` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `deadline` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tugas`
--

INSERT INTO `tugas` (`id`, `kelas_id`, `judul`, `deskripsi`, `file`, `deadline`) VALUES
(5, 8, 'Kurban', 'apa itu sapi', '1749786197_Cuplikan layar 2025-06-02 205801.png', '2025-10-10 10:00:00'),
(6, 8, 'Islam', 'apa itu islam', '1749823704_Cuplikan layar 2025-06-08 204807.png', '2025-06-13 21:10:00'),
(7, 9, 'perkalian', '3x3', '1749829789_Cuplikan layar 2025-03-23 222042.png', '2025-10-10 10:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('dosen','mahasiswa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `email`, `nama`, `password`, `role`) VALUES
(1, 'samsol@utm.com', NULL, '$2y$10$eQjBJ/0FWALbE1lbW1MDeO59nX7q2aF3sQnjoPRP/YX73N9Tssp9K', 'dosen'),
(2, 'ahmad@utm.com', NULL, '$2y$10$mx2ZBq.JEGoEBGzpDL0Qf.O/IoIkf4SOs0bgWmTWGqoxbRRsRD2KK', 'dosen'),
(3, 'gibran@utm.com', 'Gibran', '$2y$10$zDlQhVuYAKL0ucpeFe168OgQ0tooAWOtrjbfUFz.ougHYiY4a1ney', 'dosen'),
(4, 'sholeh@student.utm.com', NULL, '$2y$10$Fg/46amcrC.byh3ZwGcPJeWsOSe9YnnCGURjdqFZzPZHXDVQZXAtO', 'mahasiswa'),
(5, 'fika@utm.com', 'Fika', '$2y$10$eHkG2MT0RNV5hSz3StVBvuPBHhZk16tZYFkf2Ae4P9Rydzplm0b.K', 'dosen'),
(6, 'fikaa@student.utm.com', NULL, '$2y$10$tRJMYNwKCdGEdswZFz4HDeBEVEy.nprnVV7AGIZSPra8NGB7x4XuO', 'mahasiswa'),
(7, 'sholeh@utm.com', NULL, '$2y$10$DrCdfzhwIbhGD5FrS9nmZ.LkTutMzkm.9Ed4iQMaFF4DEWFLerGkC', 'dosen');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`),
  ADD KEY `dosen_id` (`dosen_id`);

--
-- Indeks untuk tabel `kelas_mahasiswa`
--
ALTER TABLE `kelas_mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`);

--
-- Indeks untuk tabel `pengumpulan`
--
ALTER TABLE `pengumpulan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tugas_id` (`tugas_id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`);

--
-- Indeks untuk tabel `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_id` (`kelas_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `kelas_mahasiswa`
--
ALTER TABLE `kelas_mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `pengumpulan`
--
ALTER TABLE `pengumpulan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`dosen_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `kelas_mahasiswa`
--
ALTER TABLE `kelas_mahasiswa`
  ADD CONSTRAINT `kelas_mahasiswa_ibfk_1` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`),
  ADD CONSTRAINT `kelas_mahasiswa_ibfk_2` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `pengumpulan`
--
ALTER TABLE `pengumpulan`
  ADD CONSTRAINT `pengumpulan_ibfk_1` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id`),
  ADD CONSTRAINT `pengumpulan_ibfk_2` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `tugas`
--
ALTER TABLE `tugas`
  ADD CONSTRAINT `tugas_ibfk_1` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
