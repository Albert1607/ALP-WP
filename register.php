<?php
require_once 'config/database.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error   = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $phone    = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    
    $cek = mysqli_query($conn, "SELECT user_id FROM users WHERE email = '$email'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Email sudah terdaftar!";
    } else {
        $query = "INSERT INTO users (name, email, password, phone, role) 
                  VALUES ('$name', '$email', '$password', '$phone', 'member')";
        if (mysqli_query($conn, $query)) {
            $success = "Pendaftaran berhasil! Silakan login.";
        } else {
            $error = "Gagal mendaftar. Coba lagi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - LibraFlow</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-box">
        <div class="auth-header">
            <a href="index.php" class="logo">📚 LibraFlow</a>
            <h2>Daftar Akun</h2>
            <p>Buat akun baru untuk mulai meminjam buku</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" required placeholder="Masukkan nama lengkap">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="contoh@email.com">
            </div>
            <div class="form-group">
                <label>No. Telepon</label>
                <input type="text" name="phone" placeholder="08xxxxxxxxxx">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter" minlength="6">
            </div>
            <button type="submit" class="btn btn-primary btn-besar w-full">Daftar</button>
        </form>

        <p class="auth-footer">Sudah punya akun? <a href="login.php">Masuk di sini</a></p>
    </div>
</div>

</body>
</html>
