<?php
require_once '../config/database.php';
cek_admin();

$id = (int)$_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM kategori WHERE kategori_id = $id");
$data = mysqli_fetch_assoc($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategori_id = (int)$_POST['kategori_id'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    mysqli_query($conn, "UPDATE kategori SET nama = '$nama', deskripsi = '$deskripsi' WHERE kategori_id = $kategori_id");
    header('Location: kategori.php?pesan=Kategori berhasil diupdate');
    exit;
}
?>
<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Edit Kategori - LibraFlow Admin</title>
    <link rel='stylesheet' href='/ALP-WP/assets/css/style.css?v=2'>
</head>
<body>
<div class='dashboard'>
        <div class='sidebar'>
        <div class='logo'> LibraFlow</div>
        <div class='sidebar-menu'>
            <a href='dashboard.php' class=''> Dashboard</a>
            <a href='buku.php' class=''> Buku</a>
            <a href='kategori.php' class='active'> Kategori</a>
            <a href='peminjaman.php' class=''> Peminjaman</a>
            <a href='users.php' class=''> Users</a>
            <a href='denda.php' class=''> Denda</a>
            <div class='sidebar-divider'></div>
            <a href='../logout.php'> Logout</a>
        </div>
    </div>
<div class='main-content'>
        <div class='page-header'>
            <h2>Edit Kategori</h2>
        </div>

        <div class='card'>
            <form method='POST'>
                <input type='hidden' name='kategori_id' value='<?= $data['kategori_id'] ?>'>
                <div class='form-group'>
                    <label>Nama</label>
                    <input type='text' name='nama' value='<?= htmlspecialchars($data['nama']) ?>' required>
                </div>
                <div class='form-group'>
                    <label>Deskripsi</label>
                    <textarea name='deskripsi' required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
                </div>
                <button type='submit' class='btn-primary'>Simpan</button>
                <a href='kategori.php' class='btn-danger'>Kembali</a>
            </form>
        </div>
    </div>
</div>
<script src="../assets/js/main.js?v=9"></script>
</body>
</html>

