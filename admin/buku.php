<?php
require_once '../config/database.php';
cek_admin();

$query = mysqli_query($conn, "SELECT b.*, k.nama as nama_kategori FROM buku b LEFT JOIN kategori k ON b.kategori_id = k.kategori_id ORDER BY b.buku_id DESC");
?>
<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Data Buku - LibraFlow Admin</title>
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
            <h2>Data Buku</h2>
            <a href='tambah_buku.php' class='btn-primary'>Tambah Buku</a>
        </div>

        <?php if (isset($_GET['pesan'])) : ?>
            <div class='alert-success'><?= htmlspecialchars($_GET['pesan']) ?></div>
        <?php endif; ?>

        <div class='card'>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Penerbit</th>
                        <th>Tahun</th>
                        <th>Stok</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($query)) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['judul']) ?></td>
                        <td><?= htmlspecialchars($row['penulis']) ?></td>
                        <td><?= htmlspecialchars($row['penerbit']) ?></td>
                        <td><?= $row['tahun_terbit'] ?></td>
                        <td><?= $row['stok'] ?></td>
                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                        <td>
                            <div class='aksi'>
                                <a href='edit_buku.php?id=<?= $row['buku_id'] ?>' class='btn-primary btn-kecil'>Edit</a>
                                <a href='hapus_buku.php?id=<?= $row['buku_id'] ?>' class='btn-danger btn-kecil' onclick="return confirm('Yakin hapus?')">Hapus</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="../assets/js/main.js?v=9"></script>
</body>
</html>

