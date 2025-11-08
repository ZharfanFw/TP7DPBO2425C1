-- -----------------------------------------------------
-- DATABASE: db_sewa_alat
-- -----------------------------------------------------

CREATE DATABASE IF NOT EXISTS db_sewa_alat;
USE db_sewa_alat;

-- -----------------------------------------------------
-- TABLE: alat_musik
-- -----------------------------------------------------

CREATE TABLE alat_musik (
  id_alat INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  jenis VARCHAR(100) NOT NULL,
  harga_sewa DECIMAL(10,2) NOT NULL,
  stok INT NOT NULL
);

-- Sample Data
INSERT INTO alat_musik (nama, jenis, harga_sewa, stok) VALUES
('Gitar Akustik Yamaha', 'Gitar', 50000, 5),
('Bass Elektrik Ibanez', 'Bass', 75000, 3),
('Drum Set Pearl', 'Drum', 150000, 2),
('Keyboard Roland', 'Keyboard', 100000, 4),
('Microphone Shure SM58', 'Microphone', 30000, 10);



-- -----------------------------------------------------
-- TABLE: penyewa
-- -----------------------------------------------------

CREATE TABLE penyewa (
  id_penyewa INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  alamat TEXT NOT NULL,
  no_hp VARCHAR(20) NOT NULL
);

-- Sample Data
INSERT INTO penyewa (nama, alamat, no_hp) VALUES
('Andi Pratama', 'Jl. Melati No.12, Bandung', '081234567890'),
('Budi Santoso', 'Jl. Cendana No.8, Cimahi', '081298765432'),
('Siti Nurhaliza', 'Jl. Anggrek No.4, Bandung', '089512345678'),
('Rian Saputra', 'Jl. Kopo Sayati, Bandung', '082345678901');



-- -----------------------------------------------------
-- TABLE: transaksi_sewa
-- -----------------------------------------------------

CREATE TABLE transaksi_sewa (
  id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
  id_penyewa INT NOT NULL,
  id_alat INT NOT NULL,
  tanggal_sewa DATE NOT NULL,
  tanggal_kembali DATE NOT NULL,
  total_harga DECIMAL(10,2) NOT NULL,

  FOREIGN KEY (id_penyewa) REFERENCES penyewa(id_penyewa)
    ON DELETE CASCADE ON UPDATE CASCADE,

  FOREIGN KEY (id_alat) REFERENCES alat_musik(id_alat)
    ON DELETE CASCADE ON UPDATE CASCADE
);

-- Sample Data
INSERT INTO transaksi_sewa (id_penyewa, id_alat, tanggal_sewa, tanggal_kembali, total_harga) VALUES
(1, 1, '2025-01-10', '2025-01-12', 100000),
(2, 3, '2025-01-15', '2025-01-16', 150000),
(3, 2, '2025-01-18', '2025-01-20', 150000),
(1, 5, '2025-01-22', '2025-01-22', 30000);

