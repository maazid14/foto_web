-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 01 Mar 2024 pada 09.08
-- Versi server: 10.4.25-MariaDB
-- Versi PHP: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gallery`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `albums`
--

CREATE TABLE `albums` (
  `album_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `albums`
--

INSERT INTO `albums` (`album_id`, `user_id`, `title`, `description`, `created_at`) VALUES
(13, 4, 'KENANGAN KELAS 12 RPL 1', 'SILAHKAN DI POST', '2024-03-01 04:19:45'),
(14, 6, 'album  admin', 'silahkan di post', '2024-03-01 03:30:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `commens`
--

CREATE TABLE `commens` (
  `commen_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `commens`
--

INSERT INTO `commens` (`commen_id`, `user_id`, `photo_id`, `comment_text`, `created_at`) VALUES
(1, 4, 0, 'bagus sekali', '2024-03-01 04:04:26'),
(2, 4, 0, 'bagus', '2024-03-01 04:06:27'),
(3, 4, 0, 'bagus', '2024-03-01 04:06:53'),
(4, 4, 0, 'bagus', '2024-03-01 04:07:14'),
(5, 4, 0, 'bagus', '2024-03-01 04:09:09'),
(6, 4, 0, 'bagus', '2024-03-01 04:09:43'),
(7, 4, 0, 'bagus', '2024-03-01 04:10:26'),
(8, 4, 0, 'lezat', '2024-03-01 04:28:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `likes`
--

CREATE TABLE `likes` (
  `like_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `photo_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `likes`
--

INSERT INTO `likes` (`like_id`, `user_id`, `photo_id`, `created_at`) VALUES
(1, 1, NULL, '2024-02-15 09:38:17'),
(2, 1, NULL, '2024-02-15 09:38:26'),
(3, 1, NULL, '2024-02-15 09:38:32'),
(4, 1, NULL, '2024-02-15 09:42:18'),
(5, 1, NULL, '2024-02-15 09:42:21'),
(6, 1, NULL, '2024-02-15 09:42:22'),
(7, 1, NULL, '2024-02-15 09:45:05'),
(8, 1, NULL, '2024-02-15 09:45:18'),
(9, 1, NULL, '2024-02-15 09:53:35'),
(10, 1, NULL, '2024-02-16 01:17:50'),
(11, 1, NULL, '2024-02-16 02:49:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `photos`
--

CREATE TABLE `photos` (
  `photo_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `album_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `photos`
--

INSERT INTO `photos` (`photo_id`, `user_id`, `album_id`, `title`, `description`, `image_path`, `created_at`) VALUES
(12, 0, 1, 'sonic', 'pantai', './foto/65cec29c0798c_1696778558794.jpg', '2024-02-16 03:10:41'),
(13, 0, 2, 'sonic150r', 'hondaa', './foto/65cec32461a14_1696752871964.jpg', '2024-02-16 03:11:47'),
(17, 4, 8, 'rechese', 'makanan yang sangat enak dan dingin enak di nikmati di musim panas', 'uploads/combo-special-combo-cheese-crackling-putih-removebg-preview.png', '2024-03-01 03:22:28'),
(18, 4, 8, 'hari pahlawan', '.', 'uploads/fa9ba75404ee5e2551042b52ba75fc82-1-removebg-preview.png', '2024-03-01 03:23:29'),
(20, 6, 14, 'punya admin', 'gabole di hapus', 'uploads/download__2_-removebg-preview.png', '2024-03-01 03:50:31'),
(21, 6, 14, 'punya admin', 'gabole di hapus', 'uploads/fa9ba75404ee5e2551042b52ba75fc82-1-removebg-preview.png', '2024-03-01 03:51:43'),
(22, 4, 13, 'di teraktir bendahara', 'happy', 'uploads/images__36_-removebg-preview.png', '2024-03-01 04:28:26'),
(23, 4, 13, '12', '12', 'uploads/KON.png', '2024-03-01 07:03:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(225) NOT NULL,
  `email` varchar(100) NOT NULL,
  `acces_level` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`user_id`, `name`, `username`, `password`, `email`, `acces_level`, `created_at`) VALUES
(4, 'mazidu nurusiam', 'madun', '$2y$10$IYAp/GwyC9Rs/w7RN.wUZe7PUnvKfiMvFCrvz2OQrICujLS.tkUCS', 'mazidunurusiam23@gmail.com', 'user', '2024-02-20 07:36:16'),
(5, 'labib', 'dian', '$2y$10$yERifPgGGLg3fgWp6DuCpOROP1OPUfV8xLHJJqKNHPR7UM./PO8wS', 'email@gmail.com', 'user', '2024-02-20 10:45:10'),
(6, 'admin', 'admin', '$2y$10$JKlesxNXEZOlfNvLVezAu.SdSPx2kFTcQ.ZxExOwtj.GRJsaeEOru', 'rivalrizkirivaldi@gmail.com', 'admin', '2024-02-21 00:56:58'),
(7, 'ilham', 'ilham hendi', '$2y$10$r2PGNFEI3f2t7F7wkFjgTextAjxXeLs34yzpvIF3wT/GBBGpl37uK', 'email@gmail.com', 'user', '2024-02-28 04:18:55'),
(8, 'miqdad', 'jajang', '$2y$10$Dm9kx4J2VVFMF2wGChjW3ukRSL.c8sstfD9.dPXYOlUpj7X965pX6', 'mazidunurusiam78@gmail.com', 'user', '2024-02-29 01:22:13');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`album_id`);

--
-- Indeks untuk tabel `commens`
--
ALTER TABLE `commens`
  ADD PRIMARY KEY (`commen_id`);

--
-- Indeks untuk tabel `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_id`);

--
-- Indeks untuk tabel `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`photo_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `albums`
--
ALTER TABLE `albums`
  MODIFY `album_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `commens`
--
ALTER TABLE `commens`
  MODIFY `commen_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `photos`
--
ALTER TABLE `photos`
  MODIFY `photo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
