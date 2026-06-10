<?php
require_once '../config/database.php';
cek_admin();

$id = (int)$_GET['id'];

mysqli_query($conn, "UPDATE denda SET status = 'lunas', tgl_bayar = CURDATE() WHERE denda_id = $id");
header('Location: denda.php?pesan=Denda berhasil dibayar');
exit;

