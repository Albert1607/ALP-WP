<?php
require_once '../config/database.php';
cek_member();

$user_id = $_SESSION['user_id'];


if (!isset($_GET['id'])) {
    header("Location: peminjaman.php");
    exit;
}

$peminjaman_id = (int) $_GET['id'];


$query = "SELECT * FROM peminjaman WHERE peminjaman_id = $peminjaman_id AND user_id = $user_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: peminjaman.php");
    exit;
}

$peminjaman = mysqli_fetch_assoc($result);


$query_detail = "SELECT dp.*, b.judul, b.penulis 
                 FROM detail_peminjaman dp 
                 JOIN buku b ON dp.buku_id = b.buku_id 
                 WHERE dp.peminjaman_id = $peminjaman_id";
$result_detail = mysqli_query($conn, $query_detail);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Peminjaman - LibraFlow</title>
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
            <h1>Detail Peminjaman</h1>
            <div class="user-info">Halo, <strong><?= $_SESSION['name'] ?></strong></div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <h2>Informasi Peminjaman</h2>
                <a href="peminjaman.php" class="btn btn-outline btn-kecil">← Kembali</a>
            </div>
            <table>
                <tr>
                    <th style="width: 200px;">ID Peminjaman</th>
                    <td><?= $peminjaman['peminjaman_id'] ?></td>
                </tr>
                <tr>
                    <th>Tanggal Pinjam</th>
                    <td><?= date('d-m-Y', strtotime($peminjaman['tgl_pinjam'])) ?></td>
                </tr>
                <tr>
                    <th>Tanggal Kembali Rencana</th>
                    <td><?= date('d-m-Y', strtotime($peminjaman['tgl_kembali_rencana'])) ?></td>
                </tr>
                <tr>
                    <th>Tanggal Kembali Aktual</th>
                    <td><?= $peminjaman['tgl_kembali_aktual'] ? date('d-m-Y', strtotime($peminjaman['tgl_kembali_aktual'])) : '-' ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <?php if ($peminjaman['status'] == 'aktif'): ?>
                            <span class="badge badge-blue">Aktif</span>
                        <?php elseif ($peminjaman['status'] == 'dikembalikan'): ?>
                            <span class="badge badge-green">Dikembalikan</span>
                        <?php elseif ($peminjaman['status'] == 'terlambat'): ?>
                            <span class="badge badge-red">Terlambat</span>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>

        
        <div class="card">
            <div class="card-header">
                <h2>Daftar Buku Dipinjam</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Buku</th>
                        <th>Penulis</th>
                        <th>Kondisi Awal</th>
                        <th>Kondisi Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result_detail) == 0): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: #999;">Tidak ada detail buku</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; while ($detail = mysqli_fetch_assoc($result_detail)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($detail['judul']) ?></td>
                            <td><?= htmlspecialchars($detail['penulis']) ?></td>
                            <td><?= htmlspecialchars($detail['kondisi_awal']) ?></td>
                            <td><?= $detail['kondisi_kembali'] ? htmlspecialchars($detail['kondisi_kembali']) : '-' ?></td>
                            <td>
                                <?php if ($detail['status'] == 'dipinjam'): ?>
                                    <span class="badge badge-blue">Dipinjam</span>
                                <?php elseif ($detail['status'] == 'dikembalikan'): ?>
                                    <span class="badge badge-green">Dikembalikan</span>
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
