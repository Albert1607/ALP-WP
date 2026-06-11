<?php
require_once '../config/database.php';
cek_admin();

$id = (int)$_GET['id'];


$query = mysqli_query($conn, "SELECT p.*, u.name FROM peminjaman p JOIN users u ON p.user_id = u.user_id WHERE p.peminjaman_id = $id");
$peminjaman = mysqli_fetch_assoc($query);

if (!$peminjaman) {
    header('Location: peminjaman.php');
    exit;
}

$today = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $denda_keterlambatan = 0;
    $jumlah_hari = 0;
    if ($today > $peminjaman['tgl_kembali_rencana']) {
        $status_peminjaman = 'terlambat';
        $tgl_rencana = new DateTime($peminjaman['tgl_kembali_rencana']);
        $tgl_aktual = new DateTime($today);
        $jumlah_hari = $tgl_rencana->diff($tgl_aktual)->days;
        
        
        $q_buku = mysqli_query($conn, "SELECT COUNT(*) as total_buku FROM detail_peminjaman WHERE peminjaman_id = $id");
        $jml_buku = (int)mysqli_fetch_assoc($q_buku)['total_buku'];
        
        $denda_harian = 10000; 
        $denda_keterlambatan = $jumlah_hari * $denda_harian * $jml_buku;
    } else {
        $status_peminjaman = 'dikembalikan';
    }
    
    
    mysqli_query($conn, "UPDATE peminjaman SET tgl_kembali_aktual = '$today', status = '$status_peminjaman' WHERE peminjaman_id = $id");
    
    $kondisi_kembali_arr = $_POST['kondisi_kembali']; 
    $denda_kondisi = 0;
    
    foreach ($kondisi_kembali_arr as $dp_id => $kondisi) {
        $dp_id = (int)$dp_id;
        $kondisi = mysqli_real_escape_string($conn, $kondisi);
        
        
        $q_dp = mysqli_query($conn, "SELECT buku_id FROM detail_peminjaman WHERE detail_peminjaman_id = $dp_id");
        $dp_row = mysqli_fetch_assoc($q_dp);
        $buku_id = (int)$dp_row['buku_id'];
        
        
        mysqli_query($conn, "UPDATE detail_peminjaman SET status = 'dikembalikan', kondisi_kembali = '$kondisi' WHERE detail_peminjaman_id = $dp_id");
        
        
        if ($kondisi == 'Rusak') {
            $denda_kondisi += 100000;
        } elseif ($kondisi == 'Hilang') {
            $denda_kondisi += 1000000;
        }
        
        
        if ($kondisi != 'Hilang') {
            mysqli_query($conn, "UPDATE buku SET stok = stok + 1 WHERE buku_id = $buku_id");
        }
    }
    
    
    $total_denda = $denda_keterlambatan + $denda_kondisi;
    
    if ($total_denda > 0) {
        mysqli_query($conn, "INSERT INTO denda (peminjaman_id, jumlah_hari, denda_harian, total, status) VALUES ($id, $jumlah_hari, 10000, $total_denda, 'belum_bayar')");
    }
    
    header('Location: peminjaman.php?pesan=Peminjaman berhasil dikembalikan');
    exit;
}
?>
<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Proses Pengembalian - LibraFlow Admin</title>
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
            <h2>Proses Pengembalian Buku</h2>
        </div>

        <div class='card' style="margin-bottom: 24px;">
            <div class='form-group'>
                <label>Nama Peminjam</label>
                <p><strong><?= htmlspecialchars($peminjaman['name']) ?></strong></p>
            </div>
            <div class='form-row'>
                <div class='form-group'>
                    <label>Tanggal Pinjam</label>
                    <p><?= date('d-m-Y', strtotime($peminjaman['tgl_pinjam'])) ?></p>
                </div>
                <div class='form-group'>
                    <label>Batas Pengembalian (Rencana)</label>
                    <p><?= date('d-m-Y', strtotime($peminjaman['tgl_kembali_rencana'])) ?></p>
                </div>
            </div>
        </div>

        <form method='POST'>
            <div class='card'>
                <div class='card-header'>
                    <h3>Pilih Kondisi Buku Saat Dikembalikan</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Buku</th>
                            <th>Kondisi Awal (Saat Dipinjam)</th>
                            <th>Kondisi Kembali</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $detail = mysqli_query($conn, "SELECT dp.*, b.judul FROM detail_peminjaman dp JOIN buku b ON dp.buku_id = b.buku_id WHERE dp.peminjaman_id = $id AND dp.status = 'dipinjam'");
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($detail)):
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['judul']) ?></td>
                            <td>
                                <?php if ($row['kondisi_awal'] == 'Baik'): ?>
                                    <span class="badge badge-green">Baik</span>
                                <?php elseif ($row['kondisi_awal'] == 'Rusak'): ?>
                                    <span class="badge badge-yellow">Rusak</span>
                                <?php else: ?>
                                    <span class="badge badge-red">Hilang</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <input type="hidden" name="buku_id[<?= $row['detail_peminjaman_id'] ?>]" value="<?= $row['buku_id'] ?>">
                                <select name="kondisi_kembali[<?= $row['detail_peminjaman_id'] ?>]" required style="width: auto; padding: 6px 12px; font-size: 13px;">
                                    <option value="Baik">Baik (Normal)</option>
                                    <option value="Rusak">Rusak (Denda/Ganti rugi jika ada)</option>
                                    <option value="Hilang">Hilang (Stok tidak kembali)</option>
                                </select>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 20px;">
                <button type='submit' class='btn btn-primary' onclick="return confirm('Konfirmasi pengembalian seluruh buku dengan kondisi di atas?')">Selesai & Simpan</button>
                <a href='peminjaman.php' class='btn btn-danger' style="margin-left: 8px;">Batal</a>
            </div>
        </form>
    </div>
</div>
<script src="../assets/js/main.js?v=9"></script>
</body>
</html>
