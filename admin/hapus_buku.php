<?php
require_once '../config/database.php';
cek_admin();

$id = (int) $_GET['id'];
mysqli_query($conn, "DELETE FROM buku WHERE buku_id = $id");
header('Location: buku.php?pesan=Buku berhasil dihapus');
exit;

