

CREATE DATABASE IF NOT EXISTS db_toko2;
USE db_toko2;

CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50) UNIQUE NOT NULL,
    password   VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS buku (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    judul       VARCHAR(200) NOT NULL,
    pengarang   VARCHAR(100) NOT NULL,
    tahun       YEAR        NOT NULL,
    genre       VARCHAR(50) NOT NULL,
    stok        INT         NOT NULL DEFAULT 0,
    harga       DECIMAL(10,2) NOT NULL,
    tersedia    TINYINT(1)  NOT NULL DEFAULT 1
);

INSERT INTO buku (judul, pengarang, tahun, genre, stok, harga, tersedia) VALUES
('Laskar Pelangi',  'Andrea Hirata',          2005, 'Novel',             15,  75000, 1),
('Bumi Manusia',    'Pramoedya Ananta Toer',  1980, 'Sejarah',            8,  95000, 1),
('The Alchemist',   'Paulo Coelho',           1988, 'Fiksi',             20,  85000, 1),
('Atomic Habits',   'James Clear',            2018, 'Pengembangan Diri', 12, 110000, 1),
('Harry Potter',    'J.K. Rowling',           1997, 'Fantasi',            0, 120000, 0);
