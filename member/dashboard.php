<?php
require_once '../config/database.php';
cek_member();

$user_id = $_SESSION['user_id'];


$query1 = "SELECT COUNT(*) AS total FROM peminjaman WHERE user_id = $user_id AND status = 'aktif'";
$result1 = mysqli_query($conn, $query1);
$aktif = mysqli_fetch_assoc($result1)['total'];


$query2 = "SELECT COUNT(*) AS total FROM peminjaman WHERE user_id = $user_id";
$result2 = mysqli_query($conn, $query2);
$total_pinjam = mysqli_fetch_assoc($result2)['total'];


$query3 = "SELECT COALESCE(SUM(d.total), 0) AS total_denda 
           FROM denda d 
           JOIN peminjaman p ON d.peminjaman_id = p.peminjaman_id 
           WHERE p.user_id = $user_id AND d.status = 'belum_bayar'";
$result3 = mysqli_query($conn, $query3);
$total_denda = mysqli_fetch_assoc($result3)['total_denda'];


$query4 = "SELECT * FROM peminjaman WHERE user_id = $user_id AND status = 'aktif' ORDER BY tgl_pinjam DESC";
$result4 = mysqli_query($conn, $query4);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - LibraFlow</title>
    <link rel="stylesheet" href="/ALP-WP/assets/css/style.css">
</head>
<body>

<div class="dashboard">
    <div class="sidebar">
        <div class="logo">📚 LibraFlow</div>
        <div class="sidebar-menu">
            <a href="/ALP-WP/member/dashboard.php" class="active">📊 Dashboard</a>
            <a href="/ALP-WP/member/buku.php">📚 Katalog Buku</a>
            <a href="/ALP-WP/member/peminjaman.php">📋 Peminjaman Saya</a>
            <a href="/ALP-WP/member/denda.php">💰 Denda Saya</a>
            <div class="sidebar-divider"></div>
            <a href="/ALP-WP/logout.php">🚪 Logout</a>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h1>Dashboard</h1>
            <div class="user-info">Halo, <strong><?= $_SESSION['name'] ?></strong></div>
        </div>

        
        <div class="stat-cards" style="grid-template-columns: repeat(3, 1fr);">
            <div class="stat-card">
                <div class="stat-icon">📖</div>
                <div class="stat-angka"><?= $aktif ?></div>
                <div class="stat-label">Peminjaman Aktif</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📚</div>
                <div class="stat-angka"><?= $total_pinjam ?></div>
                <div class="stat-label">Total Dipinjam</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-angka">Rp <?= number_format($total_denda, 0, ',', '.') ?></div>
                <div class="stat-label">Denda Belum Bayar</div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <h2>Peminjaman Aktif</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali Rencana</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result4) == 0): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #999;">Tidak ada peminjaman aktif</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($result4)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= date('d-m-Y', strtotime($row['tgl_pinjam'])) ?></td>
                            <td><?= date('d-m-Y', strtotime($row['tgl_kembali_rencana'])) ?></td>
                            <td><span class="badge badge-blue">Aktif</span></td>
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
