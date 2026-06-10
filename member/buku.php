<?php
require_once '../config/database.php';
cek_member();


$query = "SELECT b.*, k.nama AS nama_kategori 
          FROM buku b 
          JOIN kategori k ON b.kategori_id = k.kategori_id 
          ORDER BY b.judul ASC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Buku - LibraFlow</title>
    <link rel="stylesheet" href="/ALP-WP/assets/css/style.css">
</head>
<body>

<div class="dashboard">
    <div class="sidebar">
        <div class="logo">📚 LibraFlow</div>
        <div class="sidebar-menu">
            <a href="/ALP-WP/member/dashboard.php">📊 Dashboard</a>
            <a href="/ALP-WP/member/buku.php" class="active">📚 Katalog Buku</a>
            <a href="/ALP-WP/member/peminjaman.php">📋 Peminjaman Saya</a>
            <a href="/ALP-WP/member/denda.php">💰 Denda Saya</a>
            <div class="sidebar-divider"></div>
            <a href="/ALP-WP/logout.php">🚪 Logout</a>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h1>Katalog Buku</h1>
            <div class="user-info">Halo, <strong><?= $_SESSION['name'] ?></strong></div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>Daftar Buku</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Penerbit</th>
                        <th>Tahun</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) == 0): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; color: #999;">Belum ada buku</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['judul']) ?></td>
                            <td><?= htmlspecialchars($row['penulis']) ?></td>
                            <td><?= htmlspecialchars($row['penerbit']) ?></td>
                            <td><?= $row['tahun_terbit'] ?></td>
                            <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                            <td>
                                <?php if ($row['stok'] > 0): ?>
                                    <span class="badge badge-green">Tersedia (<?= $row['stok'] ?>)</span>
                                <?php else: ?>
                                    <span class="badge badge-red">Habis</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['stok'] > 0): ?>
                                    <a href="tambah_peminjaman.php?buku_id=<?= $row['buku_id'] ?>" class="btn btn-primary btn-kecil">Pinjam</a>
                                <?php else: ?>
                                    <button class="btn btn-kecil" disabled style="background: #e5e7eb; color: #9ca3af; border: none; cursor: not-allowed;">Pinjam</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

</body>
</html>
