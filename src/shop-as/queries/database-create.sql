CREATE DATABASE  IF NOT EXISTS `users` /*!40100 DEFAULT CHARACTER SET utf8 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `users`;
-- MySQL dump 10.13  Distrib 8.0.16, for Win64 (x86_64)
--
-- Host: 192.168.1.167    Database: users
-- ------------------------------------------------------
-- Server version	8.0.17

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `accesses`
--

DROP TABLE IF EXISTS `accesses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `accesses` (
  `access_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(32) NOT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `jti` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`access_id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `authorizations`
--

DROP TABLE IF EXISTS `authorizations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `authorizations` (
  `authorization` varchar(32) NOT NULL,
  `description` text,
  PRIMARY KEY (`authorization`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authorizations`
--

LOCK TABLES `authorizations` WRITE;
/*!40000 ALTER TABLE `authorizations` DISABLE KEYS */;
INSERT INTO `authorizations` VALUES ('analysis_approvation','approvazione dei bollettini di analisi'),('analysis_edit','modifica delle analisi di laboratorio'),('assign_authorizations','permette all\'amministratore di assegnare autorizzazioni'),('batch_edit','modifica dell\'assegnazione dei lotti'),('change_password','permesso di assegnare cambiare le password a tutti gli utenti'),('cln_vld_approvation','approvazione delle verifiche di efficacia lavaggi'),('coa_code_edit','modiifca dell codice del bollettino di analisi'),('labels_issue','emissione delle etichette'),('labels_models','creazione dei modelli di etichetta'),('nc_closing','chiusura'),('plan_approvation','approvazione dei plans'),('plan_closing','chiusura dei plans'),('receipt_approve','approvazione delle accettazioni materie prime in magazzino'),('receipt_edit','modifica delle accettazioni materie prime in magazzino'),('release_edit','modifica dei rilasci del lotto'),('rnd_approvation','approvazione progetti sviluppo RnD'),('rnd_closing','chiusura progetti sviluppo RnD'),('specification_edit','modifica delle specifiche di prodotto per analisi in laboratorio'),('test_collection_edit','modifica dell\'elenco dei test di laboratorio');
/*!40000 ALTER TABLE `authorizations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_authorizations`
--

DROP TABLE IF EXISTS `user_authorizations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `user_authorizations` (
  `uid` varchar(32) NOT NULL,
  `authorization` varchar(32) NOT NULL,
  PRIMARY KEY (`uid`,`authorization`),
  KEY `fk_user_authorizations_authorizations1_idx` (`authorization`),
  KEY `fk_user_authorizations_users1_idx` (`uid`),
  CONSTRAINT `fk_user_authorizations_authorizations1` FOREIGN KEY (`authorization`) REFERENCES `authorizations` (`authorization`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_authorizations_users1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `users` (
  `uid` varchar(32) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0',
  `registrationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `activationKey` varchar(64) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `first_name` varchar(128) DEFAULT NULL,
  `family_name` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Temporary view structure for view `users_token_data`
--

DROP TABLE IF EXISTS `users_token_data`;
/*!50001 DROP VIEW IF EXISTS `users_token_data`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `users_token_data` AS SELECT 
 1 AS `uid`,
 1 AS `username`,
 1 AS `email`,
 1 AS `firstName`,
 1 AS `familyName`,
 1 AS `authorizations`*/;
SET character_set_client = @saved_cs_client;

--
-- Dumping routines for database 'users'
--
/*!50003 DROP PROCEDURE IF EXISTS `get_user_token_data` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`%` PROCEDURE `get_user_token_data`(uid VARCHAR(32))
BEGIN
	SELECT * from users_token_data u
    WHERE u.uid = uid;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `user_assign_authorization` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`%` PROCEDURE `user_assign_authorization`(uid varchar(32), auth varchar(32))
BEGIN
	INSERT INTO user_authorizations
	(`uid`,	`authorization`)
	VALUES
	(uid, auth);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Final view structure for view `users_token_data`
--

/*!50001 DROP VIEW IF EXISTS `users_token_data`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `users_token_data` AS select `u`.`uid` AS `uid`,`u`.`username` AS `username`,`u`.`email` AS `email`,`u`.`first_name` AS `firstName`,`u`.`family_name` AS `familyName`,json_arrayagg(`ua`.`authorization`) AS `authorizations` from (`users` `u` left join `user_authorizations` `ua` on((`u`.`uid` = `ua`.`uid`))) group by `u`.`uid` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-10-25 16:55:22