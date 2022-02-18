-- MySQL dump 10.13  Distrib 8.0.25, for Win64 (x86_64)
--
-- Host: ls-8b173b5b72385bc68392398bc227c9a6892e8a73.cdm7ntwi5kkw.ap-southeast-1.rds.amazonaws.com    Database: dbsepm_dev
-- ------------------------------------------------------
-- Server version	8.0.25

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '';

--
-- Table structure for table `RUN`
--

DROP TABLE IF EXISTS `RUN`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `RUN` (
  `Class` int NOT NULL DEFAULT '1',
  `Registration_End` date NOT NULL DEFAULT ((curdate() + interval 10 day)),
  `Run_StartDate` date NOT NULL DEFAULT ((curdate() + interval 20 day)),
  `Run_EndDate` date NOT NULL DEFAULT ((curdate() + interval 21 day)),
  `Run_StartTime` time NOT NULL DEFAULT (curtime()),
  `Run_EndTime` time NOT NULL DEFAULT (addtime(curtime(),_utf8mb4'03:00:00')),
  `Run_Days` int NOT NULL DEFAULT '1',
  `Run_MinSlots` int NOT NULL DEFAULT '15',
  `Run_MaxSlots` int NOT NULL DEFAULT '30',
  `Run_Status` varchar(15) NOT NULL DEFAULT (_utf8mb4'pending'),
  `Run_Venue` varchar(25) NOT NULL DEFAULT 'SIS SR 2.3',
  `Run_Fees` int NOT NULL DEFAULT '15',
  `Run_Desc` varchar(255) NOT NULL DEFAULT 'A VERY DETAILED DESCRIPTION',
  `RunCourse_ID` varchar(15) NOT NULL,
  PRIMARY KEY (`Class`,`Run_StartDate`,`RunCourse_ID`),
  KEY `RunCourse_ID` (`RunCourse_ID`),
  CONSTRAINT `RUN_ibfk_1` FOREIGN KEY (`RunCourse_ID`) REFERENCES `COURSE` (`Course_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-08-04 15:44:51
