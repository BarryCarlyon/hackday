-- MySQL dump 10.13  Distrib 5.1.42, for apple-darwin10.2.0 (i386)
--
-- Host: localhost    Database: cf_wp_spotify
-- ------------------------------------------------------
-- Server version	5.1.42

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
-- Table structure for table `artist_ignore`
--

DROP TABLE IF EXISTS `artist_ignore`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `artist_ignore` (
  `ref_id` int(11) NOT NULL AUTO_INCREMENT,
  `screen_name` varchar(255) NOT NULL,
  `artist` varchar(255) NOT NULL,
  PRIMARY KEY (`ref_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `artist_ignore`
--

LOCK TABLES `artist_ignore` WRITE;
/*!40000 ALTER TABLE `artist_ignore` DISABLE KEYS */;
/*!40000 ALTER TABLE `artist_ignore` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `twitter_recent`
--

DROP TABLE IF EXISTS `twitter_recent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `twitter_recent` (
  `ref_id` int(11) NOT NULL AUTO_INCREMENT,
  `screen_name` varchar(255) NOT NULL,
  `profile_image` varchar(255) NOT NULL,
  `tos` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `played` varchar(255) NOT NULL,
  `refer` varchar(255) NOT NULL,
  PRIMARY KEY (`ref_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `twitter_recent`
--

LOCK TABLES `twitter_recent` WRITE;
/*!40000 ALTER TABLE `twitter_recent` DISABLE KEYS */;
INSERT INTO `twitter_recent` VALUES (1,'BarryCarlyon','https://si0.twimg.com/profile_images/1239169092/BarryCarlyon_normal.jpg','2011-08-07 08:34:08','Rickrolled','Rick Astley'),(2,'tnash','https://si0.twimg.com/profile_images/1238520361/tim_normal.jpg','2011-08-07 06:46:00','Spice Girls.','_elj'),(3,'jacksonj04','https://si0.twimg.com/profile_images/1426123764/photo_normal.jpeg','2011-08-07 07:42:53','Queen','BarryCarlyon'),(4,'toddish','https://si0.twimg.com/profile_images/229379607/3193_81943586007_565471007_2234616_4385364_n_normal.jpg','2011-08-07 07:56:51','Tiesto','BarryCarlyon');
/*!40000 ALTER TABLE `twitter_recent` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-08-07  9:54:01
