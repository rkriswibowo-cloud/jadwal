# Design Sistem Penjadwalan Penggunaan Ruang Kuliah

## 1. Gambaran Umum

Sistem Penjadwalan Penggunaan Ruang Kuliah adalah web application untuk mengelola jadwal pemakaian ruang kuliah berdasarkan ruang, jam pemakaian, kelas, program studi, hari, dan tahun akademik.

Sistem dibuat menggunakan:

- HTML
- CSS
- PHP Native
- MySQL / MariaDB melalui XAMPP
- Template dashboard AdminLTE
- `.htaccess` untuk clean URL tanpa ekstensi `.php`
- Struktur folder modular agar mudah dikembangkan dan siap hosting

Tujuan utama sistem adalah membantu admin atau operator kampus mengatur pemakaian ruang kuliah agar tidak terjadi bentrok jadwal.

---

## 2. Tujuan Sistem

Sistem ini bertujuan untuk:

1. Mengelola data ruang kuliah.
2. Mengelola data jam pemakaian ruang.
3. Mengelola data program studi.
4. Mengelola data kelas.
5. Mengelola jadwal penggunaan ruang kuliah.
6. Mencegah bentrok jadwal antar ruang, kelas, hari, dan jam.
7. Menyediakan dashboard viewer untuk melihat penggunaan ruang secara visual.
8. Menyediakan tampilan running text kelas pemakai di dalam card ruang.

---

## 3. Role Pengguna

### 3.1 Admin

Admin memiliki akses penuh terhadap seluruh sistem.

Hak akses admin:

- Login ke sistem
- Melihat dashboard admin
- CRUD data ruang
- CRUD data program studi
- CRUD data kelas
- CRUD data jam pemakaian
- CRUD data jadwal
- CRUD data user
- Mengatur role pengguna
- Melihat seluruh jadwal ruang

### 3.2 Operator Prodi

Operator Prodi bertugas mengelola data kelas dan jadwal sesuai program studi masing-masing.

Hak akses operator prodi:

- Login ke sistem
- Melihat dashboard operator
- Melihat data ruang
- Melihat data jam pemakaian
- CRUD kelas sesuai prodi
- Membuat dan mengubah jadwal sesuai prodi
- Melihat jadwal ruang

### 3.3 Viewer

Viewer hanya bertugas melihat informasi penggunaan ruang.

Hak akses viewer:

- Melihat dashboard viewer
- Melihat daftar ruang dalam bentuk card
- Melihat status penggunaan ruang
- Melihat kelas pemakai ruang melalui running text
- Tidak dapat melakukan tambah, ubah, atau hapus data

---

## 4. Fitur Utama

### 4.1 Autentikasi

Fitur autentikasi digunakan untuk membatasi akses pengguna berdasarkan role.

Fitur:

- Login
- Logout
- Session user
- Middleware pengecekan role
- Proteksi halaman berdasarkan hak akses

### 4.2 Manajemen Ruang Kuliah

Data ruang kuliah digunakan sebagai tempat pemakaian jadwal.

Field utama:

- Kode ruang
- Nama ruang
- Kapasitas
- Lokasi / gedung
- Status aktif

Fitur:

- Tambah ruang
- Edit ruang
- Hapus ruang
- Lihat daftar ruang
- Cari ruang

### 4.3 Manajemen Jam Pemakaian

Jam pemakaian digunakan sebagai slot waktu kuliah.

Field utama:

- Kode jam
- Jam mulai
- Jam selesai
- Keterangan

Contoh:

- 07:30 - 09:10
- 09:20 - 11:00
- 13:00 - 14:40

Fitur:

- Tambah jam pemakaian
- Edit jam pemakaian
- Hapus jam pemakaian
- Lihat daftar jam pemakaian

### 4.4 Manajemen Program Studi

Program studi digunakan untuk mengelompokkan kelas.

Field utama:

- Kode prodi
- Nama prodi
- Fakultas / jurusan
- Status aktif

Fitur:

- Tambah prodi
- Edit prodi
- Hapus prodi
- Lihat daftar prodi

### 4.5 Manajemen Kelas

Data kelas digunakan sebagai pengguna ruang kuliah.

Field utama:

- Kode kelas
- Nama kelas
- Program studi
- Angkatan
- Semester
- Jumlah mahasiswa
- Status aktif

Contoh:

- TI-3A
- SI-5B
- MI-1A

Fitur:

- Tambah kelas
- Edit kelas
- Hapus kelas
- Lihat daftar kelas

### 4.6 Manajemen Jadwal Ruang

Jadwal ruang adalah data inti yang menghubungkan ruang, kelas, prodi, hari, jam, dan tahun akademik.

Field utama:

- Ruang
- Kelas
- Program studi
- Hari
- Jam pemakaian
- Mata kuliah
- Dosen
- Semester
- Tahun akademik
- Status jadwal

Fitur:

- Tambah jadwal
- Edit jadwal
- Hapus jadwal
- Lihat jadwal
- Filter berdasarkan ruang
- Filter berdasarkan prodi
- Filter berdasarkan kelas
- Filter berdasarkan hari
- Filter berdasarkan tahun akademik
- Validasi bentrok jadwal

---

## 5. Aturan Validasi Jadwal

Sistem harus melakukan validasi sebelum jadwal disimpan.

### 5.1 Validasi Bentrok Ruang

Tidak boleh ada dua jadwal yang menggunakan ruang yang sama pada hari, jam, semester, dan tahun akademik yang sama.

Contoh bentrok:

- Ruang A101
- Senin
- 07:30 - 09:10
- Tahun akademik 2025/2026

Jika sudah digunakan oleh TI-3A, maka kelas lain tidak boleh memakai ruang tersebut pada slot yang sama.

### 5.2 Validasi Bentrok Kelas

Satu kelas tidak boleh memiliki dua jadwal pada hari dan jam yang sama.

### 5.3 Validasi Kapasitas Ruang

Jumlah mahasiswa dalam kelas tidak boleh melebihi kapasitas ruang.

Contoh:

- Kapasitas ruang: 40
- Jumlah mahasiswa kelas: 45
- Sistem menolak atau memberi peringatan

### 5.4 Validasi Data Aktif

Jadwal hanya boleh menggunakan data aktif:

- Ruang aktif
- Prodi aktif
- Kelas aktif
- Jam pemakaian aktif
- Tahun akademik aktif

---

## 6. Rancangan Dashboard

### 6.1 Dashboard Admin

Dashboard admin menampilkan ringkasan data sistem.

Komponen:

- Total ruang
- Total prodi
- Total kelas
- Total jadwal
- Jadwal hari ini
- Ruang sedang digunakan
- Tabel jadwal terbaru

### 6.2 Dashboard Operator Prodi

Dashboard operator menampilkan data sesuai prodi.

Komponen:

- Total kelas pada prodi tersebut
- Total jadwal prodi
- Jadwal hari ini
- Daftar jadwal aktif

### 6.3 Dashboard Viewer

Dashboard viewer dibuat untuk menampilkan penggunaan ruang secara mudah dibaca.

Konsep tampilan:

- Setiap ruang ditampilkan dalam bentuk card.
- Satu card mewakili satu ruang kuliah.
- Card menampilkan nama ruang, kapasitas, lokasi, dan status penggunaan.
- Kelas pemakai ruang ditampilkan sebagai running text di dalam card.
- Running text dibuat menggunakan CSS animation, bukan tag `<marquee>`.

Informasi di dalam card ruang:

- Nama ruang
- Kapasitas
- Lokasi / gedung
- Status: Kosong / Dipakai / Akan Dipakai
- Kelas pemakai
- Prodi pemakai
- Jam pemakaian
- Mata kuliah

Contoh card viewer:

```text
+----------------------------------+
| Ruang A101                       |
| Kapasitas: 40                    |
| Status: Dipakai                  |
|                                  |
| TI-3A | Teknik Informatika       |
| Pemrograman Web | 08:00-09:40    |
|                                  |
| Running text:                    |
| TI-3A - Teknik Informatika ...   |
+----------------------------------+
```

Contoh CSS running text:

```css
.running-text-wrapper {
    width: 100%;
    overflow: hidden;
    white-space: nowrap;
}

.running-text {
    display: inline-block;
    padding-left: 100%;
    animation: runningText 12s linear infinite;
}

@keyframes runningText {
    from {
        transform: translateX(0);
    }
    to {
        transform: translateX(-100%);
    }
}
```

---

## 7. Struktur Folder Project

Struktur folder dibuat modular agar mudah dicek, dikembangkan, dan dipindahkan ke hosting.

```text
penjadwalan-ruang/
├── app/
│   ├── config/
│   │   ├── app.php
│   │   └── database.php
│   │
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── RuangController.php
│   │   ├── ProdiController.php
│   │   ├── KelasController.php
│   │   ├── JamController.php
│   │   ├── JadwalController.php
│   │   └── UserController.php
│   │
│   ├── models/
│   │   ├── User.php
│   │   ├── Role.php
│   │   ├── Ruang.php
│   │   ├── Prodi.php
│   │   ├── Kelas.php
│   │   ├── JamPemakaian.php
│   │   ├── Jadwal.php
│   │   └── TahunAkademik.php
│   │
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── header.php
│   │   │   ├── sidebar.php
│   │   │   ├── navbar.php
│   │   │   ├── footer.php
│   │   │   └── master.php
│   │   │
│   │   ├── auth/
│   │   │   └── login.php
│   │   │
│   │   ├── dashboard/
│   │   │   ├── admin.php
│   │   │   ├── operator.php
│   │   │   └── viewer.php
│   │   │
│   │   ├── ruang/
│   │   │   ├── index.php
│   │   │   ├── create.php
│   │   │   └── edit.php
│   │   │
│   │   ├── prodi/
│   │   │   ├── index.php
│   │   │   ├── create.php
│   │   │   └── edit.php
│   │   │
│   │   ├── kelas/
│   │   │   ├── index.php
│   │   │   ├── create.php
│   │   │   └── edit.php
│   │   │
│   │   ├── jam/
│   │   │   ├── index.php
│   │   │   ├── create.php
│   │   │   └── edit.php
│   │   │
│   │   ├── jadwal/
│   │   │   ├── index.php
│   │   │   ├── create.php
│   │   │   └── edit.php
│   │   │
│   │   └── users/
│   │       ├── index.php
│   │       ├── create.php
│   │       └── edit.php
│   │
│   ├── helpers/
│   │   ├── auth_helper.php
│   │   ├── url_helper.php
│   │   └── flash_helper.php
│   │
│   └── middleware/
│       ├── AuthMiddleware.php
│       └── RoleMiddleware.php
│
├── public/
│   ├── assets/
│   │   ├── adminlte/
│   │   ├── css/
│   │   │   └── custom.css
│   │   ├── js/
│   │   │   └── custom.js
│   │   └── img/
│   │
│   ├── index.php
│   └── .htaccess
│
├── routes/
│   └── web.php
│
├── database/
│   └── penjadwalan_ruang.sql
│
├── storage/
│   └── logs/
│
├── .htaccess
├── design.md
└── README.md
```

---

## 8. Konsep Routing

Sistem menggunakan `public/index.php` sebagai front controller.

Semua request akan diarahkan ke `public/index.php`, kemudian diproses berdasarkan route yang didefinisikan di `routes/web.php`.

Contoh URL:

```text
/login
/dashboard
/ruang
/ruang/create
/ruang/edit/1
/prodi
/kelas
/jam
/jadwal
/viewer
/logout
```

URL tidak menggunakan ekstensi `.php`.

---

## 9. Konfigurasi .htaccess

### 9.1 .htaccess di Root Project

File `.htaccess` di root digunakan untuk mengarahkan seluruh request ke folder `public`.

```apache
RewriteEngine On
RewriteRule ^$ public/ [L]
RewriteRule (.*) public/$1 [L]
```

### 9.2 .htaccess di Folder Public

File `public/.htaccess` digunakan untuk menghilangkan ekstensi `.php` dan mengarahkan request ke `index.php`.

```apache
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.+)$ index.php?url=$1 [QSA,L]
```

Catatan hosting:

- Pastikan Apache mengaktifkan `mod_rewrite`.
- Pastikan `AllowOverride All` aktif jika menggunakan VPS atau konfigurasi Apache sendiri.
- Pada shared hosting, isi folder `public` dapat dijadikan root document.

---

## 10. Rancangan Database

Database menggunakan MySQL / MariaDB.

Nama database:

```text
penjadwalan_ruang
```

### 10.1 Tabel roles

```sql
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL
);
```

Data awal:

```sql
INSERT INTO roles (name, description) VALUES
('admin', 'Akses penuh sistem'),
('operator_prodi', 'Operator program studi'),
('viewer', 'Hanya melihat dashboard viewer');
```

### 10.2 Tabel users

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    prodi_id INT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);
```

### 10.3 Tabel prodi

```sql
CREATE TABLE prodi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_prodi VARCHAR(20) NOT NULL UNIQUE,
    nama_prodi VARCHAR(100) NOT NULL,
    fakultas VARCHAR(100) NULL,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL
);
```

### 10.4 Tabel ruang

```sql
CREATE TABLE ruang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_ruang VARCHAR(20) NOT NULL UNIQUE,
    nama_ruang VARCHAR(100) NOT NULL,
    kapasitas INT NOT NULL,
    lokasi VARCHAR(100) NULL,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL
);
```

### 10.5 Tabel kelas

```sql
CREATE TABLE kelas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prodi_id INT NOT NULL,
    kode_kelas VARCHAR(20) NOT NULL UNIQUE,
    nama_kelas VARCHAR(100) NOT NULL,
    angkatan YEAR NULL,
    semester INT NULL,
    jumlah_mahasiswa INT DEFAULT 0,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (prodi_id) REFERENCES prodi(id)
);
```

### 10.6 Tabel jam_pemakaian

```sql
CREATE TABLE jam_pemakaian (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_jam VARCHAR(20) NOT NULL UNIQUE,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    keterangan VARCHAR(100) NULL,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL
);
```

### 10.7 Tabel tahun_akademik

```sql
CREATE TABLE tahun_akademik (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tahun VARCHAR(20) NOT NULL,
    semester ENUM('ganjil', 'genap') NOT NULL,
    status ENUM('aktif', 'nonaktif') DEFAULT 'nonaktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL
);
```

### 10.8 Tabel jadwal

```sql
CREATE TABLE jadwal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ruang_id INT NOT NULL,
    prodi_id INT NOT NULL,
    kelas_id INT NOT NULL,
    jam_id INT NOT NULL,
    tahun_akademik_id INT NOT NULL,
    hari ENUM('senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu') NOT NULL,
    mata_kuliah VARCHAR(100) NULL,
    dosen VARCHAR(100) NULL,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (ruang_id) REFERENCES ruang(id),
    FOREIGN KEY (prodi_id) REFERENCES prodi(id),
    FOREIGN KEY (kelas_id) REFERENCES kelas(id),
    FOREIGN KEY (jam_id) REFERENCES jam_pemakaian(id),
    FOREIGN KEY (tahun_akademik_id) REFERENCES tahun_akademik(id)
);
```

---

## 11. Query Validasi Bentrok Jadwal

### 11.1 Cek Bentrok Ruang

```sql
SELECT * FROM jadwal
WHERE ruang_id = :ruang_id
AND hari = :hari
AND jam_id = :jam_id
AND tahun_akademik_id = :tahun_akademik_id
AND status = 'aktif';
```

### 11.2 Cek Bentrok Kelas

```sql
SELECT * FROM jadwal
WHERE kelas_id = :kelas_id
AND hari = :hari
AND jam_id = :jam_id
AND tahun_akademik_id = :tahun_akademik_id
AND status = 'aktif';
```

### 11.3 Cek Kapasitas Ruang

```sql
SELECT r.kapasitas, k.jumlah_mahasiswa
FROM ruang r, kelas k
WHERE r.id = :ruang_id
AND k.id = :kelas_id;
```

Jika `jumlah_mahasiswa` lebih besar dari `kapasitas`, sistem memberikan peringatan atau menolak penyimpanan.

---

## 12. Rancangan Halaman

### 12.1 Login

Path:

```text
/login
```

Komponen:

- Input email
- Input password
- Tombol login
- Validasi error

### 12.2 Dashboard Admin

Path:

```text
/dashboard
```

Komponen:

- Card total ruang
- Card total prodi
- Card total kelas
- Card total jadwal
- Tabel jadwal terbaru

### 12.3 Data Ruang

Path:

```text
/ruang
```

Komponen:

- Tabel ruang
- Tombol tambah
- Tombol edit
- Tombol hapus
- Search

### 12.4 Data Prodi

Path:

```text
/prodi
```

Komponen:

- Tabel prodi
- Tombol tambah
- Tombol edit
- Tombol hapus

### 12.5 Data Kelas

Path:

```text
/kelas
```

Komponen:

- Tabel kelas
- Filter prodi
- Tombol tambah
- Tombol edit
- Tombol hapus

### 12.6 Data Jam Pemakaian

Path:

```text
/jam
```

Komponen:

- Tabel jam pemakaian
- Tombol tambah
- Tombol edit
- Tombol hapus

### 12.7 Data Jadwal

Path:

```text
/jadwal
```

Komponen:

- Tabel jadwal
- Filter ruang
- Filter prodi
- Filter kelas
- Filter hari
- Filter tahun akademik
- Tombol tambah
- Tombol edit
- Tombol hapus

### 12.8 Dashboard Viewer Ruang

Path:

```text
/viewer
```

Komponen:

- Grid card ruang
- Status ruang
- Running text kelas pemakai
- Auto refresh opsional setiap beberapa menit

---

## 13. Contoh Struktur Card Viewer

Contoh HTML card menggunakan AdminLTE:

```html
<div class="col-md-4">
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Ruang A101</h3>
        </div>
        <div class="card-body">
            <p><strong>Kapasitas:</strong> 40</p>
            <p><strong>Lokasi:</strong> Gedung A Lantai 1</p>
            <p><strong>Status:</strong> <span class="badge badge-success">Dipakai</span></p>

            <div class="running-text-wrapper">
                <div class="running-text">
                    TI-3A | Teknik Informatika | Pemrograman Web | 08:00 - 09:40
                </div>
            </div>
        </div>
    </div>
</div>
```

---

## 14. Contoh Route

File:

```text
routes/web.php
```

Contoh route:

```php
<?php

return [
    'GET /login' => 'AuthController@login',
    'POST /login' => 'AuthController@authenticate',
    'GET /logout' => 'AuthController@logout',

    'GET /dashboard' => 'DashboardController@index',
    'GET /viewer' => 'DashboardController@viewer',

    'GET /ruang' => 'RuangController@index',
    'GET /ruang/create' => 'RuangController@create',
    'POST /ruang/store' => 'RuangController@store',
    'GET /ruang/edit' => 'RuangController@edit',
    'POST /ruang/update' => 'RuangController@update',
    'POST /ruang/delete' => 'RuangController@delete',

    'GET /prodi' => 'ProdiController@index',
    'GET /kelas' => 'KelasController@index',
    'GET /jam' => 'JamController@index',
    'GET /jadwal' => 'JadwalController@index',
];
```

---

## 15. Rancangan Controller

### 15.1 AuthController

Tanggung jawab:

- Menampilkan halaman login
- Memproses login
- Membuat session
- Logout

### 15.2 DashboardController

Tanggung jawab:

- Menampilkan dashboard berdasarkan role
- Menampilkan dashboard viewer
- Mengambil data jadwal hari ini
- Mengambil status ruang

### 15.3 RuangController

Tanggung jawab:

- Menampilkan data ruang
- Menambah ruang
- Mengubah ruang
- Menghapus ruang

### 15.4 JadwalController

Tanggung jawab:

- Menampilkan data jadwal
- Menambah jadwal
- Mengubah jadwal
- Menghapus jadwal
- Melakukan validasi bentrok jadwal
- Melakukan validasi kapasitas ruang

---

## 16. Rancangan Model

Model bertugas berkomunikasi dengan database.

Contoh method model Jadwal:

```php
class Jadwal
{
    public function all()
    {
        // mengambil semua jadwal
    }

    public function find($id)
    {
        // mengambil jadwal berdasarkan id
    }

    public function create($data)
    {
        // menyimpan jadwal baru
    }

    public function update($id, $data)
    {
        // memperbarui jadwal
    }

    public function delete($id)
    {
        // menghapus jadwal
    }

    public function checkBentrokRuang($data)
    {
        // validasi bentrok ruang
    }

    public function checkBentrokKelas($data)
    {
        // validasi bentrok kelas
    }
}
```

---

## 17. Kebutuhan Keamanan

Keamanan dasar yang harus diterapkan:

1. Password disimpan menggunakan `password_hash()`.
2. Login diverifikasi menggunakan `password_verify()`.
3. Semua query database menggunakan prepared statement.
4. Session dicek pada setiap halaman private.
5. Role dicek sebelum mengakses menu tertentu.
6. Input divalidasi di sisi server.
7. Output yang ditampilkan menggunakan `htmlspecialchars()`.
8. Folder `app`, `routes`, `database`, dan `storage` tidak boleh diakses langsung dari browser.
9. Hanya folder `public` yang menjadi document root ketika hosting.

---

## 18. Kesiapan Hosting

Agar siap hosting, struktur project dibuat dengan konsep public root.

Rekomendasi hosting:

- Upload seluruh project ke hosting.
- Arahkan domain atau subdomain ke folder `public`.
- Jika shared hosting tidak mendukung document root custom, pindahkan isi folder `public` ke `public_html`, lalu sesuaikan path konfigurasi.
- Ubah konfigurasi database di `app/config/database.php`.
- Pastikan `.htaccess` aktif.
- Pastikan file sensitif tidak dapat diakses langsung.

---

## 19. Prioritas Pengembangan

Tahap pengembangan disarankan sebagai berikut:

### Tahap 1: Setup Awal

- Membuat struktur folder
- Setup koneksi database
- Setup routing dasar
- Setup AdminLTE
- Setup `.htaccess`

### Tahap 2: Autentikasi dan Role

- Login
- Logout
- Session
- Middleware auth
- Middleware role

### Tahap 3: Master Data

- CRUD ruang
- CRUD prodi
- CRUD kelas
- CRUD jam pemakaian
- CRUD tahun akademik

### Tahap 4: Jadwal

- CRUD jadwal
- Validasi bentrok ruang
- Validasi bentrok kelas
- Validasi kapasitas ruang

### Tahap 5: Dashboard Viewer

- Card per ruang
- Status penggunaan ruang
- Running text kelas pemakai
- Filter hari
- Tampilan responsif

### Tahap 6: Finalisasi Hosting

- Testing clean URL
- Testing permission folder
- Testing database hosting
- Testing role
- Testing validasi jadwal

---

## 20. Catatan Tambahan

Sistem ini dirancang sederhana tetapi tetap modular. Dengan struktur seperti ini, sistem dapat dikembangkan lebih lanjut menjadi:

- Export jadwal ke PDF
- Import data kelas dari Excel
- Filter jadwal per semester
- Jadwal berdasarkan dosen
- Tampilan TV monitor untuk viewer ruang
- Auto refresh dashboard viewer
- Notifikasi jika terjadi bentrok jadwal
- Riwayat perubahan jadwal

---

## 21. Kesimpulan

Sistem Penjadwalan Penggunaan Ruang Kuliah ini dirancang sebagai web application PHP native berbasis AdminLTE dengan database MySQL/MariaDB. Sistem mendukung CRUD ruang, jam pemakaian, kelas, program studi, user, dan jadwal.

Struktur folder dibuat modular agar mudah dicek, mudah dikembangkan, dan siap dipindahkan ke hosting. Penggunaan `.htaccess` memungkinkan URL bersih tanpa ekstensi `.php`. Dashboard viewer dirancang menampilkan ruang dalam bentuk card dengan running text kelas pemakai agar informasi penggunaan ruang mudah dilihat secara cepat.
