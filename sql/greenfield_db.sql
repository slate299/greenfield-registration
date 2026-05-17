-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: localhost    Database: greenfield_db
-- ------------------------------------------------------
-- Server version	8.0.42

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `courses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `course_code` varchar(20) NOT NULL,
  `course_name` varchar(150) NOT NULL,
  `description` text,
  `instructor` varchar(100) DEFAULT NULL,
  `capacity` int DEFAULT '30',
  `semester` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `course_code` (`course_code`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (9,'SMA3101','Probability and Statistics III','Advanced probability theory, statistical inference, and data analysis techniques','Prof. James Otieno',45,'Semester 1, 2026','2026-05-17 19:04:54'),(10,'SMA2100','Introduction to Abstract Algebra','Group theory, ring theory, and algebraic structures','Dr. Mercy Wanjiku',40,'Semester 1, 2026','2026-05-17 19:04:54'),(11,'ICS2402','Internet Application Programming','Web development, PHP, JavaScript, and modern web frameworks','Prof. Kamau Maina',35,'Semester 1, 2026','2026-05-17 19:04:54'),(12,'ICS2305','System Analysis and Design','Software development lifecycle, requirements gathering, and UML','Dr. Achieng Omondi',40,'Semester 2, 2026','2026-05-17 19:04:54'),(13,'BUS1102','Entrepreneurship Skills','Business planning, innovation, and startup management','Prof. Njeri Mwangi',47,'Semester 2, 2026','2026-05-17 19:04:54'),(14,'SMA1104','Linear Algebra I','Matrices, vectors, eigenvalues, and linear transformations','Dr. Odhiambo Oduor',45,'Semester 1, 2026','2026-05-17 19:04:54'),(15,'ICS2312','Operating Systems II','Process management, memory management, file systems, and concurrency','Prof. Wanjiru Kariuki',35,'Semester 2, 2026','2026-05-17 19:04:54'),(16,'ICS2405','Statistical Programming','R programming, Python for statistics, and data visualization','Dr. Atieno Ochieng',30,'Semester 2, 2026','2026-05-17 19:04:54');
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registrations`
--

DROP TABLE IF EXISTS `registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `registrations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `course_id` int NOT NULL,
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('enrolled','dropped','completed') DEFAULT 'enrolled',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_registration` (`user_id`,`course_id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registrations`
--

LOCK TABLES `registrations` WRITE;
/*!40000 ALTER TABLE `registrations` DISABLE KEYS */;
INSERT INTO `registrations` VALUES (11,2,13,'2026-05-17 19:10:14','enrolled');
/*!40000 ALTER TABLE `registrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('student','admin') DEFAULT 'student',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `student_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `student_id` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'System Administrator','admin@greenfield.edu','$2y$10$osJDDkfkRyi4.njO4Ef5Mumej8ughMWF5Iclav7lnShRxCECRel7y','admin','2026-05-17 19:05:07',NULL),(2,'John Mwangi','john.mwangi@student.edu','$2y$10$dr1AbvgD4kqZLC1243571O8d3Sffk9tajqTihipvhOOaJuWrDg/gy','student','2026-05-17 19:05:07','GF-2024-001'),(3,'Mary Wanjiku','mary.wanjiku@student.edu','$2y$10$dr1AbvgD4kqZLC1243571O8d3Sffk9tajqTihipvhOOaJuWrDg/gy','student','2026-05-17 19:05:07','GF-2024-002'),(4,'Peter Omondi','peter.omondi@student.edu','$2y$10$dr1AbvgD4kqZLC1243571O8d3Sffk9tajqTihipvhOOaJuWrDg/gy','student','2026-05-17 19:05:07','GF-2024-003'),(5,'Jane Achieng','jane.achieng@student.edu','$2y$10$dr1AbvgD4kqZLC1243571O8d3Sffk9tajqTihipvhOOaJuWrDg/gy','student','2026-05-17 19:05:07','GF-2024-004');
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

-- Dump completed on 2026-05-17 22:27:40
