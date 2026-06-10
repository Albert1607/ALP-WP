<?php
require_once '../config/database.php';
cek_admin();

$id = (int)$_GET['id'];

$query = mysqli_query($conn, "SELECT role FROM users WHERE user_id = $id");
$user = mysqli_fetch_assoc($query);

if ($user['role'] == 'admin') {
    header('Location: users.php?pesan=Tidak bisa menghapus admin');
    exit;
}

mysqli_query($conn, "DELETE FROM users WHERE user_id = $id");
header('Location: users.php?pesan=User berhasil dihapus');
exit;

