<?php
session_start();
include "koneksi.php";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $query = mysqli_query($koneksi, 
        "SELECT u.*, h.role 
         FROM user u 
         JOIN hak_akses h ON u.hak_akses_id = h.id 
         WHERE username='$username'");

    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);

        if ($password === $data['password']) {
            $_SESSION['user'] = $data;

            if ($data['role'] == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: user.php");
            }
            exit;
        } else {
            echo "<script>alert('Username/Password salah!');</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/stylee.css"/>
    <link rel="stylesheet" href="css/loginn.css"/>
    <link rel="icon" type="image/png" href="image/seminar.png">

</head>
<header>
    <div class="container-navbar">
        <div class="logo">
            <img src="image/seminar.png" alt="Logo" />
            <span>GO-Minar</span>
        </div>
        <ul class="ul-navbar">
            <li class="li-navbar"><a href="home.php">HOME</a></li>
            <li class="li-navbar"><a href="tentang.php">ABOUT</a></li>
            <li class="li-navbar"><a href="kategori.php">CATEGORIES</a></li>
            <li class="li-navbar"><a href="login.php">LOGIN</a></li>
        </ul>
    </div>
</header>

<body>
    <div class="container-login">
        <h3>MASUK AKUN</h3>
            <form action="" method="POST">
                <div class="form-group">
                    <input type="text" placeholder="username" name="username"/>
                </div>
                <div class="form-group">
                    <input type="password" placeholder="password" name="password"/>
                </div>
                    <input type="submit" name="login" value="Masuk Sekarang" class="btn btn-login">
            </form>
        <p>Belum punya akun?</p>
            <a href="register.php">
                <input type="submit" value="Daftar sekarang" class="btn btn-register">
        </a>
    </div>
</body>
</html>