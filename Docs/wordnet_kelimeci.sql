-- phpMyAdmin SQL Dump
-- version 3.4.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 27, 2011 at 01:28 PM
-- Server version: 5.0.92
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `wordnet_kelimeci`
--

-- --------------------------------------------------------

--
-- Table structure for table `antonyms`
--

CREATE TABLE IF NOT EXISTS `antonyms` (
  `wId` bigint(20) unsigned NOT NULL auto_increment,
  `antId` bigint(20) unsigned NOT NULL COMMENT 'Zıt anlamlı kelimenin id''si',
  `clsId` smallint(5) unsigned default NULL,
  KEY `fk_antonyms_1` (`wId`),
  KEY `fk_antonyms_2` (`antId`),
  KEY `clsId` (`clsId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='kelimenin zıt anlamlarını tutar' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE IF NOT EXISTS `classes` (
  `id` smallint(1) unsigned NOT NULL auto_increment,
  `name` varchar(50) collate utf8_turkish_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='kelimenin türlerini tutar' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `meanings`
--

CREATE TABLE IF NOT EXISTS `meanings` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `wId` bigint(20) unsigned NOT NULL,
  `lang` varchar(10) collate utf8_turkish_ci NOT NULL COMMENT 'anlamın dilini belirtir eng, tr vb.',
  `clsId` smallint(1) unsigned NOT NULL,
  `meaning` text collate utf8_turkish_ci,
  PRIMARY KEY  (`id`),
  KEY `fk_meanings_1` (`wId`),
  KEY `fk_meanings_2` (`clsId`),
  KEY `meaingsindex` (`lang`,`wId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='kelimenin anlamlarını ve cümlelerini tutar' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `nearbyWords`
--

CREATE TABLE IF NOT EXISTS `nearbyWords` (
  `wId` bigint(20) unsigned NOT NULL auto_increment,
  `nearbyId` bigint(20) unsigned NOT NULL COMMENT 'Yakın anlamlı kelimenin id''si',
  KEY `fk_nearbyWords_1` (`wId`),
  KEY `fk_nearbyWords_2` (`nearbyId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='kelimenin yakın anlamlarını tutar' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `quotes`
--

CREATE TABLE IF NOT EXISTS `quotes` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `wId` bigint(20) unsigned NOT NULL,
  `quote` text collate utf8_turkish_ci,
  PRIMARY KEY  (`id`),
  KEY `fk_quotes_1` (`wId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='Kelimenin alıntılarını tutan tablodur.Çekilen verilerden olu' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `synonyms`
--

CREATE TABLE IF NOT EXISTS `synonyms` (
  `wId` bigint(20) unsigned NOT NULL auto_increment,
  `synId` bigint(20) unsigned NOT NULL COMMENT 'Eş anlamlı kelimenin id''si',
  `clsId` smallint(5) unsigned default NULL,
  KEY `fk_synonyms_1` (`wId`),
  KEY `fk_synonyms_2` (`synId`),
  KEY `clsId` (`clsId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='kelimenin eş anlamlarını tutar' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE IF NOT EXISTS `test` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userId` int(10) unsigned NOT NULL,
  `wordId` bigint(20) unsigned NOT NULL,
  `testTypeId` tinyint(1) unsigned NOT NULL,
  `result` tinyint(1) unsigned NOT NULL COMMENT 'Test sonucu; 0=başarısız, 1=başarılı',
  `answer` text collate utf8_turkish_ci NOT NULL COMMENT 'soru ve soruya verilen cevap gibi veriler',
  `crtDate` timestamp NULL default NULL,
  PRIMARY KEY  (`id`),
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
  `id` tinyint(1) unsigned NOT NULL auto_increment,
  `name` varchar(45) collate utf8_turkish_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='test türlerini tutar' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `userQuotes`
--

CREATE TABLE IF NOT EXISTS `userQuotes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userId` int(10) unsigned NOT NULL,
  `wordId` bigint(20) unsigned NOT NULL,
  `quote` text collate utf8_turkish_ci NOT NULL,
  `crtDate` timestamp NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `fk_userQuotes_1` (`userId`),
  KEY `fk_userQuotes_2` (`wordId`),
  KEY `uquotei` (`userId`,`wordId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='kullanıcıların eklediği alıntılar' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `active` tinyint(1) unsigned NOT NULL default '0',
  `email` varchar(80) collate utf8_turkish_ci NOT NULL,
  `password` varchar(40) collate utf8_turkish_ci NOT NULL COMMENT 'minlength=5',
  `fname` varchar(15) collate utf8_turkish_ci default NULL,
  `lname` varchar(15) collate utf8_turkish_ci default NULL,
  `crtDate` timestamp NULL default CURRENT_TIMESTAMP,
  `lastLogin` timestamp NULL default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `loginbyemail` (`password`,`email`,`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vocabulary`
--

CREATE TABLE IF NOT EXISTS `vocabulary` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `userId` int(10) unsigned NOT NULL,
  `wordId` bigint(20) unsigned NOT NULL,
  `level` tinyint(1) unsigned default '0' COMMENT 'kelimenin öğrenilme seviyesi',
  `crtDate` timestamp NULL default CURRENT_TIMESTAMP,
  `tags` varchar(30) collate utf8_turkish_ci default NULL COMMENT 'kelimenin etiketi',
  PRIMARY KEY  (`id`),
  KEY `fk_vocabulary_1` (`userId`),
  KEY `fk_vocabulary_2` (`wordId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='kullanıcıların kelime dağarcını tutar.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wordClasses`
--

CREATE TABLE IF NOT EXISTS `wordClasses` (
  `wId` bigint(20) unsigned NOT NULL auto_increment,
  `clsId` smallint(1) unsigned NOT NULL,
  KEY `fk_wordClasses_1` (`clsId`),
  KEY `fk_wordClasses_2` (`wId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='kelime ile kelimenin türünün bağlarını tutar' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wordContents`
--

CREATE TABLE IF NOT EXISTS `wordContents` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `wId` bigint(20) unsigned NOT NULL,
  `url` text collate utf8_turkish_ci,
  `content` longtext collate utf8_turkish_ci,
  `crawlDate` timestamp NULL default NULL,
  `webPageName` varchar(20) collate utf8_turkish_ci NOT NULL COMMENT 'ver çekilen sayfanın adı google,urban,dictionary,seslisozluk',
  PRIMARY KEY  (`id`),
  KEY `fk_wordContents_1` (`wId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='kelimeyle ilgili sayfanın html verisini tutar' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wordInfo`
--

CREATE TABLE IF NOT EXISTS `wordInfo` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `wId` bigint(20) unsigned NOT NULL,
  `name` varchar(25) collate utf8_turkish_ci default NULL,
  `value` text collate utf8_turkish_ci,
  PRIMARY KEY  (`id`),
  KEY `fk_wordInfo_1` (`wId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='etimoloji,okunuşu,dil v.b. bilgiler tutulur' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `words`
--

CREATE TABLE IF NOT EXISTS `words` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `word` varchar(45) collate utf8_turkish_ci default NULL,
  PRIMARY KEY  (`id`)
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
