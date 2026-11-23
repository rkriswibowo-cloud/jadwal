# Sistem Penjadwalan Penggunaan Ruang Kuliah

Web app PHP Native modular untuk mengelola jadwal penggunaan ruang kuliah.

## Teknologi

- PHP Native
- MySQL/MariaDB via XAMPP
- AdminLTE 3 via CDN
- Bootstrap/jQuery via CDN
- `.htaccess` clean URL

## Instalasi di XAMPP

1. Copy folder proyek ke `htdocs`, misalnya `htdocs/jadwal`.
2. Buat database `db_penjadwalan_ruang` di phpMyAdmin.
3. Import file `database/penjadwalan_ruang.sql`.
4. Sesuaikan konfigurasi di `app/config/database.php`.
5. Buka `http://localhost/jadwal`.

## Update Database Lama

Jika database sudah pernah di-import sebelum fitur kelas gabungan, jalankan file `database/update_jadwal_kelas_gabungan.sql` di phpMyAdmin agar jadwal lama masuk ke relasi kelas gabungan.

Jika database sudah ada sebelum menu Mata Kuliah dan Dosen, jalankan file `database/update_mata_kuliah_dosen.sql` di phpMyAdmin. Aplikasi juga akan mencoba membuat tabel master tersebut otomatis saat menu dibuka.

## Login default

- Admin: `admin@kampus.test` / `admin123`
- Operator: `operator@kampus.test` / `operator123`
- Viewer: `viewer@kampus.test` / `viewer123`

## Fitur Cetak Jadwal

- **Cetak Jadwal Kelas**: Menampilkan jadwal kuliah per prodi dan kelas dalam matriks mingguan (Senin - Minggu).
- **Cetak Jadwal Dosen**: Menampilkan jadwal mengajar per dosen (dengan NIDN, filter semester, tahun akademik, matriks mingguan, serta tabel rincian jadwal mengajar).

## Catatan hosting

Idealnya document root hosting diarahkan ke folder `public`. Jika hosting atau XAMPP memakai root proyek, `.htaccess` root akan meneruskan request ke `public` secara otomatis.


