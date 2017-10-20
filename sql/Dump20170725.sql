-- MySQL dump 10.13  Distrib 5.7.13, for linux-glibc2.5 (x86_64)
--
-- Host: localhost    Database: intranet
-- ------------------------------------------------------
-- Server version	5.7.19-0ubuntu0.16.04.1

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
-- Table structure for table `intranet_assigned_project`
--

DROP TABLE IF EXISTS `intranet_assigned_project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `intranet_assigned_project` (
  `project_assign_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`project_assign_id`),
  KEY `project_id` (`project_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `intranet_assigned_project_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `intranet_project` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `intranet_assigned_project_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `intranet_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intranet_assigned_project`
--

LOCK TABLES `intranet_assigned_project` WRITE;
/*!40000 ALTER TABLE `intranet_assigned_project` DISABLE KEYS */;
INSERT INTO `intranet_assigned_project` VALUES (1,1,6),(2,4,6),(3,5,6),(26,1,8),(33,6,6);
/*!40000 ALTER TABLE `intranet_assigned_project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `intranet_assigned_task`
--

DROP TABLE IF EXISTS `intranet_assigned_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `intranet_assigned_task` (
  `task_assign_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`task_assign_id`),
  KEY `user_id` (`user_id`),
  KEY `task_id` (`task_id`),
  CONSTRAINT `intranet_assigned_task_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `intranet_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `intranet_assigned_task_ibfk_2` FOREIGN KEY (`task_id`) REFERENCES `intranet_task` (`task_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intranet_assigned_task`
--

LOCK TABLES `intranet_assigned_task` WRITE;
/*!40000 ALTER TABLE `intranet_assigned_task` DISABLE KEYS */;
INSERT INTO `intranet_assigned_task` VALUES (1,1,6),(2,2,6),(3,3,6),(4,5,6),(6,5,8),(7,6,6),(9,6,8);
/*!40000 ALTER TABLE `intranet_assigned_task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `intranet_color`
--

DROP TABLE IF EXISTS `intranet_color`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `intranet_color` (
  `color_id` int(11) NOT NULL AUTO_INCREMENT,
  `color_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_hex` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`color_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intranet_color`
--

LOCK TABLES `intranet_color` WRITE;
/*!40000 ALTER TABLE `intranet_color` DISABLE KEYS */;
INSERT INTO `intranet_color` VALUES (1,'Default','#1b6d85'),(3,'Amarelo','yellow'),(5,'Azul','blue'),(8,'Vermelho','red'),(9,'Laranja','orange');
/*!40000 ALTER TABLE `intranet_color` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `intranet_note`
--

DROP TABLE IF EXISTS `intranet_note`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `intranet_note` (
  `note_id` int(11) NOT NULL AUTO_INCREMENT,
  `note_content` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note_type` tinyint(4) DEFAULT '0',
  `note_date` date NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`note_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `intranet_note_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `intranet_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intranet_note`
--

LOCK TABLES `intranet_note` WRITE;
/*!40000 ALTER TABLE `intranet_note` DISABLE KEYS */;
INSERT INTO `intranet_note` VALUES (1,'nota divina',1,'2017-06-01',6),(2,'vou chegar atrasado !',1,'2017-06-13',6),(3,'nota universal',2,'2017-07-26',6),(4,'nota pessoal 1',1,'2017-07-24',6),(5,'dassd',2,'2017-06-27',6);
/*!40000 ALTER TABLE `intranet_note` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `intranet_project`
--

DROP TABLE IF EXISTS `intranet_project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `intranet_project` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_start_date` date DEFAULT NULL,
  `project_end_date` date DEFAULT NULL,
  `project_summary` text COLLATE utf8mb4_unicode_ci,
  `project_progress` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intranet_project`
--

LOCK TABLES `intranet_project` WRITE;
/*!40000 ALTER TABLE `intranet_project` DISABLE KEYS */;
INSERT INTO `intranet_project` VALUES (1,'Restaurante','2017-06-06','2017-08-08','ss',1),(4,'Escola','2017-06-07','2017-07-10','',1),(5,'Bombeiros','2017-06-08','2017-08-10',NULL,2),(6,'Pastelaria','2017-06-10','2017-07-12','projecto pastelaria',1),(7,'Tenis','2017-06-12','2017-07-12','website tenis',2);
/*!40000 ALTER TABLE `intranet_project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `intranet_task`
--

DROP TABLE IF EXISTS `intranet_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `intranet_task` (
  `task_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_start_date` date DEFAULT NULL,
  `task_end_date` date DEFAULT NULL,
  `project_id` int(11) NOT NULL,
  `task_summary` text COLLATE utf8mb4_unicode_ci,
  `task_progress` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`task_id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `intranet_task_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `intranet_project` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intranet_task`
--

LOCK TABLES `intranet_task` WRITE;
/*!40000 ALTER TABLE `intranet_task` DISABLE KEYS */;
INSERT INTO `intranet_task` VALUES (1,'login restaurante','2017-06-08','2017-06-10',1,'such login',1),(2,'login escola','2017-06-08','2017-06-08',4,NULL,1),(3,'login bombeiros','2017-06-08','2017-06-09',5,NULL,1),(5,'newsletter restaurante','2017-06-22','2017-06-30',1,'muito importante',2),(6,'login pastelaria','2017-07-10','2017-07-21',6,'mui importante',1);
/*!40000 ALTER TABLE `intranet_task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `intranet_user`
--

DROP TABLE IF EXISTS `intranet_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `intranet_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `password` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` tinyint(4) NOT NULL DEFAULT '1',
  `color` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_color_id` (`color`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intranet_user`
--

LOCK TABLES `intranet_user` WRITE;
/*!40000 ALTER TABLE `intranet_user` DISABLE KEYS */;
INSERT INTO `intranet_user` VALUES (6,'Administrador','admin','david@gmail.com','1969-06-19','31b3d7f1f72ea376a4a1e3004cb5881d8160d106',0,3),(8,'test2','test2','test2@gmail.com','2010-10-10','a0f1490a20d0211c997b44bc357e1972deab8ae3',1,1),(13,'test3','test3','test2@test.com','2017-07-05','a0f1490a20d0211c997b44bc357e1972deab8ae3',1,1),(14,'test4','test4','test4@test.com','2017-07-06','a0f1490a20d0211c997b44bc357e1972deab8ae3',1,1),(15,'test5','test5','test5@gmail.com','2017-07-06','a0f1490a20d0211c997b44bc357e1972deab8ae3',1,1),(17,'test7','test7','test7@gmail.com','2017-07-10','a0f1490a20d0211c997b44bc357e1972deab8ae3',1,1),(18,'test8','test8','test8@gmail.com','2017-07-11','a0f1490a20d0211c997b44bc357e1972deab8ae3',1,1),(19,'test9','test9','test9@gmail.com','2017-07-12','a0f1490a20d0211c997b44bc357e1972deab8ae3',1,1),(20,'test10','test10','test10@gmail.com','2017-07-18','a0f1490a20d0211c997b44bc357e1972deab8ae3',1,8),(21,'test12','test12','test12@gmail.com','2017-07-20','a0f1490a20d0211c997b44bc357e1972deab8ae3',1,5);
/*!40000 ALTER TABLE `intranet_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `intranet_vacation`
--

DROP TABLE IF EXISTS `intranet_vacation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `intranet_vacation` (
  `vacation_id` int(11) NOT NULL AUTO_INCREMENT,
  `vacation_start_date` date DEFAULT NULL,
  `vacation_end_date` date DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`vacation_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `intranet_vacation_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `intranet_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intranet_vacation`
--

LOCK TABLES `intranet_vacation` WRITE;
/*!40000 ALTER TABLE `intranet_vacation` DISABLE KEYS */;
INSERT INTO `intranet_vacation` VALUES (44,'2017-06-05','2017-06-05',6),(45,'2017-06-28','2017-06-30',6),(46,'2017-07-13','2017-07-13',6),(47,'2017-07-27','2017-07-27',6),(49,'2017-07-19','2017-07-22',6),(50,'2018-07-17','2018-07-22',6),(51,'2017-07-23','2017-07-25',8),(53,'2017-08-14','2017-08-18',6),(54,'2017-01-16','2017-01-18',6),(56,'2017-07-31','2017-08-01',6),(58,'2017-10-23','2017-10-27',8),(59,'2017-09-20','2017-09-22',20);
/*!40000 ALTER TABLE `intranet_vacation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `intranet_wiki_article`
--

DROP TABLE IF EXISTS `intranet_wiki_article`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `intranet_wiki_article` (
  `article_id` int(11) NOT NULL AUTO_INCREMENT,
  `article_name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `article_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `article_creation_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `article_modification_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`article_id`),
  KEY `category_id` (`category_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `intranet_wiki_article_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `intranet_wiki_category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `intranet_wiki_article_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `intranet_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intranet_wiki_article`
--

LOCK TABLES `intranet_wiki_article` WRITE;
/*!40000 ALTER TABLE `intranet_wiki_article` DISABLE KEYS */;
INSERT INTO `intranet_wiki_article` VALUES (6,'Company policy about web marketing','Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.',2,6,'2017-05-29 13:03:56','2017-07-19 13:04:16'),(7,'Facebook marketing','Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.',2,6,'2017-05-29 13:03:56',NULL),(8,'How to make a marketing plan','Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.',2,6,'2017-05-29 13:03:56',NULL),(26,'Common installation problems','Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.',5,6,'2017-05-29 13:07:02',NULL),(27,'Company information','Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.',5,6,'2017-05-29 13:07:02',NULL),(28,'Documentation','Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.',5,6,'2017-05-29 13:07:02',NULL),(29,'Installing wordpress','Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.',5,6,'2017-05-29 13:07:03',NULL),(50,'help2guide','guidemega',11,6,'2017-06-01 16:14:11',NULL),(51,'Helpsmvc','hold my beer',11,6,'2017-06-02 12:04:48',NULL),(52,'beer','love myself some beerss',11,6,'2017-06-02 12:06:42','2017-07-20 10:57:28'),(53,'marketingstuff','important!',2,6,'2017-06-02 12:58:28','2017-06-27 10:58:56'),(54,'helpingstuff','important help stuff',11,6,'2017-06-02 12:58:51',NULL),(55,'adas','dsadas',11,6,'2017-06-19 16:07:43',NULL),(56,'drup1','dasd',12,6,'2017-07-03 17:31:50',NULL),(57,'drup2','dafa',2,6,'2017-07-03 17:31:54',NULL),(58,'drup3','gfsdfsd',2,6,'2017-07-03 17:31:58',NULL),(59,'drup4g','gfsd',2,6,'2017-07-03 17:32:01',NULL),(61,'fsf','aaaa',12,6,'2017-07-03 17:32:32',NULL),(62,'fsfddd','fsewq',12,6,'2017-07-03 17:32:38',NULL),(63,'www','fff',12,6,'2017-07-03 17:32:45',NULL);
/*!40000 ALTER TABLE `intranet_wiki_article` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `intranet_wiki_category`
--

DROP TABLE IF EXISTS `intranet_wiki_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `intranet_wiki_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intranet_wiki_category`
--

LOCK TABLES `intranet_wiki_category` WRITE;
/*!40000 ALTER TABLE `intranet_wiki_category` DISABLE KEYS */;
INSERT INTO `intranet_wiki_category` VALUES (2,'MARKETING'),(5,'WORDPRESSS'),(11,'HELP2'),(12,'DRUP');
/*!40000 ALTER TABLE `intranet_wiki_category` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-07-25 13:14:43
