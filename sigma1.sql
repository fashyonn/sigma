-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Apr 2026 pada 21.42
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
-- Database: `sigma1`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `donasi`
--

CREATE TABLE `donasi` (
  `donasiID` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `nominal` bigint(20) NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `noRef` varchar(50) NOT NULL,
  `status` enum('Terverifikasi','Belum Terverifikasi') DEFAULT 'Belum Terverifikasi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `donasi`
--

INSERT INTO `donasi` (`donasiID`, `nama`, `tanggal`, `nominal`, `keterangan`, `noRef`, `status`) VALUES
(1, 'Budi Budiman', '2026-04-25', 250000, 'Beasiswa Pendidikan', '98217612798', 'Belum Terverifikasi'),
(2, 'Ichwan', '2026-04-24', 159000, 'Pembangunan Masjid', '987654', 'Terverifikasi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kas`
--

CREATE TABLE `kas` (
  `kasID` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jenis` enum('Kas Masuk','Kas Keluar') NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `nominal` bigint(20) NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `donasiID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kas`
--

INSERT INTO `kas` (`kasID`, `tanggal`, `jenis`, `kategori`, `nominal`, `keterangan`, `donasiID`) VALUES
(1, '2026-04-24', 'Kas Masuk', 'Donasi', 159000, '-', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `userID` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(225) NOT NULL,
  `role` varchar(100) NOT NULL,
  `status` enum('nonaktif','aktif') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`userID`, `username`, `email`, `role`, `status`) VALUES
('USER-001', 'ada_lah', 'ada@.id', 'Main Admin', 'aktif');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `donasi`
--
ALTER TABLE `donasi`
  ADD PRIMARY KEY (`donasiID`),
  ADD UNIQUE KEY `noRef` (`noRef`);

--
-- Indeks untuk tabel `kas`
--
ALTER TABLE `kas`
  ADD PRIMARY KEY (`kasID`),
  ADD KEY `donasiID` (`donasiID`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `donasi`
--
ALTER TABLE `donasi`
  MODIFY `donasiID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `kas`
--
ALTER TABLE `kas`
  MODIFY `kasID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `kas`
--
ALTER TABLE `kas`
  ADD CONSTRAINT `kas_ibfk_1` FOREIGN KEY (`donasiID`) REFERENCES `donasi` (`donasiID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
