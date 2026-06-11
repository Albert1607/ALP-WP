<?php
require_once '../config/database.php';
cek_admin();
?>
<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Data Users - LibraFlow Admin</title>
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
            <a href='users.php' class='active'> Users</a>
            <a href='denda.php' class=''> Denda</a>
            <div class='sidebar-divider'></div>
            <a href='../logout.php'> Logout</a>
        </div>
    </div>
<div class='main-content'>
        <div class='page-header'>
            <h2>Data Users</h2>
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
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Tgl Daftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = mysqli_query($conn, "SELECT * FROM users ORDER BY user_id DESC");
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($query)):
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td>
                            <?php if ($row['role'] == 'admin'): ?>
                                <span class='badge-blue'>Admin</span>
                            <?php else: ?>
                                <span class='badge-green'>Member</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d-m-Y', strtotime($row['created_at'])) ?></td>
                        <td>
                            <?php if ($row['role'] != 'admin'): ?>
                                <div class='aksi'>
                                    <a href='hapus_user.php?id=<?= $row['user_id'] ?>' class='btn-danger btn-kecil' onclick="return confirm('Yakin hapus user ini?')">Hapus</a>
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

