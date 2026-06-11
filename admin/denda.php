<?php
require_once '../config/database.php';
cek_admin();
?>
<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Data Denda - LibraFlow Admin</title>
    <link rel='stylesheet' href='/ALP-WP/assets/css/style.css?v=2'>
</head>
<body>
<div class='dashboard'>
        <div class='sidebar'>
        <div class='logo'> LibraFlow</div>
        <div class='sidebar-menu'>
            <a href='dashboard.php' class=''> Dashboard</a>
            <a href='buku.php' class=''> Buku</a>
            <a href='kategori.php' class=''> Kategori</a>
            <a href='peminjaman.php' class=''> Peminjaman</a>
            <a href='users.php' class=''> Users</a>
            <a href='denda.php' class='active'> Denda</a>
            <div class='sidebar-divider'></div>
            <a href='../logout.php'> Logout</a>
        </div>
    </div>
<div class='main-content'>
        <div class='page-header'>
            <h2>Data Denda</h2>
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
                        <th>Jumlah Hari</th>
                        <th>Denda/Hari</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tgl Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = mysqli_query($conn, "SELECT d.*, u.name FROM denda d JOIN peminjaman p ON d.peminjaman_id = p.peminjaman_id JOIN users u ON p.user_id = u.user_id ORDER BY d.denda_id DESC");
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($query)):
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= $row['jumlah_hari'] ?></td>
                        <td>Rp <?= number_format($row['denda_harian'], 0, ',', '.') ?></td>
                        <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                        <td>
                            <?php if ($row['status'] == 'belum_bayar'): ?>
                                <span class='badge-red'>Belum Bayar</span>
                            <?php else: ?>
                                <span class='badge-green'>Lunas</span>
                            <?php endif; ?>
                        </td>
                        <td><?= (!empty($row['tgl_bayar'])) ? date('d-m-Y', strtotime($row['tgl_bayar'])) : '-' ?></td>
                        <td>
                            <?php if ($row['status'] == 'belum_bayar'): ?>
                                <div class='aksi'>
                                    <a href='bayar_denda.php?id=<?= $row['denda_id'] ?>' class='btn-primary btn-kecil' onclick="return confirm('Konfirmasi pembayaran?')">Bayar</a>
                                </div>
                            <?php else: ?>
                                <div class='aksi'>-</div>
                            <?php endif; ?>
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

