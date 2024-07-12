-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: db_esame
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

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
-- Table structure for table `appuntamento`
--

DROP TABLE IF EXISTS `appuntamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `appuntamento` (
  `IDappuntamento` int(11) NOT NULL AUTO_INCREMENT,
  `luogo` varchar(1000) NOT NULL,
  `data_ora` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `iddestinatario` int(11) NOT NULL,
  `idmittente` int(11) NOT NULL,
  `stato` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`IDappuntamento`),
  KEY `appuntamento_utente_FK` (`iddestinatario`),
  KEY `appuntamento_utente_FK_1` (`idmittente`),
  CONSTRAINT `appuntamento_utente_FK` FOREIGN KEY (`iddestinatario`) REFERENCES `utente` (`IDutente`),
  CONSTRAINT `appuntamento_utente_FK_1` FOREIGN KEY (`idmittente`) REFERENCES `utente` (`IDutente`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appuntamento`
--

LOCK TABLES `appuntamento` WRITE;
/*!40000 ALTER TABLE `appuntamento` DISABLE KEYS */;
INSERT INTO `appuntamento` VALUES (1,'duomo milano','2024-07-09 15:26:58',3,2,'accettato');
/*!40000 ALTER TABLE `appuntamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `foto`
--

DROP TABLE IF EXISTS `foto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `foto` (
  `IDfoto` int(11) NOT NULL AUTO_INCREMENT,
  `percorso` varchar(250) NOT NULL,
  `idutente` int(11) NOT NULL,
  PRIMARY KEY (`IDfoto`),
  KEY `foto_utente_FK` (`idutente`),
  CONSTRAINT `foto_utente_FK` FOREIGN KEY (`idutente`) REFERENCES `utente` (`IDutente`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `foto`
--

LOCK TABLES `foto` WRITE;
/*!40000 ALTER TABLE `foto` DISABLE KEYS */;
INSERT INTO `foto` VALUES (1,'messi.jpg',1),(2,'beyonce-690x362.jpg',2),(3,'jovanotti.jpg',3),(4,'michellehunz.jpg',4),(5,'kvara.jpg',5),(6,'Angelina-mango.jpg',6),(7,'marcomengoni.jpg',7);
/*!40000 ALTER TABLE `foto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messaggio`
--

DROP TABLE IF EXISTS `messaggio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messaggio` (
  `IDmessaggio` int(11) NOT NULL AUTO_INCREMENT,
  `testo` varchar(1000) NOT NULL,
  `data_ora` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `IDmittente` int(11) NOT NULL,
  `IDdestinatario` int(11) NOT NULL,
  PRIMARY KEY (`IDmessaggio`),
  KEY `messaggio_utente_FK` (`IDmittente`),
  KEY `messaggio_utente_FK_1` (`IDdestinatario`),
  CONSTRAINT `messaggio_utente_FK` FOREIGN KEY (`IDmittente`) REFERENCES `utente` (`IDutente`),
  CONSTRAINT `messaggio_utente_FK_1` FOREIGN KEY (`IDdestinatario`) REFERENCES `utente` (`IDutente`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messaggio`
--

LOCK TABLES `messaggio` WRITE;
/*!40000 ALTER TABLE `messaggio` DISABLE KEYS */;
INSERT INTO `messaggio` VALUES (1,'ciao, piacere di conoscerti','2024-07-09 15:16:22',2,3),(2,'ciao, piacere, lorenzo','2024-07-09 16:09:29',3,4);
/*!40000 ALTER TABLE `messaggio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `preferenza`
--

DROP TABLE IF EXISTS `preferenza`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `preferenza` (
  `IDpreferenza` int(11) NOT NULL AUTO_INCREMENT,
  `eta_minima` int(10) unsigned NOT NULL,
  `eta_massima` int(10) unsigned NOT NULL,
  `sesso_preferito` char(1) NOT NULL,
  `altro` varchar(1000) DEFAULT NULL,
  `idutente` int(11) NOT NULL,
  PRIMARY KEY (`IDpreferenza`),
  KEY `preferenza_utente_FK` (`idutente`),
  CONSTRAINT `preferenza_utente_FK` FOREIGN KEY (`idutente`) REFERENCES `utente` (`IDutente`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `preferenza`
--

LOCK TABLES `preferenza` WRITE;
/*!40000 ALTER TABLE `preferenza` DISABLE KEYS */;
INSERT INTO `preferenza` VALUES (2,25,40,'F','appassionata di calcio',1),(5,30,60,'M','amante del mare ed alto',4),(6,18,25,'F','ragazza di Napoli',5),(7,20,26,'M','simpatico e dolce',6),(8,30,60,'F','amante della musica',3),(9,40,65,'M','musicista',2),(10,25,50,'M','atletici',7);
/*!40000 ALTER TABLE `preferenza` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utente`
--

DROP TABLE IF EXISTS `utente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `utente` (
  `IDutente` int(11) NOT NULL AUTO_INCREMENT,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(32) NOT NULL,
  `Nome` varchar(50) NOT NULL,
  `Cognome` varchar(50) NOT NULL,
  `Sesso` varchar(1) NOT NULL,
  `Eta` int(11) NOT NULL,
  PRIMARY KEY (`IDutente`),
  UNIQUE KEY `Email` (`Email`),
  KEY `utente_Password_IDX` (`Password`,`Email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utente`
--

LOCK TABLES `utente` WRITE;
/*!40000 ALTER TABLE `utente` DISABLE KEYS */;
INSERT INTO `utente` VALUES (1,'leomessi@gmail.com','ce0a8ad8a758c3d242d9e050c4707fb6','leo','messi','M',37),(2,'beyonce@gmail.com','88f1798e205c841fe851b42095329f84','beyonce','.','F',40),(3,'lorenzocherubini@gmail.com','c480fdd53d9014065e61f14e90a63b5a','lorenzo','cherubini','M',60),(4,'michellehunzicher@gmail.com','c621dfa89b2de2e4faaa0edf45399fc8','michelle','hunzicher','F',54),(5,'kvichakvaratschelia@gmail.com','55fc0af63f0fe830085dccfed0e40c15','kvicha','kvaratschelia','M',22),(6,'angelinamango@gmail.com','9651c02165b7872e3a4483f0c78a1b23','angelina','mango','F',22),(7,'marcomengoni@gmail.com','eb1f6e9b2c2c9dcef9ac6a946ae1cd5d','marco','mengoni','M',35);
/*!40000 ALTER TABLE `utente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'db_esame'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-07-10 17:43:20
