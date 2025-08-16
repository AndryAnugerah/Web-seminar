<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
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
            <?php if (isset($_SESSION['user'])): ?>
                <?php if ($_SESSION['user']['role'] == 'admin'): ?>
                    <li class="li-navbar"><a href="admin.php">APPROVAL TIKET</a></li>
                <?php else: ?>
                    <li class="li-navbar"><a href="user.php">PESAN TIKET</a></li>
                <?php endif; ?>
                <li class="li-navbar"><a href="logout.php">LOGOUT</a></li>
            <?php else: ?>
                <li class="li-navbar"><a href="login.php">LOGIN</a></li>
            <?php endif; ?>
        </ul>
    </div>
</header>