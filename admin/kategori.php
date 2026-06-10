<?php
require_once '../config/database.php';
cek_admin();
?>
<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Data Kategori - LibraFlow Admin</title>
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
            <h2>Data Kategori</h2>
            <a href='tambah_kategori.php' class='btn-primary'>Tambah Kategori</a>
        </div>

        <?php if (isset($_GET['pesan'])): ?>
            <div class='alert-success'><?= htmlspecialchars($_GET['pesan']) ?></div>
        <?php endif; ?>

        <div class='card'>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = mysqli_query($conn, "SELECT * FROM kategori ORDER BY kategori_id DESC");
                    $no = 1;
                    while ($data = mysqli_fetch_assoc($query)):
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($data['nama']) ?></td>
                        <td><?= htmlspecialchars($data['deskripsi']) ?></td>
                        <td>
                            <div class='aksi'>
                                <a href='edit_kategori.php?id=<?= $data['kategori_id'] ?>' class='btn-primary btn-kecil'>Edit</a>
                                <a href='hapus_kategori.php?id=<?= $data['kategori_id'] ?>' class='btn-danger btn-kecil' onclick="return confirm('Yakin hapus?')">Hapus</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>

