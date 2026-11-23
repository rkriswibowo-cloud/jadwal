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



## Docker dan Cloudflare Tunnel

1. Salin `.env.example` ke `.env`, batasi izin file, lalu isi kata sandi database, email admin, dan kata sandi admin awal. Gunakan nilai yang berbeda untuk setiap kata sandi.

   ```sh
   (umask 077; cp .env.example .env)
   ```

2. Uji aplikasi secara lokal.

   ```sh
   docker compose up -d --build
   ```

   Buka `http://localhost:8093/`. Pada volume baru, MariaDB mengimpor data bawaan repo, menghapus tiga akun demo, lalu layanan setup membuat admin dari `.env`. Data MariaDB lokal yang sudah digunakan oleh Apache tidak disalin. Data Docker tersimpan di volume `jadwal_database`.

3. Untuk hosting, buat atau pilih tunnel kelolaan Cloudflare. Tambahkan **Published application** untuk subdomain yang diinginkan dan isi service origin dengan `http://web:80`. Nama `web` dapat diakses oleh kontainer cloudflared di Compose.

4. Isi `CLOUDFLARE_TUNNEL_TOKEN` dari dashboard Cloudflare dan atur `JADWAL_COOKIE_SECURE=1`. Jalankan stack dengan profil Cloudflare.

   ```sh
   docker compose --profile cloudflare up -d --build
   ```

   Setelah cookie aman diaktifkan, gunakan alamat HTTPS yang diterbitkan Cloudflare untuk masuk. Jika kontainer cloudflared tidak tersambung, periksa `docker compose --profile cloudflare logs cloudflared`.

Panduan resmi tersedia di [Cloudflare Tunnel](https://developers.cloudflare.com/tunnel/concepts/routing/) dan [Tunnel tokens](https://developers.cloudflare.com/tunnel/reference/tunnel-tokens/).

### Deploy melalui Portainer

Gunakan environment **Docker Standalone** yang menjalankan proyek ini. Jalur bind mount di `docker-compose.portainer.yml` merujuk ke filesystem host Docker. Jika folder proyek berada di lokasi lain, isi `JADWAL_SOURCE_DIR` dengan jalur absolut folder tersebut.

1. Buka `https://localhost:9443`, pilih environment Docker, lalu **Images → Build a new image → Web editor**. Beri nama image `jadwal-app:local`, tempel isi `docker/Dockerfile.portainer`, dan unggah `jadwal-context.tar.gz` lewat **Select files**. Lalu klik **Build the image**. Jika image `jadwal-app:local` sudah dibangun menggunakan Dockerfile utama di host yang sama, lewati langkah ini.
2. Pilih **Stacks → Add stack → Web editor**, beri nama `jadwal`, lalu tempel `docker-compose.portainer.yml`.
3. Di **Environment variables**, isi `JADWAL_DB_PASSWORD`, `JADWAL_DB_ROOT_PASSWORD`, `JADWAL_ADMIN_EMAIL`, dan `JADWAL_ADMIN_PASSWORD`. Untuk Portainer, cookie HTTPS aktif secara default.
4. Klik **Deploy the stack** di Portainer. Kontainer `setup` selesai dan berhenti setelah admin tersedia. Cek `http://127.0.0.1:8093/` dari host Docker.
5. Untuk `cloudflared` yang berjalan langsung di host Docker, arahkan **Published application** subdomain ke `http://127.0.0.1:8093`. Stack Portainer ini tidak menjalankan kontainer Cloudflare Tunnel.

Untuk memperbarui arsip build dengan kode lokal terbaru, jalankan dari folder proyek:

```sh
tar --exclude=app/config/database.local.php --exclude=app/config/obe.local.php --exclude=public/sso-callback.php \
    -czf /home/rony/jadwal-context.tar.gz app routes public docker/apache.conf docker/bootstrap-admin.php
```

Image Docker menggunakan login lokal Jadwal. Callback SSO platform yang mengandalkan layanan Apache host tidak disertakan dalam image.

[Dokumentasi build image Portainer](https://docs.portainer.io/user/docker/images/build) · [Dokumentasi deployment stack](https://docs.portainer.io/user/docker/stacks/add)


### Sinkronisasi periode dari OBE

Jalankan `database/academic_periods.sql` sekali pada database Jadwal yang sudah ada sebelum memasang kode ini. Volume Docker baru mengimpor skema melalui Compose. Buat akun MariaDB khusus yang hanya dapat membaca kolom `id`, `nama`, dan `is_active` pada `obe_cpl.tahun_akademik`. Simpan konfigurasi privat sebagai file PHP `app/config/obe.local.php` yang mengembalikan array dengan kunci `socket`, `username`, dan `password`. Pada host ini, `socket` bernilai `/run/mysqld/mysqld.sock`.

Stack Portainer memasang file konfigurasi dan direktori socket OBE secara baca-saja. Gunakan YAML Portainer terbaru jika melakukan deploy ulang dari Web editor. Akun admin Jadwal dapat membuka **Akun → Periode Akademik → Sinkronkan dari OBE**. Jadwal yang sudah tersimpan mempertahankan tahun dan semesternya.
