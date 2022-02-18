-- Create table
CREATE DATABASE sers_uat /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE SERS_UAT;

-- MySQL dump 10.13  Distrib 8.0.21, for Win64 (x86_64)
--
-- Host: localhost    Database: sers-local
-- ------------------------------------------------------
-- Server version	8.0.18

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

--
-- Table structure for table `course`
--

DROP TABLE IF EXISTS `course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `course` (
  `Course_ID` varchar(15) NOT NULL,
  `Course_Name` varchar(255) NOT NULL,
  `Course_Desc` varchar(255) NOT NULL,
  `Course_Status` varchar(255) NOT NULL DEFAULT 'not offered',
  `Category` varchar(255) NOT NULL,
  PRIMARY KEY (`Course_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course`
--

LOCK TABLES `course` WRITE;
/*!40000 ALTER TABLE `course` DISABLE KEYS */;
INSERT INTO `course` VALUES ('SCIS100','Web Application Development 1','Basic web development','not offered','Basic'),('SCIS101','Database management','MySQL and data management tools','not offered','Basic'),('SCIS103','AI','Intro to artificial intelligence','not offered','Basic'),('SCIS108','ML','Intro to machine learning','not offered','Basic'),('SCIS110','CT','Learn computational thinking','not offered','Basic'),('SCIS159','Data mining','Learn how to mine data','not offered','Basic'),('SCIS200','Web Application Development 2','Front end web development','not offered','Intermediate'),('SCIS202','ESD','Enterprise solutions','not offered','Intermediate'),('SCIS217','Data structures and algorithms','Learn about data structures and algorithms','not offered','Intermediate'),('SCIS222','Natural language communication','Learn how to process text','not offered','Intermediate');
/*!40000 ALTER TABLE `course` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-08-03 17:02:51


-- FILTERED_COURSE

DROP TABLE IF EXISTS `filtered_course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `filtered_course` (
  `Vote_Start` date NOT NULL,
  `Vote_End` date NOT NULL DEFAULT ((curdate() + interval 10 day)),
  `FCourse_Status` varchar(255) DEFAULT (_utf8mb4'ongoing'),
  `FCourse_ID` varchar(15) NOT NULL,
  PRIMARY KEY (`Vote_Start`,`FCourse_ID`),
  KEY `FCourse_ID` (`FCourse_ID`),
  CONSTRAINT `filtered_course_ibfk_1` FOREIGN KEY (`FCourse_ID`) REFERENCES `course` (`Course_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filtered_course`
--

LOCK TABLES `filtered_course` WRITE;
/*!40000 ALTER TABLE `filtered_course` DISABLE KEYS */;
/*!40000 ALTER TABLE `filtered_course` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-08-03 17:02:51

-- RUN

DROP TABLE IF EXISTS `run`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `run` (
  `Class` int(11) NOT NULL DEFAULT '1',
  `Registration_End` date NOT NULL DEFAULT ((curdate() + interval 10 day)),
  `Run_StartDate` date NOT NULL,
  `Run_EndDate` date NOT NULL DEFAULT ((curdate() + interval 21 day)),
  `Run_StartTime` time NOT NULL DEFAULT (curtime()),
  `Run_EndTime` time NOT NULL DEFAULT (addtime(curtime(),_utf8mb4'03:00:00')),
  `Run_Days` int(11) NOT NULL DEFAULT '1',
  `Run_MinSlots` int(11) NOT NULL DEFAULT '15',
  `Run_MaxSlots` int(11) NOT NULL DEFAULT '30',
  `Run_Status` varchar(255) NOT NULL DEFAULT (_utf8mb4'pending'),
  `Run_Venue` varchar(255) NOT NULL DEFAULT 'SIS SR 2.3',
  `Run_Fees` int(11) NOT NULL DEFAULT '15',
  `Run_Desc` varchar(255) NOT NULL DEFAULT 'A VERY DETAILED DESCRIPTION',
  `RunCourse_ID` varchar(15) NOT NULL,
  PRIMARY KEY (`Class`,`Run_StartDate`,`RunCourse_ID`),
  KEY `RunCourse_ID` (`RunCourse_ID`),
  CONSTRAINT `run_ibfk_1` FOREIGN KEY (`RunCourse_ID`) REFERENCES `course` (`Course_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `run`
--

LOCK TABLES `run` WRITE;
/*!40000 ALTER TABLE `run` DISABLE KEYS */;
/*!40000 ALTER TABLE `run` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-08-03 17:02:52

-- ROLE

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role` (
  `Role_ID` int(11) NOT NULL,
  `Role_Name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Role_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'Student'),(2,'Admin'),(3,'Instructor'),(4,'Management'),(5,'Pending');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-08-03 17:02:52

-- USER

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `User_ID` int(11) NOT NULL AUTO_INCREMENT,
  `User_Name` varchar(255) NOT NULL,
  `User_Email` varchar(255) NOT NULL,
  `User_Password` varchar(255) NOT NULL,
  `URole_ID` int(11) NOT NULL,
  `Verified` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`User_ID`),
  UNIQUE KEY `User_Email` (`User_Email`),
  KEY `URole_ID` (`URole_ID`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`URole_ID`) REFERENCES `role` (`Role_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=9585833 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'External Instructor','einstructor@bmail.com','$2y$10$WGJedH7XrpiiE3bsakql.ex3pnHAk/fBBnpFDVK8duMJylwjaoUcK',3,0),(2,'Einstructor2','einstructor2@bmail.com','$2y$10$QizLK3T.7EBBR0/zA1MT.OHtEPgePxWylx85GzF5ug5p7ZRHszwCy',3,0),(3,'external instructor123','external123@bmail.com','$2y$10$vhvEk6TUM1urUbIO7hzeQ.EMRl43DMSAdw1egk1.iFgfN.r7C/H2e',2,0),(8888,'admin','admin@smu.edu.sg','$2y$10$HXhvsvddP7R4/zw.VtBKtu9AgZwGh7eYiuhTKOpKrFiAMMo3B7gf2',2,0),(1234567,'TestStudent','testStudent@smu.edu.sg','$2y$10$ntv.FCD5Ask2RcPEOqpxu.Caa/SHDyOTciO7jHMZOJFiAXClmeGgG',1,0),(1234569,'Intructor2','instructor2@smu.edu.sg','$2y$10$STbuzL9bfAonaKagm2mtQOtyo/w7piW8oub65HnY6UKRnngLw6Pz6',3,0),(1350384,'Han Xingjian','xjhan.2019@smu.edu.sg','$2y$10$0tqX.w2ot3JWLV1PWozbVOezn2IwJvOShdWl/dkx/SG4M.e31TMfW',1,0),(1350385,'Xingjian','xjhan.2020@smu.edu.sg','$2y$10$M80eY2zII0RkX8CMdHeMt.dRgCOlj2wsQM7xDpyb..dnuXdGz6Eda',1,0),(3049652,'teststudent','teststudent2@smu.edu.sg','$2y$10$ClqxVstnhqGpU5nOl796r.Q21xtyrNtvZAQNMdP6tiwmzgvp7JD1y',1,0),(3349293,'student!!!','student@smu.edu.sg','$2y$10$WtJIw0zi9HA3zHb9SnacoOCjHz9fT7r.tgptqsnnHQMUwfwTBAdPy',1,0),(3928432,'Instructor Test','instructortest123@smu.edu.sg','$2y$10$D1uftTbwkKP7FiNjI5.C0eiBMYCSp6Eqy8Tb0GvWlEiL7bDI/l2Li',3,0),(3949283,'testtesttest','testinstructo123@smu.edu.sg','$2y$10$2cQXwLDIEXUtRXbILMVGOODzDmLfFf/O6oBCh3kpfZ7TbZgPGXSQ6',3,0),(3949596,'Intrusctor','Instructor@smu.edu.sg','$2y$10$jaDDlonOliKA72d5SVzNvuSArbsRmDP7elHzGX.9uMo8/BpU4vVrq',3,0),(4444444,'InstructorTest2','instructor3@smu.edu.sg','$2y$10$T54nkBy9BNl.7m3/c4qe3eI.1qfpzV7FlsswipEkexxAdINUXmegC',4,0),(9585832,'instructor34','instructor34@smu.edu.sg','$2y$10$PEzdVsDBVnbaichzuoI79uNp/jMphDgVH.lNb3Vf9pT.MO.O3mGUK',5,0);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-08-03 17:02:51

-- INTEREST

--
-- Table structure for table `interest`
--

DROP TABLE IF EXISTS `interest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `interest` (
  `ICourse_ID` varchar(15) NOT NULL,
  `IVote_Start` date NOT NULL,
  `IUser_ID` int(11) NOT NULL,
  `Indicated_Date` date NOT NULL DEFAULT (curdate()),
  PRIMARY KEY (`ICourse_ID`,`IVote_Start`,`IUser_ID`),
  KEY `IUser_ID` (`IUser_ID`),
  CONSTRAINT `interest_ibfk_1` FOREIGN KEY (`ICourse_ID`, `IVote_Start`) REFERENCES `filtered_course` (`FCourse_ID`, `Vote_Start`),
  CONSTRAINT `interest_ibfk_2` FOREIGN KEY (`IUser_ID`) REFERENCES `user` (`User_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `interest`
--

LOCK TABLES `interest` WRITE;
/*!40000 ALTER TABLE `interest` DISABLE KEYS */;
/*!40000 ALTER TABLE `interest` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-08-03 17:02:52

-- REGISTRATION

--
-- Table structure for table `registration`
--

DROP TABLE IF EXISTS `registration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `registration` (
  `RUser_ID` int(11) NOT NULL,
  `RegCourse_ID` varchar(15) NOT NULL,
  `RClass` int(11) NOT NULL,
  `RRun_StartDate` date NOT NULL,
  `Reg_Datetime` datetime DEFAULT (now()),
  `Reg_Status` varchar(255) DEFAULT (_utf8mb4'pending'),
  `Completion_Status` varchar(255) DEFAULT (_utf8mb4'not completed'),
  PRIMARY KEY (`RUser_ID`,`RegCourse_ID`,`RClass`,`RRun_StartDate`),
  KEY `RegCourse_ID` (`RegCourse_ID`,`RClass`,`RRun_StartDate`),
  CONSTRAINT `registration_ibfk_1` FOREIGN KEY (`RUser_ID`) REFERENCES `user` (`User_ID`),
  CONSTRAINT `registration_ibfk_2` FOREIGN KEY (`RegCourse_ID`, `RClass`, `RRun_StartDate`) REFERENCES `run` (`RunCourse_ID`, `Class`, `Run_StartDate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registration`
--

LOCK TABLES `registration` WRITE;
/*!40000 ALTER TABLE `registration` DISABLE KEYS */;
/*!40000 ALTER TABLE `registration` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-08-03 17:02:52

-- EXTERNAL_USER

--
-- Table structure for table `external_user`
--

DROP TABLE IF EXISTS `external_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `external_user` (
  `EUser_ID` int(11) NOT NULL,
  `Company` varchar(255) NOT NULL,
  `Designation` varchar(255) NOT NULL,
  `Contact` int(11) NOT NULL,
  `Alumni` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`EUser_ID`),
  CONSTRAINT `external_user_ibfk_1` FOREIGN KEY (`EUser_ID`) REFERENCES `user` (`User_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `external_user`
--

LOCK TABLES `external_user` WRITE;
/*!40000 ALTER TABLE `external_user` DISABLE KEYS */;
INSERT INTO `external_user` VALUES (1,'Apple','Pro',12345678,1),(2,'Samsung','Pro',12345678,0),(3,'Samsung','Cleaner',94850234,1);
/*!40000 ALTER TABLE `external_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-08-03 17:02:51

-- COURSE PROPOSED

--
-- Table structure for table `course_proposed`
--

DROP TABLE IF EXISTS `course_proposed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `course_proposed` (
  `CPUser_ID` int(11) NOT NULL,
  `CP_ID` int(11) NOT NULL AUTO_INCREMENT,
  `CP_Name` varchar(50) NOT NULL,
  `CP_Desc` varchar(255) NOT NULL,
  `CP_Status` varchar(15) NOT NULL DEFAULT 'reviewing',
  PRIMARY KEY (`CP_ID`),
  KEY `cp_fk` (`CPUser_ID`),
  CONSTRAINT `cp_fk` FOREIGN KEY (`CPUser_ID`) REFERENCES `user` (`User_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_proposed`
--

LOCK TABLES `course_proposed` WRITE;
/*!40000 ALTER TABLE `course_proposed` DISABLE KEYS */;
INSERT INTO `course_proposed` VALUES (1350384,5,'test course','asd123','approved'),(1350384,6,'Test456','abc123','approved'),(1350384,7,'Test proposal','proposing','rejected'),(1350385,8,'porposal123','proposal','reviewing'),(1350384,11,'New Course','abc123','approved'),(1350384,12,'Test','123','approved'),(1350384,13,'test course','asd123','reviewing'),(1350384,14,'testpropose12312321321','propsose','rejected');
/*!40000 ALTER TABLE `course_proposed` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-08-03 17:02:51

-- INSTRUCTOR_RUN

--
-- Table structure for table `instructor_run`
--

DROP TABLE IF EXISTS `instructor_run`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `instructor_run` (
  `Instructor_ID` int(11) NOT NULL,
  `IRCourse_ID` varchar(15) NOT NULL,
  `IRRun_StartDate` date NOT NULL,
  `IRClass` int(11) NOT NULL,
  KEY `IRCourse_ID` (`IRCourse_ID`,`IRClass`,`IRRun_StartDate`),
  KEY `Instructor_ID` (`Instructor_ID`),
  CONSTRAINT `instructor_run_ibfk_1` FOREIGN KEY (`IRCourse_ID`, `IRClass`, `IRRun_StartDate`) REFERENCES `run` (`RunCourse_ID`, `Class`, `Run_StartDate`) ON UPDATE CASCADE,
  CONSTRAINT `instructor_run_ibfk_2` FOREIGN KEY (`Instructor_ID`) REFERENCES `user` (`User_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instructor_run`
--

LOCK TABLES `instructor_run` WRITE;
/*!40000 ALTER TABLE `instructor_run` DISABLE KEYS */;
/*!40000 ALTER TABLE `instructor_run` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-08-03 17:02:51

DROP TABLE IF EXISTS `certificate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `certificate` (
  `CUser_ID` int(11) NOT NULL,
  `Certificate_ID` int(11) NOT NULL,
  `Title` varchar(100) NOT NULL,
  `Organisation` varchar(100) NOT NULL,
  `Issue_Date` date NOT NULL,
  `Expiration` date DEFAULT NULL,
  `Certificate_URL` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`CUser_ID`,`Certificate_ID`,`Organisation`),
  CONSTRAINT `certificate_ibfk_1` FOREIGN KEY (`CUser_ID`) REFERENCES `user` (`User_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `certificate`
--

LOCK TABLES `certificate` WRITE;
/*!40000 ALTER TABLE `certificate` DISABLE KEYS */;
/*!40000 ALTER TABLE `certificate` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-08-03 17:02:51
