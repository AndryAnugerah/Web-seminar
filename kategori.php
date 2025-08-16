<?php
include 'koneksi.php'; // pastikan koneksi database sudah benar

// Query untuk mengambil semua produk
$produk = mysqli_query($koneksi, "SELECT * FROM produk");

// Cek apakah query berhasil
if (!$produk) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Go-Minar</title>
    <link rel="stylesheet" href="css/stylee.css">
    <link rel="stylesheet" href="css/kategoriii.css">
    <link rel="icon" type="image/png" href="image/seminar.png">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <!-- Tabel data produk -->
<div class ="table-container">
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Tanggal</th>

            </tr>
        </thead>
        <tbody>
            <?php if(mysqli_num_rows($produk) > 0): ?>
                <?php while($row = $produk->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                    <td><?= htmlspecialchars($row['harga']) ?></td>
                    <td><?= htmlspecialchars($row['stok']) ?></td>
                    <td><?= htmlspecialchars($row['tanggal']) ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center;">Data produk kosong</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
</body>
</html>
