<?php
session_start();
include "koneksi.php";

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $phone    = mysqli_real_escape_string($koneksi, $_POST['phone']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    // cek konfirmasi password
    if ($password !== $confirm) {
        echo "<script>alert('Password tidak sama!'); window.history.back();</script>";
        exit;
    }

    // cek apakah username sudah ada
    $cek = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username sudah digunakan!'); window.history.back();</script>";
        exit;
    }

    // query insert data
    $query = "INSERT INTO user (username, password, nama, hak_akses_id, email, phone) 
              VALUES ('$username', '$password', '$username', 2, '$email', '$phone')";

    $insert = mysqli_query($koneksi, $query);

    if ($insert) {
        echo "<script>alert('Registrasi berhasil!'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Registrasi gagal!'); window.history.back();</script>";
        // optional debug:
        // echo "Error: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/stylee.css"/>
    <link rel="stylesheet" href="css/registeerr.css"/>
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
    <div class="container-register">
        <h3>DAFTAR AKUN</h3>
            <form action="" method="POST">
                <div class="form-group">
                    <input type="text" placeholder="username" name="username" required />
                </div>
                <div class="form-group">
                    <input type="email" placeholder="email" name="email" required />
                </div>
                <div class="form-group">
                    <input type="number" placeholder="phone" name="phone" required />
                 </div>
                 <div class="form-group">
                     <input type="password" placeholder="password" name="password" required />
                </div>
                <div class="form-group">
                     <input type="password" placeholder="confirm-password" name="confirm_password" required />
                </div>
                <div>
                    <button type="submit" name="register" class="btn btn-register">Daftar Sekarang</button>
                </div>
                </form>


            <p>Sudah punya akun?</p>
                <a href="Login.php">
                    <input type="button" value="Masuk sekarang" class="btn btn-login">
                </a>
    </div>
</body>

</html>