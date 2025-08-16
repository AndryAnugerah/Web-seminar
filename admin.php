<?php
session_start();
require_once "koneksi.php";

// Cek hanya admin yang bisa akses
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// ====================== PRODUK ======================
// Tambah produk
if (isset($_POST['tambah'])) {
    $nama = trim($_POST['nama_produk']);
    $deskripsi = trim($_POST['deskripsi']);
    $harga = (float)$_POST['harga'];
    $stok = (int)$_POST['stok'];
    $tanggal = trim($_POST['tanggal']);

    if (!empty($tanggal)) {
        $stmt = $koneksi->prepare("INSERT INTO produk (nama_produk, deskripsi, harga, stok, tanggal) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdis", $nama, $deskripsi, $harga, $stok, $tanggal);
        $stmt->execute();
        $stmt->close();
        header("Location: admin.php");
        exit;
    }
}

// Hapus produk
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $stmt = $koneksi->prepare("DELETE FROM produk WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin.php");
    exit;
}

// Edit produk
$editMode = false;
$produk_edit = null;
if (isset($_GET['id'])) {
    $editMode = true;
    $id_edit = (int)$_GET['id'];
    $stmt = $koneksi->prepare("SELECT * FROM produk WHERE id = ?");
    $stmt->bind_param("i", $id_edit);
    $stmt->execute();
    $result = $stmt->get_result();
    $produk_edit = $result->fetch_assoc();
    $stmt->close();

    if (isset($_POST['update'])) {
        $nama = trim($_POST['nama_produk']);
        $deskripsi = trim($_POST['deskripsi']);
        $harga = (float)$_POST['harga'];
        $stok = (int)$_POST['stok'];
        $tanggal = trim($_POST['tanggal']);

        $stmt = $koneksi->prepare("UPDATE produk SET nama_produk=?, deskripsi=?, harga=?, stok=?, tanggal=? WHERE id=?");
        $stmt->bind_param("ssdssi", $nama, $deskripsi, $harga, $stok, $tanggal, $id_edit);
        $stmt->execute();
        $stmt->close();
        header("Location: admin.php");
        exit;
    }
}

// Baca produk
$produk = $koneksi->query("SELECT * FROM produk");

// ====================== PESANAN ======================
// Update status pesanan
if (isset($_POST['update_status'])) {
    $pesanan_id = (int)$_POST['pesanan_id'];
    $status_baru = $_POST['status'];

    $stmt = $koneksi->prepare("UPDATE pesanan SET status=? WHERE id=?");
    $stmt->bind_param("si", $status_baru, $pesanan_id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin.php");
    exit;
}

// Daftar status
$statuses = ['pending'=>'Pending', 'diproses'=>'Diproses', 'selesai'=>'Selesai', 'batal'=>'Batal'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Go-Minar</title>
    <link rel="stylesheet" href="css/stylee.css">
    <link rel="stylesheet" href="css/homee.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="icon" type="image/png" href="image/seminar.png">

</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container">

    <!-- ================== PRODUK ================== -->
    <h2>Kelola Produk</h2>

    <?php if($editMode && $produk_edit): ?>
        <h3>Edit Produk</h3>
        <form method="POST">
            <input type="text" name="nama_produk" value="<?= htmlspecialchars($produk_edit['nama_produk']) ?>" placeholder="Nama Produk" required>
            <textarea name="deskripsi" placeholder="Deskripsi"><?= htmlspecialchars($produk_edit['deskripsi']) ?></textarea>
            <input type="number" name="harga" value="<?= $produk_edit['harga'] ?>" step="0.01" placeholder="Harga" required>
            <input type="number" name="stok" value="<?= $produk_edit['stok'] ?>" placeholder="Stok" required>
            <input type="date" name="tanggal" value="<?= $produk_edit['tanggal'] ?>" required>
            <button type="submit" name="update">Update Produk</button>
        </form>
        <a href="admin.php">Batal</a>
    <?php else: ?>
        <h3>Tambah Produk</h3>
        <form method="POST">
            <input type="text" name="nama_produk" placeholder="Nama Produk" required>
            <textarea name="deskripsi" placeholder="Deskripsi"></textarea>
            <input type="number" name="harga" placeholder="Harga" step="0.01" required>
            <input type="number" name="stok" placeholder="Stok" required>
            <input type="date" name="tanggal" required>
            <button type="submit" name="tambah">Tambah Produk</button>
        </form>
    <?php endif; ?>

    <h3>Daftar Produk</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Produk</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if($produk->num_rows > 0): ?>
                <?php while($row = $produk->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                        <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                        <td><?= number_format($row['harga'], 2) ?></td>
                        <td><?= $row['stok'] ?></td>
                        <td><?= $row['tanggal'] ?></td>
                        <td>
                            <a href="admin.php?id=<?= $row['id'] ?>">Edit</a> |
                            <a href="admin.php?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin mau hapus?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align:center; font-style:italic;">Belum ada produk.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- ================== PESANAN ================== -->
    <h2>Kelola Pesanan</h2>

    <?php foreach($statuses as $status_key => $status_label):
        $stmt = $koneksi->prepare("
            SELECT p.id AS pesanan_id, u.username, pr.nama_produk, p.jumlah, p.tanggal_pesan, p.status
            FROM pesanan p
            JOIN user u ON p.user_id = u.id
            JOIN produk pr ON p.produk_id = pr.id
            WHERE p.status=?
            ORDER BY p.tanggal_pesan DESC
        ");
        $stmt->bind_param("s", $status_key);
        $stmt->execute();
        $result = $stmt->get_result();
    ?>

    <h3>Pesanan <?= $status_label ?></h3>
    <?php if($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Tanggal Pesan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr class="status-<?= strtolower($row['status']) ?>">
                    <td><?= $row['pesanan_id'] ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                    <td><?= $row['jumlah'] ?></td>
                    <td><?= $row['tanggal_pesan'] ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="pesanan_id" value="<?= $row['pesanan_id'] ?>">
                            <select name="status" onchange="this.form.submit()">
                                <?php foreach($statuses as $s_key => $s_label): ?>
                                <option value="<?= $s_key ?>" <?= $row['status']==$s_key?'selected':'' ?>><?= $s_label ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="update_status" value="1">
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Tidak ada pesanan <?= strtolower($status_label) ?>.</p>
    <?php endif; ?>
    <?php endforeach; ?>

</div>
</body>
</html>
