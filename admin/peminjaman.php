<?php
require_once '../config/database.php';
cek_admin();
?>
<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Peminjaman - LibraFlow Admin</title>
    <link rel='stylesheet' href='/ALP-WP/assets/css/style.css'>
</head>
<body>
<div class='dashboard'>
        <div class='sidebar'>
        <div class='logo'>📚 LibraFlow</div>
        <div class='sidebar-menu'>
            <a href='dashboard.php' class=''>📊 Dashboard</a>
            <a href='buku.php' class=''>📚 Buku</a>
            <a href='kategori.php' class=''>📂 Kategori</a>
            <a href='peminjaman.php' class='active'>📋 Peminjaman</a>
            <a href='users.php' class=''>👥 Users</a>
            <a href='denda.php' class=''>💰 Denda</a>
            <div class='sidebar-divider'></div>
            <a href='../logout.php'>🚪 Logout</a>
        </div>
    </div>
<div class='main-content'>
        <div class='page-header'>
            <h2>Data Peminjaman</h2>
            <a href='tambah_peminjaman.php' class='btn-primary'>Tambah Peminjaman</a>
        </div>

        <?php if (isset($_GET['pesan'])): ?>
            <div class='alert-success'><?= htmlspecialchars($_GET['pesan']) ?></div>
        <?php endif; ?>

        <div class='card'>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Peminjam</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali Rencana</th>
                        <th>Tgl Kembali Aktual</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = mysqli_query($conn, "SELECT p.*, u.name FROM peminjaman p JOIN users u ON p.user_id = u.user_id ORDER BY p.peminjaman_id DESC");
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($query)):
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= date('d-m-Y', strtotime($row['tgl_pinjam'])) ?></td>
                        <td><?= date('d-m-Y', strtotime($row['tgl_kembali_rencana'])) ?></td>
                        <td><?= $row['tgl_kembali_aktual'] ? date('d-m-Y', strtotime($row['tgl_kembali_aktual'])) : '-' ?></td>
                        <td>
                            <?php if ($row['status'] == 'aktif'): ?>
                                <span class='badge-blue'>Aktif</span>
                            <?php elseif ($row['status'] == 'dikembalikan'): ?>
                                <span class='badge-green'>Dikembalikan</span>
                            <?php elseif ($row['status'] == 'terlambat'): ?>
                                <span class='badge-red'>Terlambat</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class='aksi'>
                                <a href='detail_peminjaman.php?id=<?= $row['peminjaman_id'] ?>' class='btn-primary btn-kecil'>Detail</a>
                                <?php if ($row['status'] == 'aktif'): ?>
                                    <a href='pengembalian.php?id=<?= $row['peminjaman_id'] ?>' class='btn-danger btn-kecil' onclick="return confirm('Proses pengembalian?')">Kembalikan</a>
                                <?php endif; ?>
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

