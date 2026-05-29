-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2026 at 10:54 PM
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
  `mnożnik_promocji` float NOT NULL DEFAULT 1,
  `zdjęcie` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `produkty`
--

INSERT INTO `produkty` (`id`, `nazwa`, `cena`, `kategoria`, `dostępność`, `mnożnik_promocji`, `zdjęcie`) VALUES
(1, 'hotdog', 6, 1, 1, 1, 'haroł.png'),
(2, 'double-dog', 8, 1, 1, 1, 'double_dog.png'),
(3, 'tost z serem', 2.5, 1, 1, 1, 'tost_z_serem.png'),
(4, 'tost z szynką', 2.5, 1, 1, 0.8, 'tost_z_szynką.png'),
(5, 'tost z masłem', 1.5, 1, 1, 1, 'tost_z_masłem.png'),
(6, 'tost ser szynka', 4, 1, 1, 1, 'tost_z_szynką_i_serem.png'),
(7, 'bułka sucha', 1.5, 1, 1, 1, 'sucha_bułka.png'),
(8, 'bułka z masłem', 2, 1, 1, 1, 'bułka_z_masłem.png'),
(9, 'bułka z serem', 3, 1, 1, 0.75, 'bułka_z_serem.png'),
(10, 'bułka z szynką', 3, 1, 1, 1, 'bułka_z_szynką.png'),
(11, 'bułka z sosem', 3, 1, 1, 1, 'bułka_z_sosem.png'),
(12, 'bułka ser szynka', 4, 1, 1, 1, 'bułka_z_serem_i_szynką.png'),
(13, 'buła gołosza', 6, 1, 1, 1, 'bułka_gołosza.png'),
(16, 'espresso', 1.5, 2, 1, 1, 'espresso.png'),
(17, 'espresso macchiato', 2.5, 2, 1, 0.9, 'espresso_macchiato.png'),
(18, 'kawa czarna', 2, 2, 1, 1, 'kawa_czarna.png'),
(19, 'kawa biała', 2.5, 2, 1, 1, 'kawa_biała.png'),
(20, 'cappuccino', 3.5, 2, 1, 1, 'cappuccino.png'),
(21, 'latte macchiato', 3.5, 2, 1, 1, 'latte_macchiato.png'),
(22, 'herbata', 2.5, 2, 1, 1, 'herbata.png'),
(23, 'tymbark karton 1l', 4.5, 3, 1, 0.95, 'tymbark_karton_1l.png'),
(24, 'tymbark plastik 2l', 5, 3, 1, 1, 'tymbark_plastik_2l.png'),
(25, 'tymbark szkło 0,25l', 2.5, 3, 1, 1, 'tymbark_szkło_0,25l.png'),
(26, 'tymbark plastik 0,5l', 3, 3, 1, 1, 'tymbark_plastik_0,5l.png'),
(27, 'lipton icetea 1,5l', 6, 3, 1, 1, 'lipton_icetea_1,5l.png'),
(28, 'lipton icetea 0,5l', 3.5, 3, 1, 1, 'lipton_icetea_0,5l.png'),
(29, 'müllermilch', 4.5, 3, 1, 1, 'mullermilch.png'),
(30, 'cynamonka', 4, 4, 1, 0.5, 'cynamonka.png'),
(31, 'oblaty', 2, 5, 1, 1, 'oblaty.png'),
(32, 'prince-polo', 2.5, 4, 1, 1, 'prince_polo.png'),
(33, 'lody pałeczki', 2, 4, 1, 1, 'pałeczki.png'),
(34, 'lion', 3, 4, 1, 1, 'lion.png'),
(35, 'góralki', 2.5, 4, 1, 1, 'góralki.png'),
(36, 'zupka Knorr', 6, 2, 1, 1, 'knorr.png');

-- --------------------------------------------------------

--
-- Table structure for table `użytkownik`
--

CREATE TABLE `użytkownik` (
  `id` int(10) UNSIGNED NOT NULL,
  `mail` varchar(60) NOT NULL,
  `hasło` varchar(60) NOT NULL,
  `nazwa_użytkownika` varchar(40) NOT NULL,
  `czy_admin` int(11) NOT NULL DEFAULT 0,
  `token` varchar(8) DEFAULT NULL,
  `szamsy` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `użytkownik`
--

INSERT INTO `użytkownik` (`id`, `mail`, `hasło`, `nazwa_użytkownika`, `czy_admin`, `token`, `szamsy`) VALUES
(1, 'janekKabanek@mail.com', '$2y$10$w0T8kGjndJX2eFdrvYWf1.phwwx3u24jviP7pJ/Aq2N/NiJfexms2', 'janekKabanek', 0, NULL, 108),
(2, 'janekKabanek2@mail.com', '$2y$10$erl42MBNl/gXSGU83SDBHO2.Eb.AbbGZb5lBjus0knQIA3zexqnPO', 'janekKabanek2', 0, NULL, 0),
(3, 'janekKabanek3@mail.com', '$2y$10$EGcnaSubmFqHDs7vw9e8zuV4QyTSPJ5CB.t6t7hTBe7OK4UjQgal2', 'janekKabanek3', 0, NULL, 0),
(4, 'jakKaban@mail.com', '$2y$10$Q3HKPa.Jcme6PkOWn2pji.0xCF9n7SkmQku0Gxi9r.BEepitB6Z3e', 'InnyJanek', 0, NULL, 110444);

-- --------------------------------------------------------

--
-- Table structure for table `zamówienia_online`
--

CREATE TABLE `zamówienia_online` (
  `id` int(10) UNSIGNED NOT NULL,
  `użytkownik_id` int(10) UNSIGNED NOT NULL,
  `numer_zamowienia` varchar(30) NOT NULL,
  `produkt_id` int(10) UNSIGNED NOT NULL,
  `szczegóły` varchar(1000) DEFAULT NULL,
  `ilość` int(11) NOT NULL DEFAULT 0,
  `stan_przygotowania` varchar(25) DEFAULT 'Płatność zaakceptowana'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `zamówienia_online`
--

INSERT INTO `zamówienia_online` (`id`, `użytkownik_id`, `numer_zamowienia`, `produkt_id`, `szczegóły`, `ilość`, `stan_przygotowania`) VALUES
(1, 1, 'ZAM-1779995852-543', 5, 'dużo masła', 2, 'Płatność zaakceptowana'),
(2, 1, 'ZAM-1779995852-543', 6, '', 2, 'Płatność zaakceptowana'),
(3, 4, 'ZAM-1779996053-446', 2, '', 1, 'Płatność zaakceptowana'),
(4, 4, 'ZAM-1779996053-446', 21, '', 1, 'Płatność zaakceptowana'),
(5, 4, 'ZAM-1779996053-446', 35, '', 1, 'Płatność zaakceptowana'),
(6, 4, 'ZAM-1779996707-986', 5, '', 1, 'Płatność zaakceptowana'),
(7, 4, 'ZAM-1779996707-986', 6, '', 2, 'Płatność zaakceptowana'),
(8, 1, 'ZAM-1780087977-603', 9, '', 4, 'Płatność zaakceptowana'),
(9, 1, 'ZAM-1780088012-137', 30, 'dużo cynamonu', 2, 'Płatność zaakceptowana'),
(10, 1, 'ZAM-1780088012-137', 6, '', 1, 'Płatność zaakceptowana');

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `użytkownik`
--
ALTER TABLE `użytkownik`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `zamówienia_online`
--
ALTER TABLE `zamówienia_online`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
