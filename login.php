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
            <form>
                <div class="form-group">
                    <input type="text" placeholder="username" name="username"/>
                </div>
                <div class="form-group">
                    <input type="password" placeholder="password" name="password"/>
                </div>
                    <input type="button" value="Masuk Sekarang" class="btn btn-login">
            </form>
        <p>Belum punya akun?</p>
            <a href="register.php">
                <input type="button" value="Daftar sekarang" class="btn btn-register">
        </a>
    </div>
</body>
</html>