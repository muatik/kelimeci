-- phpMyAdmin SQL Dump-- version 3.3.1-- http://www.phpmyadmin.net---- Host: lo



calhost-- Generation Time: Sep 09, 2011 at 10:36 AM-- Server version:

 5.5.14-- PHP Version: 5.3.6SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";-




 --- Database: `kelimeci`------ Dumping data for table `classes`--





 INSERT INTO `classes` (`id`, `name`) VALUES(3, 'adjective'),(4, 'ad



 verb'),(2, 'noun'),(5, 'preposition'),(1, 'veb');---- Dumping dat





 a for table `meanings`--INSERT INTO `meanings` (`id`, `wId`, `lang`,


  `clsId`, `meaning`) VALUES(1, 1, 'eng', 2, 'a test, trial, or tentati
  ve procedure; an act or operation for the purpose of discovering something unknown or of testing a principle, supposition, etc.: a chemical experiment; a teaching experiment; an experiment in living.'),(2, 2, 'en
  g', 1, 'to habituate or abandon (oneself) to something compulsively or obsessively: a writer addicted to the use of high-flown language; children addicted to video games.'),(3, 2, 'eng', 2, 'a person who is addicted  to 
  an activity, habit, or substance: a drug addict.');---- Dumping data


   for table `nearbyWords`------ Dumping data for table `quotes`--






   ---- Dumping data for table `synonyms`------ Dumping data for t







   able `test`------ Dumping data for table `testTypes`------ Du









   mping data for table `userQuotes`------ Dumping data for table `us




   ers`--INSERT INTO `users` (`id`, `active`, `email`, `password`, `fna


   me`, `lname`, `crtDate`, `lastLogin`) VALUES(1, 1, 'muatik@gmail.com',
    '12345', 'Mustafa', 'Atik', '2011-09-08 21:12:33', '2011-09-08 21:12:33'),(2, 1, 'mr.ermangulhan@gmail.com', '12345', 'Erman', 'Gülhan', '2011-09-0
    8 21:12:33', '2011-09-08 21:13:02'),(3, 1, 'alpaycom@gmail.com', '1234
    5', 'Alpay', 'Önal', '2011-09-08 21:12:58', '2011-09-08 21:12:58');-

    --- Dumping data for table `vocabulary`------ Dumping data for ta





    ble `wordClasses`------ Dumping data for table `wordContents`--







    ---- Dumping data for table `wordInfo`--INSERT INTO `wordInfo` (`i




    d`, `wId`, `name`, `value`) VALUES(1, 1, 'lang', 'en'),(2, 2, 'lang',

     'en'),(3, 3, 'lang', 'en'),(4, 4, 'lang', 'en');---- Dumping data




      for table `words`--INSERT INTO `words` (`id`, `word`) VALUES(1, 'e



      xperimentation'),(2, 'addict'),(3, 'explore'),(4, 'instinctively');



      
