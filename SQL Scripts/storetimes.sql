
-- MySQL dump 10.13  Distrib 5.1.34, for apple-darwin9.5.0 (i386)
--
-- Host: 127.0.0.1    Database: sales
-- ------------------------------------------------------
-- Server version	5.5.9

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `storetimes`
--

DROP TABLE IF EXISTS `storetimes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `storetimes` (
  `storeId` int(11) NOT NULL,
  `mon` varchar(20) NOT NULL,
  `tue` varchar(20) NOT NULL,
  `wed` varchar(20) NOT NULL,
  `thu` varchar(20) NOT NULL,
  `fri` varchar(20) NOT NULL,
  `sat` varchar(20) NOT NULL,
  `sun` varchar(20) NOT NULL,
  `pk` int(3) NOT NULL,
  PRIMARY KEY (`pk`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `storetimes`
--

LOCK TABLES `storetimes` WRITE;
/*!40000 ALTER TABLE `storetimes` DISABLE KEYS */;
INSERT INTO `storetimes` VALUES (1,'08:00 - 17:00','08:00 - 17:00','08:00 - 17:00','08:00 - 19:00','08:00 - 17:00','09:00 - 18:00','10:00 - 14:00',1),(2,'08:00 - 17:00','08:00 - 17:00','08:00 - 17:00','08:00 - 19:00','08:00 - 17:00','09:00 - 18:00','10:00 - 14:00',2),(3,'08:00 - 17:00','08:00 - 17:00','08:00 - 17:00','08:00 - 19:00','08:00 - 17:00','09:00 - 18:00','10:00 - 14:00',3),(4,'08:00 - 17:00','08:00 - 17:00','08:00 - 17:00','08:00 - 19:00','08:00 - 17:00','09:00 - 18:00','10:00 - 14:00',4);
/*!40000 ALTER TABLE `storetimes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-03-29 10:57:42
