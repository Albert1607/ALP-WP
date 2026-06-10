<?php
require_once '../config/database.php';
cek_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    mysqli_query($conn, "INSERT INTO kategori (nama, deskripsi) VALUES ('$nama', '$deskripsi')");
    header('Location: kategori.php?pesan=Kategori berhasil ditambahkan');
    exit;
}
?>
<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Tambah Kategori - LibraFlow Admin</title>
    <link rel='stylesheet' href='/ALP-WP/assets/css/style.css'>
</head>
<body>
<div class='dashboard'>
        <div class='sidebar'>
        <div class='logo'>📚 LibraFlow</div>
        <div class='sidebar-menu'>
            <a href='dashboard.php' class=''>📊 Dashboard</a>
            <a href='buku.php' class=''>📚 Buku</a>
            <a href='kategori.php' class='active'>📂 Kategori</a>
            <a href='peminjaman.php' class=''>📋 Peminjaman</a>
            <a href='users.php' class=''>👥 Users</a>
            <a href='denda.php' class=''>💰 Denda</a>
            <div class='sidebar-divider'></div>
            <a href='../logout.php'>🚪 Logout</a>
        </div>
    </div>
<div class='main-content'>
        <div class='page-header'>
            <h2>Tambah Kategori</h2>
        </div>

        <div class='card'>
            <form method='POST'>
                <div class='form-group'>
                    <label>Nama</label>
                    <input type='text' name='nama' required>
                </div>
                <div class='form-group'>
                    <label>Deskripsi</label>
                    <textarea name='deskripsi' required></textarea>
                </div>
                <button type='submit' class='btn-primary'>Simpan</button>
                <a href='kategori.php' class='btn-danger'>Kembali</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>

