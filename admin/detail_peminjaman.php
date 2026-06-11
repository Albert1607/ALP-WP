<?php
require_once '../config/database.php';
cek_admin();

$id = (int)$_GET['id'];
$query = mysqli_query($conn, "SELECT p.*, u.name FROM peminjaman p JOIN users u ON p.user_id = u.user_id WHERE p.peminjaman_id = $id");
$peminjaman = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Detail Peminjaman - LibraFlow Admin</title>
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
            <a href='peminjaman.php' class='active'> Peminjaman</a>
            <a href='users.php' class=''> Users</a>
            <a href='denda.php' class=''> Denda</a>
            <div class='sidebar-divider'></div>
            <a href='../logout.php'> Logout</a>
        </div>
    </div>
<div class='main-content'>
        <div class='page-header'>
            <h2>Detail Peminjaman</h2>
        </div>

        <div class='card'>
            <div class='form-group'>
                <label>Nama Peminjam</label>
                <p><?= htmlspecialchars($peminjaman['name']) ?></p>
            </div>
            <div class='form-group'>
                <label>Tanggal Pinjam</label>
                <p><?= date('d-m-Y', strtotime($peminjaman['tgl_pinjam'])) ?></p>
            </div>
            <div class='form-group'>
                <label>Tanggal Kembali Rencana</label>
                <p><?= date('d-m-Y', strtotime($peminjaman['tgl_kembali_rencana'])) ?></p>
            </div>
            <div class='form-group'>
                <label>Tanggal Kembali Aktual</label>
                <p><?= $peminjaman['tgl_kembali_aktual'] ? date('d-m-Y', strtotime($peminjaman['tgl_kembali_aktual'])) : '-' ?></p>
            </div>
            <div class='form-group'>
                <label>Status</label>
                <p>
                    <?php if ($peminjaman['status'] == 'aktif'): ?>
                        <span class='badge-blue'>Aktif</span>
                    <?php elseif ($peminjaman['status'] == 'dikembalikan'): ?>
                        <span class='badge-green'>Dikembalikan</span>
                    <?php elseif ($peminjaman['status'] == 'terlambat'): ?>
                        <span class='badge-red'>Terlambat</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <div class='card'>
            <div class='card-header'>
                <h3>Daftar Buku Dipinjam</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Buku</th>
                        <th>Kondisi Awal</th>
                        <th>Kondisi Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $detail = mysqli_query($conn, "SELECT dp.*, b.judul FROM detail_peminjaman dp JOIN buku b ON dp.buku_id = b.buku_id WHERE dp.peminjaman_id = $id");
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($detail)):
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['judul']) ?></td>
                        <td><?= htmlspecialchars($row['kondisi_awal']) ?></td>
                        <td><?= $row['kondisi_kembali'] ? htmlspecialchars($row['kondisi_kembali']) : '-' ?></td>
                        <td>
                            <?php if ($row['status'] == 'dipinjam'): ?>
                                <span class='badge-yellow'>Dipinjam</span>
                            <?php elseif ($row['status'] == 'dikembalikan'): ?>
                                <span class='badge-green'>Dikembalikan</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <a href='peminjaman.php' class='btn-primary'>Kembali</a>
    </div>
</div>
<script src="../assets/js/main.js?v=9"></script>
</body>
</html>

