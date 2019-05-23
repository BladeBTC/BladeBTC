-- MySQL dump 10.17  Distrib 10.3.15-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: telegram_bot
-- ------------------------------------------------------
-- Server version	10.3.15-MariaDB-1:10.3.15+maria~bionic-log


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


--
-- Create Database
--
CREATE DATABASE telegram_bot;


--
-- Create Database
--
USE telegram_bot;


--
-- Table structure for table `bot_setting`
--

DROP TABLE IF EXISTS `bot_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bot_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` varchar(200) DEFAULT NULL,
  `app_name` varchar(100) DEFAULT NULL,
  `support_chat_id` varchar(100) DEFAULT NULL,
  `wallet_id` varchar(200) DEFAULT NULL,
  `wallet_password` varchar(200) DEFAULT NULL,
  `wallet_second_password` varchar(200) DEFAULT NULL,
  `jwt_issuer` varchar(3) DEFAULT 'CMS',
  `jwt_audience` varchar(3) DEFAULT 'All',
  `jwt_key` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bot_setting`
--

LOCK TABLES `bot_setting` WRITE;
/*!40000 ALTER TABLE `bot_setting` DISABLE KEYS */;
/*!40000 ALTER TABLE `bot_setting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `error_logs`
--

DROP TABLE IF EXISTS `error_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `error_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `error_number` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `error` text CHARACTER SET utf8mb4 DEFAULT NULL,
  `file` text CHARACTER SET utf8mb4 DEFAULT NULL,
  `line` text CHARACTER SET utf8mb4 DEFAULT NULL,
  `source` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` timestamp NULL DEFAULT current_timestamp(),
  `deleted` int(1) NOT NULL DEFAULT 0,
  `deleted_account_id` int(11) DEFAULT NULL,
  `deleted_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `error_logs`
--

LOCK TABLES `error_logs` WRITE;
/*!40000 ALTER TABLE `error_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `error_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gui_account`
--

DROP TABLE IF EXISTS `gui_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `username` varchar(32) DEFAULT NULL,
  `password` tinytext DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `profile_img` text DEFAULT NULL,
  `last_login_date` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `last_login_ip` varchar(20) DEFAULT NULL,
  `login_attempt` int(1) NOT NULL DEFAULT 0,
  `account_group` int(1) DEFAULT NULL,
  `inscription_date` timestamp NULL DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `deleted_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `account_group` (`account_group`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gui_account`
--

LOCK TABLES `gui_account` WRITE;
/*!40000 ALTER TABLE `gui_account` DISABLE KEYS */;
INSERT INTO `gui_account` VALUES (1,'BladeBTC','(Admin)','bladebtc','$2y$10$ricm9SeFh3q/NaHAMLE6O.tpuUYjYJVMjYaSIjPMAnOSzM4cSavrG','bladebtc@bladebtc.com','avatar.png','2019-05-21 20:20:51','192.168.0.17',0,1,NULL,0,NULL);
/*!40000 ALTER TABLE `gui_account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gui_group`
--

DROP TABLE IF EXISTS `gui_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(2) NOT NULL,
  `group_name` varchar(35) NOT NULL,
  `dashboard` tinytext DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gui_group`
--

LOCK TABLES `gui_group` WRITE;
/*!40000 ALTER TABLE `gui_group` DISABLE KEYS */;
INSERT INTO `gui_group` VALUES (1,1,'Admin','dashboard');
/*!40000 ALTER TABLE `gui_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gui_menu`
--

DROP TABLE IF EXISTS `gui_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(1) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `icon` tinytext NOT NULL,
  `display_order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gui_menu`
--

LOCK TABLES `gui_menu` WRITE;
/*!40000 ALTER TABLE `gui_menu` DISABLE KEYS */;
INSERT INTO `gui_menu` VALUES (1,1,'Configuration (GUI)','fa-cogs',2),(2,2,'Telegram (Bot)','fa-telegram',1);
/*!40000 ALTER TABLE `gui_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gui_module`
--

DROP TABLE IF EXISTS `gui_module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_module` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `description` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `icon` varchar(200) NOT NULL,
  `access_level` tinytext NOT NULL,
  `parent` int(11) NOT NULL,
  `static` int(1) NOT NULL DEFAULT 0,
  `visits` int(11) DEFAULT 0,
  `last_visit` timestamp NULL DEFAULT NULL,
  `active` int(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gui_module`
--

LOCK TABLES `gui_module` WRITE;
/*!40000 ALTER TABLE `gui_module` DISABLE KEYS */;
INSERT INTO `gui_module` VALUES (1,'Dashboard','dashboard','fa-wrench','1',-1,1,0,NULL,1),(2,'Account','manage-account','fa-wrench','1',1,0,0,NULL,1),(3,'Menu','manage-menu','fa-wrench','1',1,0,0,NULL,1),(4,'Modules','manage-module','fa-wrench','1',1,0,0,NULL,1),(5,'My Account','profile','fa-wrench','1',-1,1,0,NULL,1),(6,'RBAC','manage-rbac','fa-wrench','1',1,0,0,NULL,1),(7,'Denied','denied','fa-wrench','1',-1,1,0,NULL,1),(8,'Settings (Bot)','telegram-bot-settings','fa-wrench','1',2,0,0,NULL,1),(9,'Error Logs','telegram-error-log','fa-wrench','1',2,0,0,NULL,1),(10,'Investment Plans (Bot)','telegram-investment-plan','fa-wrench','1',2,0,0,NULL,1),(11,'Users (Bot)','telegram-users','fa-wrench','1',2,0,0,NULL,1);
/*!40000 ALTER TABLE `gui_module` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gui_rbac_assignment`
--

DROP TABLE IF EXISTS `gui_rbac_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_rbac_assignment` (
  `group_id` int(11) NOT NULL,
  `rbac_items_id` int(11) NOT NULL,
  PRIMARY KEY (`group_id`,`rbac_items_id`),
  KEY `rbac_items_id` (`rbac_items_id`),
  CONSTRAINT `gui_rbac_assignment_ibfk_1` FOREIGN KEY (`rbac_items_id`) REFERENCES `gui_rbac_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `gui_rbac_assignment_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `gui_group` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gui_rbac_assignment`
--

LOCK TABLES `gui_rbac_assignment` WRITE;
/*!40000 ALTER TABLE `gui_rbac_assignment` DISABLE KEYS */;
INSERT INTO `gui_rbac_assignment` VALUES (1,1),(1,2);
/*!40000 ALTER TABLE `gui_rbac_assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gui_rbac_items`
--

DROP TABLE IF EXISTS `gui_rbac_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_rbac_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gui_rbac_items`
--

LOCK TABLES `gui_rbac_items` WRITE;
/*!40000 ALTER TABLE `gui_rbac_items` DISABLE KEYS */;
INSERT INTO `gui_rbac_items` VALUES (1,'Can see the loading time.'),(2,'Can see debug bar.');
/*!40000 ALTER TABLE `gui_rbac_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `investment`
--

DROP TABLE IF EXISTS `investment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `investment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `telegram_id` int(11) NOT NULL,
  `amount` decimal(15,8) NOT NULL,
  `contract_end_date` timestamp NULL DEFAULT NULL,
  `contract_start_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `telegram_id` (`telegram_id`),
  CONSTRAINT `investment_ibfk_1` FOREIGN KEY (`telegram_id`) REFERENCES `users` (`telegram_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `investment`
--

LOCK TABLES `investment` WRITE;
/*!40000 ALTER TABLE `investment` DISABLE KEYS */;
/*!40000 ALTER TABLE `investment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `investment_plans`
--

DROP TABLE IF EXISTS `investment_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `investment_plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `minimum_invest` decimal(15,8) NOT NULL,
  `minimum_reinvest` decimal(15,8) NOT NULL,
  `minimum_payout` decimal(15,8) NOT NULL,
  `base_rate` int(11) NOT NULL,
  `contract_day` int(11) NOT NULL,
  `commission_rate` int(11) NOT NULL,
  `timer_time_hour` int(1) NOT NULL DEFAULT 4,
  `required_confirmations` int(11) NOT NULL,
  `interest_on_reinvest` int(11) NOT NULL,
  `withdraw_fee` int(11) NOT NULL,
  `active` int(1) NOT NULL DEFAULT 0,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_account_id` int(11) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `deleted_date` timestamp NULL DEFAULT NULL,
  `deleted_account_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `investment_plans`
--

LOCK TABLES `investment_plans` WRITE;
/*!40000 ALTER TABLE `investment_plans` DISABLE KEYS */;
INSERT INTO `investment_plans` VALUES (1,0.02000000,0.00500000,0.05000000,6,30,5,4,3,1,50000,1,'2019-05-16 20:16:18',1,0,NULL,NULL);
/*!40000 ALTER TABLE `investment_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `referrals`
--

DROP TABLE IF EXISTS `referrals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `referrals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `telegram_id_referent` int(11) NOT NULL,
  `telegram_id_referred` int(11) NOT NULL,
  `bind_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `telegram_id_referred` (`telegram_id_referred`),
  KEY `telegram_id_referent` (`telegram_id_referent`),
  CONSTRAINT `referrals_ibfk_1` FOREIGN KEY (`telegram_id_referent`) REFERENCES `users` (`telegram_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `referrals_ibfk_2` FOREIGN KEY (`telegram_id_referred`) REFERENCES `users` (`telegram_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `referrals`
--

LOCK TABLES `referrals` WRITE;
/*!40000 ALTER TABLE `referrals` DISABLE KEYS */;
/*!40000 ALTER TABLE `referrals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `telegram_id` int(25) DEFAULT NULL,
  `amount` decimal(15,8) DEFAULT NULL,
  `withdraw_address` tinytext DEFAULT NULL,
  `message` text DEFAULT NULL,
  `tx_hash` text DEFAULT NULL,
  `tx_id` text DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `telegram_id` (`telegram_id`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`telegram_id`) REFERENCES `users` (`telegram_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `telegram_username` tinytext DEFAULT NULL,
  `telegram_first` tinytext DEFAULT NULL,
  `telegram_last` tinytext DEFAULT NULL,
  `telegram_id` int(25) DEFAULT NULL,
  `balance` double(15,8) NOT NULL DEFAULT 0.00000000,
  `invested` double(15,8) NOT NULL DEFAULT 0.00000000,
  `profit` double(15,8) NOT NULL DEFAULT 0.00000000,
  `commission` double(15,8) NOT NULL DEFAULT 0.00000000,
  `payout` double(15,8) NOT NULL DEFAULT 0.00000000,
  `investment_address` tinytext DEFAULT NULL,
  `last_confirmed` double(15,8) DEFAULT NULL,
  `wallet_address` tinytext DEFAULT NULL,
  `referral_link` tinytext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `telegram_id` (`telegram_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-05-21 20:42:54
