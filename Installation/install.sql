-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 25, 2012 at 05:37 PM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sales_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `artist`
--

CREATE TABLE IF NOT EXISTS `artist` (
  `artistId` int(11) NOT NULL AUTO_INCREMENT,
  `forename` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `websiteUrl` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `nationality` varchar(50) NOT NULL,
  `bandName` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`artistId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=95 ;

--
-- Dumping data for table `artist`
--


-- --------------------------------------------------------

--
-- Table structure for table `chart`
--

CREATE TABLE IF NOT EXISTS `chart` (
  `chartId` int(11) NOT NULL AUTO_INCREMENT,
  `config` text,
  `chartName` varchar(50) DEFAULT NULL,
  `chartType` varchar(50) DEFAULT NULL,
  `serialisedClass` blob,
  `isEditable` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`chartId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=124 ;

--
-- Dumping data for table `chart`
--


-- --------------------------------------------------------

--
-- Table structure for table `chartpermission`
--

CREATE TABLE IF NOT EXISTS `chartpermission` (
  `chartPermId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) DEFAULT NULL,
  `userGroupId` int(11) DEFAULT NULL,
  `chartId` int(11) DEFAULT NULL,
  PRIMARY KEY (`chartPermId`),
  KEY `fkUserId` (`userId`),
  KEY `fkGroupId` (`userGroupId`),
  KEY `fkChartId` (`chartId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `chartpermission`
--


-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `emailAddress` varchar(100) NOT NULL,
  `forename` varchar(50) DEFAULT NULL,
  `surname` varchar(50) DEFAULT NULL,
  `addressLine1` varchar(50) DEFAULT NULL,
  `addressLine2` varchar(50) DEFAULT NULL,
  `town` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `postcode` varchar(50) DEFAULT NULL,
  `telephoneNumber` varchar(50) DEFAULT NULL,
  `customerId` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`customerId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `customer`
--


-- --------------------------------------------------------

--
-- Table structure for table `dashboardlayout`
--

CREATE TABLE IF NOT EXISTS `dashboardlayout` (
  `dashboardLayoutId` int(11) NOT NULL AUTO_INCREMENT,
  `chartPos` int(11) NOT NULL,
  `tabId` int(11) NOT NULL,
  `chartId` int(11) DEFAULT NULL,
  `customFilter` text,
  PRIMARY KEY (`dashboardLayoutId`),
  KEY `tabIDFK` (`tabId`),
  KEY `chartIDFK` (`chartId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `dashboardlayout`
--


-- --------------------------------------------------------

--
-- Table structure for table `dashboardtab`
--

CREATE TABLE IF NOT EXISTS `dashboardtab` (
  `tabId` int(11) NOT NULL AUTO_INCREMENT,
  `tabName` varchar(50) NOT NULL,
  `tabDescription` text NOT NULL,
  `userId` int(11) NOT NULL,
  PRIMARY KEY (`tabId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `dashboardtab`
--

INSERT INTO `dashboardtab` (`tabId`, `tabName`, `tabDescription`, `userId`) VALUES
(10, 'Default Tab', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `datelookup`
--

CREATE TABLE IF NOT EXISTS `datelookup` (
  `DateKey` int(11) NOT NULL,
  `DateFull` datetime DEFAULT NULL,
  `CharacterDate` varchar(10) DEFAULT NULL,
  `FullYear` char(4) DEFAULT NULL,
  `QuarterNumber` tinyint(4) DEFAULT NULL,
  `WeekNumber` tinyint(4) DEFAULT NULL,
  `WeekDayName` varchar(10) DEFAULT NULL,
  `MonthDay` tinyint(4) DEFAULT NULL,
  `MonthName` varchar(12) DEFAULT NULL,
  `YearDay` smallint(6) DEFAULT NULL,
  `DateDefinition` varchar(30) DEFAULT NULL,
  `WeekDay` tinyint(4) DEFAULT NULL,
  `MonthNumber` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`DateKey`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `datelookup`
--


-- --------------------------------------------------------

--
-- Table structure for table `genre`
--

CREATE TABLE IF NOT EXISTS `genre` (
  `genreId` int(11) NOT NULL AUTO_INCREMENT,
  `genreName` varchar(50) NOT NULL,
  PRIMARY KEY (`genreId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `genre`
--

INSERT INTO `genre` (`genreId`, `genreName`) VALUES
(5, 'Opera'),
(6, 'Choral'),
(7, 'Symphonic'),
(8, 'Modern');

-- --------------------------------------------------------

--
-- Table structure for table `groupmembership`
--

CREATE TABLE IF NOT EXISTS `groupmembership` (
  `userId` int(11) NOT NULL,
  `groupId` int(11) NOT NULL,
  PRIMARY KEY (`userId`,`groupId`),
  KEY `groupId` (`groupId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groupmembership`
--

INSERT INTO `groupmembership` (`userId`, `groupId`) VALUES
(1, 42);

-- --------------------------------------------------------

--
-- Table structure for table `importlog`
--

CREATE TABLE IF NOT EXISTS `importlog` (
  `logId` int(11) NOT NULL AUTO_INCREMENT,
  `logDate` datetime NOT NULL,
  `log` mediumtext,
  `inputtedIds` text,
  `importName` varchar(50) DEFAULT NULL,
  `ranBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`logId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=245 ;

--
-- Dumping data for table `importlog`
--


-- --------------------------------------------------------

--
-- Table structure for table `nationality`
--

CREATE TABLE IF NOT EXISTS `nationality` (
  `nationality` varchar(80) NOT NULL,
  PRIMARY KEY (`nationality`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nationality`
--

INSERT INTO `nationality` (`nationality`) VALUES
('Afghan'),
('Albanian'),
('Algerian'),
('American'),
('Andorran'),
('Angolan'),
('Antiguans'),
('Argentinean'),
('Armenian'),
('Australian'),
('Austrian'),
('Azerbaijani'),
('Bahamian'),
('Bahraini'),
('Bangladeshi'),
('Barbadian'),
('Barbudans'),
('Batswana'),
('Belarusian'),
('Belgian'),
('Belizean'),
('Beninese'),
('Bhutanese'),
('Bolivian'),
('Bosnian'),
('Brazilian'),
('British'),
('Bruneian'),
('Bulgarian'),
('Burkinabe'),
('Burmese'),
('Burundian'),
('Cambodian'),
('Cameroonian'),
('Canadian'),
('Cape Verdean'),
('Central African'),
('Chadian'),
('Chilean'),
('Chinese'),
('Colombian'),
('Comoran'),
('Congolese'),
('Costa Rican'),
('Croatian'),
('Cuban'),
('Cypriot'),
('Czech'),
('Danish'),
('Djibouti'),
('Dominican'),
('Dutch'),
('East Timorese'),
('Ecuadorean'),
('Egyptian'),
('Emirian'),
('Equatorial Guinean'),
('Eritrean'),
('Estonian'),
('Ethiopian'),
('Fijian'),
('Filipino'),
('Finnish'),
('French'),
('Gabonese'),
('Gambian'),
('Georgian'),
('German'),
('Ghanaian'),
('Greek'),
('Grenadian'),
('Guatemalan'),
('Guinea-Bissauan'),
('Guinean'),
('Guyanese'),
('Haitian'),
('Herzegovinian'),
('Honduran'),
('Hungarian'),
('Icelander'),
('Indian'),
('Indonesian'),
('Iranian'),
('Iraqi'),
('Irish'),
('Israeli'),
('Italian'),
('Ivorian'),
('Jamaican'),
('Japanese'),
('Jordanian'),
('Kazakhstani'),
('Kenyan'),
('Kittian and Nevisian'),
('Kuwaiti'),
('Kyrgyz'),
('Laotian'),
('Latvian'),
('Lebanese'),
('Liberian'),
('Libyan'),
('Liechtensteiner'),
('Lithuanian'),
('Luxembourger'),
('Macedonian'),
('Malagasy'),
('Malawian'),
('Malaysian'),
('Maldivan'),
('Malian'),
('Maltese'),
('Marshallese'),
('Mauritanian'),
('Mauritian'),
('Mexican'),
('Micronesian'),
('Moldovan'),
('Monacan'),
('Mongolian'),
('Moroccan'),
('Mosotho'),
('Motswana'),
('Mozambican'),
('Namibian'),
('Nauruan'),
('Nepalese'),
('Netherlander'),
('New Zealander'),
('Ni-Vanuatu'),
('Nicaraguan'),
('Nigerian'),
('Nigerien'),
('North Korean'),
('Northern Irish'),
('Norwegian'),
('Omani'),
('Pakistani'),
('Palauan'),
('Panamanian'),
('Papua New Guinean'),
('Paraguayan'),
('Peruvian'),
('Polish'),
('Portuguese'),
('Qatari'),
('Romanian'),
('Russian'),
('Rwandan'),
('Saint Lucian'),
('Salvadoran'),
('Samoan'),
('San Marinese'),
('Sao Tomean'),
('Saudi'),
('Scottish'),
('Senegalese'),
('Serbian'),
('Seychellois'),
('Sierra Leonean'),
('Singaporean'),
('Slovakian'),
('Slovenian'),
('Solomon Islander'),
('Somali'),
('South African'),
('South Korean'),
('Spanish'),
('Sri Lankan'),
('Sudanese'),
('Surinamer'),
('Swazi'),
('Swedish'),
('Swiss'),
('Syrian'),
('Taiwanese'),
('Tajik'),
('Tanzanian'),
('Thai'),
('Togolese'),
('Tongan'),
('Trinidadian or Tobagonian'),
('Tunisian'),
('Turkish'),
('Tuvaluan'),
('Ugandan'),
('Ukrainian'),
('Uruguayan'),
('Uzbekistani'),
('Venezuelan'),
('Vietnamese'),
('Welsh'),
('Yemenite'),
('Zambian'),
('Zimbabwean');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `productId` int(11) NOT NULL AUTO_INCREMENT,
  `artistId` int(11) NOT NULL,
  `genreId` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `releaseDate` date NOT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`productId`),
  KEY `genreId` (`genreId`),
  KEY `product_ibfk_1` (`artistId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=68 ;

--
-- Dumping data for table `product`
--


-- --------------------------------------------------------

--
-- Table structure for table `salesdata`
--

CREATE TABLE IF NOT EXISTS `salesdata` (
  `saleId` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `storeId` smallint(6) NOT NULL,
  `cashierName` varchar(20) NOT NULL,
  `itemId` int(11) NOT NULL,
  `itemDiscount` int(11) NOT NULL,
  `customerEmail` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`saleId`),
  KEY `storeId` (`storeId`),
  KEY `itemId` (`itemId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=378 ;

--
-- Dumping data for table `salesdata`
--


-- --------------------------------------------------------

--
-- Table structure for table `salesview`
--

CREATE TABLE IF NOT EXISTS `salesview` (
  `saleId` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `storeId` smallint(6) DEFAULT NULL,
  `cashierName` varchar(20) DEFAULT NULL,
  `itemId` int(11) DEFAULT NULL,
  `itemDiscount` int(11) DEFAULT NULL,
  `customerEmail` varchar(100) DEFAULT NULL,
  `storeName` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `salesview`
--


-- --------------------------------------------------------

--
-- Stand-in structure for view `sales_view_v2`
--
CREATE TABLE IF NOT EXISTS `sales_view_v2` (
`SALE_DATE` date
,`SALE_MONTHYEAR` varchar(7)
,`SALE_YEAR` int(4)
,`CASHIER_NAME` varchar(20)
,`STORE_ID` smallint(6)
,`ITEM_DISCOUNT` int(11)
,`CUSTOMER_EMAIL` varchar(100)
,`ARTIST_NAME` varchar(101)
,`BAND_NAME` varchar(100)
,`GENRE` varchar(50)
,`PRODUCT_NAME` varchar(100)
,`PRODUCT_RELEASE_DATE` date
,`PRODUCT_PRICE` double
,`STORE_NAME` varchar(50)
,`STORE_CITY` varchar(50)
);
-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE IF NOT EXISTS `store` (
  `storeId` smallint(6) NOT NULL AUTO_INCREMENT,
  `storeName` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  PRIMARY KEY (`storeId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `store`
--


-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `forename` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `active` bit(1) NOT NULL,
  `username` varchar(50) NOT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userId`, `forename`, `surname`, `password`, `active`, `username`) VALUES
(1, 'Admin', 'User', '098f6bcd4621d373cade4e832627b4f6', '1', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `usergroup`
--

CREATE TABLE IF NOT EXISTS `usergroup` (
  `groupId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `storeId` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`groupId`),
  KEY `storeId` (`storeId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

--
-- Dumping data for table `usergroup`
--

INSERT INTO `usergroup` (`groupId`, `name`, `description`, `storeId`) VALUES
(42, 'Administrators', 'A group for all administrators', NULL);

-- --------------------------------------------------------

--
-- Structure for view `sales_view_v2`
--
DROP TABLE IF EXISTS `sales_view_v2`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `sales_view_v2` AS select `sd`.`date` AS `SALE_DATE`,concat(month(`sd`.`date`),'-',year(`sd`.`date`)) AS `SALE_MONTHYEAR`,year(`sd`.`date`) AS `SALE_YEAR`,`sd`.`cashierName` AS `CASHIER_NAME`,`sd`.`storeId` AS `STORE_ID`,`sd`.`itemDiscount` AS `ITEM_DISCOUNT`,`sd`.`customerEmail` AS `CUSTOMER_EMAIL`,concat(`ar`.`forename`,' ',`ar`.`surname`) AS `ARTIST_NAME`,`ar`.`bandName` AS `BAND_NAME`,`ge`.`genreName` AS `GENRE`,`pr`.`name` AS `PRODUCT_NAME`,`pr`.`releaseDate` AS `PRODUCT_RELEASE_DATE`,`pr`.`price` AS `PRODUCT_PRICE`,`st`.`storeName` AS `STORE_NAME`,`st`.`city` AS `STORE_CITY` from ((((`salesdata` `sd` left join `store` `st` on((`sd`.`storeId` = `st`.`storeId`))) left join `product` `pr` on((`sd`.`itemId` = `pr`.`productId`))) left join `artist` `ar` on((`ar`.`artistId` = `pr`.`artistId`))) left join `genre` `ge` on((`ge`.`genreId` = `pr`.`genreId`))) order by `sd`.`date`;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chartpermission`
--
ALTER TABLE `chartpermission`
  ADD CONSTRAINT `fkChartId` FOREIGN KEY (`chartId`) REFERENCES `chart` (`chartId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkGroupId` FOREIGN KEY (`userGroupId`) REFERENCES `usergroup` (`groupId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkUserId` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dashboardlayout`
--
ALTER TABLE `dashboardlayout`
  ADD CONSTRAINT `dashboardlayout_ibfk_1` FOREIGN KEY (`tabId`) REFERENCES `dashboardtab` (`tabId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dashboardlayout_ibfk_2` FOREIGN KEY (`chartId`) REFERENCES `chart` (`chartId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `groupmembership`
--
ALTER TABLE `groupmembership`
  ADD CONSTRAINT `groupmembership_ibfk_4` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `groupmembership_ibfk_5` FOREIGN KEY (`groupId`) REFERENCES `usergroup` (`groupId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`artistId`) REFERENCES `artist` (`artistId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`genreId`) REFERENCES `genre` (`genreId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `salesdata`
--
ALTER TABLE `salesdata`
  ADD CONSTRAINT `salesdata_ibfk_1` FOREIGN KEY (`itemId`) REFERENCES `product` (`productId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `salesdata_ibfk_2` FOREIGN KEY (`storeId`) REFERENCES `store` (`storeId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `usergroup`
--
ALTER TABLE `usergroup`
  ADD CONSTRAINT `usergroup_ibfk_1` FOREIGN KEY (`storeId`) REFERENCES `store` (`storeId`) ON DELETE SET NULL ON UPDATE NO ACTION;
