-- phpMyAdmin SQL Dump
-- version 3.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 01, 2011 at 02:56 AM
-- Server version: 5.1.57
-- PHP Version: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `kelimeci`
--

-- --------------------------------------------------------

--
-- Table structure for table `antonyms`
--

CREATE TABLE IF NOT EXISTS `antonyms` (
  `wId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `antId` bigint(20) unsigned NOT NULL COMMENT 'Zıt anlamlı kelimenin id''si',
  `clsId` smallint(5) unsigned DEFAULT NULL,
  KEY `fk_antonyms_1` (`wId`),
  KEY `fk_antonyms_2` (`antId`),
  KEY `clsId` (`clsId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='kelimenin zıt anlamlarını tutar' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE IF NOT EXISTS `classes` (
  `id` smallint(1) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='kelimenin türlerini tutar' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `meanings`
--

CREATE TABLE IF NOT EXISTS `meanings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `wId` bigint(20) unsigned NOT NULL,
  `lang` varchar(10) COLLATE utf8_turkish_ci NOT NULL COMMENT 'anlamın dilini belirtir eng, tr vb.',
  `clsId` smallint(1) unsigned NOT NULL,
  `meaning` text COLLATE utf8_turkish_ci,
  PRIMARY KEY (`id`),
  KEY `fk_meanings_1` (`wId`),
  KEY `fk_meanings_2` (`clsId`),
  KEY `meaingsindex` (`lang`,`wId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='kelimenin anlamlarını ve cümlelerini tutar' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `nearbyWords`
--

CREATE TABLE IF NOT EXISTS `nearbyWords` (
  `wId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nearbyId` bigint(20) unsigned NOT NULL COMMENT 'Yakın anlamlı kelimenin id''si',
  KEY `fk_nearbyWords_1` (`wId`),
  KEY `fk_nearbyWords_2` (`nearbyId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='kelimenin yakın anlamlarını tutar' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `quotes`
--

CREATE TABLE IF NOT EXISTS `quotes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `wId` bigint(20) unsigned NOT NULL,
  `quote` text COLLATE utf8_turkish_ci,
  PRIMARY KEY (`id`),
  KEY `fk_quotes_1` (`wId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='Kelimenin alıntılarını tutan tablodur.Çekilen verilerden olu' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `synonyms`
--

CREATE TABLE IF NOT EXISTS `synonyms` (
  `wId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `synId` bigint(20) unsigned NOT NULL COMMENT 'Eş anlamlı kelimenin id''si',
  `clsId` smallint(5) unsigned DEFAULT NULL,
  KEY `fk_synonyms_1` (`wId`),
  KEY `fk_synonyms_2` (`synId`),
  KEY `clsId` (`clsId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='kelimenin eş anlamlarını tutar' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE IF NOT EXISTS `test` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL,
  `wordId` bigint(20) unsigned NOT NULL,
  `testTypeId` tinyint(1) unsigned NOT NULL,
  `result` tinyint(1) unsigned NOT NULL COMMENT 'Test sonucu; 0=başarısız, 1=başarılı',
  `answer` text COLLATE utf8_turkish_ci NOT NULL COMMENT 'soru ve soruya verilen cevap gibi veriler',
  `crtDate` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_test_1` (`userId`),
  KEY `fk_test_2` (`wordId`),
  KEY `fk_test_3` (`testTypeId`),
  KEY `testqueryi1` (`userId`,`wordId`,`testTypeId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='test sonuçlarının tutulduğu tablo' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `testTypes`
--

CREATE TABLE IF NOT EXISTS `testTypes` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='test türlerini tutar' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `userQuotes`
--

CREATE TABLE IF NOT EXISTS `userQuotes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL,
  `wordId` bigint(20) unsigned NOT NULL,
  `quote` text COLLATE utf8_turkish_ci NOT NULL,
  `crtDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_userQuotes_1` (`userId`),
  KEY `fk_userQuotes_2` (`wordId`),
  KEY `uquotei` (`userId`,`wordId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='kullanıcıların eklediği alıntılar' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE  TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `active` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 ,
  `email` VARCHAR(80) NOT NULL ,
  `username` VARCHAR(20) NULL ,
  `password` VARCHAR(40) NOT NULL COMMENT 'minlength=5' ,
  `fname` VARCHAR(15) NULL ,
  `lname` VARCHAR(15) NULL ,
  `birthDate` DATE NULL ,
  `city` VARCHAR(40) NULL ,
  `crtDate` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
  `lastLogin` TIMESTAMP NULL ,
  `practice` TINYINT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) ,
  INDEX `loginbyemail` (`password` ASC, `email` ASC, `active` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vocabulary`
--

CREATE TABLE IF NOT EXISTS `vocabulary` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL,
  `wordId` bigint(20) unsigned NOT NULL,
  `level` tinyint(1) unsigned DEFAULT '0' COMMENT 'kelimenin öğrenilme seviyesi',
  `crtDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tags` varchar(30) COLLATE utf8_turkish_ci DEFAULT NULL COMMENT 'kelimenin etiketi',
  PRIMARY KEY (`id`),
  KEY `fk_vocabulary_1` (`userId`),
  KEY `fk_vocabulary_2` (`wordId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='kullanıcıların kelime dağarcını tutar.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wordClasses`
--

CREATE TABLE IF NOT EXISTS `wordClasses` (
  `wId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `clsId` smallint(1) unsigned NOT NULL,
  KEY `fk_wordClasses_1` (`clsId`),
  KEY `fk_wordClasses_2` (`wId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='kelime ile kelimenin türünün bağlarını tutar' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wordContents`
--

CREATE TABLE IF NOT EXISTS `wordContents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `wId` bigint(20) unsigned NOT NULL,
  `url` text COLLATE utf8_turkish_ci,
  `content` longtext COLLATE utf8_turkish_ci,
  `crawlDate` timestamp NULL DEFAULT NULL,
  `webPageName` varchar(20) COLLATE utf8_turkish_ci NOT NULL COMMENT 'ver çekilen sayfanın adı google,urban,dictionary,seslisozluk',
  PRIMARY KEY (`id`),
  KEY `fk_wordContents_1` (`wId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='kelimeyle ilgili sayfanın html verisini tutar' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wordInfo`
--

CREATE TABLE IF NOT EXISTS `wordInfo` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `wId` bigint(20) unsigned NOT NULL,
  `name` varchar(25) COLLATE utf8_turkish_ci DEFAULT NULL,
  `value` text COLLATE utf8_turkish_ci,
  PRIMARY KEY (`id`),
  KEY `fk_wordInfo_1` (`wId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='etimoloji,okunuşu,dil v.b. bilgiler tutulur' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `words`
--

CREATE TABLE IF NOT EXISTS `words` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `word` varchar(45) COLLATE utf8_turkish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='tüm kelimelerin saklandığı tablo' AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `antonyms`
--
ALTER TABLE `antonyms`
  ADD CONSTRAINT `antonyms_ibfk_3` FOREIGN KEY (`clsId`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `antonyms_ibfk_1` FOREIGN KEY (`wId`) REFERENCES `words` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `antonyms_ibfk_2` FOREIGN KEY (`antId`) REFERENCES `words` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `meanings`
--
ALTER TABLE `meanings`
  ADD CONSTRAINT `meanings_ibfk_1` FOREIGN KEY (`clsId`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_meanings_1` FOREIGN KEY (`wId`) REFERENCES `words` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `nearbyWords`
--
ALTER TABLE `nearbyWords`
  ADD CONSTRAINT `nearbyWords_ibfk_1` FOREIGN KEY (`wId`) REFERENCES `words` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nearbyWords_ibfk_2` FOREIGN KEY (`nearbyId`) REFERENCES `words` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quotes`
--
ALTER TABLE `quotes`
  ADD CONSTRAINT `fk_quotes_1` FOREIGN KEY (`wId`) REFERENCES `words` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `synonyms`
--
ALTER TABLE `synonyms`
  ADD CONSTRAINT `synonyms_ibfk_3` FOREIGN KEY (`clsId`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `synonyms_ibfk_1` FOREIGN KEY (`wId`) REFERENCES `words` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `synonyms_ibfk_2` FOREIGN KEY (`synId`) REFERENCES `words` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `test`
--
ALTER TABLE `test`
  ADD CONSTRAINT `fk_test_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_test_2` FOREIGN KEY (`wordId`) REFERENCES `words` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_test_3` FOREIGN KEY (`testTypeId`) REFERENCES `testTypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `userQuotes`
--
ALTER TABLE `userQuotes`
  ADD CONSTRAINT `fk_userQuotes_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_userQuotes_2` FOREIGN KEY (`wordId`) REFERENCES `words` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vocabulary`
--
ALTER TABLE `vocabulary`
  ADD CONSTRAINT `fk_vocabulary_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vocabulary_2` FOREIGN KEY (`wordId`) REFERENCES `words` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wordClasses`
--
ALTER TABLE `wordClasses`
  ADD CONSTRAINT `wordClasses_ibfk_1` FOREIGN KEY (`clsId`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_wordClasses_2` FOREIGN KEY (`wId`) REFERENCES `words` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wordContents`
--
ALTER TABLE `wordContents`
  ADD CONSTRAINT `fk_wordContents_1` FOREIGN KEY (`wId`) REFERENCES `words` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wordInfo`
--
ALTER TABLE `wordInfo`
  ADD CONSTRAINT `fk_wordInfo_1` FOREIGN KEY (`wId`) REFERENCES `words` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

CREATE  TABLE IF NOT EXISTS `feedbacks` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(50) NULL ,
  `email` VARCHAR(80) NULL ,
  `comments` TEXT NULL ,
  `crtDate` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB, 
COMMENT = 'feedbackleri tutar.' 
 
