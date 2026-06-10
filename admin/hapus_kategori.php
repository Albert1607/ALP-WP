<?php
require_once '../config/database.php';
cek_admin();

$id = (int)$_GET['id'];
mysqli_query($conn, "DELETE FROM kategori WHERE kategori_id = $id");
header('Location: kategori.php?pesan=Kategori berhasil dihapus');
exit;

