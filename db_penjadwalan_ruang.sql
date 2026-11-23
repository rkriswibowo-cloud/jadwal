-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: db_penjadwalan_ruang
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Current Database: `db_penjadwalan_ruang`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `db_penjadwalan_ruang` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `db_penjadwalan_ruang`;

--
-- Table structure for table `dosen`
--

DROP TABLE IF EXISTS `dosen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dosen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nidn` varchar(30) DEFAULT NULL,
  `nama_dosen` varchar(150) NOT NULL,
  `email` varchar(120) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nidn` (`nidn`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dosen`
--

LOCK TABLES `dosen` WRITE;
/*!40000 ALTER TABLE `dosen` DISABLE KEYS */;
INSERT INTO `dosen` VALUES (1,'0707069402','Rony Kriswibowo, M.Kom','rkriswibowo@gmail.com','aktif','2026-05-25 08:54:48');
/*!40000 ALTER TABLE `dosen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jadwal`
--

DROP TABLE IF EXISTS `jadwal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jadwal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ruang_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `jam_id` int(11) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL,
  `mata_kuliah` varchar(150) NOT NULL,
  `dosen` varchar(150) DEFAULT NULL,
  `semester_akademik` enum('Ganjil','Genap') NOT NULL DEFAULT 'Ganjil',
  `tahun_akademik` varchar(20) NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_kelas_slot` (`kelas_id`,`hari`,`jam_id`,`semester_akademik`,`tahun_akademik`),
  KEY `fk_jadwal_jam` (`jam_id`),
  KEY `idx_jadwal_ruang_slot` (`ruang_id`,`hari`,`jam_id`,`semester_akademik`,`tahun_akademik`),
  CONSTRAINT `fk_jadwal_jam` FOREIGN KEY (`jam_id`) REFERENCES `jam_pemakaian` (`id`),
  CONSTRAINT `fk_jadwal_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`),
  CONSTRAINT `fk_jadwal_ruang` FOREIGN KEY (`ruang_id`) REFERENCES `ruang` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jadwal`
--

LOCK TABLES `jadwal` WRITE;
/*!40000 ALTER TABLE `jadwal` DISABLE KEYS */;
INSERT INTO `jadwal` VALUES (7,5,4,1,'Selasa','Aplikasi Komputer','Rony Kriswibowo, M.Kom','Ganjil','2026/2027','aktif','2026-10-01 11:05:04'),(8,5,2,1,'Selasa','Aplikasi Komputer','Rony Kriswibowo, M.Kom','Ganjil','2026/2027','aktif','2026-10-01 11:05:51'),(9,5,5,1,'Selasa','Aplikasi Komputer','Rony Kriswibowo, M.Kom','Ganjil','2026/2027','aktif','2026-10-01 11:08:58'),(10,5,3,1,'Selasa','Aplikasi Komputer','Rony Kriswibowo, M.Kom','Ganjil','2026/2027','aktif','2026-10-01 11:10:19'),(11,1,6,5,'Selasa','Tata Kelola IT','Rony Kriswibowo, M.Kom','Ganjil','2026/2027','aktif','2026-10-01 11:20:38'),(12,5,10,9,'Rabu','Aplikasi Komputer','Rony Kriswibowo, M.Kom','Ganjil','2026/2027','aktif','2026-10-01 11:22:19'),(13,5,9,2,'Rabu','Aplikasi Komputer','Rony Kriswibowo, M.Kom','Ganjil','2026/2027','aktif','2026-10-01 11:23:03'),(14,5,6,5,'Rabu','Manajemen Proyek IT','Rony Kriswibowo, M.Kom','Ganjil','2026/2027','aktif','2026-10-01 11:24:01'),(15,5,12,2,'Kamis','Aplikasi Komputer','Rony Kriswibowo, M.Kom','Ganjil','2026/2027','aktif','2026-10-01 11:26:14'),(16,5,11,2,'Kamis','Aplikasi Komputer','Rony Kriswibowo, M.Kom','Ganjil','2026/2027','aktif','2026-10-01 11:26:53'),(17,6,7,7,'Kamis','Audit SI','Rony Kriswibowo, M.Kom','Ganjil','2026/2027','aktif','2026-10-01 11:27:29'),(18,7,13,10,'Rabu','Kepemimpinan dan Manajemen Organisasi','Rony Kriswibowo, M.Kom','Ganjil','2026/2027','aktif','2026-10-01 11:32:51'),(19,7,14,9,'Jumat','Pemodelan dan Simulasi Perangkat Lunak','Rony Kriswibowo, M.Kom','Ganjil','2026/2027','aktif','2026-10-01 11:34:23'),(20,6,15,3,'Kamis','Software Quality Assurance','Rony Kriswibowo, M.Kom','Ganjil','2026/2027','aktif','2026-10-06 01:57:04');
/*!40000 ALTER TABLE `jadwal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jadwal_kelas`
--

DROP TABLE IF EXISTS `jadwal_kelas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jadwal_kelas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jadwal_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_jadwal_kelas` (`jadwal_id`,`kelas_id`),
  KEY `idx_jadwal_kelas_kelas` (`kelas_id`),
  CONSTRAINT `fk_jadwal_kelas_jadwal` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_jadwal_kelas_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=313 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jadwal_kelas`
--

LOCK TABLES `jadwal_kelas` WRITE;
/*!40000 ALTER TABLE `jadwal_kelas` DISABLE KEYS */;
INSERT INTO `jadwal_kelas` VALUES (86,8,2,'2026-10-01 11:05:51'),(90,7,4,'2026-10-01 11:06:03'),(98,9,5,'2026-10-01 11:08:58'),(102,10,3,'2026-10-01 11:10:19'),(108,11,6,'2026-10-01 11:20:38'),(116,13,9,'2026-10-01 11:23:03'),(120,12,10,'2026-10-01 11:23:19'),(130,14,6,'2026-10-01 11:25:04'),(135,15,12,'2026-10-01 11:26:14'),(141,16,11,'2026-10-01 11:26:53'),(145,17,7,'2026-10-01 11:27:29'),(156,18,13,'2026-10-01 11:32:52'),(162,19,14,'2026-10-01 11:34:23'),(289,20,15,'2026-10-06 01:57:04');
/*!40000 ALTER TABLE `jadwal_kelas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jam_pemakaian`
--

DROP TABLE IF EXISTS `jam_pemakaian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jam_pemakaian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_jam` varchar(30) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `keterangan` varchar(120) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_jam` (`kode_jam`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jam_pemakaian`
--

LOCK TABLES `jam_pemakaian` WRITE;
/*!40000 ALTER TABLE `jam_pemakaian` DISABLE KEYS */;
INSERT INTO `jam_pemakaian` VALUES (1,'JAM1','07:30:00','08:20:00','Jam ke-1','2026-05-23 12:30:27'),(2,'JAM2','08:20:00','09:10:00','Jam ke-2','2026-05-23 12:30:27'),(3,'JAM3','09:10:00','10:00:00','Jam ke-3','2026-05-23 12:30:27'),(5,'JAM4','10:00:00','10:50:00','Jam ke-4','2026-05-24 02:13:25'),(6,'JAM5','10:50:00','11:40:00','Jam ke-5','2026-05-24 02:13:25'),(7,'JAM6','11:40:00','12:30:00','Jam ke-6','2026-05-24 02:13:25'),(8,'JAM7','12:30:00','13:20:00','Jam ke-7','2026-05-24 02:13:25'),(9,'JAM8','13:20:00','14:10:00','Jam ke-8','2026-05-24 02:13:25'),(10,'JAM9','14:10:00','15:00:00','Jam ke-9','2026-05-24 02:13:25'),(11,'JAM10','15:00:00','15:50:00','Jam ke-10','2026-05-24 02:13:25'),(12,'JAM11','15:50:00','16:40:00','Jam ke-11','2026-05-24 02:13:25'),(13,'JAM12','16:40:00','17:30:00','Jam ke-12','2026-05-24 02:13:25');
/*!40000 ALTER TABLE `jam_pemakaian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kelas`
--

DROP TABLE IF EXISTS `kelas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kelas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prodi_id` int(11) NOT NULL,
  `kode_kelas` varchar(30) NOT NULL,
  `nama_kelas` varchar(120) NOT NULL,
  `angkatan` year(4) DEFAULT NULL,
  `semester` int(11) DEFAULT 1,
  `jumlah_mahasiswa` int(11) DEFAULT 0,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_kelas` (`kode_kelas`),
  KEY `fk_kelas_prodi` (`prodi_id`),
  CONSTRAINT `fk_kelas_prodi` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kelas`
--

LOCK TABLES `kelas` WRITE;
/*!40000 ALTER TABLE `kelas` DISABLE KEYS */;
INSERT INTO `kelas` VALUES (2,1,'H-26','H-26 RPL',2026,1,10,'aktif','2026-05-23 12:30:27'),(3,2,'I-26','I-26 Sistem Informasi',2025,1,10,'aktif','2026-05-23 12:30:27'),(4,3,'F-26','F-26 Bisnis Digital',2026,1,20,'aktif','2026-10-01 10:57:54'),(5,4,'G-26','G-26 Kewirausahaan',2026,1,10,'aktif','2026-10-01 10:59:20'),(6,2,'I-24','I-24 Sistem Informasi',2024,1,10,'aktif','2026-10-01 11:00:16'),(7,2,'I-23','I-23 Sistem Informasi',2023,7,5,'aktif','2026-10-01 11:01:03'),(8,3,'F-25','F-25 Bisnis Digital',2025,3,5,'aktif','2026-10-01 11:01:50'),(9,10,'D-25','D-25 Adminkes',2025,3,30,'aktif','2026-10-01 11:16:12'),(10,9,'D-26','D-26 Fisioterapi',2026,1,30,'aktif','2026-10-01 11:16:58'),(11,8,'A-26','A-26 TLM',2026,1,20,'aktif','2026-10-01 11:17:29'),(12,6,'C-26','C-26 Farmasi',2026,1,40,'aktif','2026-10-01 11:19:46'),(13,2,'I-25','I-25 Sistem Informasi',2025,3,20,'aktif','2026-10-01 11:31:18'),(14,1,'H-25','H-25 RPL',2025,3,20,'aktif','2026-10-01 11:33:38'),(15,1,'H-23','H-23',2023,7,10,'aktif','2026-10-06 01:56:26');
/*!40000 ALTER TABLE `kelas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mata_kuliah`
--

DROP TABLE IF EXISTS `mata_kuliah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mata_kuliah` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_mk` varchar(30) NOT NULL,
  `nama_mk` varchar(150) NOT NULL,
  `sks` int(11) NOT NULL DEFAULT 0,
  `semester` int(11) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_mk` (`kode_mk`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mata_kuliah`
--

LOCK TABLES `mata_kuliah` WRITE;
/*!40000 ALTER TABLE `mata_kuliah` DISABLE KEYS */;
INSERT INTO `mata_kuliah` VALUES (1,'SIF701','Skripsi',2,6,'aktif','2026-05-25 08:55:09'),(2,'UAM-003','Aplikasi Komputer',1,1,'aktif','2026-10-01 11:02:46'),(3,'SIF505','Tata Kelola IT',3,5,'aktif','2026-10-01 11:03:26'),(4,'SIF501','Manajemen Proyek IT',3,5,'aktif','2026-10-01 11:03:50'),(5,'SIF707','Audit SI',3,7,'aktif','2026-10-01 11:04:27'),(6,'FEB201','Pemrograman Web',3,3,'aktif','2026-10-01 11:25:39'),(7,'SIF308','Kepemimpinan dan Manajemen Organisasi',2,3,'aktif','2026-10-01 11:28:11'),(8,'RPL303','Pemodelan dan Simulasi Perangkat Lunak',3,3,'aktif','2026-10-01 11:28:50'),(9,'RPL704','Software Quality Assurance',2,7,'aktif','2026-10-01 11:29:29');
/*!40000 ALTER TABLE `mata_kuliah` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prodi`
--

DROP TABLE IF EXISTS `prodi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prodi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_prodi` varchar(30) NOT NULL,
  `nama_prodi` varchar(120) NOT NULL,
  `fakultas` varchar(120) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_prodi` (`kode_prodi`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prodi`
--

LOCK TABLES `prodi` WRITE;
/*!40000 ALTER TABLE `prodi` DISABLE KEYS */;
INSERT INTO `prodi` VALUES (1,'RPL','Rekayasa Perangkat Lunak','Fakultas Teknologi dan Bisnis','aktif','2026-05-23 12:30:26'),(2,'SI','Sistem Informasi','Fakultas Teknologi dan Bisnis','aktif','2026-05-23 12:30:26'),(3,'BD','Bisnis Digital','Fakultas Teknologi dan Bisnis','aktif','2026-05-24 02:26:21'),(4,'KWU','Kewirausahaan','Fakultas Teknologi dan Bisnis','aktif','2026-05-24 02:26:35'),(5,'S1FAR','Farmasi','Fakultas Ilmu Kesehatan','aktif','2026-05-24 02:26:57'),(6,'D3FAR','D3 Farmasi','Fakultas Ilmu Kesehatan','aktif','2026-05-24 02:27:11'),(7,'PSPA','Apoteker','Fakultas Ilmu Kesehatan','aktif','2026-05-24 02:27:26'),(8,'TLM','Teknologi Laboratorium Medik','Fakultas Ilmu Kesehatan','aktif','2026-05-24 02:27:59'),(9,'FISIO','Fisioterapi','Fakultas Ilmu Kesehatan','aktif','2026-05-24 02:28:15'),(10,'ADMINKES','Administrasi Kesehatan','Fakultas Ilmu Kesehatan','aktif','2026-05-24 02:28:32');
/*!40000 ALTER TABLE `prodi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin'),(2,'operator_prodi'),(3,'viewer');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ruang`
--

DROP TABLE IF EXISTS `ruang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ruang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_ruang` varchar(30) NOT NULL,
  `nama_ruang` varchar(120) NOT NULL,
  `kapasitas` int(11) NOT NULL DEFAULT 0,
  `lokasi` varchar(120) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_ruang` (`kode_ruang`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ruang`
--

LOCK TABLES `ruang` WRITE;
/*!40000 ALTER TABLE `ruang` DISABLE KEYS */;
INSERT INTO `ruang` VALUES (1,'RK 6.3B','RK 6.3B',30,'Lantai 6','aktif','2026-05-23 12:30:27'),(5,'CBT','LAB KOM CBT',60,'Lt 1','aktif','2026-10-01 10:52:27'),(6,'W.FTB','Working Space FTB',20,'Lt 2 RGU','aktif','2026-10-01 11:15:36'),(7,'Online','Zoom Online',50,'ZOOM','aktif','2026-10-01 11:30:16');
/*!40000 ALTER TABLE `ruang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `prodi_id` int(11) DEFAULT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_users_role` (`role_id`),
  KEY `fk_users_prodi` (`prodi_id`),
  CONSTRAINT `fk_users_prodi` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,NULL,'Administrator','admin@kampus.test','$2y$12$.Y4D2OiA1M.RCRvHKyR8OOvdNB1Wk.Twto4aoP/JxKEnjLrjXN6ra','aktif','2026-05-23 12:30:27'),(2,2,1,'Operator Prodi TI','operator@kampus.test','$2y$12$zwBnUUZwmNR9sVMGnUUxTujIbM1/qOd3Z9ohM56/iDhTNYlEUQcyC','aktif','2026-05-23 12:30:27'),(3,3,NULL,'Viewer','viewer@kampus.test','$2y$12$4grqKOupzgU07IpL9LQLtOu9sx4h8jYqj/IjbMHxJywu.jbS5iBW.','aktif','2026-05-23 12:30:27');
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

-- Dump completed on 2026-11-23 13:20:46
