<?php
require_once '../config/database.php';
cek_member();

$user_id = $_SESSION['user_id'];


$query = "SELECT * FROM peminjaman WHERE user_id = $user_id ORDER BY tgl_pinjam DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Saya - LibraFlow</title>
    <link rel="stylesheet" href="/ALP-WP/assets/css/style.css">
</head>
<body>

<div class="dashboard">
    <div class="sidebar">
        <div class="logo">📚 LibraFlow</div>
        <div class="sidebar-menu">
            <a href="/ALP-WP/member/dashboard.php">📊 Dashboard</a>
            <a href="/ALP-WP/member/buku.php">📚 Katalog Buku</a>
            <a href="/ALP-WP/member/peminjaman.php" class="active">📋 Peminjaman Saya</a>
            <a href="/ALP-WP/member/denda.php">💰 Denda Saya</a>
            <div class="sidebar-divider"></div>
            <a href="/ALP-WP/logout.php">🚪 Logout</a>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h1>Peminjaman Saya</h1>
            <div class="user-info">Halo, <strong><?= $_SESSION['name'] ?></strong></div>
        </div>

        <?php if (isset($_GET['pesan'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['pesan']) ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h2>Riwayat Peminjaman</h2>
                <a href="tambah_peminjaman.php" class="btn btn-primary btn-kecil">Pinjam Buku</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali Rencana</th>
                        <th>Tgl Kembali Aktual</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) == 0): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: #999;">Belum ada peminjaman</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= date('d-m-Y', strtotime($row['tgl_pinjam'])) ?></td>
                            <td><?= date('d-m-Y', strtotime($row['tgl_kembali_rencana'])) ?></td>
                            <td><?= $row['tgl_kembali_aktual'] ? date('d-m-Y', strtotime($row['tgl_kembali_aktual'])) : '-' ?></td>
                            <td>
                                <?php if ($row['status'] == 'aktif'): ?>
                                    <span class="badge badge-blue">Aktif</span>
                                <?php elseif ($row['status'] == 'dikembalikan'): ?>
                                    <span class="badge badge-green">Dikembalikan</span>
                                <?php elseif ($row['status'] == 'terlambat'): ?>
                                    <span class="badge badge-red">Terlambat</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="detail_peminjaman.php?id=<?= $row['peminjaman_id'] ?>" class="btn btn-primary btn-kecil">Detail</a>
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
