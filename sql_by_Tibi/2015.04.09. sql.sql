-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Hoszt: 127.0.0.1
-- Létrehozás ideje: 2015. Ápr 09. 17:03
-- Szerver verzió: 5.6.16
-- PHP verzió: 5.5.11

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Adatbázis: `fejleszt_erp`
--
DROP DATABASE `fejleszt_erp`;
CREATE  DATABASE `fejleszt_erp`;
USE `fejleszt_erp`;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `felhasznalok`
--

CREATE TABLE IF NOT EXISTS `felhasznalok` (
  `felhasznalo_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nev` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `jelszo` varchar(64) NOT NULL,
  `jog` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `aktiv` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `objectID` int(11) unsigned NOT NULL,
  PRIMARY KEY (`felhasznalo_id`),
  UNIQUE KEY `email` (`email`),
  KEY `object_id_FK` (`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='User adatok' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `objects`
--

CREATE TABLE IF NOT EXISTS `objects` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `penztar`
--

CREATE TABLE IF NOT EXISTS `penztar` (
  `penztar_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `megnevezes` varchar(255) NOT NULL,
  `egyenleg` float unsigned NOT NULL,
  `objectID` int(11) unsigned NOT NULL,
  PRIMARY KEY (`penztar_id`),
  KEY `objectID` (`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Pénztár adatok' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `penztar_felhasznalo`
--

CREATE TABLE IF NOT EXISTS `penztar_felhasznalo` (
  `penztarID` int(10) unsigned NOT NULL,
  `felhasznaloID` int(10) unsigned NOT NULL,
  `objectID` int(11) unsigned NOT NULL,
  KEY `objectID` (`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='pénztárhoz rendelt felhasználók';

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `penztar_tetel`
--

CREATE TABLE IF NOT EXISTS `penztar_tetel` (
  `penztarID` int(10) unsigned NOT NULL,
  `tetel_sorszam` int(10) unsigned NOT NULL,
  `megnevezes` varchar(255) NOT NULL,
  `osszeg` float NOT NULL,
  `datum` date NOT NULL,
  `objectID` int(11) unsigned NOT NULL,
  PRIMARY KEY (`penztarID`,`tetel_sorszam`),
  KEY `objectID` (`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Pénztárhoz tartozó tételek, az összeg előjeles!';

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `szamla`
--

CREATE TABLE IF NOT EXISTS `szamla` (
  `sorszam_elotag` varchar(5) NOT NULL COMMENT 'csak a számlatömb táblában lévőből lehet megadni',
  `sorszam_szam` int(10) unsigned NOT NULL,
  `kiallito_nev` varchar(255) NOT NULL,
  `kiallito_cim` varchar(255) NOT NULL,
  `kiallito_adoszam` varchar(13) NOT NULL,
  `kiallito_bszla` varchar(26) NOT NULL,
  `ugyfelID` int(10) unsigned NOT NULL,
  `befogado_nev` varchar(255) NOT NULL,
  `befogado_cim` varchar(255) NOT NULL,
  `befogado_adoszam` varchar(13) NOT NULL,
  `befogado_bszla` varchar(26) NOT NULL,
  `fizetesi_mod` varchar(20) NOT NULL,
  `kiallitas_datum` date NOT NULL,
  `teljesites_datum` date NOT NULL,
  `fizetes_datum` date NOT NULL,
  `megjegyzes` varchar(255) NOT NULL,
  `objectID` int(11) unsigned NOT NULL,
  PRIMARY KEY (`sorszam_elotag`,`sorszam_szam`),
  KEY `objectID` (`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Számla adatok, mindhez kötelező min. 1 tétel!';

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `szamla_kifizetes`
--

CREATE TABLE IF NOT EXISTS `szamla_kifizetes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sorszam_elotag` varchar(5) NOT NULL,
  `sorszam_szam` int(10) unsigned NOT NULL,
  `kifizetes_datum` date NOT NULL,
  `kifizetett_osszeg` float NOT NULL,
  `objectID` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `objectID` (`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='a számlához tartozó kifizetések' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `szamla_tetel`
--

CREATE TABLE IF NOT EXISTS `szamla_tetel` (
  `szamla_sorszam_elotag` varchar(5) NOT NULL,
  `szamla_sorszam_szam` int(10) unsigned NOT NULL,
  `sorszam` int(10) unsigned NOT NULL,
  `vamtarifaszam` varchar(20) NOT NULL,
  `megnevezes` varchar(255) NOT NULL,
  `mennyiseg_egyseg` varchar(10) NOT NULL,
  `mennyiseg` float unsigned NOT NULL,
  `afa` float unsigned NOT NULL,
  `netto_ar` float NOT NULL,
  `brutto_ar` float NOT NULL,
  `objectID` int(11) unsigned NOT NULL,
  PRIMARY KEY (`szamla_sorszam_elotag`,`szamla_sorszam_szam`,`sorszam`),
  KEY `objectID` (`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='szla_tetel, mindegyik létező számlához kapcsolódik!';

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `szamlatomb`
--

CREATE TABLE IF NOT EXISTS `szamlatomb` (
  `szamlatomb_id` int(10) NOT NULL AUTO_INCREMENT,
  `megnevezes` varchar(255) NOT NULL,
  `szamla_elotag` varchar(5) NOT NULL,
  `szamla_kezdoszam` int(10) unsigned NOT NULL,
  `lezaras_datum` date NOT NULL,
  `objectID` int(11) unsigned NOT NULL,
  PRIMARY KEY (`szamlatomb_id`),
  UNIQUE KEY `szamla_elotag` (`szamla_elotag`),
  KEY `objectID` (`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Számlatömb adatok, csak itt megadott előtagú szla készülhet!' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `ugyfel`
--

CREATE TABLE IF NOT EXISTS `ugyfel` (
  `ugyfel_id` int(10) unsigned NOT NULL,
  `nev` varchar(255) NOT NULL,
  `cim_irszam` varchar(10) NOT NULL,
  `cim_varos` varchar(255) NOT NULL,
  `cim_utca_hsz` varchar(255) NOT NULL,
  `telefon` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `objectID` int(11) unsigned NOT NULL,
  PRIMARY KEY (`ugyfel_id`),
  KEY `objectID` (`objectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Ügyfél adatokhoz, lehet törölni ügyfelet!';

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `felhasznalok`
--
ALTER TABLE `felhasznalok`
  ADD CONSTRAINT `object_id_FK` FOREIGN KEY (`objectID`) REFERENCES `objects` (`id`) ON UPDATE CASCADE;

--
-- Megkötések a táblához `penztar`
--
ALTER TABLE `penztar`
  ADD CONSTRAINT `penztar_ibfk_1` FOREIGN KEY (`objectID`) REFERENCES `objects` (`id`) ON UPDATE CASCADE;

--
-- Megkötések a táblához `penztar_felhasznalo`
--
ALTER TABLE `penztar_felhasznalo`
  ADD CONSTRAINT `penztar_felhasznalo_ibfk_1` FOREIGN KEY (`objectID`) REFERENCES `objects` (`id`) ON UPDATE CASCADE;

--
-- Megkötések a táblához `penztar_tetel`
--
ALTER TABLE `penztar_tetel`
  ADD CONSTRAINT `penztar_tetel_ibfk_2` FOREIGN KEY (`objectID`) REFERENCES `objects` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `penztar_tetel_ibfk_1` FOREIGN KEY (`penztarID`) REFERENCES `penztar` (`penztar_id`) ON UPDATE CASCADE;

--
-- Megkötések a táblához `szamla`
--
ALTER TABLE `szamla`
  ADD CONSTRAINT `szamla_ibfk_2` FOREIGN KEY (`objectID`) REFERENCES `objects` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `szamla_ibfk_1` FOREIGN KEY (`sorszam_elotag`) REFERENCES `szamlatomb` (`szamla_elotag`) ON UPDATE CASCADE;

--
-- Megkötések a táblához `szamla_kifizetes`
--
ALTER TABLE `szamla_kifizetes`
  ADD CONSTRAINT `szamla_kifizetes_ibfk_1` FOREIGN KEY (`objectID`) REFERENCES `objects` (`id`) ON UPDATE CASCADE;

--
-- Megkötések a táblához `szamla_tetel`
--
ALTER TABLE `szamla_tetel`
  ADD CONSTRAINT `szamla_tetel_ibfk_1` FOREIGN KEY (`objectID`) REFERENCES `objects` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `szla_FK` FOREIGN KEY (`szamla_sorszam_elotag`, `szamla_sorszam_szam`) REFERENCES `szamla` (`sorszam_elotag`, `sorszam_szam`);

--
-- Megkötések a táblához `szamlatomb`
--
ALTER TABLE `szamlatomb`
  ADD CONSTRAINT `szamlatomb_ibfk_1` FOREIGN KEY (`objectID`) REFERENCES `objects` (`id`) ON UPDATE CASCADE;

--
-- Megkötések a táblához `ugyfel`
--
ALTER TABLE `ugyfel`
  ADD CONSTRAINT `ugyfel_ibfk_1` FOREIGN KEY (`objectID`) REFERENCES `objects` (`id`) ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
