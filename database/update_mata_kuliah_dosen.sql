CREATE TABLE IF NOT EXISTS mata_kuliah (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode_mk VARCHAR(30) NOT NULL UNIQUE,
  nama_mk VARCHAR(150) NOT NULL UNIQUE,
  sks INT NOT NULL DEFAULT 0,
  semester INT NULL,
  status ENUM('aktif','nonaktif') DEFAULT 'aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS dosen (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nidn VARCHAR(30) NULL UNIQUE,
  nama_dosen VARCHAR(150) NOT NULL UNIQUE,
  email VARCHAR(120) NULL,
  status ENUM('aktif','nonaktif') DEFAULT 'aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT IGNORE INTO mata_kuliah (kode_mk, nama_mk, status)
SELECT CONCAT('MK-', MIN(id)), mata_kuliah, 'aktif'
FROM jadwal
WHERE mata_kuliah IS NOT NULL AND TRIM(mata_kuliah) <> ''
GROUP BY mata_kuliah;

INSERT IGNORE INTO dosen (nidn, nama_dosen, status)
SELECT CONCAT('DSN-', MIN(id)), dosen, 'aktif'
FROM jadwal
WHERE dosen IS NOT NULL AND TRIM(dosen) <> ''
GROUP BY dosen;
