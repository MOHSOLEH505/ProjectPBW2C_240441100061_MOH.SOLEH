<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

$pesan = "";
$dosen_id = $_SESSION['user_id'];

// Ambil nama dosen
$ambilNama = mysqli_query($conn, "SELECT nama FROM users WHERE id = $dosen_id");
$dataDosen = mysqli_fetch_assoc($ambilNama);
$nama_dosen = $dataDosen['nama'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $kode = $_POST['kode'];

    $cek = mysqli_query($conn, "SELECT * FROM kelas WHERE kode = '$kode'");
    if (mysqli_num_rows($cek) > 0) {
        $pesan = "<div class='alert alert-danger'>Kode kelas sudah digunakan!</div>";
    } else {
        $insert = mysqli_query($conn, "INSERT INTO kelas (kode, nama, nama_dosen, dosen_id) VALUES ('$kode', '$nama', '$nama_dosen', $dosen_id)");
        if ($insert) {
            $pesan = "<div class='alert alert-success'>Kelas berhasil ditambahkan. <a href='dashboard_dosen.php'>Kembali ke Dashboard</a></div>";
        } else {
            $pesan = "<div class='alert alert-danger'>Gagal menambahkan kelas.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 15px 30px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .header img {
            flex-shrink: 0;
        }

        .sidebar {
            position: fixed;
            top: 60px;
            left: 0;
            width: 250px;
            height: calc(100vh - 60px);
            background-color:rgb(243, 243, 243);
            border-right: 1px solid #dee2e6;
            padding-top: 20px;
        }

        .sidebar a {
            text-decoration: none;
            color: #333;
            display: block;
            padding: 15px 20px;
            border-radius: 10px;
            margin: 5px 15px;
            transition: 0.3s;
        }

        .sidebar .active {
            background-color: #6c757d;
            color: #fff;
        }

        .content {
            margin-top: 80px;
            margin-left: 250px;
            padding: 30px;
        }

        .form-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: auto;
        }
    </style>
</head>
<body>

<div class="header d-flex justify-content-between align-items-center">
    <h4 class="text-center mb-0">Selamat Datang, <strong><?= htmlspecialchars($nama_dosen) ?></strong></h4>
    <div>
        <img src="wwws.png" alt="Logo" style="height: 50px; margin-right: 15px;">
    </div>
</div>

<div class="sidebar">
    <a href="dashboard_dosen.php" class="text-center mt-3 shadow-sm">Daftar Kelas</a>
    <a href="tambah_kelas_dosen.php" class="text-center mt-3 shadow-sm active">Tambah Kelas</a>
    <a href="logout.php" class="text-center mt-3 shadow-sm">Logout</a>
</div>

<div class="content">
    <div class="form-box">
        <?= $pesan ?>
        <form method="POST">
            <div class="mb-3">
                <label> Nama Kelas</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label> Kode Kelas</label>
                <input type="text" name="kode" class="form-control" required>
            </div>
            <div class="mb-3">
                <label> Nama Dosen</label>
                <input type="text" name="nama_dosen" class="form-control" value="<?= htmlspecialchars($nama_dosen) ?>" readonly>
            </div>
            <button type="submit" class="btn btn-success">Tambah</button>
            <a href="dashboard_dosen.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

</body>
</html>
