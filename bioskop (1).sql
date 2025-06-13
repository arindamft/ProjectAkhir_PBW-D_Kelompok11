-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 14 Jun 2025 pada 00.40
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bioskop`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`) VALUES
(3, 'arinda@adminbioskop.com', '$2y$10$cPPQHikZIOTIWZM/H/Dvt.FHiM0ZN6I77svu8zPmzfRpejXHYBuTq'),
(4, 'amin@adminbioskop.com', '$2y$10$VDQcL7fTwnjIxZQOxLr6RO1hUN/w3MATEz9X.lObQvE8Zq9n3Zq/2');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_tiket`
--

CREATE TABLE `detail_tiket` (
  `id` int(11) NOT NULL,
  `id_tiket` int(11) DEFAULT NULL,
  `kursi_nomor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_tiket`
--

INSERT INTO `detail_tiket` (`id`, `id_tiket`, `kursi_nomor`) VALUES
(1, 1, 1),
(2, 1, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `film`
--

CREATE TABLE `film` (
  `id` int(11) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `tanggal_rilis` date DEFAULT NULL,
  `durasi` int(11) DEFAULT NULL,
  `sutradara` varchar(100) DEFAULT NULL,
  `rating_usia` varchar(10) DEFAULT NULL,
  `sinopsis` text DEFAULT NULL,
  `aktor` text DEFAULT NULL,
  `status` enum('Tayang','Segera Tayang') DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `rating_film` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `film`
--

INSERT INTO `film` (`id`, `judul`, `genre`, `tanggal_rilis`, `durasi`, `sutradara`, `rating_usia`, `sinopsis`, `aktor`, `status`, `gambar`, `rating_film`) VALUES
(1, 'Thunderbolts*', 'Action/Sci-Fi', '2025-05-02', 126, 'Jake Schreirer', 'R13', 'Thunderbolts* atau The New Avengers adalah film pahlawan super Amerika yang berbasis tim Marvel Comics Thunderbolts. Film yang diproduksi oleh Marvel Studios dan didistribusikan oleh Walt Disney Studios Motion Pictures ini adalah film ke-36 Marvel Cinematic Universe.', 'Florence Pugh, Lewis Pullman, Sebastian Stan', 'Tayang', '684c6dccd7b89_Gambar WhatsApp 2025-06-13 pukul 22.57.25_e143740d.jpg', 4.6),
(2, 'Sumala', 'Horror/Slasher', '0000-00-00', 113, 'Rizal Mantovani', 'D17', 'Sumala adalah sebuah film hantu thriller Indonesia tahun 2024 yang diproduksi oleh Hitmaker Studios dan disutradarai oleh Rizal Mantovani. Film tersebut oleh Luna Maya, Darius Sinathrya dan Makayla Rose.', 'Makayla Rose, Luna Maya, Darius Sinathrya', 'Segera Tayang', '684c986a30dfe_sumala.jpg', NULL),
(3, 'Interstellar', 'Sci-Fi', '0000-00-00', 169, 'Christoper Nolan', 'R13', 'Interstellar adalah film fiksi ilmiah epos tahun 2014 yang disutradarai oleh Christoper Nolan dan diproduseri oleh Emma Thomas, Christoper Nolan dan Lynda Obst. Naskah film ini ditulis oleh Jonathan Nolan dan Christoper Nolan.', 'Matthew McConaughey, Anne Hathaway, Jessica Chastain', 'Segera Tayang', '684c9886c2a54_interstellar.jpg', NULL),
(4, 'Titanic', 'Romance/Adventure', '0000-00-00', 194, 'James Cameron', 'R13', 'Titanic adalah sebuah film epik, roman, dan bencana Amerika Serikat produksi tahun 1997 yang diskenarioi sekaligus disutradarai oleh James Cameron. Film ini bercerita tentang kisah antara Jack dan Rose (diperankan oleh Leonardo DiCaprio dan Kate Winslet) yang berasal dari status sosial berbeda di atas kapal RMS Titanic yang tenggelam dalam pelayaran perdananya pada tanggal 15 April 1912.', 'Leonardo DiCaprio, Kate Winslet, Billy Zane', 'Segera Tayang', '684c989e22521_titanic.jpg', NULL),
(5, 'Sinners', 'Horror', '0000-00-00', 137, 'Ryan Coogler', 'D17', 'Sinners adalah sebuah film horor petualangan supranatural Amerika Serikat tahun 2025 yang ditulis dan disutradarai oleh Ryan Coogler dan mengambil latar waktu 1930-an di Amerika Selatan. Film tersebut tayang perdana di bioskop Amerika Serikat pada 18 April 2025.', 'Michael B. Jordan, Hailee Steinfeld, Wunmi Mosaku', 'Segera Tayang', '684c9950632cc_sinners.jpg', NULL),
(6, 'Spider-Man', 'Action/Sci-Fi', '0000-00-00', 148, 'Jon Watts', 'R13', 'Pertama kalinya dalam sejarah layar lebar Spider-Man, identitas asli dari pahlawan nan ramah ini terbongkar, sehingga membuat tanggung jawabnya sebagai berkekuatan super berkekuatan super berbenturan dengan kehidupan normalnya dan menempatkan semua orang terdekatnya dalam posisi paling terancam.', 'Tom Holland, Zendaya, Benedict Cumberbatch', 'Segera Tayang', '684c9901ec6f8_spiderman.jpg', NULL),
(7, 'Jalan Pulang', 'Horror/Mystery', '0000-00-00', 97, 'JeroPoint', 'D17', 'Lastini (Luna Maya) melakukan perjalanan untuk menemui para dukun dan orang pintar di seluruh Pulau Jawa, demi menyembuhkan putrinya, Arum (Saskia Chadwick), yang dirasuki iblis jahat. Mampukah Lastini menemukan jalan pulang dari permasalahannya?', 'Luna Maya, Taskya Namya, Shareefa Danish', 'Segera Tayang', '684c99145c7b4_jalan pulang.jpg', NULL),
(8, 'Dilan: Dia Adalah Dilanku 1990', 'Romance', '2018-01-25', 160, 'Pidi Baiq', 'R13', 'Film \"Dilan 1990\" diangkat dari novel best seller berjudul \"Dilan: Dia adalah Dilanku Tahun 1990\" karya Pidi Baiq. Cerita dimulai ketika Milea, seorang siswi pindahan dari Jakarta, bersekolah di sebuah SMA di Bandung. Di sana, ia bertemu dengan Dilan, seorang pemuda yang percaya diri dan dikenal sebagai \"panglima\" geng motor. Dilan langsung meramal bahwa Milea akan menjadi pacarnya, yang membuat Milea merasa tertarik namun juga bingung', 'Iqbal Ramadhan, Vanesha Prescilla', 'Tayang', 'dilan 1990.jpeg', 4.5),
(9, 'DreadOut', 'Horor Fantasi', '2019-01-13', 160, 'Kimo Stamboel', 'Semua Umur', 'Film DreadOut 2019 bercerita tentang Linda, seorang gadis SMA yang memiliki kemampuan untuk melihat makhluk halus. Suatu hari, Linda dan teman-temannya pergi ke sebuah gedung tua yang konon angker', 'Caitlin Haldernam, Jefri Nichol, Susan Sameeh', 'Tayang', 'OIP.jpeg', 4),
(10, 'Jumbo', 'Fantasi Petualangan', '2025-03-31', 160, 'Ryan Andriandhy', 'Semua Umur', 'Film Jumbo mengisahkan seorang anak yatim piatu berusia 10 tahun bernama Don. Ia sering diremehkan karena memiliki tubuh yang besar. Don mempunyai sebuah buku dongeng warisan orang tuanya, yang penuh ilustrasi dan cerita ajaib. Buku tersebut bukan hanya kenang-kenangan, tetapi juga menjadi sumber inspirasi dan pelarian bagi Don dari dunia yang terasa tidak ramah karena kerap diremehkan oleh teman-temannya.', 'Prince Poetiray, Quinn Salman, Muhammad Adhiyat, Graciella Abigail', 'Tayang', 'jumbo.jpeg', 5),
(11, 'Agak Laen', 'Horor Komedi', '2024-02-01', 160, 'Muhadkly Acho', 'D17', 'Bene, Boris, Jegel, dan Oki merupakan empat sekawan yang telah berteman sejak lama. Namun, kondisi ekonomi mereka masih terpuruk meski sudah lama merantau. Keempat sahabat itu akhirnya melihat peluang baru saat pasar malam baru didirikan di dekat kediaman mereka.', 'Bene Dion, Oki Rengga, Indra Jegel', 'Tayang', '684c731a0e3be_agak laen.jpeg', 4.6),
(12, 'Warkop DKI Reborn', 'Komedi', '2016-09-08', 160, 'Anggy Umbara', 'R13', 'Warkop DKI Reborn adalah sebuah seri film komedi yang diproduksi oleh Falcon Pictures. Seri film tersebut adalah sebuah adaptasi dan sempalan dari Warkop DKI asli.', 'Abimana Aryasatya, Vino Bastian, Tora Sudiro', 'Tayang', '684c9701f27c2_warkop dki.jpg', 4.4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_tayang`
--

CREATE TABLE `jadwal_tayang` (
  `id` int(11) NOT NULL,
  `film_id` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `studio` varchar(50) DEFAULT NULL,
  `kursi_tersedia` int(11) DEFAULT NULL,
  `harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jadwal_tayang`
--

INSERT INTO `jadwal_tayang` (`id`, `film_id`, `tanggal`, `jam_mulai`, `jam_selesai`, `studio`, `kursi_tersedia`, `harga`) VALUES
(1, 1, '2025-05-02', '13:00:00', '15:06:00', 'Studio 1', 25, 25000),
(2, 1, '2025-05-02', '16:00:00', '18:06:00', 'Studio 1', 25, 25000),
(3, 8, '2018-01-25', '10:00:00', '12:40:00', 'Studio 1', 28, 30000),
(4, 8, '2018-01-25', '11:00:00', '13:40:00', 'Studio 2', 25, 30000),
(5, 8, '2018-01-25', '11:30:00', '14:10:00', 'Studio 3', 25, 30000),
(6, 8, '2018-01-25', '14:00:00', '16:40:00', 'Studio 4', 26, 30000),
(7, 8, '2018-01-25', '15:00:00', '17:40:00', 'Studio 5', 30, 30000),
(8, 9, '2019-01-13', '12:00:00', '14:40:00', 'Studio 1', 25, 30000),
(9, 9, '2019-01-13', '13:00:00', '15:40:00', 'Studio 2', 25, 30000),
(10, 9, '2019-01-13', '14:30:00', '17:10:00', 'Studio 3', 30, 30000),
(11, 9, '2019-01-13', '14:00:00', '16:40:00', 'Studio 4', 30, 30000),
(12, 1, '2025-05-02', '15:00:00', '17:06:00', 'Studio 4', 30, 25000),
(13, 1, '2025-05-02', '14:00:00', '16:06:00', 'Studio 5', 25, 25000),
(14, 10, '2025-03-31', '10:30:00', '13:10:00', 'Studio 1', 40, 35000),
(15, 10, '2025-03-31', '12:00:00', '14:40:00', 'Studio 2', 40, 35000),
(16, 10, '2025-03-31', '15:30:00', '18:10:00', 'Studio 4', 30, 35000),
(17, 10, '2025-03-31', '14:30:00', '17:10:00', 'Studio 3', 30, 35000),
(18, 11, '2025-02-01', '10:30:00', '13:10:00', 'Studio 1', 35, 30000),
(19, 11, '2025-02-01', '12:30:00', '15:10:00', 'Studio 3', 30, 30000),
(20, 11, '2025-02-01', '13:30:00', '16:10:00', 'Studio 5', 35, 30000),
(21, 11, '2025-02-01', '15:30:00', '18:10:00', 'Studio 4', 25, 30000),
(22, 12, '2016-09-08', '10:30:00', '13:10:00', 'Studio 1', 20, 20000),
(23, 12, '2016-09-08', '12:30:00', '15:10:00', 'Studio 4', 25, 25000),
(24, 12, '2016-09-08', '15:30:00', '18:10:00', 'Studio 2', 25, 25000),
(25, 12, '2025-09-08', '14:00:00', '16:40:00', 'Studio 2', 35, 25000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengunjung`
--

CREATE TABLE `pengunjung` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengunjung`
--

INSERT INTO `pengunjung` (`id`, `nama`, `email`, `password`) VALUES
(1, 'arinda', 'arinda@gmail.com', '$2y$10$4jIF2Itr7LUT3ZOb9ftHi.nCscI2iLtaQo.ZR4r.HsXhQcw0.UipC'),
(2, 'Amin', 'sigmaohio@gmail.com', '$2y$10$8oqsynfkefnnzLd3l7pFMu2bPe/SDmZC28rzG6Pipg3au3tvUPDG2'),
(3, 'Aminuddin', 'skibidi@gmail.com', '$2y$10$0F35PwExKctiSr7qgS02juddAgcdaR.9hWt2TiagDuY5lRO9OW2qe'),
(4, 'adib', 'adibida@gmail.com', '$2y$10$P7tq267Kt7j4Ng.iEyUjHOa4hlZpJzZ28US8qIBFZFWlVM8pmsk7u'),
(5, 'arindz', 'nutrisari@gmail.com', '$2y$10$dYb.xU5B3Vm/xDOnaEDhvec9ph2uDJIoeWpM3k9J.Y5URy1pu6/Z6');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tiket`
--

CREATE TABLE `tiket` (
  `id` int(11) NOT NULL,
  `id_pengunjung` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tanggal_pembelian` datetime DEFAULT NULL,
  `id_jadwal` int(11) DEFAULT NULL,
  `metode_pembayaran` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tiket`
--

INSERT INTO `tiket` (`id`, `id_pengunjung`, `jumlah`, `tanggal_pembelian`, `id_jadwal`, `metode_pembayaran`) VALUES
(1, 4, 2, NULL, 3, 'ShopeePay');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `detail_tiket`
--
ALTER TABLE `detail_tiket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tiket` (`id_tiket`);

--
-- Indeks untuk tabel `film`
--
ALTER TABLE `film`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jadwal_tayang`
--
ALTER TABLE `jadwal_tayang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `film_id` (`film_id`);

--
-- Indeks untuk tabel `pengunjung`
--
ALTER TABLE `pengunjung`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tiket`
--
ALTER TABLE `tiket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pengunjung` (`id_pengunjung`),
  ADD KEY `id_jadwal` (`id_jadwal`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `detail_tiket`
--
ALTER TABLE `detail_tiket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `film`
--
ALTER TABLE `film`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `jadwal_tayang`
--
ALTER TABLE `jadwal_tayang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `pengunjung`
--
ALTER TABLE `pengunjung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tiket`
--
ALTER TABLE `tiket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_tiket`
--
ALTER TABLE `detail_tiket`
  ADD CONSTRAINT `detail_tiket_ibfk_1` FOREIGN KEY (`id_tiket`) REFERENCES `tiket` (`id`);

--
-- Ketidakleluasaan untuk tabel `jadwal_tayang`
--
ALTER TABLE `jadwal_tayang`
  ADD CONSTRAINT `jadwal_tayang_ibfk_1` FOREIGN KEY (`film_id`) REFERENCES `film` (`id`);

--
-- Ketidakleluasaan untuk tabel `tiket`
--
ALTER TABLE `tiket`
  ADD CONSTRAINT `tiket_ibfk_1` FOREIGN KEY (`id_pengunjung`) REFERENCES `pengunjung` (`id`),
  ADD CONSTRAINT `tiket_ibfk_2` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal_tayang` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
