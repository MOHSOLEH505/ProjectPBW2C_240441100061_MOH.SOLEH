<?php
session_start();
include "koneksi.php";

// Cek apakah user login dan peran dosen
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil nama dosen
$dosen = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama FROM users WHERE id = $user_id"));

// Ambil daftar kelas
$kelas = mysqli_query($conn, "SELECT * FROM kelas WHERE dosen_id = $user_id");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Dosen</title>
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
            flex-shrink: 0; /* Mencegah gambar menyusut atau memperbesar header */
        }

        .sidebar {
            position: fixed;
            top: 60px;
            left: 0;
            width: 250px;
            height: calc(100vh - 60px);
            background-color:rgb(247, 247, 247);
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

        .sidebar h1 {
            font-size: 25px;
        }

        .sidebar .active {
            background-color: #007bff;
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

        .sidebar-link {
            display: block;
            padding: 15px 20px;
            border-radius: 10px;
            color: #333;
            text-decoration: none;
            transition: 0.3s;
        }

        .sidebar-link:hover {
            background-color: #0d6efd; /* bootstrap primary */
            color: white;
        }

        .sidebar .active {
            background-color: #6c757d;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="header d-flex justify-content-between align-items-center">
    <h4 class="text-center mb-0">Selamat Datang, <strong><?= htmlspecialchars($dosen['nama'] ?? 'Dosen') ?></strong></h4>
    <div>
        <img src="wwws.png" alt="Logo" style="height: 50px; margin-right: 15px;">
    </div>
</div>

<div class="sidebar">
    <a href="dashboard_dosen.php" class="text-center mt-3 shadow-sm active">Daftar Kelas</a>
    <a href="tambah_kelas_dosen.php" class="btn mt-3 shadow-sm">Tambah Kelas</a>
    <a href="logout.php" class="text-center mt-3 shadow-sm">Logout</a>
</div>

<div class="content">
    <div class="row">
        <?php while ($k = mysqli_fetch_assoc($kelas)) : ?>
            <div class="col-md-4 mb-4">
                <div class="card card-class p-3">
                    <h5><?= htmlspecialchars($k['nama']) ?></h5>
                    <p class="text-muted"><?= htmlspecialchars($k['kode']) ?></p><br>
                    <div class="d-grid gap-2">
                        <a href="tambah_tugas_dosen.php?kelas_id=<?= $k['id'] ?>" class="btn btn-primary btn-sm">Tambah Tugas</a>
                        <a href="lihat_tugas_dosen.php?kelas_id=<?= $k['id'] ?>" class="btn btn-info btn-sm">Lihat Tugas</a>
                        <a href="hapus_kelas_dosen.php?kelas_id=<?= $k['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus kelas ini?')">Hapus</a>
                    </div>
                </div>
            </div>
        <?php endwhile ?>
    </div>
</div>

</body>
</html>
