<?php
require_once '../config/database.php';
cek_admin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $buku_id = (int) $_POST['buku_id'];
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $penulis = mysqli_real_escape_string($conn, $_POST['penulis']);
    $penerbit = mysqli_real_escape_string($conn, $_POST['penerbit']);
    $tahun_terbit = mysqli_real_escape_string($conn, $_POST['tahun_terbit']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);
    $kategori_id = mysqli_real_escape_string($conn, $_POST['kategori_id']);

    mysqli_query($conn, "UPDATE buku SET judul='$judul', penulis='$penulis', penerbit='$penerbit', tahun_terbit='$tahun_terbit', stok='$stok', kategori_id='$kategori_id' WHERE buku_id=$buku_id");
    header('Location: buku.php?pesan=Buku berhasil diupdate');
    exit;
}

$id = (int) $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM buku WHERE buku_id = $id");
$buku = mysqli_fetch_assoc($query);

$kategori = mysqli_query($conn, "SELECT * FROM kategori");
?>
<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Edit Buku - LibraFlow Admin</title>
    <link rel='stylesheet' href='/ALP-WP/assets/css/style.css'>
</head>
<body>
<div class='dashboard'>
        <div class='sidebar'>
        <div class='logo'>📚 LibraFlow</div>
        <div class='sidebar-menu'>
            <a href='dashboard.php' class=''>📊 Dashboard</a>
            <a href='buku.php' class='active'>📚 Buku</a>
            <a href='kategori.php' class=''>📂 Kategori</a>
            <a href='peminjaman.php' class=''>📋 Peminjaman</a>
            <a href='users.php' class=''>👥 Users</a>
            <a href='denda.php' class=''>💰 Denda</a>
            <div class='sidebar-divider'></div>
            <a href='../logout.php'>🚪 Logout</a>
        </div>
    </div>
<div class='main-content'>
        <div class='page-header'>
            <h2>Edit Buku</h2>
        </div>

        <div class='card'>
            <form method='POST'>
                <input type='hidden' name='buku_id' value='<?= $buku['buku_id'] ?>'>
                <div class='form-group'>
                    <label>Judul</label>
                    <input type='text' name='judul' value='<?= htmlspecialchars($buku['judul']) ?>' required>
                </div>
                <div class='form-group'>
                    <label>Penulis</label>
                    <input type='text' name='penulis' value='<?= htmlspecialchars($buku['penulis']) ?>' required>
                </div>
                <div class='form-group'>
                    <label>Penerbit</label>
                    <input type='text' name='penerbit' value='<?= htmlspecialchars($buku['penerbit']) ?>' required>
                </div>
                <div class='form-group'>
                    <label>Tahun Terbit</label>
                    <input type='number' name='tahun_terbit' value='<?= $buku['tahun_terbit'] ?>' required>
                </div>
                <div class='form-group'>
                    <label>Stok</label>
                    <input type='number' name='stok' value='<?= $buku['stok'] ?>' required>
                </div>
                <div class='form-group'>
                    <label>Kategori</label>
                    <select name='kategori_id'>
                        <?php while ($row = mysqli_fetch_assoc($kategori)) : ?>
                            <option value='<?= $row['kategori_id'] ?>' <?= ($row['kategori_id'] == $buku['kategori_id']) ? 'selected' : '' ?>><?= htmlspecialchars($row['nama']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type='submit' class='btn-primary'>Simpan</button>
                <a href='buku.php' class='btn-danger'>Kembali</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>

