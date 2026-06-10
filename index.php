<?php
$site_name = "LibraFlow";
$total_buku = 1240;
$total_anggota = 320;
$total_dipinjam = 85;

$kategori = [
    ["nama" => "Fiksi",      "icon" => "📖"],
    ["nama" => "Sains",      "icon" => "🔬"],
    ["nama" => "Teknologi",  "icon" => "💻"],
    ["nama" => "Sejarah",    "icon" => "🏛️"],
    ["nama" => "Psikologi",  "icon" => "🧠"],
    ["nama" => "Agama",      "icon" => "🌙"],
];

$buku_populer = [
    ["judul" => "Laskar Pelangi",  "pengarang" => "Andrea Hirata",            "kategori" => "Fiksi",     "stok" => 3],
    ["judul" => "Bumi Manusia",    "pengarang" => "Pramoedya Ananta Toer",    "kategori" => "Sastra",    "stok" => 2],
    ["judul" => "Sapiens",         "pengarang" => "Yuval Noah Harari",        "kategori" => "Sejarah",   "stok" => 5],
    ["judul" => "Atomic Habits",   "pengarang" => "James Clear",              "kategori" => "Psikologi", "stok" => 0],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $site_name ?> - Sistem Peminjaman Buku</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>


<nav class="navbar">
    <div class="container">
        <div class="logo">📚 <?= $site_name ?></div>
        <ul class="nav-menu">
            <li><a href="#beranda">Beranda</a></li>
            <li><a href="#kategori">Kategori</a></li>
            <li><a href="#buku">Koleksi</a></li>
            <li><a href="#cara-kerja">Cara Kerja</a></li>
        </ul>
        <div class="nav-buttons">
            <a href="login.php"    class="btn btn-outline">Masuk</a>
            <a href="register.php" class="btn btn-primary">Daftar</a>
        </div>
    </div>
</nav>


<section class="hero" id="beranda">
    <div class="container">
        <div class="hero-text">
            <h1>Sistem Peminjaman Buku<br><span class="green">Digital & Mudah</span></h1>
            <p>Pinjam buku favoritmu kapan saja. Pantau status peminjaman, tanggal kembali, dan riwayat buku dengan mudah.</p>
            <div class="hero-buttons">
                <a href="register.php" class="btn btn-primary btn-besar">Daftar Sekarang</a>
                <a href="#buku"        class="btn btn-outline btn-besar">Lihat Koleksi</a>
            </div>
        </div>

        
        <div class="hero-stats">
            <div class="stat-box">
                <div class="stat-angka"><?= number_format($total_buku) ?></div>
                <div class="stat-label">Total Buku</div>
            </div>
            <div class="stat-box">
                <div class="stat-angka"><?= number_format($total_anggota) ?></div>
                <div class="stat-label">Anggota</div>
            </div>
            <div class="stat-box">
                <div class="stat-angka"><?= number_format($total_dipinjam) ?></div>
                <div class="stat-label">Sedang Dipinjam</div>
            </div>
        </div>
    </div>
</section>


<section class="section bg-abu" id="kategori">
    <div class="container">
        <h2 class="section-title">Kategori Buku</h2>
        <p class="section-sub">Temukan buku sesuai minat dan kebutuhanmu</p>

        <div class="kategori-grid">
            <?php foreach ($kategori as $k): ?>
            <div class="kategori-card">
                <div class="kategori-icon"><?= $k['icon'] ?></div>
                <div class="kategori-nama"><?= $k['nama'] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>


<section class="section" id="buku">
    <div class="container">
        <h2 class="section-title">Buku Populer</h2>
        <p class="section-sub">Judul yang paling banyak dipinjam</p>

        <div class="buku-grid">
            <?php foreach ($buku_populer as $b): ?>
            <div class="buku-card">
                <div class="buku-cover">📗</div>
                <div class="buku-info">
                    <span class="buku-kategori"><?= $b['kategori'] ?></span>
                    <h3 class="buku-judul"><?= $b['judul'] ?></h3>
                    <p class="buku-pengarang">✍️ <?= $b['pengarang'] ?></p>
                    <div class="buku-footer">
                        <?php if ($b['stok'] > 0): ?>
                            <span class="stok tersedia">Stok: <?= $b['stok'] ?></span>
                            <a href="login.php" class="btn btn-primary btn-kecil">Pinjam</a>
                        <?php else: ?>
                            <span class="stok habis">Stok Habis</span>
                            <button class="btn btn-outline btn-kecil" disabled>Antri</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>


<section class="section bg-abu" id="cara-kerja">
    <div class="container">
        <h2 class="section-title">Cara Kerja</h2>
        <p class="section-sub">3 langkah mudah untuk meminjam buku</p>

        <div class="langkah-grid">
            <div class="langkah">
                <div class="langkah-nomor">1</div>
                <div class="langkah-icon">📝</div>
                <h3>Daftar Akun</h3>
                <p>Buat akun gratis dengan email dan nomor telepon kamu.</p>
            </div>
            <div class="langkah-panah">→</div>
            <div class="langkah">
                <div class="langkah-nomor">2</div>
                <div class="langkah-icon">🔍</div>
                <h3>Cari Buku</h3>
                <p>Cari buku berdasarkan judul, pengarang, atau kategori.</p>
            </div>
            <div class="langkah-panah">→</div>
            <div class="langkah">
                <div class="langkah-nomor">3</div>
                <div class="langkah-icon">✅</div>
                <h3>Pinjam & Nikmati</h3>
                <p>Konfirmasi peminjaman dan ambil buku di perpustakaan.</p>
            </div>
        </div>
    </div>
</section>


<section class="cta-section">
    <div class="container">
        <h2>Siap Mulai Membaca?</h2>
        <p>Bergabung sekarang dan nikmati kemudahan meminjam buku secara digital.</p>
        <a href="register.php" class="btn btn-putih btn-besar">Daftar Gratis</a>
    </div>
</section>


<footer class="footer">
    <div class="container">
        <div class="footer-isi">
            <div class="footer-brand">
                <div class="logo">📚 <?= $site_name ?></div>
                <p>Sistem Peminjaman Buku Digital untuk perpustakaan modern.</p>
            </div>
            <div class="footer-link">
                <h4>Menu</h4>
                <a href="#beranda">Beranda</a>
                <a href="#kategori">Kategori</a>
                <a href="#buku">Koleksi</a>
                <a href="login.php">Masuk</a>
            </div>
            <div class="footer-link">
                <h4>Akun</h4>
                <a href="register.php">Daftar</a>
                <a href="login.php">Masuk</a>
                <a href="profil.php">Profil</a>
            </div>
        </div>
        <div class="footer-bawah">
            <p>&copy; <?= date('Y') ?> <?= $site_name ?>. Dibuat untuk tugas Web Programming.</p>
        </div>
    </div>
</footer>

<script src="assets/js/main.js"></script>
</body>
</html>
