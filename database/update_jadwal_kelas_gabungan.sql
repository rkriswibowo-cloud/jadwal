USE db_penjadwalan_ruang;

CREATE TABLE IF NOT EXISTS jadwal_kelas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  jadwal_id INT NOT NULL,
  kelas_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_jadwal_kelas_jadwal FOREIGN KEY (jadwal_id) REFERENCES jadwal(id) ON DELETE CASCADE,
  CONSTRAINT fk_jadwal_kelas_kelas FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE RESTRICT,
  UNIQUE KEY uniq_jadwal_kelas (jadwal_id, kelas_id),
  KEY idx_jadwal_kelas_kelas (kelas_id)
);

INSERT IGNORE INTO jadwal_kelas (jadwal_id, kelas_id)
SELECT id, kelas_id
FROM jadwal;

SET @has_idx_jadwal_ruang_slot := (
  SELECT COUNT(1)
  FROM information_schema.statistics
  WHERE table_schema = DATABASE()
    AND table_name = 'jadwal'
    AND index_name = 'idx_jadwal_ruang_slot'
);
SET @sql_idx_jadwal_ruang_slot := IF(
  @has_idx_jadwal_ruang_slot = 0,
  'ALTER TABLE jadwal ADD INDEX idx_jadwal_ruang_slot (ruang_id, hari, jam_id, semester_akademik, tahun_akademik)',
  'SELECT 1'
);
PREPARE stmt_idx_jadwal_ruang_slot FROM @sql_idx_jadwal_ruang_slot;
EXECUTE stmt_idx_jadwal_ruang_slot;
DEALLOCATE PREPARE stmt_idx_jadwal_ruang_slot;

SET @has_uniq_ruang_slot := (
  SELECT COUNT(1)
  FROM information_schema.statistics
  WHERE table_schema = DATABASE()
    AND table_name = 'jadwal'
    AND index_name = 'uniq_ruang_slot'
);
SET @sql_uniq_ruang_slot := IF(
  @has_uniq_ruang_slot > 0,
  'ALTER TABLE jadwal DROP INDEX uniq_ruang_slot',
  'SELECT 1'
);
PREPARE stmt_uniq_ruang_slot FROM @sql_uniq_ruang_slot;
EXECUTE stmt_uniq_ruang_slot;
DEALLOCATE PREPARE stmt_uniq_ruang_slot;
