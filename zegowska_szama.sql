-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2026 at 08:46 PM
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
-- Table structure for table `produkty`
--

CREATE TABLE `produkty` (
  `id` int(10) UNSIGNED NOT NULL,
  `nazwa` varchar(30) NOT NULL,
  `cena` float NOT NULL,
  `kategoria` varchar(40) NOT NULL,
  `dostępność` int(11) NOT NULL DEFAULT 0,
  `mnożnik_promocji` float NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

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
  `token` varchar(8) DEFAULT NULL
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
-- Indexes for table `produkty`
--
ALTER TABLE `produkty`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `produkty`
--
ALTER TABLE `produkty`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- Constraints for table `zamówienia_online`
--
ALTER TABLE `zamówienia_online`
  ADD CONSTRAINT `FK_ZAM_PROD` FOREIGN KEY (`produkt_id`) REFERENCES `produkty` (`id`),
  ADD CONSTRAINT `FK_ZAM_UŻ` FOREIGN KEY (`użytkownik_id`) REFERENCES `użytkownik` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
