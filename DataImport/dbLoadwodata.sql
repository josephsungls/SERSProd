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


