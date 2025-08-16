<?php
session_start();
include "koneksi.php"; // pastikan koneksi ke database

// Hanya user yang bisa akses
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'user') {
    header("Location: login.php");
    exit;
}

// Proses form jika dikirim
if (isset($_POST['pesan'])) {
    $user_id = $_SESSION['user']['id']; // ambil id user dari session
    $produk_id = (int)$_POST['seminar_id']; // sesuai kolom di tabel pesanan
    $jumlah = (int)$_POST['jumlah'];

    // Ambil stok saat ini dari tabel produk
    $stmt = $koneksi->prepare("SELECT stok FROM produk WHERE id=?");
    $stmt->bind_param("i", $produk_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $produk = $result->fetch_assoc();
    $stmt->close();

    if ($produk && $produk['stok'] >= $jumlah) {
        // Insert ke tabel pesanan
        $stmt = $koneksi->prepare("INSERT INTO pesanan (user_id, produk_id, jumlah, tanggal_pesan, status) VALUES (?, ?, ?, NOW(), 'pending')");
        $stmt->bind_param("iii", $user_id, $produk_id, $jumlah);

        if ($stmt->execute()) {
            $stmt->close();

            // Kurangi stok produk
            $stmt = $koneksi->prepare("UPDATE produk SET stok = stok - ? WHERE id=?");
            $stmt->bind_param("ii", $jumlah, $produk_id);
            $stmt->execute();
            $stmt->close();

            $success = "Tiket berhasil dipesan!";
        } else {
            $error = "Terjadi kesalahan: " . $koneksi->error;
        }
    } else {
        $error = "Stok tidak cukup untuk jumlah tiket yang dipesan!";
    }
}

// Ambil daftar seminar
$seminar = mysqli_query($koneksi, "SELECT * FROM produk"); 

// Ambil riwayat pesanan user
$user_id = $_SESSION['user']['id'];
$riwayat = mysqli_query($koneksi, "
    SELECT p.id AS pesanan_id, pr.nama_produk, p.jumlah, p.tanggal_pesan, p.status
    FROM pesanan p
    JOIN produk pr ON p.produk_id = pr.id
    WHERE p.user_id = $user_id
    ORDER BY p.tanggal_pesan DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Tiket - Go-Minar</title>
    <link rel="stylesheet" href="css/stylee.css">
    <link rel="stylesheet" href="css/homee.css">
    <link rel="stylesheet" href="css/user.css">
    <link rel="icon" type="image/png" href="image/seminar.png">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h2>Pesan Tiket Seminar</h2>

        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

        <!-- Form Pesanan -->
        <form method="POST" action="">
            <label for="seminar_id">Pilih Seminar:</label>
            <select name="seminar_id" id="seminar_id" required>
                <option value="">-- Pilih Seminar --</option>
                <?php while($row = mysqli_fetch_assoc($seminar)) : ?>
                    <option value="<?= $row['id'] ?>">
                        <?= $row['nama_produk'] ?> - Rp <?= number_format($row['harga'], 0, ',', '.') ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="jumlah">Jumlah Tiket:</label>
            <input type="number" name="jumlah" id="jumlah" min="1" value="1" required>

            <button type="submit" name="pesan">Pesan Tiket</button>
        </form>

        <!-- Riwayat Pesanan -->
        <h2>Riwayat Pesanan</h2>
        <?php if(mysqli_num_rows($riwayat) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Seminar</th>
                        <th>Jumlah</th>
                        <th>Tanggal Pesan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; while($row = mysqli_fetch_assoc($riwayat)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['nama_produk'] ?></td>
                            <td><?= $row['jumlah'] ?></td>
                            <td><?= $row['tanggal_pesan'] ?></td>
                            <td class="status-<?= strtolower($row['status']) ?>"><?= ucfirst($row['status']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Belum ada pesanan.</p>
        <?php endif; ?>
    </div>
</body>
</html>
