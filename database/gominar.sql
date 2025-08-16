--buat database gominar
CREATE DATABASE gominar;
USE gominar;

--table hak_akses 
CREATE TABLE hak_akses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role VARCHAR(20) NOT NULL
);

--table user
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    hak_akses_id INT NOT NULL DEFAULT 2
);

--table produk dan pemesanan
ALTER TABLE user ADD CONSTRAINT fk_hak_akses FOREIGN KEY (hak_akses_id) REFERENCES hak_akses(id);

CREATE TABLE produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    harga DECIMAL(10,2) NOT NULL,
    tanggal DATE NOT NULL,
    stok INT NOT NULL
);

CREATE TABLE pesanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    produk_id INT NOT NULL,
    jumlah INT NOT NULL,
    tanggal_pesan DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'diproses', 'selesai', 'batal') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (produk_id) REFERENCES produk(id)
);

--memasukan role admin ke table hak_akses
INSERT INTO hak_akses (role) VALUES ('admin'), ('user');

-- kolom id pesanan untuk memunculkan id acak kepada user.
ALTER TABLE pesanan ADD COLUMN id_pesanan VARCHAR(20) NULL;

