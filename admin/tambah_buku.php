<?php
require_once '../config/database.php';
cek_admin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $penulis = mysqli_real_escape_string($conn, $_POST['penulis']);
    $penerbit = mysqli_real_escape_string($conn, $_POST['penerbit']);
    $tahun_terbit = mysqli_real_escape_string($conn, $_POST['tahun_terbit']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);
    $kategori_id = mysqli_real_escape_string($conn, $_POST['kategori_id']);

    mysqli_query($conn, "INSERT INTO buku (judul, penulis, penerbit, tahun_terbit, stok, kategori_id) VALUES ('$judul', '$penulis', '$penerbit', '$tahun_terbit', '$stok', '$kategori_id')");
    header('Location: buku.php?pesan=Buku berhasil ditambahkan');
    exit;
}

$kategori = mysqli_query($conn, "SELECT * FROM kategori");
?>
<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Tambah Buku - LibraFlow Admin</title>
    <link rel='stylesheet' href='/ALP-WP/assets/css/style.css?v=2'>
</head>
<body>
<div class='dashboard'>
        <div class='sidebar'>
        <div class='logo'> LibraFlow</div>
        <div class='sidebar-menu'>
            <a href='dashboard.php' class=''> Dashboard</a>
            <a href='buku.php' class='active'> Buku</a>
            <a href='kategori.php' class=''> Kategori</a>
            <a href='peminjaman.php' class=''> Peminjaman</a>
            <a href='users.php' class=''> Users</a>
            <a href='denda.php' class=''> Denda</a>
            <div class='sidebar-divider'></div>
            <a href='../logout.php'> Logout</a>
        </div>
    </div>
<div class='main-content'>
        <div class='page-header'>
            <h2>Tambah Buku</h2>
        </div>

        <div class='card'>
            <form method='POST'>
                <div class='form-group'>
                    <label>Judul</label>
                    <input type='text' name='judul' required>
                </div>
                <div class='form-group'>
                    <label>Penulis</label>
                    <input type='text' name='penulis' required>
                </div>
                <div class='form-group'>
                    <label>Penerbit</label>
                    <input type='text' name='penerbit' required>
                </div>
                <div class='form-group'>
                    <label>Tahun Terbit</label>
                    <input type='number' name='tahun_terbit' required>
                </div>
                <div class='form-group'>
                    <label>Stok</label>
                    <input type='number' name='stok' required>
                </div>
                <div class='form-group'>
                    <label>Kategori</label>
                    <select name='kategori_id'>
                        <?php while ($row = mysqli_fetch_assoc($kategori)) : ?>
                            <option value='<?= $row['kategori_id'] ?>'><?= htmlspecialchars($row['nama']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type='submit' class='btn-primary'>Simpan</button>
                <a href='buku.php' class='btn-danger'>Kembali</a>
            </form>
        </div>
    </div>
</div>
<script src="../assets/js/main.js?v=9"></script>
</body>
</html>

