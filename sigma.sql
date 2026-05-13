-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 13 Bulan Mei 2026 pada 10.14
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
-- Database: `sigma`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `donasi`
--

CREATE TABLE `donasi` (
  `donasiID` int(11) NOT NULL,
  `programID` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nominal` bigint(20) NOT NULL,
  `noRef` varchar(50) NOT NULL,
  `status` enum('Terverifikasi','Belum Terverifikasi') NOT NULL DEFAULT 'Belum Terverifikasi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `donasi`
--

INSERT INTO `donasi` (`donasiID`, `programID`, `tanggal`, `nama`, `nominal`, `noRef`, `status`) VALUES
(1, 1, '2026-04-01', 'Hamba Allah', 500000, '452901837465', 'Terverifikasi'),
(2, 2, '2026-04-05', 'Bapak Budi', 1000000, '193847562019', 'Terverifikasi'),
(3, 4, '2026-04-10', 'Ibu Siti', 2000000, '837492015638', 'Terverifikasi'),
(4, 3, '2026-04-12', 'Keluarga Ahmad', 750000, '563829104756', 'Terverifikasi'),
(5, 5, '2026-04-15', 'H. Lulung', 3500000, '920183746529', 'Terverifikasi'),
(6, 1, '2026-04-20', 'Hamba Allah', 200000, '374659201837', 'Terverifikasi'),
(7, 2, '2026-04-25', 'Donatur Tetap', 1500000, '284756193847', 'Terverifikasi'),
(8, 4, '2026-04-28', 'PT Berkah', 5000000, '659201837465', 'Terverifikasi'),
(9, 3, '2026-05-01', 'Hamba Allah', 300000, '104756382910', 'Terverifikasi'),
(10, 5, '2026-05-05', 'Keluarga Wijaya', 3500000, '746529018374', 'Terverifikasi'),
(11, 1, '2026-05-10', 'Iwan', 100000, '938475620193', 'Belum Terverifikasi'),
(12, 2, '2026-05-11', 'Hamba Allah', 500000, '382910475638', 'Belum Terverifikasi'),
(13, 4, '2026-05-12', 'CV Makmur', 1000000, '201837465920', 'Belum Terverifikasi'),
(14, 3, '2026-05-12', 'Siti Aminah', 250000, '847561938475', 'Belum Terverifikasi'),
(15, 5, '2026-05-13', 'Bapak Anton', 3500000, '592018374659', 'Belum Terverifikasi'),
(16, 3, '2026-05-13', 'Budi Budiman', 100000, '592018394659', 'Belum Terverifikasi'),
(32, 3, '2026-05-14', 'Budiono Siregar', 5000000, '093897562019', 'Belum Terverifikasi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kas`
--

CREATE TABLE `kas` (
  `kasID` int(11) NOT NULL,
  `programID` int(11) NOT NULL,
  `donasiID` int(11) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jenis` enum('Kas Masuk','Kas Keluar') NOT NULL,
  `nominal` bigint(20) NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kas`
--

INSERT INTO `kas` (`kasID`, `programID`, `donasiID`, `tanggal`, `jenis`, `nominal`, `keterangan`) VALUES
(1, 1, 1, '2026-04-01', 'Kas Masuk', 500000, 'Donasi'),
(2, 2, 2, '2026-04-05', 'Kas Masuk', 1000000, 'Donasi'),
(3, 4, 3, '2026-04-10', 'Kas Masuk', 2000000, 'Donasi'),
(4, 3, 4, '2026-04-12', 'Kas Masuk', 750000, 'Donasi'),
(5, 5, 5, '2026-04-15', 'Kas Masuk', 3500000, 'Donasi'),
(6, 1, 6, '2026-04-20', 'Kas Masuk', 200000, 'Donasi'),
(7, 2, 7, '2026-04-25', 'Kas Masuk', 1500000, 'Donasi'),
(8, 4, 8, '2026-04-28', 'Kas Masuk', 5000000, 'Donasi'),
(9, 3, 9, '2026-05-01', 'Kas Masuk', 300000, 'Donasi'),
(10, 5, 10, '2026-05-05', 'Kas Masuk', 3500000, 'Donasi'),
(11, 1, NULL, '2026-04-03', 'Kas Masuk', 1500000, 'Kotak Amal Jumat Pekan 1'),
(12, 1, NULL, '2026-04-10', 'Kas Masuk', 1700000, 'Kotak Amal Jumat Pekan 2'),
(13, 1, NULL, '2026-04-17', 'Kas Masuk', 1650000, 'Kotak Amal Jumat Pekan 3'),
(14, 1, NULL, '2026-04-24', 'Kas Masuk', 1800000, 'Kotak Amal Jumat Pekan 4'),
(15, 1, NULL, '2026-05-01', 'Kas Masuk', 1550000, 'Kotak Amal Jumat Pekan 1 Mei'),
(16, 1, NULL, '2026-05-08', 'Kas Masuk', 1600000, 'Kotak Amal Jumat Pekan 2 Mei'),
(17, 2, NULL, '2026-04-10', 'Kas Masuk', 5000000, 'Bantuan Pendidikan dari Pemda'),
(18, 4, NULL, '2026-04-15', 'Kas Masuk', 10000000, 'Infak Material Renovasi Warga'),
(19, 3, NULL, '2026-04-20', 'Kas Masuk', 2000000, 'Sadaqah Tunai Hamba Allah'),
(20, 5, NULL, '2026-05-01', 'Kas Masuk', 10500000, 'Setoran Tunai Arisan Kurban Warga'),
(21, 1, NULL, '2026-04-05', 'Kas Keluar', 500000, 'Bayar Listrik Masjid Bulan Maret'),
(22, 1, NULL, '2026-04-06', 'Kas Keluar', 150000, 'Bayar Air PDAM'),
(23, 1, NULL, '2026-04-10', 'Kas Keluar', 300000, 'Beli Alat Kebersihan Masjid'),
(24, 2, NULL, '2026-04-15', 'Kas Keluar', 2000000, 'Penyaluran Beasiswa Anak Yatim'),
(25, 4, NULL, '2026-04-20', 'Kas Keluar', 3500000, 'Beli Cat dan Semen untuk Dinding'),
(26, 3, NULL, '2026-04-25', 'Kas Keluar', 1500000, 'Pembagian Sembako Janda Dhuafa'),
(27, 4, NULL, '2026-05-02', 'Kas Keluar', 2500000, 'Upah Tukang Renovasi Minggu 1'),
(28, 1, NULL, '2026-05-05', 'Kas Keluar', 500000, 'Bayar Listrik Masjid Bulan April'),
(29, 1, NULL, '2026-05-06', 'Kas Keluar', 150000, 'Bayar Air PDAM'),
(30, 3, NULL, '2026-05-10', 'Kas Keluar', 500000, 'Bantuan Darurat Berobat Warga');

-- --------------------------------------------------------

--
-- Struktur dari tabel `program`
--

CREATE TABLE `program` (
  `programID` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `gambar` varchar(225) DEFAULT NULL,
  `target` bigint(20) NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'nonaktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `program`
--

INSERT INTO `program` (`programID`, `nama`, `gambar`, `target`, `status`) VALUES
(1, 'Umum', 'https://images.unsplash.com/photo-1582174591014-c6862c9e10a7?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 100000000, 'aktif'),
(2, 'Beasiswa Pendidikan', 'https://plus.unsplash.com/premium_photo-1713296255442-e9338f42aad8?q=80&w=422&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 500000000, 'aktif'),
(3, 'Bantuan Sosial', 'https://plus.unsplash.com/premium_photo-1683133593455-814352d8f6d0?q=80&w=929&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 75000000, 'aktif'),
(4, 'Renovasi Masjid', 'https://images.unsplash.com/photo-1777919393703-ad0a60555274?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 90000000, 'aktif'),
(5, 'Kurban Sapi', 'https://images.unsplash.com/photo-1620811004671-40b81b6f6978?q=80&w=896&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 21000000, 'aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(225) NOT NULL,
  `role` enum('administrator','bendahara') NOT NULL,
  `status` enum('nonaktif','aktif') NOT NULL DEFAULT 'nonaktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`userID`, `username`, `password`, `email`, `role`, `status`) VALUES
(1, 'closetoyou', 'admin2112', 'closetoyou@realityclub.id', 'administrator', 'aktif'),
(2, 'admin_pusat', 'admin123', 'admin@sigma.com', 'administrator', 'aktif'),
(3, 'budi_bendahara', 'uang123', 'budi@sigma.com', 'bendahara', 'aktif'),
(4, 'siti_admin', 'siti123', 'siti@sigma.com', 'administrator', 'aktif'),
(5, 'anton_bendahara', 'kasir123', 'anton@sigma.com', 'bendahara', 'nonaktif');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `donasi`
--
ALTER TABLE `donasi`
  ADD PRIMARY KEY (`donasiID`),
  ADD UNIQUE KEY `noRef` (`noRef`),
  ADD KEY `fkprogramdonasi` (`programID`);

--
-- Indeks untuk tabel `kas`
--
ALTER TABLE `kas`
  ADD PRIMARY KEY (`kasID`),
  ADD KEY `donasiID` (`donasiID`),
  ADD KEY `fkprogram` (`programID`);

--
-- Indeks untuk tabel `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`programID`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `donasi`
--
ALTER TABLE `donasi`
  MODIFY `donasiID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `kas`
--
ALTER TABLE `kas`
  MODIFY `kasID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `program`
--
ALTER TABLE `program`
  MODIFY `programID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `donasi`
--
ALTER TABLE `donasi`
  ADD CONSTRAINT `fkprogramdonasi` FOREIGN KEY (`programID`) REFERENCES `program` (`programID`);

--
-- Ketidakleluasaan untuk tabel `kas`
--
ALTER TABLE `kas`
  ADD CONSTRAINT `fkprogram` FOREIGN KEY (`programID`) REFERENCES `program` (`programID`),
  ADD CONSTRAINT `kas_ibfk_1` FOREIGN KEY (`donasiID`) REFERENCES `donasi` (`donasiID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
