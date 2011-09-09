SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `active` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 ,
  `email` VARCHAR(80) NOT NULL ,
  `password` VARCHAR(40) NOT NULL COMMENT 'minlength=5' ,
  `fname` VARCHAR(15) NULL ,
  `lname` VARCHAR(15) NULL ,
  `crtDate` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
  `lastLogin` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) ,
  INDEX `loginbyemail` (`password` ASC, `email` ASC, `active` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_turkish_ci;


-- -----------------------------------------------------
-- Table `words`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `words` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `word` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_turkish_ci, 
COMMENT = 'tüm kelimelerin saklandığı tablo' ;


-- -----------------------------------------------------
-- Table `vocabulary`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `vocabulary` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `userId` INT UNSIGNED NOT NULL ,
  `wordId` BIGINT UNSIGNED NOT NULL ,
  `level` TINYINT(1) UNSIGNED NULL DEFAULT 0 COMMENT 'kelimenin öğrenilme seviyesi' ,
  `crtDate` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
  `tags` VARCHAR(30) NULL COMMENT 'kelimenin etiketi' ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_vocabulary_1` (`userId` ASC) ,
  INDEX `fk_vocabulary_2` (`wordId` ASC) ,
  CONSTRAINT `fk_vocabulary_1`
    FOREIGN KEY (`userId` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_vocabulary_2`
    FOREIGN KEY (`wordId` )
    REFERENCES `words` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_turkish_ci, 
COMMENT = 'kullanıcıların kelime dağarcını tutar.' ;


-- -----------------------------------------------------
-- Table `userQuotes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `userQuotes` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `userId` INT UNSIGNED NOT NULL ,
  `wordId` BIGINT UNSIGNED NOT NULL ,
  `quote` TEXT NOT NULL ,
  `crtDate` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_userQuotes_1` (`userId` ASC) ,
  INDEX `fk_userQuotes_2` (`wordId` ASC) ,
  INDEX `uquotei` (`userId` ASC, `wordId` ASC) ,
  CONSTRAINT `fk_userQuotes_1`
    FOREIGN KEY (`userId` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_userQuotes_2`
    FOREIGN KEY (`wordId` )
    REFERENCES `words` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_turkish_ci, 
COMMENT = 'kullanıcıların eklediği alıntılar' ;


-- -----------------------------------------------------
-- Table `testTypes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `testTypes` (
  `id` TINYINT(1) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_turkish_ci, 
COMMENT = 'test türlerini tutar' ;


-- -----------------------------------------------------
-- Table `test`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `test` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `userId` INT UNSIGNED NOT NULL ,
  `wordId` BIGINT UNSIGNED NOT NULL ,
  `testTypeId` TINYINT(1) UNSIGNED NOT NULL ,
  `result` TINYINT(1) UNSIGNED NOT NULL COMMENT 'Test sonucu; 0=başarısız, 1=başarılı' ,
  `answer` TEXT NOT NULL COMMENT 'soru ve soruya verilen cevap gibi veriler' ,
  `crtDate` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_test_1` (`userId` ASC) ,
  INDEX `fk_test_2` (`wordId` ASC) ,
  INDEX `fk_test_3` (`testTypeId` ASC) ,
  INDEX `testqueryi1` (`userId` ASC, `wordId` ASC, `testTypeId` ASC) ,
  CONSTRAINT `fk_test_1`
    FOREIGN KEY (`userId` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_test_2`
    FOREIGN KEY (`wordId` )
    REFERENCES `words` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_test_3`
    FOREIGN KEY (`testTypeId` )
    REFERENCES `testTypes` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_turkish_ci, 
COMMENT = 'test sonuçlarının tutulduğu tablo' ;


-- -----------------------------------------------------
-- Table `classes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `classes` (
  `id` TINYINT(1) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(20) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_turkish_ci, 
COMMENT = 'kelimenin türlerini tutar' ;


-- -----------------------------------------------------
-- Table `wordClasses`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `wordClasses` (
  `wId` BIGINT UNSIGNED NOT NULL ,
  `clsId` TINYINT(1) UNSIGNED NOT NULL ,
  INDEX `fk_wordClasses_1` (`clsId` ASC) ,
  INDEX `fk_wordClasses_2` (`wId` ASC) ,
  CONSTRAINT `fk_wordClasses_1`
    FOREIGN KEY (`clsId` )
    REFERENCES `classes` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_wordClasses_2`
    FOREIGN KEY (`wId` )
    REFERENCES `words` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_turkish_ci, 
COMMENT = 'kelime ile kelimenin türünün bağlarını tutar' ;


-- -----------------------------------------------------
-- Table `synonyms`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `synonyms` (
  `wId` BIGINT UNSIGNED NOT NULL ,
  `synId` BIGINT UNSIGNED NOT NULL COMMENT 'Eş anlamlı kelimenin id\'si' ,
  INDEX `fk_synonyms_1` (`wId` ASC) ,
  INDEX `fk_synonyms_2` (`synId` ASC) ,
  CONSTRAINT `fk_synonyms_1`
    FOREIGN KEY (`wId` )
    REFERENCES `words` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_synonyms_2`
    FOREIGN KEY (`synId` )
    REFERENCES `words` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_turkish_ci, 
COMMENT = 'kelimenin eş anlamlarını tutar' ;


-- -----------------------------------------------------
-- Table `nearbyWords`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `nearbyWords` (
  `wId` BIGINT UNSIGNED NOT NULL ,
  `nearbyId` BIGINT UNSIGNED NOT NULL COMMENT 'Yakın anlamlı kelimenin id\'si' ,
  INDEX `fk_nearbyWords_1` (`wId` ASC) ,
  INDEX `fk_nearbyWords_2` (`nearbyId` ASC) ,
  CONSTRAINT `fk_nearbyWords_1`
    FOREIGN KEY (`wId` )
    REFERENCES `words` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_nearbyWords_2`
    FOREIGN KEY (`nearbyId` )
    REFERENCES `words` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_turkish_ci, 
COMMENT = 'kelimenin yakın anlamlarını tutar' ;


-- -----------------------------------------------------
-- Table `wordInfo`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `wordInfo` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `wId` BIGINT UNSIGNED NOT NULL ,
  `name` VARCHAR(25) NULL ,
  `value` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_wordInfo_1` (`wId` ASC) ,
  CONSTRAINT `fk_wordInfo_1`
    FOREIGN KEY (`wId` )
    REFERENCES `words` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_turkish_ci, 
COMMENT = 'etimoloji,okunuşu,dil v.b. bilgiler tutulur' ;


-- -----------------------------------------------------
-- Table `wordContents`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `wordContents` (
  `id` BIGINT UNSIGNED NOT NULL ,
  `wId` BIGINT UNSIGNED NOT NULL ,
  `url` TEXT NULL ,
  `content` LONGTEXT NULL ,
  `crawlDate` TIMESTAMP NULL ,
  `crawled` TINYINT(1) NULL ,
  INDEX `fk_wordContents_1` (`wId` ASC) ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_wordContents_1`
    FOREIGN KEY (`wId` )
    REFERENCES `words` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_turkish_ci, 
COMMENT = 'kelimeyle ilgili sayfanın html verisini tutar' ;


-- -----------------------------------------------------
-- Table `meanings`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `meanings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `wId` BIGINT UNSIGNED NOT NULL ,
  `lang` VARCHAR(10) NOT NULL COMMENT 'anlamın dilini belirtir eng, tr vb.' ,
  `clsId` TINYINT(1) UNSIGNED NOT NULL ,
  `meaning` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_meanings_1` (`wId` ASC) ,
  INDEX `fk_meanings_2` (`clsId` ASC) ,
  INDEX `meaingsindex` (`lang` ASC, `wId` ASC) ,
  CONSTRAINT `fk_meanings_1`
    FOREIGN KEY (`wId` )
    REFERENCES `words` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_meanings_2`
    FOREIGN KEY (`clsId` )
    REFERENCES `classes` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_turkish_ci, 
COMMENT = 'kelimenin anlamlarını ve cümlelerini tutar' ;


-- -----------------------------------------------------
-- Table `quotes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `quotes` (
  `id` BIGINT UNSIGNED NOT NULL ,
  `wId` BIGINT UNSIGNED NOT NULL ,
  `quote` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_quotes_1` (`wId` ASC) ,
  CONSTRAINT `fk_quotes_1`
    FOREIGN KEY (`wId` )
    REFERENCES `words` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_turkish_ci, 
COMMENT = 'Kelimenin alıntılarını tutan tablodur.Çekilen verilerden olu' /* comment truncated */ ;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
