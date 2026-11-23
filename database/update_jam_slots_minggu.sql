USE db_penjadwalan_ruang;

ALTER TABLE jadwal
  MODIFY hari ENUM('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL;

INSERT INTO jam_pemakaian (kode_jam, jam_mulai, jam_selesai, keterangan) VALUES
('JAM1','07:30:00','08:20:00','Jam ke-1'),
('JAM2','08:20:00','09:10:00','Jam ke-2'),
('JAM3','09:10:00','10:00:00','Jam ke-3'),
('JAM4','10:00:00','10:50:00','Jam ke-4'),
('JAM5','10:50:00','11:40:00','Jam ke-5'),
('JAM6','11:40:00','12:30:00','Jam ke-6'),
('JAM7','12:30:00','13:20:00','Jam ke-7'),
('JAM8','13:20:00','14:10:00','Jam ke-8'),
('JAM9','14:10:00','15:00:00','Jam ke-9'),
('JAM10','15:00:00','15:50:00','Jam ke-10'),
('JAM11','15:50:00','16:40:00','Jam ke-11'),
('JAM12','16:40:00','17:30:00','Jam ke-12')
ON DUPLICATE KEY UPDATE
  jam_mulai = VALUES(jam_mulai),
  jam_selesai = VALUES(jam_selesai),
  keterangan = VALUES(keterangan);
