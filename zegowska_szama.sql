-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2026 at 11:20 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zegowska_szama`
--

-- --------------------------------------------------------

--
-- Table structure for table `kategorie`
--

CREATE TABLE `kategorie` (
  `id` int(10) UNSIGNED NOT NULL,
  `nazwa_kategorii` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategorie`
--

INSERT INTO `kategorie` (`id`, `nazwa_kategorii`) VALUES
(1, 'ciepłe'),
(2, 'ciepłe_napoje'),
(3, 'napoje'),
(4, 'słodkie'),
(5, 'słone');

-- --------------------------------------------------------

--
-- Table structure for table `produkty`
--

CREATE TABLE `produkty` (
  `id` int(10) UNSIGNED NOT NULL,
  `nazwa` varchar(30) NOT NULL,
  `cena` float NOT NULL,
  `kategoria` int(10) UNSIGNED NOT NULL,
  `dostępność` int(11) NOT NULL DEFAULT 0,
  `mnożnik_promocji` float NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `produkty`
--

INSERT INTO `produkty` (`id`, `nazwa`, `cena`, `kategoria`, `dostępność`, `mnożnik_promocji`) VALUES
(1, 'hotdog', 6, 1, 1, 1),
(2, 'double-dog', 8, 1, 1, 1),
(3, 'tost z serem', 2.5, 1, 1, 1),
(4, 'tost z szynką', 2.5, 1, 1, 1),
(5, 'tost z masłem', 1.5, 1, 1, 1),
(6, 'tost z serem i szynką', 4, 1, 1, 1),
(7, 'bułka sucha', 1.5, 1, 1, 1),
(8, 'bułka z masłem', 2, 1, 1, 1),
(9, 'bułka z serem', 3, 1, 1, 1),
(10, 'bułka z szynką', 3, 1, 1, 1),
(11, 'bułka z sosem', 3, 1, 1, 1),
(12, 'bułka z serem i szynką', 4, 1, 1, 1),
(13, 'buła gołosza', 6, 1, 1, 1),
(15, 'zupka Knorr', 6, 2, 1, 1),
(16, 'espresso', 1.5, 2, 1, 1),
(17, 'espresso macchiato', 2.5, 2, 1, 1),
(18, 'kawa czarna', 2, 2, 1, 1),
(19, 'kawa biała', 2.5, 2, 1, 1),
(20, 'cappuccino', 3.5, 2, 1, 1),
(21, 'latte macchiato', 3.5, 2, 1, 1),
(22, 'herbata', 2.5, 2, 1, 1),
(23, 'tymbark karton 1l', 4.5, 3, 1, 1),
(24, 'tymbark plastik 2l', 5, 3, 1, 1),
(25, 'tymbark szkło 0,25l', 2.5, 3, 1, 1),
(26, 'tymbark plastik 0,5l', 3, 3, 1, 1),
(27, 'lipton icetea 1,5l', 6, 3, 1, 1),
(28, 'lipton icetea 0,5l', 3.5, 3, 1, 1),
(29, 'müllermilch', 4.5, 3, 1, 1),
(30, 'cynamonka', 4, 4, 1, 1),
(31, 'oblaty', 2, 5, 1, 1),
(32, 'prince-polo', 2.5, 4, 1, 1),
(33, 'lody pałeczki', 2, 4, 1, 1),
(34, 'lion', 3, 4, 1, 1),
(35, 'góralki', 2.5, 4, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `użytkownik`
--

CREATE TABLE `użytkownik` (
  `id` int(10) UNSIGNED NOT NULL,
  `mail` varchar(60) NOT NULL,
  `hasło` varchar(60) NOT NULL,
  `nazwa_użytkownika` varchar(40) NOT NULL,
  `wiek` int(11) NOT NULL DEFAULT 0,
  `czy_admin` int(11) NOT NULL DEFAULT 0,
  `token` varchar(8) DEFAULT NULL,
  `szamsy` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zamówienia_online`
--

CREATE TABLE `zamówienia_online` (
  `id` int(10) UNSIGNED NOT NULL,
  `użytkownik_id` int(10) UNSIGNED NOT NULL,
  `produkt_id` int(10) UNSIGNED NOT NULL,
  `szczegóły` varchar(1000) DEFAULT NULL,
  `ilość` int(11) NOT NULL DEFAULT 0,
  `stan_przygotowania` varchar(25) DEFAULT 'Płatność zaakceptowana'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategorie`
--
ALTER TABLE `kategorie`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `produkty`
--
ALTER TABLE `produkty`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategoria` (`kategoria`);

--
-- Indexes for table `użytkownik`
--
ALTER TABLE `użytkownik`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zamówienia_online`
--
ALTER TABLE `zamówienia_online`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_ZAM_UŻ` (`użytkownik_id`),
  ADD KEY `FK_ZAM_PROD` (`produkt_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategorie`
--
ALTER TABLE `kategorie`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `produkty`
--
ALTER TABLE `produkty`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `użytkownik`
--
ALTER TABLE `użytkownik`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zamówienia_online`
--
ALTER TABLE `zamówienia_online`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `produkty`
--
ALTER TABLE `produkty`
  ADD CONSTRAINT `produkty_ibfk_1` FOREIGN KEY (`kategoria`) REFERENCES `kategorie` (`id`);

--
-- Constraints for table `zamówienia_online`
--
ALTER TABLE `zamówienia_online`
  ADD CONSTRAINT `FK_ZAM_PROD` FOREIGN KEY (`produkt_id`) REFERENCES `produkty` (`id`),
  ADD CONSTRAINT `FK_ZAM_UŻ` FOREIGN KEY (`użytkownik_id`) REFERENCES `użytkownik` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
