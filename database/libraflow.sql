CREATE DATABASE libraflow;
USE libraflow;


CREATE TABLE kategori (
    kategori_id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT
);


CREATE TABLE buku (
    buku_id INT AUTO_INCREMENT PRIMARY KEY,
    kategori_id INT,
    judul VARCHAR(200) NOT NULL,
    penulis VARCHAR(150),
    penerbit VARCHAR(150),
    tahun_terbit INT,
    stok INT DEFAULT 0,
    cover_image VARCHAR(255),
    FOREIGN KEY (kategori_id) REFERENCES kategori(kategori_id)
);


CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('admin','member') DEFAULT 'member',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE peminjaman (
    peminjaman_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    tgl_pinjam DATE NOT NULL,
    tgl_kembali_rencana DATE NOT NULL,
    tgl_kembali_aktual DATE,
    status ENUM('aktif','dikembalikan','terlambat') DEFAULT 'aktif',
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);


CREATE TABLE detail_peminjaman (
    detail_peminjaman_id INT AUTO_INCREMENT PRIMARY KEY,
    peminjaman_id INT,
    buku_id INT,
    kondisi_awal VARCHAR(50) DEFAULT 'baik',
    kondisi_kembali VARCHAR(50),
    status ENUM('dipinjam','dikembalikan') DEFAULT 'dipinjam',
    FOREIGN KEY (peminjaman_id) REFERENCES peminjaman(peminjaman_id),
    FOREIGN KEY (buku_id) REFERENCES buku(buku_id)
);


CREATE TABLE denda (
    denda_id INT AUTO_INCREMENT PRIMARY KEY,
    peminjaman_id INT,
    jumlah_hari INT DEFAULT 0,
    denda_harian INT DEFAULT 1000,
    total INT DEFAULT 0,
    status ENUM('belum_bayar','lunas') DEFAULT 'belum_bayar',
    tgl_bayar DATE,
    FOREIGN KEY (peminjaman_id) REFERENCES peminjaman(peminjaman_id)
);



INSERT INTO users (name, email, password, phone, role) VALUES
('Admin', 'admin@libraflow.com', '$2y$10$SOt1GQ8A545OLW29mfq3k.4on7tK9kyCNRDn10RYnEunak3f3PSHu', '081234567890', 'admin');


INSERT INTO kategori (nama, deskripsi) VALUES
('Fiksi', 'Novel dan cerita fiksi'),
('Sains', 'Buku ilmu pengetahuan'),
('Teknologi', 'Buku tentang teknologi dan komputer'),
('Sejarah', 'Buku sejarah dan peradaban'),
('Psikologi', 'Buku tentang psikologi dan pengembangan diri'),
('Agama', 'Buku keagamaan');


INSERT INTO buku (kategori_id, judul, penulis, penerbit, tahun_terbit, stok) VALUES
(1, 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2005, 5),
(1, 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Hasta Mitra', 1980, 3),
(2, 'Sapiens', 'Yuval Noah Harari', 'Harper', 2011, 4),
(3, 'Clean Code', 'Robert C. Martin', 'Prentice Hall', 2008, 2),
(4, 'Sejarah Indonesia Modern', 'M.C. Ricklefs', 'Gadjah Mada UP', 2005, 3),
(5, 'Atomic Habits', 'James Clear', 'Avery', 2018, 6),
(1, 'Negeri 5 Menara', 'Ahmad Fuadi', 'Gramedia', 2009, 4),
(3, 'The Pragmatic Programmer', 'David Thomas', 'Addison-Wesley', 2019, 2);
