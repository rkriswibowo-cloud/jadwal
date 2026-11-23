CREATE DATABASE IF NOT EXISTS db_penjadwalan_ruang CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_penjadwalan_ruang;

DROP TABLE IF EXISTS jadwal_kelas;
DROP TABLE IF EXISTS jadwal;
DROP TABLE IF EXISTS dosen;
DROP TABLE IF EXISTS mata_kuliah;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS kelas;
DROP TABLE IF EXISTS jam_pemakaian;
DROP TABLE IF EXISTS ruang;
DROP TABLE IF EXISTS prodi;
DROP TABLE IF EXISTS roles;

CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE prodi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode_prodi VARCHAR(30) NOT NULL UNIQUE,
  nama_prodi VARCHAR(120) NOT NULL,
  fakultas VARCHAR(120) NULL,
  status ENUM('aktif','nonaktif') DEFAULT 'aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE ruang (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode_ruang VARCHAR(30) NOT NULL UNIQUE,
  nama_ruang VARCHAR(120) NOT NULL,
  kapasitas INT NOT NULL DEFAULT 0,
  lokasi VARCHAR(120) NULL,
  status ENUM('aktif','nonaktif') DEFAULT 'aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE jam_pemakaian (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode_jam VARCHAR(30) NOT NULL UNIQUE,
  jam_mulai TIME NOT NULL,
  jam_selesai TIME NOT NULL,
  keterangan VARCHAR(120) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE mata_kuliah (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode_mk VARCHAR(30) NOT NULL UNIQUE,
  nama_mk VARCHAR(150) NOT NULL UNIQUE,
  sks INT NOT NULL DEFAULT 0,
  semester INT NULL,
  status ENUM('aktif','nonaktif') DEFAULT 'aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE dosen (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nidn VARCHAR(30) NULL UNIQUE,
  nama_dosen VARCHAR(150) NOT NULL UNIQUE,
  email VARCHAR(120) NULL,
  status ENUM('aktif','nonaktif') DEFAULT 'aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE kelas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  prodi_id INT NOT NULL,
  kode_kelas VARCHAR(30) NOT NULL UNIQUE,
  nama_kelas VARCHAR(120) NOT NULL,
  angkatan YEAR NULL,
  semester INT DEFAULT 1,
  jumlah_mahasiswa INT DEFAULT 0,
  status ENUM('aktif','nonaktif') DEFAULT 'aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_kelas_prodi FOREIGN KEY (prodi_id) REFERENCES prodi(id) ON DELETE RESTRICT
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  role_id INT NOT NULL,
  prodi_id INT NULL,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  status ENUM('aktif','nonaktif') DEFAULT 'aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT,
  CONSTRAINT fk_users_prodi FOREIGN KEY (prodi_id) REFERENCES prodi(id) ON DELETE SET NULL
);

CREATE TABLE jadwal (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ruang_id INT NOT NULL,
  kelas_id INT NOT NULL,
  jam_id INT NOT NULL,
  hari ENUM('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL,
  mata_kuliah VARCHAR(150) NOT NULL,
  dosen VARCHAR(150) NULL,
  semester_akademik ENUM('Ganjil','Genap') NOT NULL DEFAULT 'Ganjil',
  tahun_akademik VARCHAR(20) NOT NULL,
  status ENUM('aktif','nonaktif') DEFAULT 'aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_jadwal_ruang FOREIGN KEY (ruang_id) REFERENCES ruang(id) ON DELETE RESTRICT,
  CONSTRAINT fk_jadwal_kelas FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE RESTRICT,
  CONSTRAINT fk_jadwal_jam FOREIGN KEY (jam_id) REFERENCES jam_pemakaian(id) ON DELETE RESTRICT,
  KEY idx_jadwal_ruang_slot (ruang_id, hari, jam_id, semester_akademik, tahun_akademik),
  UNIQUE KEY uniq_kelas_slot (kelas_id, hari, jam_id, semester_akademik, tahun_akademik)
);

CREATE TABLE jadwal_kelas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  jadwal_id INT NOT NULL,
  kelas_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_jadwal_kelas_jadwal FOREIGN KEY (jadwal_id) REFERENCES jadwal(id) ON DELETE CASCADE,
  CONSTRAINT fk_jadwal_kelas_kelas FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE RESTRICT,
  UNIQUE KEY uniq_jadwal_kelas (jadwal_id, kelas_id),
  KEY idx_jadwal_kelas_kelas (kelas_id)
);

INSERT INTO roles (id, name) VALUES (1,'admin'), (2,'operator_prodi'), (3,'viewer');
INSERT INTO prodi (id, kode_prodi, nama_prodi, fakultas) VALUES
(1,'TI','Teknik Informatika','Fakultas Teknologi'),
(2,'SI','Sistem Informasi','Fakultas Teknologi');
INSERT INTO ruang (id, kode_ruang, nama_ruang, kapasitas, lokasi) VALUES
(1,'A101','Ruang A101',40,'Gedung A'),
(2,'A102','Ruang A102',35,'Gedung A'),
(3,'B201','Ruang B201',50,'Gedung B');
INSERT INTO jam_pemakaian (id, kode_jam, jam_mulai, jam_selesai, keterangan) VALUES
(1,'JAM1','07:30:00','08:20:00','Jam ke-1'),
(2,'JAM2','08:20:00','09:10:00','Jam ke-2'),
(3,'JAM3','09:10:00','10:00:00','Jam ke-3'),
(4,'JAM4','10:00:00','10:50:00','Jam ke-4'),
(5,'JAM5','10:50:00','11:40:00','Jam ke-5'),
(6,'JAM6','11:40:00','12:30:00','Jam ke-6'),
(7,'JAM7','12:30:00','13:20:00','Jam ke-7'),
(8,'JAM8','13:20:00','14:10:00','Jam ke-8'),
(9,'JAM9','14:10:00','15:00:00','Jam ke-9'),
(10,'JAM10','15:00:00','15:50:00','Jam ke-10'),
(11,'JAM11','15:50:00','16:40:00','Jam ke-11'),
(12,'JAM12','16:40:00','17:30:00','Jam ke-12');
INSERT INTO mata_kuliah (id, kode_mk, nama_mk, sks, semester) VALUES
(1,'IF301','Pemrograman Web',3,3),
(2,'IF302','Basis Data',3,3),
(3,'IF303','Algoritma dan Struktur Data',3,1);
INSERT INTO dosen (id, nidn, nama_dosen, email) VALUES
(1,'DSN001','Budi Santoso','budi@kampus.test'),
(2,'DSN002','Siti Aminah','siti@kampus.test'),
(3,'DSN003','Agus Wijaya','agus@kampus.test');
INSERT INTO kelas (id, prodi_id, kode_kelas, nama_kelas, angkatan, semester, jumlah_mahasiswa) VALUES
(1,1,'TI-3A','TI 3A',2024,3,32),
(2,1,'TI-5B','TI 5B',2023,5,30),
(3,2,'SI-1A','SI 1A',2025,1,34);
INSERT INTO users (role_id, prodi_id, name, email, password) VALUES
(1,NULL,'Administrator','admin@kampus.test','$2y$12$.Y4D2OiA1M.RCRvHKyR8OOvdNB1Wk.Twto4aoP/JxKEnjLrjXN6ra'),
(2,1,'Operator Prodi TI','operator@kampus.test','$2y$12$zwBnUUZwmNR9sVMGnUUxTujIbM1/qOd3Z9ohM56/iDhTNYlEUQcyC'),
(3,NULL,'Viewer','viewer@kampus.test','$2y$12$4grqKOupzgU07IpL9LQLtOu9sx4h8jYqj/IjbMHxJywu.jbS5iBW.');
INSERT INTO jadwal (ruang_id, kelas_id, jam_id, hari, mata_kuliah, dosen, semester_akademik, tahun_akademik) VALUES
(1,1,1,'Senin','Pemrograman Web','Budi Santoso','Ganjil','2025/2026'),
(2,2,2,'Selasa','Basis Data','Siti Aminah','Ganjil','2025/2026'),
(3,3,3,'Rabu','Algoritma dan Struktur Data','Agus Wijaya','Ganjil','2025/2026');

INSERT INTO jadwal_kelas (jadwal_id, kelas_id)
SELECT id, kelas_id FROM jadwal;
