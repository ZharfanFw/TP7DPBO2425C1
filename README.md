# 🎵 Sistem Sewa Alat Musik

Aplikasi web berbasis PHP untuk mengelola sistem penyewaan alat musik dengan fitur CRUD lengkap menggunakan Object-Oriented Programming (OOP) dan Prepared Statement.

## 📋 Deskripsi

Sistem Sewa Alat Musik adalah aplikasi manajemen rental alat musik yang memungkinkan pengelolaan data alat musik, data penyewa, dan transaksi sewa. Aplikasi ini dibangun dengan konsep OOP murni dan menggunakan prepared statement untuk keamanan database.

## 🗄️ Struktur Database

Aplikasi ini menggunakan 3 tabel utama dengan relasi foreign key:

### 1. Tabel `alat_musik`

| Kolom      | Tipe Data     | Keterangan           |
| ---------- | ------------- | -------------------- |
| id_alat    | INT (PK, AI)  | ID unik alat musik   |
| nama       | VARCHAR(100)  | Nama alat musik      |
| jenis      | VARCHAR(100)  | Jenis/kategori alat  |
| harga_sewa | DECIMAL(10,2) | Harga sewa per hari  |
| stok       | INT           | Jumlah stok tersedia |

### 2. Tabel `penyewa`

| Kolom      | Tipe Data    | Keterangan           |
| ---------- | ------------ | -------------------- |
| id_penyewa | INT (PK, AI) | ID unik penyewa      |
| nama       | VARCHAR(100) | Nama lengkap penyewa |
| alamat     | TEXT         | Alamat lengkap       |
| no_hp      | VARCHAR(20)  | Nomor HP/telepon     |

### 3. Tabel `transaksi_sewa`

| Kolom           | Tipe Data     | Keterangan                 |
| --------------- | ------------- | -------------------------- |
| id_transaksi    | INT (PK, AI)  | ID unik transaksi          |
| id_penyewa      | INT (FK)      | Relasi ke tabel penyewa    |
| id_alat         | INT (FK)      | Relasi ke tabel alat_musik |
| tanggal_sewa    | DATE          | Tanggal mulai sewa         |
| tanggal_kembali | DATE          | Tanggal pengembalian       |
| total_harga     | DECIMAL(10,2) | Total biaya sewa           |

**Relasi:**

- `transaksi_sewa.id_penyewa` → `penyewa.id_penyewa` (ON DELETE CASCADE, ON UPDATE CASCADE)
- `transaksi_sewa.id_alat` → `alat_musik.id_alat` (ON DELETE CASCADE, ON UPDATE CASCADE)

## 📁 Struktur Folder

```
.
├── class/
│   ├── AlatMusik.php          # Class untuk manajemen alat musik
│   ├── Penyewa.php            # Class untuk manajemen penyewa
│   └── TransaksiSewa.php      # Class untuk manajemen transaksi
├── config/
│   └── db.php                 # Konfigurasi koneksi database (PDO)
├── database/
│   └── db_sewa_alat.sql       # File SQL untuk import database
├── view/
│   ├── header.php             # Template header
│   ├── footer.php             # Template footer
│   ├── alat_musik.php         # Halaman CRUD alat musik
│   ├── penyewa.php            # Halaman CRUD penyewa
│   └── transaksi.php          # Halaman CRUD transaksi
├── index.php                  # Dashboard utama
├── style.css                  # File CSS styling
└── README.md                  # Dokumentasi
```

## 🚀 Fitur Utama

### ✅ CRUD Alat Musik

- Tambah alat musik baru
- Lihat daftar semua alat musik
- Edit data alat musik
- Hapus data alat musik
- Manajemen stok otomatis

### ✅ CRUD Penyewa

- Tambah penyewa baru
- Lihat daftar semua penyewa
- Edit data penyewa
- Hapus data penyewa
- Validasi nomor HP

### ✅ CRUD Transaksi

- Tambah transaksi sewa
- Lihat semua transaksi dengan data lengkap (JOIN)
- Edit transaksi
- Hapus transaksi
- Auto-calculate total harga berdasarkan durasi
- Manajemen stok otomatis (kurang saat sewa, kembali saat hapus)

### ✅ Dashboard

- Statistik total alat musik
- Statistik total penyewa
- Statistik total transaksi
- Total pendapatan
- Daftar transaksi terbaru

## 🔒 Keamanan

- ✅ **100% Prepared Statement** - Semua query menggunakan prepared statement untuk mencegah SQL Injection
- ✅ **PDO (PHP Data Objects)** - Menggunakan PDO untuk database abstraction
- ✅ **Input Validation** - Validasi input di sisi client dan server
- ✅ **XSS Prevention** - Menggunakan `htmlspecialchars()` untuk output data

## 💻 Teknologi yang Digunakan

- **PHP 7.4+** - Backend logic dengan OOP
- **MySQL** - Database management
- **PDO** - Database connection
- **HTML5** - Structure
- **CSS3** - Styling dengan gradient modern
- **JavaScript** - Client-side validation dan interactivity

## 📦 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/username/TP7DPBO2425C1.git
cd TP7DPBO2425C1
```

### 2. Import Database

- Buka phpMyAdmin
- Create database baru atau gunakan existing
- Import file `database/db_sewa_alat.sql`

### 3. Konfigurasi Database

Edit file `config/db.php` sesuai dengan konfigurasi database Anda:

```php
private $host = "localhost";
private $username = "root";
private $password = "1234";  // Sesuaikan password Anda
private $dbname = "db_sewa_alat";
```

### 4. Jalankan Aplikasi

- Pastikan XAMPP/WAMP sudah running
- Akses melalui browser: `http://localhost/TP7DPBO2425C1/`

## 🎯 Alur Kerja Aplikasi

### Flow Transaksi Sewa:

1. User membuka halaman **Transaksi**
2. Memilih **Penyewa** dari dropdown
3. Memilih **Alat Musik** yang tersedia (stok > 0)
4. Input **Tanggal Sewa** dan **Tanggal Kembali**
5. Sistem **otomatis menghitung** total harga (harga_sewa × durasi)
6. Submit transaksi
7. Sistem **otomatis mengurangi stok** alat musik
8. Transaksi tersimpan di database

### Flow Hapus Transaksi:

1. User klik tombol **Hapus** pada transaksi
2. Konfirmasi delete muncul
3. Jika OK, sistem hapus transaksi
4. Sistem **otomatis mengembalikan stok** alat musik

## 📸 Screenshot & Demo

### Dashboard

![Dashboard](docs/dashboard.png)

- Menampilkan statistik keseluruhan
- 4 card statistik (Total Alat, Penyewa, Transaksi, Pendapatan)
- Tabel transaksi terbaru
- Quick actions button

### Halaman Alat Musik

![Alat Musik](docs/alat_musik.png)

- Form tambah/edit alat musik
- Tabel data dengan badge stok
- Button Edit & Hapus dengan konfirmasi

### Halaman Penyewa

![Penyewa](docs/penyewa.png)

- Form tambah/edit penyewa
- Tabel data penyewa lengkap
- Validasi nomor HP

### Halaman Transaksi

![Transaksi](docs/transaksi.png)

- Form dengan dropdown dinamis
- Auto-calculate total harga
- Tabel dengan JOIN data penyewa dan alat
- Manajemen stok otomatis

## 🧪 Testing

Aplikasi telah diuji dengan skenario:

- ✅ CRUD semua tabel berjalan normal
- ✅ Foreign key constraint berfungsi
- ✅ Prepared statement mencegah SQL injection
- ✅ Auto-calculate total harga akurat
- ✅ Manajemen stok otomatis berfungsi
- ✅ Validasi form berjalan baik
- ✅ Responsive design di berbagai device

## 👨‍💻 Pengembang

**[Nama Anda]**  
NIM: [NIM Anda]  
Kelas: [Kelas Anda]

## 📝 Lisensi

Project ini dibuat untuk keperluan tugas praktikum Desain dan Pemrograman Berorientasi Objek.

## 🙏 Acknowledgments

- Terima kasih kepada dosen pengampu mata kuliah DPBO
- Dokumentasi PHP Official
- Stack Overflow Community

---

⭐ **Jangan lupa berikan star jika project ini membantu!**
