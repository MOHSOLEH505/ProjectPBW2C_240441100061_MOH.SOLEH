<?php
session_start();
include "koneksi.php";

// Cek apakah user login dan merupakan mahasiswa
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: login.php");
    exit;
}

$mahasiswa_id = $_SESSION['user_id'];

// Ambil nama mahasiswa
$mahasiswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama FROM users WHERE id = $mahasiswa_id"));

// Ambil semua kelas yang diikuti mahasiswa
$query = mysqli_query($conn, "
    SELECT kelas.*, users.nama AS nama_dosen
    FROM kelas_mahasiswa 
    JOIN kelas ON kelas.id = kelas_mahasiswa.kelas_id 
    JOIN users ON kelas.dosen_id = users.id
    WHERE kelas_mahasiswa.mahasiswa_id = $mahasiswa_id
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Mahasiswa</title>
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
    <?php if (mysqli_num_rows($query) > 0): ?>
        <?php while ($kelas = mysqli_fetch_assoc($query)): ?>
            <div class="card mb-4 card-class p-3">
                <div class="card-header bg-primary text-white rounded mb-3">
                    <h5><?= htmlspecialchars($kelas['nama']) ?> (<?= htmlspecialchars($kelas['kode']) ?>)</h5>
                    <small>Dosen: <?= htmlspecialchars($kelas['nama_dosen']) ?></small>
                </div>
                <div class="card-body">
                    <h6>Tugas di kelas ini:</h6>
                    <ul class="list-group">
                        <?php
                        $kelas_id = $kelas['id'];
                        $query_tugas = mysqli_query($conn, "SELECT * FROM tugas WHERE kelas_id = $kelas_id");

                        if (mysqli_num_rows($query_tugas) > 0):
                            while ($tugas = mysqli_fetch_assoc($query_tugas)):
                        ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= htmlspecialchars($tugas['judul']) ?>
                                <a href="detail_tugas_mahasiswa.php?tugas_id=<?= $tugas['id'] ?>" class="btn btn-sm btn-outline-info">Detail</a>
                            </li>
                        <?php endwhile; else: ?>
                            <li class="list-group-item text-muted">Belum ada tugas.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-info">Kamu belum bergabung dengan kelas manapun.</div>
    <?php endif; ?>
</div>

</body>
</html>
