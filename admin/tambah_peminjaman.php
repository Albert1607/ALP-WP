<?php
require_once '../config/database.php';
cek_admin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = (int)$_POST['user_id'];
    
    // Convert dd-mm-yyyy or dd/mm/yyyy to YYYY-MM-DD for database
    $raw_pinjam = str_replace('/', '-', $_POST['tgl_pinjam']);
    $tgl_pinjam = date('Y-m-d', strtotime($raw_pinjam));
    
    $raw_kembali = str_replace('/', '-', $_POST['tgl_kembali_rencana']);
    $tgl_kembali_rencana = date('Y-m-d', strtotime($raw_kembali));
    
    $buku_ids = $_POST['buku_id'];

    mysqli_query($conn, "INSERT INTO peminjaman (user_id, tgl_pinjam, tgl_kembali_rencana, status) VALUES ($user_id, '$tgl_pinjam', '$tgl_kembali_rencana', 'aktif')");
    $peminjaman_id = mysqli_insert_id($conn);

    foreach ($buku_ids as $buku_id) {
        $buku_id = (int)$buku_id;
        $kondisi_awal = mysqli_real_escape_string($conn, $_POST['kondisi_awal'][$buku_id]);
        mysqli_query($conn, "INSERT INTO detail_peminjaman (peminjaman_id, buku_id, kondisi_awal, status) VALUES ($peminjaman_id, $buku_id, '$kondisi_awal', 'dipinjam')");
        mysqli_query($conn, "UPDATE buku SET stok = stok - 1 WHERE buku_id = $buku_id");
    }

    header('Location: peminjaman.php?pesan=Peminjaman berhasil ditambahkan');
    exit;
}
?>
<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Tambah Peminjaman - LibraFlow Admin</title>
    <link rel='stylesheet' href='/ALP-WP/assets/css/style.css'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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
            <h2>Tambah Peminjaman</h2>
        </div>

        <div class='card'>
            <form method='POST'>
                <div class='form-group'>
                    <label>Anggota</label>
                    <select name='user_id' required>
                        <option value=''>-- Pilih Anggota --</option>
                        <?php
                        $members = mysqli_query($conn, "SELECT user_id, name FROM users WHERE role='member'");
                        while ($member = mysqli_fetch_assoc($members)):
                        ?>
                            <option value='<?= $member['user_id'] ?>'><?= htmlspecialchars($member['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class='form-group'>
                    <label>Tanggal Pinjam (Format: dd-mm-yyyy)</label>
                    <input type='text' name='tgl_pinjam' value='<?= date("d-m-Y") ?>' placeholder='dd-mm-yyyy' required>
                </div>

                <div class='form-group'>
                    <label>Tanggal Kembali Rencana (Format: dd-mm-yyyy)</label>
                    <input type='text' name='tgl_kembali_rencana' value='<?= date("d-m-Y", strtotime("+7 days")) ?>' placeholder='dd-mm-yyyy' required>
                </div>

                <div class='form-group'>
                    <label>Pilih Buku & Tentukan Kondisi Awal</label>
                    <?php
                    $buku_list = mysqli_query($conn, "SELECT * FROM buku WHERE stok > 0");
                    while ($buku = mysqli_fetch_assoc($buku_list)):
                    ?>
                        <div style="margin-bottom: 12px; display: flex; align-items: center; gap: 12px;">
                            <input type='checkbox' name='buku_id[]' value='<?= $buku['buku_id'] ?>' id="buku_<?= $buku['buku_id'] ?>">
                            <label for="buku_<?= $buku['buku_id'] ?>" style="font-weight: normal; margin-bottom: 0; display: inline; cursor: pointer; font-size: 13px; color: #333;">
                                <?= htmlspecialchars($buku['judul']) ?> (Stok: <?= $buku['stok'] ?>)
                            </label>
                            <select name="kondisi_awal[<?= $buku['buku_id'] ?>]" style="width: auto; padding: 4px 8px; font-size: 12px; margin-left: auto;">
                                <option value="Baik">Baik</option>
                                <option value="Rusak">Rusak</option>
                                <option value="Hilang">Hilang</option>
                            </select>
                        </div>
                    <?php endwhile; ?>
                </div>

                <button type='submit' class='btn-primary'>Simpan</button>
                <a href='peminjaman.php' class='btn-danger'>Kembali</a>
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
</body>
</html>

