-- MySQL dump 10.13  Distrib 5.1.41, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: 2boxdb
-- ------------------------------------------------------
-- Server version	5.1.41-3ubuntu12.7

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
-- Table structure for table `autoreply`
--

DROP TABLE IF EXISTS `autoreply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `autoreply` (
  `2boxid` mediumint(9) DEFAULT NULL,
  `autoreplyid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `autoreplytext` varchar(140) DEFAULT NULL,
  `count` mediumint(9) DEFAULT NULL,
  PRIMARY KEY (`autoreplyid`),
  KEY `2boxid` (`2boxid`),
  KEY `filterid` (`autoreplyid`)
) ENGINE=MyISAM AUTO_INCREMENT=98 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `filters`
--

DROP TABLE IF EXISTS `filters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filters` (
  `2boxid` mediumint(9) DEFAULT NULL,
  `filterid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filtername` varchar(256) DEFAULT NULL,
  `filter` varchar(140) DEFAULT NULL,
  `boxto` varchar(140) DEFAULT NULL,
  `action` varchar(256) DEFAULT NULL,
  `notifyemail` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`filterid`),
  KEY `2boxid` (`2boxid`),
  KEY `filterid` (`filterid`)
) ENGINE=MyISAM AUTO_INCREMENT=80 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hashtags`
--

DROP TABLE IF EXISTS `hashtags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hashtags` (
  `2boxid` mediumint(9) DEFAULT NULL,
  `hashtagid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hashtagname` varchar(1000) DEFAULT NULL,
  `boxto` varchar(140) DEFAULT NULL,
  `action` varchar(256) DEFAULT NULL,
  `lasthashtagid` bigint(200) DEFAULT NULL,
  `autoreplyid` int(10) DEFAULT '0',
  PRIMARY KEY (`hashtagid`),
  KEY `2boxid` (`2boxid`),
  KEY `filterid` (`hashtagid`)
) ENGINE=MyISAM AUTO_INCREMENT=79 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `msgmentions`
--

DROP TABLE IF EXISTS `msgmentions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `msgmentions` (
  `2boxid` mediumint(9) DEFAULT NULL,
  `id_str` bigint(200) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `screen_name` varchar(20) DEFAULT NULL,
  `text` varchar(140) DEFAULT NULL,
  `box` varchar(140) DEFAULT NULL,
  `2boxmsgid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`2boxmsgid`),
  KEY `2boxid` (`2boxid`),
  KEY `2boxmsgid` (`2boxmsgid`)
) ENGINE=MyISAM AUTO_INCREMENT=495963 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `twitterprofiles`
--

DROP TABLE IF EXISTS `twitterprofiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `twitterprofiles` (
  `2boxid` mediumint(9) DEFAULT NULL,
  `tid` bigint(200) NOT NULL DEFAULT '0',
  `image_url` text,
  `location` text,
  `created_at` datetime DEFAULT NULL,
  `lang` varchar(12) DEFAULT NULL,
  `followers` bigint(200) DEFAULT NULL,
  `description` text,
  `geo_enabled` tinyint(4) DEFAULT NULL,
  `protected` tinyint(4) DEFAULT NULL,
  `verified` tinyint(4) DEFAULT NULL,
  `tags` text,
  `screen_name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`tid`),
  KEY `2boxid` (`2boxid`)
) ENGINE=MyISAM AUTO_INCREMENT=80 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `2boxid` mediumint(9) NOT NULL AUTO_INCREMENT,
  `twitterid` bigint(200) DEFAULT NULL,
  `screenname` varchar(20) DEFAULT NULL,
  `startmsgid` bigint(200) DEFAULT NULL,
  `token` varchar(200) DEFAULT NULL,
  `tokensecret` varchar(200) DEFAULT NULL,
  `boxes` text,
  `email` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`2boxid`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-08-08 12:09:09
