<?php
require_once '../config/database.php';
cek_admin();

// Total Buku
$q1 = mysqli_query($conn, "SELECT COUNT(*) as total FROM buku");
$total_buku = mysqli_fetch_assoc($q1)['total'];

// Total Anggota
$q2 = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='member'");
$total_anggota = mysqli_fetch_assoc($q2)['total'];

// Peminjaman Aktif
$q3 = mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman WHERE status='aktif'");
$peminjaman_aktif = mysqli_fetch_assoc($q3)['total'];

// Denda Belum Bayar
$q4 = mysqli_query($conn, "SELECT COUNT(*) as total FROM denda WHERE status='belum_bayar'");
$denda_belum = mysqli_fetch_assoc($q4)['total'];

// Peminjaman Terbaru
$q5 = mysqli_query($conn, "SELECT p.*, u.name FROM peminjaman p JOIN users u ON p.user_id = u.user_id ORDER BY p.tgl_pinjam DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Dashboard - LibraFlow Admin</title>
    <link rel='stylesheet' href='/ALP-WP/assets/css/style.css'>
</head>
<body>
<div class='dashboard'>
        <div class='sidebar'>
        <div class='logo'>📚 LibraFlow</div>
        <div class='sidebar-menu'>
            <a href='dashboard.php' class='active'>📊 Dashboard</a>
            <a href='buku.php' class=''>📚 Buku</a>
            <a href='kategori.php' class=''>📂 Kategori</a>
            <a href='peminjaman.php' class=''>📋 Peminjaman</a>
            <a href='users.php' class=''>👥 Users</a>
            <a href='denda.php' class=''>💰 Denda</a>
            <div class='sidebar-divider'></div>
            <a href='../logout.php'>🚪 Logout</a>
        </div>
    </div>
<div class='main-content'>
        <div class='stat-cards'>
            <div class='stat-card'>
                <h3>Total Buku</h3>
                <p class='stat-number'><?= $total_buku ?></p>
            </div>
            <div class='stat-card'>
                <h3>Total Anggota</h3>
                <p class='stat-number'><?= $total_anggota ?></p>
            </div>
            <div class='stat-card'>
                <h3>Peminjaman Aktif</h3>
                <p class='stat-number'><?= $peminjaman_aktif ?></p>
            </div>
            <div class='stat-card'>
                <h3>Denda Belum Bayar</h3>
                <p class='stat-number'><?= $denda_belum ?></p>
            </div>
        </div>

        <div class='card'>
            <div class='card-header'>Peminjaman Terbaru</div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($q5)) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= date('d-m-Y', strtotime($row['tgl_pinjam'])) ?></td>
                        <td><?= date('d-m-Y', strtotime($row['tgl_kembali_rencana'])) ?></td>
                        <td>
                            <?php if ($row['status'] == 'aktif') : ?>
                                <span class='badge-blue'>Aktif</span>
                            <?php elseif ($row['status'] == 'dikembalikan') : ?>
                                <span class='badge-green'>Dikembalikan</span>
                            <?php elseif ($row['status'] == 'terlambat') : ?>
                                <span class='badge-red'>Terlambat</span>
                            <?php endif; ?>
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

