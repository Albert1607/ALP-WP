<?php
require_once '../config/database.php';
cek_member();

$user_id = $_SESSION['user_id'];

// Ambil semua denda milik user ini
$query = "SELECT d.*, p.tgl_pinjam 
          FROM denda d 
          JOIN peminjaman p ON d.peminjaman_id = p.peminjaman_id 
          WHERE p.user_id = $user_id 
          ORDER BY p.tgl_pinjam DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denda Saya - LibraFlow</title>
    <link rel="stylesheet" href="/ALP-WP/assets/css/style.css">
</head>
<body>

<div class="dashboard">
    <div class="sidebar">
        <div class="logo">📚 LibraFlow</div>
        <div class="sidebar-menu">
            <a href="/ALP-WP/member/dashboard.php">📊 Dashboard</a>
            <a href="/ALP-WP/member/buku.php">📚 Katalog Buku</a>
            <a href="/ALP-WP/member/peminjaman.php">📋 Peminjaman Saya</a>
            <a href="/ALP-WP/member/denda.php" class="active">💰 Denda Saya</a>
            <div class="sidebar-divider"></div>
            <a href="/ALP-WP/logout.php">🚪 Logout</a>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h1>Denda Saya</h1>
            <div class="user-info">Halo, <strong><?= $_SESSION['name'] ?></strong></div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>Riwayat Denda</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tgl Pinjam</th>
                        <th>Jumlah Hari Telat</th>
                        <th>Denda/Hari</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tgl Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) == 0): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: #999;">Tidak ada denda</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= date('d-m-Y', strtotime($row['tgl_pinjam'])) ?></td>
                            <td><?= $row['jumlah_hari'] ?> hari</td>
                            <td>Rp <?= number_format($row['denda_harian'], 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                            <td>
                                <?php if ($row['status'] == 'belum_bayar'): ?>
                                    <span class="badge badge-red">Belum Bayar</span>
                                <?php elseif ($row['status'] == 'lunas'): ?>
                                    <span class="badge badge-green">Lunas</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $row['tgl_bayar'] ? date('d-m-Y', strtotime($row['tgl_bayar'])) : '-' ?></td>
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
