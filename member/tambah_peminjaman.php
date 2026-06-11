<?php
require_once '../config/database.php';
cek_member();

$user_id = $_SESSION['user_id'];
$preselected_buku_id = isset($_GET['buku_id']) ? (int)$_GET['buku_id'] : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $raw_pinjam = str_replace('/', '-', $_POST['tgl_pinjam']);
    $tgl_pinjam = date('Y-m-d', strtotime($raw_pinjam));
    
    $raw_kembali = str_replace('/', '-', $_POST['tgl_kembali_rencana']);
    $tgl_kembali_rencana = date('Y-m-d', strtotime($raw_kembali));
    
    if (empty($_POST['buku_id'])) {
        $error = "Pilih minimal 1 buku yang ingin dipinjam.";
    } else {
        $buku_ids = $_POST['buku_id'];
        
        
        mysqli_query($conn, "INSERT INTO peminjaman (user_id, tgl_pinjam, tgl_kembali_rencana, status) VALUES ($user_id, '$tgl_pinjam', '$tgl_kembali_rencana', 'aktif')");
        $peminjaman_id = mysqli_insert_id($conn);
        
        
        foreach ($buku_ids as $buku_id) {
            $buku_id = (int)$buku_id;
            mysqli_query($conn, "INSERT INTO detail_peminjaman (peminjaman_id, buku_id, kondisi_awal, status) VALUES ($peminjaman_id, $buku_id, 'Baik', 'dipinjam')");
            mysqli_query($conn, "UPDATE buku SET stok = stok - 1 WHERE buku_id = $buku_id");
        }
        
        header('Location: peminjaman.php?pesan=Peminjaman berhasil dilakukan');
        exit;
    }
}


$default_kembali = date('d-m-Y', strtotime('+7 days'));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjam Buku - LibraFlow</title>
    <link rel="stylesheet" href="/ALP-WP/assets/css/style.css?v=2">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body>

<div class="dashboard">
    <div class="sidebar">
        <div class="logo"> LibraFlow</div>
        <div class="sidebar-menu">
            <a href="/ALP-WP/member/dashboard.php"> Dashboard</a>
            <a href="/ALP-WP/member/buku.php"> Katalog Buku</a>
            <a href="/ALP-WP/member/peminjaman.php" class="active"> Peminjaman Saya</a>
            <a href="/ALP-WP/member/denda.php"> Denda Saya</a>
            <div class="sidebar-divider"></div>
            <a href="/ALP-WP/logout.php"> Logout</a>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h1>Pinjam Buku</h1>
            <div class="user-info">Halo, <strong><?= htmlspecialchars($_SESSION['name']) ?></strong></div>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="card">
            <form method="POST">
                <div class="form-group">
                    <label>Nama Peminjam</label>
                    <input type="text" value="<?= htmlspecialchars($_SESSION['name']) ?>" disabled>
                </div>

                <div class="form-group">
                    <label>Tanggal Pinjam (Format: dd-mm-yyyy)</label>
                    <input type="text" name="tgl_pinjam" value="<?= date('d-m-Y') ?>" placeholder="dd-mm-yyyy" required>
                </div>

                <div class="form-group">
                    <label>Tanggal Rencana Kembali (Batas Pengembalian) (Format: dd-mm-yyyy)</label>
                    <input type="text" name="tgl_kembali_rencana" value="<?= $default_kembali ?>" placeholder="dd-mm-yyyy" required>
                </div>

                <div class="form-group">
                    <label style="margin-bottom: 12px; display: block;">Pilih Buku yang Ingin Dipinjam</label>
                    <?php
                    $buku_list = mysqli_query($conn, "SELECT b.*, k.nama AS nama_kategori FROM buku b JOIN kategori k ON b.kategori_id = k.kategori_id WHERE b.stok > 0 ORDER BY b.judul ASC");
                    if (mysqli_num_rows($buku_list) == 0):
                    ?>
                        <p style="color: #ef4444; font-size: 13px; font-weight: bold;">Maaf, stok seluruh buku saat ini sedang kosong.</p>
                    <?php else: ?>
                        <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px; background: #fff;">
                            <?php while ($buku = mysqli_fetch_assoc($buku_list)): 
                                $checked = ($buku['buku_id'] == $preselected_buku_id) ? "checked" : "";
                            ?>
                                <div style="margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                                    <input type="checkbox" name="buku_id[]" value="<?= $buku['buku_id'] ?>" id="buku_<?= $buku['buku_id'] ?>" <?= $checked ?>>
                                    <label for="buku_<?= $buku['buku_id'] ?>" style="font-weight: normal; cursor: pointer; display: inline; font-size: 13px; color: #333;">
                                        <strong><?= htmlspecialchars($buku['judul']) ?></strong> oleh <?= htmlspecialchars($buku['penulis']) ?> 
                                        <span style="color: #16a34a;">(Stok: <?= $buku['stok'] ?>)</span>
                                    </label>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div style="margin-top: 24px;">
                    <button type="submit" class="btn btn-primary" <?= (mysqli_num_rows($buku_list) == 0) ? "disabled" : "" ?>>Konfirmasi Pinjam</button>
                    <a href="peminjaman.php" class="btn btn-outline" style="margin-left: 8px;">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    flatpickr("input[name='tgl_pinjam']", {
        dateFormat: "d-m-Y",
        defaultDate: "today"
    });
    flatpickr("input[name='tgl_kembali_rencana']", {
        dateFormat: "d-m-Y",
        defaultDate: new Date().fp_incr(7) // 7 hari ke depan
    });
</script>
<script src="../assets/js/main.js?v=9"></script>
</body>
</html>
