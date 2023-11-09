-- MySQL dump 10.13  Distrib 5.5.62, for Win64 (AMD64)
--
-- Host: localhost    Database: mis
-- ------------------------------------------------------
-- Server version	5.7.24

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
-- Table structure for table `areas`
--

DROP TABLE IF EXISTS `areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `areas` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `area` varchar(255) NOT NULL,
  `status` enum('PUBLISH','DRAFT','UNPUBLISH','DELETED') DEFAULT 'PUBLISH',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(10) NOT NULL DEFAULT '0',
  `user_update` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `areas`
--

LOCK TABLES `areas` WRITE;
/*!40000 ALTER TABLE `areas` DISABLE KEYS */;
/*!40000 ALTER TABLE `areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL,
  `status` enum('PUBLISH','DRAFT','UNPUBLISH','DELETED') DEFAULT 'PUBLISH',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(10) NOT NULL DEFAULT '0',
  `user_update` int(10) NOT NULL DEFAULT '0',
  `category` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `no_cif` varchar(255) NOT NULL,
  `nik` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `birth_place` varchar(255) DEFAULT NULL,
  `gender` enum('MALE','FEMALE','UNDEFINED') NOT NULL DEFAULT 'UNDEFINED',
  `marital` enum('SINGLE','MARRIED','DIVORCED','UNDEFINED') NOT NULL DEFAULT 'UNDEFINED',
  `address` text,
  `city` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `job` varchar(255) DEFAULT NULL,
  `citizenship` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `sibling_name` varchar(255) DEFAULT NULL,
  `sibling_birth_date` date DEFAULT NULL,
  `sibling_birth_place` varchar(255) DEFAULT NULL,
  `sibling_address_1` text,
  `sibling_job` varchar(255) DEFAULT NULL,
  `sibling_relation` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `sibling_address_2` varchar(100) DEFAULT NULL,
  `address_1` text,
  `address_2` text,
  `status` enum('PUBLISH','DRAFT','UNPUBLISH','DELETED') DEFAULT 'PUBLISH',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(10) NOT NULL DEFAULT '0',
  `user_update` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employees` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_unit` int(10) unsigned NOT NULL DEFAULT '0',
  `nik` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `birth_date` date NOT NULL,
  `birth_place` varchar(255) NOT NULL,
  `gender` enum('MALE','FEMALE','UNDEFINED') NOT NULL DEFAULT 'UNDEFINED',
  `mobile` varchar(255) NOT NULL,
  `marital` enum('SINGLE','MARRIED','DIVORCED','UNDEFINED') NOT NULL DEFAULT 'UNDEFINED',
  `blood_group` enum('+A','+B','+AB','+0','-A','-B','-AB','-O') NOT NULL DEFAULT '+A',
  `address` text,
  `position` varchar(255) DEFAULT NULL,
  `status` enum('PUBLISH','DRAFT','UNPUBLISH','DELETED') DEFAULT 'PUBLISH',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(10) NOT NULL DEFAULT '0',
  `user_update` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fraction_of_money`
--

DROP TABLE IF EXISTS `fraction_of_money`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fraction_of_money` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `currency` varchar(255) NOT NULL,
  `read` text NOT NULL,
  `amount` float NOT NULL DEFAULT '0',
  `status` enum('PUBLISH','DRAFT','UNPUBLISH','DELETED') DEFAULT 'PUBLISH',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(10) NOT NULL DEFAULT '0',
  `user_update` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fraction_of_money`
--

LOCK TABLES `fraction_of_money` WRITE;
/*!40000 ALTER TABLE `fraction_of_money` DISABLE KEYS */;
/*!40000 ALTER TABLE `fraction_of_money` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `levels`
--

DROP TABLE IF EXISTS `levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `levels` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `level` varchar(255) NOT NULL,
  `status` enum('PUBLISH','DRAFT','UNPUBLISH','DELETED') DEFAULT 'PUBLISH',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(10) NOT NULL DEFAULT '0',
  `user_update` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `levels`
--

LOCK TABLES `levels` WRITE;
/*!40000 ALTER TABLE `levels` DISABLE KEYS */;
INSERT INTO `levels` VALUES (1,'administrator','PUBLISH','2020-07-01 04:55:26','2020-07-01 04:55:26',1,1),(2,'pusat','PUBLISH','2020-07-01 04:55:32','2020-07-01 04:55:32',1,1),(3,'unit','PUBLISH','2020-07-01 04:55:40','2020-07-01 04:55:40',1,1);
/*!40000 ALTER TABLE `levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `levels_privileges`
--

DROP TABLE IF EXISTS `levels_privileges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `levels_privileges` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_level` int(10) unsigned NOT NULL DEFAULT '0',
  `id_menu` int(10) unsigned NOT NULL DEFAULT '0',
  `can_access` enum('WRITE','READ','DENIED') NOT NULL DEFAULT 'DENIED',
  `status` enum('PUBLISH','DRAFT','UNPUBLISH','DELETED') DEFAULT 'PUBLISH',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(10) NOT NULL DEFAULT '0',
  `user_update` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `levels_privileges`
--

LOCK TABLES `levels_privileges` WRITE;
/*!40000 ALTER TABLE `levels_privileges` DISABLE KEYS */;
INSERT INTO `levels_privileges` VALUES (1,1,1,'WRITE','PUBLISH','2020-07-01 05:01:11','2020-07-01 05:01:11',1,1),(2,1,2,'WRITE','PUBLISH','2020-07-01 05:01:12','2020-07-01 05:01:12',1,1),(3,1,3,'WRITE','PUBLISH','2020-07-01 05:01:14','2020-07-01 05:01:14',1,1),(4,1,4,'WRITE','PUBLISH','2020-07-01 05:01:15','2020-07-01 05:01:15',1,1),(5,1,5,'WRITE','PUBLISH','2020-07-01 05:01:17','2020-07-01 05:01:17',1,1),(6,1,6,'WRITE','PUBLISH','2020-07-01 05:01:18','2020-07-01 05:01:18',1,1),(7,1,7,'WRITE','PUBLISH','2020-07-01 05:01:19','2020-07-01 05:01:19',1,1),(8,1,8,'WRITE','PUBLISH','2020-07-01 05:01:21','2020-07-01 05:01:21',1,1),(9,1,9,'WRITE','PUBLISH','2020-07-01 05:01:22','2020-07-01 05:01:22',1,1),(10,1,10,'WRITE','PUBLISH','2020-07-01 05:01:25','2020-07-01 05:01:25',1,1),(11,1,11,'WRITE','PUBLISH','2020-07-01 05:01:26','2020-07-01 05:01:26',1,1),(12,1,12,'WRITE','PUBLISH','2020-07-01 05:01:27','2020-07-01 05:01:27',1,1),(13,1,13,'WRITE','PUBLISH','2020-07-01 05:01:29','2020-07-01 05:01:29',1,1),(14,1,14,'WRITE','PUBLISH','2020-07-01 05:01:31','2020-07-01 05:01:31',1,1),(15,1,15,'WRITE','PUBLISH','2020-07-01 05:01:32','2020-07-01 05:01:32',1,1),(16,1,16,'WRITE','PUBLISH','2020-07-01 05:01:34','2020-07-01 05:01:34',1,1),(17,1,17,'WRITE','PUBLISH','2020-07-01 05:01:37','2020-07-01 05:01:37',1,1),(18,1,18,'WRITE','PUBLISH','2020-07-01 05:01:39','2020-07-01 05:01:39',1,1),(19,1,19,'WRITE','PUBLISH','2020-07-01 05:01:42','2020-07-01 05:01:42',1,1),(20,1,20,'WRITE','PUBLISH','2020-07-01 05:01:42','2020-07-01 05:01:42',1,1),(21,1,21,'WRITE','PUBLISH','2020-07-01 05:01:44','2020-07-01 05:01:44',1,1),(22,1,22,'WRITE','PUBLISH','2020-07-01 05:01:45','2020-07-01 05:01:45',1,1),(23,1,23,'WRITE','PUBLISH','2020-07-01 05:01:46','2020-07-01 05:01:46',1,1),(24,1,24,'WRITE','PUBLISH','2020-07-01 05:01:48','2020-07-01 05:01:48',1,1),(25,1,25,'WRITE','PUBLISH','2020-07-01 05:01:51','2020-07-01 05:01:51',1,1),(26,1,26,'WRITE','PUBLISH','2020-07-01 05:01:55','2020-07-01 05:01:55',1,1),(27,1,27,'WRITE','PUBLISH','2020-07-01 05:01:56','2020-07-01 05:01:56',1,1),(28,1,28,'WRITE','PUBLISH','2020-07-01 05:01:58','2020-07-01 05:01:58',1,1),(29,1,29,'WRITE','PUBLISH','2020-07-01 05:01:59','2020-07-01 05:01:59',1,1),(30,1,30,'WRITE','PUBLISH','2020-07-01 05:02:00','2020-07-01 05:02:00',1,1),(31,1,31,'WRITE','PUBLISH','2020-07-01 05:02:01','2020-07-01 05:02:01',1,1),(32,1,32,'WRITE','PUBLISH','2020-07-01 05:02:03','2020-07-01 05:02:03',1,1);
/*!40000 ALTER TABLE `levels_privileges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master`
--

DROP TABLE IF EXISTS `master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `master` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` enum('PUBLISH','DRAFT','UNPUBLISH','DELETED') DEFAULT 'PUBLISH',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(10) NOT NULL DEFAULT '0',
  `user_update` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master`
--

LOCK TABLES `master` WRITE;
/*!40000 ALTER TABLE `master` DISABLE KEYS */;
/*!40000 ALTER TABLE `master` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menus` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `dept` int(10) NOT NULL DEFAULT '0',
  `order` int(10) NOT NULL DEFAULT '0',
  `status` enum('PUBLISH','DRAFT','UNPUBLISH','DELETED') DEFAULT 'PUBLISH',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(10) NOT NULL DEFAULT '0',
  `user_update` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,0,'Dashboard',0,1,'PUBLISH','2020-07-01 04:44:15','2020-07-01 04:44:15',1,1),(2,1,'pusat',1,2,'PUBLISH','2020-07-01 04:44:37','2020-07-01 04:44:37',1,1),(3,1,'performaunit',1,4,'PUBLISH','2020-07-01 04:45:25','2020-07-01 04:45:25',1,1),(4,1,'disburse',1,6,'PUBLISH','2020-07-01 04:45:35','2020-07-01 04:45:35',1,1),(5,1,'targetbooking',1,8,'PUBLISH','2020-07-01 04:45:44','2020-07-01 04:45:44',1,1),(6,1,'targetoutstanding',1,10,'PUBLISH','2020-07-01 04:45:56','2020-07-01 04:45:56',1,1),(7,1,'pencairan',1,12,'PUBLISH','2020-07-01 04:46:07','2020-07-01 04:46:07',1,1),(8,1,'pelunasan',1,14,'PUBLISH','2020-07-01 04:46:29','2020-07-01 04:46:29',1,1),(9,1,'saldokas',1,16,'PUBLISH','2020-07-01 04:46:38','2020-07-01 04:46:38',1,1),(10,1,'saldobank',1,18,'PUBLISH','2020-07-01 04:46:46','2020-07-01 04:46:46',1,1),(11,1,'area',1,20,'PUBLISH','2020-07-01 04:46:53','2020-07-01 04:46:53',1,1),(12,1,'units',1,22,'PUBLISH','2020-07-01 04:47:01','2020-07-01 04:47:01',1,1),(13,0,'site-settings',0,24,'PUBLISH','2020-07-01 04:47:27','2020-07-01 04:47:27',1,1),(14,13,'levels',1,25,'PUBLISH','2020-07-01 04:47:45','2020-07-01 04:47:45',1,1),(15,13,'menu',1,27,'PUBLISH','2020-07-01 04:47:55','2020-07-01 04:47:55',1,1),(16,13,'Privileges',1,29,'PUBLISH','2020-07-01 04:48:09','2020-07-01 04:48:09',1,1),(17,0,'transactions',0,31,'PUBLISH','2020-07-01 04:48:34','2020-07-01 04:48:34',1,1),(18,17,'LoanInstallments',1,32,'PUBLISH','2020-07-01 04:48:50','2020-07-01 04:48:50',1,1),(19,17,'Mortages',1,34,'PUBLISH','2020-07-01 04:49:04','2020-07-01 04:49:04',1,1),(20,17,'RegularPawns',1,36,'PUBLISH','2020-07-01 04:49:23','2020-07-01 04:49:23',1,1),(21,17,'RegularPawns',1,38,'PUBLISH','2020-07-01 04:49:42','2020-07-01 04:49:42',1,1),(22,17,'Repayment',1,40,'PUBLISH','2020-07-01 04:49:52','2020-07-01 04:49:52',1,1),(23,17,'UnitsDailyCash',1,42,'PUBLISH','2020-07-01 04:50:04','2020-07-01 04:50:04',1,1),(24,0,'datamaster',0,44,'PUBLISH','2020-07-01 04:50:25','2020-07-01 04:50:25',1,1),(25,24,'Areas',1,45,'PUBLISH','2020-07-01 04:50:36','2020-07-01 04:50:36',1,1),(26,24,'bookcash',1,47,'PUBLISH','2020-07-01 04:51:28','2020-07-01 04:51:28',1,1),(27,24,'Customers',1,49,'PUBLISH','2020-07-01 04:51:41','2020-07-01 04:51:41',1,1),(28,24,'employees',1,51,'PUBLISH','2020-07-01 04:52:01','2020-07-01 04:52:01',1,1),(29,24,'FractionOfMoney',1,53,'PUBLISH','2020-07-01 04:52:16','2020-07-01 04:52:16',1,1),(30,24,'Mapingcategory',1,55,'PUBLISH','2020-07-01 04:52:36','2020-07-01 04:52:36',1,1),(31,24,'Units',1,57,'PUBLISH','2020-07-01 04:52:50','2020-07-01 04:52:50',1,1),(32,24,'UnitsTarget',1,59,'PUBLISH','2020-07-01 04:53:06','2020-07-01 04:53:06',1,1);
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `units` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_area` int(10) unsigned NOT NULL DEFAULT '0',
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('PUBLISH','DRAFT','UNPUBLISH','DELETED') DEFAULT 'PUBLISH',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(10) NOT NULL DEFAULT '0',
  `user_update` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units`
--

LOCK TABLES `units` WRITE;
/*!40000 ALTER TABLE `units` DISABLE KEYS */;
/*!40000 ALTER TABLE `units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units_cash_book`
--

DROP TABLE IF EXISTS `units_cash_book`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `units_cash_book` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_unit` int(10) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('PUBLISH','DRAFT','UNPUBLISH','DELETED') DEFAULT 'PUBLISH',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(10) NOT NULL DEFAULT '0',
  `user_update` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units_cash_book`
--

LOCK TABLES `units_cash_book` WRITE;
/*!40000 ALTER TABLE `units_cash_book` DISABLE KEYS */;
/*!40000 ALTER TABLE `units_cash_book` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units_cash_book_money`
--

DROP TABLE IF EXISTS `units_cash_book_money`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `units_cash_book_money` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_unit_cash_book` int(10) unsigned NOT NULL DEFAULT '0',
  `id_fraction_of_money` int(10) unsigned NOT NULL DEFAULT '0',
  `summary` float NOT NULL DEFAULT '0',
  `amount` float NOT NULL DEFAULT '0',
  `status` enum('PUBLISH','DRAFT','UNPUBLISH','DELETED') DEFAULT 'PUBLISH',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(10) NOT NULL DEFAULT '0',
  `user_update` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units_cash_book_money`
--

LOCK TABLES `units_cash_book_money` WRITE;
/*!40000 ALTER TABLE `units_cash_book_money` DISABLE KEYS */;
/*!40000 ALTER TABLE `units_cash_book_money` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units_dailycashs`
--

DROP TABLE IF EXISTS `units_dailycashs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `units_dailycashs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_unit` int(10) unsigned NOT NULL,
  `date` date DEFAULT NULL,
  `description` text,
  `cash_code` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `amount` float NOT NULL DEFAULT '0',
  `status` enum('PUBLISH','DRAFT','UNPUBLISH','DELETED') DEFAULT 'PUBLISH',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(10) NOT NULL DEFAULT '0',
  `user_update` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units_dailycashs`
--

LOCK TABLES `units_dailycashs` WRITE;
/*!40000 ALTER TABLE `units_dailycashs` DISABLE KEYS */;
/*!40000 ALTER TABLE `units_dailycashs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units_loaninstallments`
--

DROP TABLE IF EXISTS `units_loaninstallments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `units_loaninstallments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `no_sbk` varchar(255) NOT NULL,
  `id_customer` int(10) unsigned NOT NULL DEFAULT '0',
  `date_sbk` date DEFAULT NULL,
  `date_repayment` date DEFAULT NULL,
  `amount` float NOT NULL DEFAULT '0',
  `periode` int(11) NOT NULL DEFAULT '0',
  `capital_lease` float DEFAULT '0',
  `id_unit` int(10) unsigned NOT NULL,
  `nic` varchar(255) DEFAULT NULL,
  `detail` text,
  `description_1` text,
  `description_2` text,
  `description_3` text,
  `description_4` text,
  `status` enum('PUBLISH','DRAFT','UNPUBLISH','DELETED') DEFAULT 'PUBLISH',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(10) NOT NULL DEFAULT '0',
  `user_update` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units_loaninstallments`
--

LOCK TABLES `units_loaninstallments` WRITE;
/*!40000 ALTER TABLE `units_loaninstallments` DISABLE KEYS */;
/*!40000 ALTER TABLE `units_loaninstallments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units_mortages`
--

DROP TABLE IF EXISTS `units_mortages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `units_mortages` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_unit` int(10) unsigned NOT NULL,
  `id_customer` int(10) unsigned NOT NULL DEFAULT '0',
  `no_sbk` varchar(255) NOT NULL,
  `nic` varchar(255) DEFAULT NULL,
  `date_sbk` date DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `date_auction` date DEFAULT NULL,
  `amount_admin` float NOT NULL DEFAULT '0',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(10) NOT NULL DEFAULT '0',
  `user_update` int(10) NOT NULL DEFAULT '0',
  `estimation` float NOT NULL DEFAULT '0',
  `amount_loan` float NOT NULL DEFAULT '0',
  `capital_lease` float NOT NULL DEFAULT '0',
  `periode` int(10) DEFAULT '0',
  `installment` int(10) DEFAULT '0',
  `interest` float NOT NULL DEFAULT '0',
  `status` enum('PUBLISH','DRAFT','UNPUBLISH','DELETED') DEFAULT 'PUBLISH',
  `status_transaction` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units_mortages`
--

LOCK TABLES `units_mortages` WRITE;
/*!40000 ALTER TABLE `units_mortages` DISABLE KEYS */;
/*!40000 ALTER TABLE `units_mortages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units_regularpawns`
--

DROP TABLE IF EXISTS `units_regularpawns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `units_regularpawns` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `no_sbk` varchar(255) NOT NULL,
  `nic` varchar(255) DEFAULT NULL,
  `id_customer` int(10) unsigned NOT NULL DEFAULT '0',
  `date_sbk` date DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `amount` float NOT NULL DEFAULT '0',
  `date_auction` date DEFAULT NULL,
  `estimation` float NOT NULL DEFAULT '0',
  `admin` float NOT NULL DEFAULT '0',
  `capital_lease` float NOT NULL DEFAULT '0',
  `periode` int(11) NOT NULL DEFAULT '0',
  `installment` float NOT NULL DEFAULT '0',
  `status_transaction` varchar(255) DEFAULT NULL,
  `id_unit` int(10) unsigned NOT NULL,
  `type_item` varchar(255) DEFAULT NULL,
  `type_bmh` varchar(255) DEFAULT NULL,
  `description_1` text,
  `description_2` text,
  `description_3` text,
  `description_4` text,
  `status` enum('PUBLISH','DRAFT','UNPUBLISH','DELETED') DEFAULT 'PUBLISH',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(10) NOT NULL DEFAULT '0',
  `user_update` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units_regularpawns`
--

LOCK TABLES `units_regularpawns` WRITE;
/*!40000 ALTER TABLE `units_regularpawns` DISABLE KEYS */;
/*!40000 ALTER TABLE `units_regularpawns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units_repayments`
--

DROP TABLE IF EXISTS `units_repayments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `units_repayments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_unit` int(10) unsigned NOT NULL DEFAULT '0',
  `id_customer` int(10) unsigned NOT NULL DEFAULT '0',
  `no_sbk` varchar(255) NOT NULL,
  `nic` varchar(255) DEFAULT NULL,
  `date_sbk` date DEFAULT NULL,
  `date_repayment` date DEFAULT NULL,
  `money_loan` float NOT NULL DEFAULT '0',
  `periode` int(11) NOT NULL DEFAULT '0',
  `capital_lease` float NOT NULL DEFAULT '0',
  `status_transaction` varchar(255) DEFAULT NULL,
  `description_1` text,
  `description_2` text,
  `description_3` text,
  `description_4` text,
  `status` enum('PUBLISH','DRAFT','UNPUBLISH','DELETED') DEFAULT 'PUBLISH',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(10) NOT NULL DEFAULT '0',
  `user_update` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units_repayments`
--

LOCK TABLES `units_repayments` WRITE;
/*!40000 ALTER TABLE `units_repayments` DISABLE KEYS */;
/*!40000 ALTER TABLE `units_repayments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units_targets`
--

DROP TABLE IF EXISTS `units_targets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `units_targets` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_unit` int(10) unsigned NOT NULL,
  `month` int(10) NOT NULL DEFAULT '0',
  `year` int(10) NOT NULL DEFAULT '0',
  `amount` float NOT NULL DEFAULT '0',
  `status` enum('PUBLISH','DRAFT','UNPUBLISH','DELETED') DEFAULT 'PUBLISH',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(10) NOT NULL DEFAULT '0',
  `user_update` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units_targets`
--

LOCK TABLES `units_targets` WRITE;
/*!40000 ALTER TABLE `units_targets` DISABLE KEYS */;
/*!40000 ALTER TABLE `units_targets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_employee` int(10) unsigned NOT NULL,
  `id_level` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('PUBLISH','DRAFT','UNPUBLISH','DELETED') DEFAULT 'PUBLISH',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(10) NOT NULL DEFAULT '0',
  `user_update` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,1,'administrator','$2y$10$yvwK7wUI/NXqakhzQmtLj.PsR8FhRJ29f7B8V039m0GDLoHX02f1.','PUBLISH','2020-07-01 04:42:51','2020-07-01 04:42:51',0,0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'mis'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-07-01 12:50:52
