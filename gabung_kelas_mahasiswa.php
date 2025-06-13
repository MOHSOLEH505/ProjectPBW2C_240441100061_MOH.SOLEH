<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: login.php");
    exit;
}

$pesan = "";
$mahasiswa_id = $_SESSION['user_id'];

// Ambil nama mahasiswa buat header
$mahasiswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama FROM users WHERE id = $mahasiswa_id"));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_kelas = mysqli_real_escape_string($conn, $_POST['kode_kelas']);

    // Cek apakah kelas dengan kode tersebut ada
    $kelas = mysqli_query($conn, "SELECT * FROM kelas WHERE kode = '$kode_kelas'");
    if (mysqli_num_rows($kelas) > 0) {
        $kelas_data = mysqli_fetch_assoc($kelas);
        $kelas_id = $kelas_data['id'];

        // Cek apakah sudah pernah gabung
        $cek = mysqli_query($conn, "SELECT * FROM kelas_mahasiswa WHERE kelas_id = $kelas_id AND mahasiswa_id = $mahasiswa_id");
        if (mysqli_num_rows($cek) == 0) {
            // Gabung kelas
            $insert = mysqli_query($conn, "INSERT INTO kelas_mahasiswa (kelas_id, mahasiswa_id) VALUES ($kelas_id, $mahasiswa_id)");
            if ($insert) {
                $pesan = "<div class='alert alert-success'>Berhasil bergabung ke kelas <strong>{$kelas_data['nama']}</strong>.</div>";
            } else {
                $pesan = "<div class='alert alert-danger'>Gagal bergabung ke kelas.</div>";
            }
        } else {
            $pesan = "<div class='alert alert-warning'>Kamu sudah tergabung dalam kelas ini.</div>";
        }
    } else {
        $pesan = "<div class='alert alert-danger'>Kode kelas tidak ditemukan.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Gabung Kelas</title>
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
        .sidebar {
            position: fixed;
            top: 60px;
            left: 0;
            width: 250px;
            height: calc(100vh - 60px);
            background-color: #f7f7f7;
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
        .card-class {
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: 0.3s;
        }
    </style>
</head>
<body>

<div class="header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Selamat Datang, <strong><?= htmlspecialchars($mahasiswa['nama'] ?? 'Mahasiswa') ?></strong></h4>
    <div>
        <img src="wwws.png" alt="Logo" style="height: 50px; margin-right: 15px;">
    </div>
</div>

<div class="sidebar">
    <a href="dashboard_mahasiswa.php" class="text-center mt-3 shadow-sm">Daftar Kelas</a>
    <a href="gabung_kelas_mahasiswa.php" class="btn mt-3 shadow-sm active">Gabung Kelas</a>
    <a href="lihat_pengumpulan_mahasiswa.php" class="btn mt-3 shadow-sm">Lihat Pengumpulan</a>
    <a href="logout.php" class="text-center mt-3 shadow-sm">Logout</a>
</div>

<div class="content">
    <div class="card p-4 card-class">
        <h3 class="mb-4">Gabung Kelas</h3>
        <?= $pesan ?>
        <form method="POST">
            <div class="mb-3">
                <label>Kode Kelas</label>
                <input type="text" name="kode_kelas" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Gabung</button>
            <a href="dashboard_mahasiswa.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

</body>
</html>
