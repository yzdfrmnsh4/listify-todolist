-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Bulan Mei 2024 pada 01.45
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbproject`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `task`
--

CREATE TABLE `task` (
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `task_description` varchar(255) NOT NULL,
  `status` enum('To Do','In Progress','Completed') NOT NULL,
  `tanggal` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `task`
--

INSERT INTO `task` (`task_id`, `user_id`, `task_name`, `task_description`, `status`, `tanggal`, `created_at`, `updated_at`) VALUES
(76, 3, 'Ke Yogyakarta', 'Liburan', 'To Do', '2024-05-30 12:50:00', '2024-05-26 05:50:36', '2024-05-26 05:50:36'),
(79, 10, 'Sekolah', 'Belajar', 'To Do', '2024-05-31 13:04:00', '2024-05-26 06:04:28', '2024-05-26 06:04:28'),
(91, 11, 'Sekolah', 'Ayo sekolah ', 'To Do', '2024-06-03 23:06:00', '2024-05-28 16:06:55', '2024-05-28 16:06:55'),
(100, 3, 'mandi', 'aa', 'To Do', '2024-05-29 00:07:00', '2024-05-28 17:07:43', '2024-05-28 17:07:43'),
(101, 3, 'mandi', 'aa', 'To Do', '2024-05-27 00:07:00', '2024-05-28 17:07:52', '2024-05-28 17:07:52'),
(102, 3, 'cuci piring', 'aa', 'To Do', '2024-05-30 00:07:00', '2024-05-28 17:08:01', '2024-05-28 17:08:01'),
(103, 3, 'cuci piring', 'r', 'To Do', '2024-05-30 00:20:00', '2024-05-28 17:20:57', '2024-05-28 17:20:57'),
(104, 3, 'persib', 'ww', 'To Do', '2024-05-29 00:23:00', '2024-05-28 17:23:41', '2024-05-28 17:23:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `task_items`
--

CREATE TABLE `task_items` (
  `item_id` int(11) NOT NULL,
  `task_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `is_completed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `task_items`
--

INSERT INTO `task_items` (`item_id`, `task_id`, `user_id`, `item_name`, `is_completed`) VALUES
(30, 76, 3, 'Malioboro', 0),
(31, 76, 3, 'Candi Borobudur', 0),
(32, 76, 3, 'Pantai Parangtritis', 0),
(44, 91, 11, 'Upacara', 0),
(45, 91, 11, 'Gamelab', 0),
(46, 91, 11, 'Pkk', 0),
(47, 91, 11, 'Makan', 0),
(48, 91, 11, 'Sholat', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `task_manager`
--

CREATE TABLE `task_manager` (
  `id` int(11) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `start_time` date NOT NULL,
  `end_time` date NOT NULL,
  `status` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `task_manager`
--

INSERT INTO `task_manager` (`id`, `task_name`, `start_time`, `end_time`, `status`, `description`, `user_id`, `created_at`) VALUES
(27, 'PR MATEMATIKA', '2024-05-12', '2024-05-13', 'On Progres', '', 9, '2024-05-26 04:21:36'),
(37, 'PR AGAMA', '2024-05-24', '2024-05-28', 'Not started', '', 7, '2024-05-26 04:21:36'),
(39, 'PR MATEMATIKA', '2024-05-26', '2024-05-30', 'Not started', '', 3, '2024-05-26 04:34:58'),
(40, 'PR AGAMA', '2024-05-27', '2024-05-28', 'Not started', '', 3, '2024-05-26 05:42:03'),
(41, 'PR BAHASA INGGRIS', '2024-05-26', '2024-05-28', 'On Progres', '', 10, '2024-05-26 05:53:21'),
(42, 'cuci motor', '2024-05-26', '2024-05-28', 'On Progres', '', 3, '2024-05-26 08:30:54'),
(43, 'PR MATEMATIKA', '2024-05-26', '2024-05-28', 'Not started', '', 7, '2024-05-26 11:36:39'),
(45, 'cuci piring', '2024-05-25', '2024-05-27', 'Not started', '', 8, '2024-05-28 06:14:25'),
(46, 'mandi', '2024-05-28', '2024-06-08', 'Not started', '', 8, '2024-05-28 13:46:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tuser`
--

CREATE TABLE `tuser` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tuser`
--

INSERT INTO `tuser` (`id`, `fullname`, `email`, `password`) VALUES
(3, 'Muhammad Yazid', 'muhammadyazid735@gmail.com', 'e3ky3kc4xn@N'),
(4, 'yazid', 'yazid@gmail.com', 'abcd123'),
(5, 'Yono', 'yono21@gmail.com', '12345'),
(6, 'Rizki maulana', 'rizki22@gmail.com', 'bismillah123'),
(7, 'firman', 'firman@gmail.com', 'user'),
(8, 'John Doe', 'johndoe@gmail.com', '12345678'),
(9, 'ciro alves', 'ciroalves@gmail.com', 'ciro123'),
(10, 'Robbie gaspar', 'Gaspar@gmail.com', '12345'),
(11, 'David Luis', 'David@gmail.com', 'dds3'),
(12, 'Original', 'original@gmail.com', '1234'),
(13, 'Palsu', 'original@gmail.com', '1234'),
(14, 'tes', 'tes@gmail.com', '1233');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`task_id`);

--
-- Indeks untuk tabel `task_items`
--
ALTER TABLE `task_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indeks untuk tabel `task_manager`
--
ALTER TABLE `task_manager`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tuser`
--
ALTER TABLE `tuser`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `task`
--
ALTER TABLE `task`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT untuk tabel `task_items`
--
ALTER TABLE `task_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT untuk tabel `task_manager`
--
ALTER TABLE `task_manager`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT untuk tabel `tuser`
--
ALTER TABLE `tuser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `task_items`
--
ALTER TABLE `task_items`
  ADD CONSTRAINT `task_items_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `task` (`task_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
