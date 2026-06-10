<?php
$host = "localhost";
$user = "root";
$pass = "root";
$db   = "libraflow";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


function cek_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /ALP-WP/login.php");
        exit;
    }
}


function cek_admin() {
    cek_login();
    if ($_SESSION['role'] != 'admin') {
        header("Location: /ALP-WP/member/dashboard.php");
        exit;
    }
}


function cek_member() {
    cek_login();
    if ($_SESSION['role'] != 'member') {
        header("Location: /ALP-WP/admin/dashboard.php");
        exit;
    }
}
?>
