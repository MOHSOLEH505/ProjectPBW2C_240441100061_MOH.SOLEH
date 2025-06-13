<?php
session_start();
include "koneksi.php";

// Cek login dan role
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: login.php");
    exit;
}

// Ambil nama mahasiswa untuk ditampilkan di header
$mahasiswa_id = $_SESSION['user_id'];
$mahasiswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama FROM users WHERE id = $mahasiswa_id"));

// Cek tugas_id
if (!isset($_GET['tugas_id'])) {
    die("Tugas tidak ditemukan.");
}

$tugas_id = intval($_GET['tugas_id']);
$query = mysqli_query($conn, "SELECT * FROM tugas WHERE id = $tugas_id");

if (mysqli_num_rows($query) == 0) {
    die("Tugas tidak ditemukan.");
}

$tugas = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Tugas</title>
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
    <a href="gabung_kelas_mahasiswa.php" class="btn mt-3 shadow-sm">Gabung Kelas</a>
    <a href="lihat_pengumpulan_mahasiswa.php" class="btn mt-3 shadow-sm">Lihat Pengumpulan</a>
    <a href="logout.php" class="text-center mt-3 shadow-sm">Logout</a>
</div>

<div class="content">
    <div class="card p-4 card-class">
        <h3 class="mb-3"><?= htmlspecialchars($tugas['judul']) ?></h3>

        <div class="mb-3">
            <strong>Deskripsi:</strong>
            <p><?= nl2br(htmlspecialchars($tugas['deskripsi'])) ?></p>
        </div>

        <?php if (!empty($tugas['file'])): ?>
            <div class="mb-3">
                <strong>File:</strong>
                <p><a href="uploads/<?= rawurlencode($tugas['file']) ?>" target="_blank">Buka file</a></p>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <strong>Deadline:</strong>
            <p><?= htmlspecialchars($tugas['deadline']) ?></p>
        </div>

        <a href="upload_tugas_mahasiswa.php?tugas_id=<?= $tugas['id'] ?>" class="btn btn-primary">Upload Tugas</a>
        <a href="dashboard_mahasiswa.php" class="btn btn-secondary">Kembali</a>
    </div>
</div>

</body>
</html>
