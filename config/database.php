<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "root";
$db   = "libraflow";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Mulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fungsi cek login
function cek_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /ALP-WP/login.php");
        exit;
    }
}

// Fungsi cek admin
function cek_admin() {
    cek_login();
    if ($_SESSION['role'] != 'admin') {
        header("Location: /ALP-WP/member/dashboard.php");
        exit;
    }
}

// Fungsi cek member
function cek_member() {
    cek_login();
    if ($_SESSION['role'] != 'member') {
        header("Location: /ALP-WP/admin/dashboard.php");
        exit;
    }
}
?>
