# Janji

Saya Zharfan Faza Wibawa dengan NIM 2403995 mengerjakan Tugas Praktikum 7 dalam mata kuliah Desain dan Pemrograman Berorientasi Objek untuk keberkahanNya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.

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

https://github.com/user-attachments/assets/0742a0af-d8d4-47a3-bd04-92510fe1527b

### ✅ CRUD Penyewa

- Tambah penyewa baru
- Lihat daftar semua penyewa
- Edit data penyewa
- Hapus data penyewa
- Validasi nomor HP

https://github.com/user-attachments/assets/e2c55829-1ad8-4805-962e-e7ed0259c2cb

### ✅ CRUD Transaksi

- Tambah transaksi sewa
- Lihat semua transaksi dengan data lengkap (JOIN)
- Edit transaksi
- Hapus transaksi
- Auto-calculate total harga berdasarkan durasi
- Manajemen stok otomatis (kurang saat sewa, kembali saat hapus)

https://github.com/user-attachments/assets/f4162768-295c-489f-a04a-45afe3f0415a

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
