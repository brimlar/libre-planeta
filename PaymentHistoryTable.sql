-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 12, 2011 at 08:41 AM
-- Server version: 5.1.47
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `homepla5_hpdb1`
--

-- --------------------------------------------------------

--
-- Table structure for table `PaymentHistoryTable`
--

CREATE TABLE IF NOT EXISTS `PaymentHistoryTable` (
  `AutoID` int(11) NOT NULL AUTO_INCREMENT,
  `TranslatedID` int(11) NOT NULL,
  `EmailAddress` varchar(45) NOT NULL,
  `TxnID` varchar(17) NOT NULL,
  `TxnDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `TxnAmount` decimal(4,2) NOT NULL,
  `PreviousExpireDate` date NOT NULL,
  `PreviouslyPaid` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`AutoID`),
  UNIQUE KEY `TxnID` (`TxnID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;
