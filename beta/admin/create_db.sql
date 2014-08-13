-- Based on https://github.com/anochens/melodiesforus/blob/master/create_db.sql
-- This file recreates the database for a new server
--
-- MySQL dump 10.13  Distrib 5.5.34, for Linux (i686)
--
-- Host: localhost    Database: negative_options
-- ------------------------------------------------------
-- Server version	5.5.34

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
-- Table structure for table `page_event`
--

DROP TABLE IF EXISTS `page_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_event` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `session_id` int(20) NOT NULL,
  `page_name` varchar(25) COLLATE utf8_bin NOT NULL,
  `subject_name` varchar(50) COLLATE utf8_bin NOT NULL,
  `event_name` varchar(20) COLLATE utf8_bin NOT NULL,
  `ts` varchar(30) COLLATE utf8_bin NOT NULL,
  `ts_ms` varchar(15) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `session_id` (`session_id`),
  CONSTRAINT `page_event_ibfk_3` FOREIGN KEY (`session_id`) REFERENCES `session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20964 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `session` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `ip` varchar(20) COLLATE utf8_bin NOT NULL,
  `param_hitId` varchar(35) COLLATE utf8_bin NOT NULL,
  `param_workerId` varchar(20) COLLATE utf8_bin NOT NULL,
  `treatment_id` int(20) NOT NULL,
  `pre_info` longtext COLLATE utf8_bin NOT NULL,
  `email_sent` varchar(15) COLLATE utf8_bin NOT NULL DEFAULT 'undef',
  `consent` varchar(5) COLLATE utf8_bin NOT NULL DEFAULT 'no',
  `pre_mturk_id` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT 'undef',
  `pre_email` varchar(300) COLLATE utf8_bin NOT NULL DEFAULT 'undef',
  `post_info` longtext COLLATE utf8_bin NOT NULL,
  `post_email` varchar(300) COLLATE utf8_bin NOT NULL DEFAULT 'undef',
  PRIMARY KEY (`id`),
  KEY `treatment_id` (`treatment_id`),
  CONSTRAINT `session_ibfk_1` FOREIGN KEY (`treatment_id`) REFERENCES `treatment` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=905 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-01-27 19:16:02
-- MySQL dump 10.13  Distrib 5.5.34, for Linux (i686)
--
-- Host: localhost    Database: negative_options
-- ------------------------------------------------------
-- Server version	5.5.34

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

-- Dump completed on 2014-01-27 19:16:05
