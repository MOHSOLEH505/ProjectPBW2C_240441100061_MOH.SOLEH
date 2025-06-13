<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$dosen = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama FROM users WHERE id = $user_id"));

if (!isset($_GET['tugas_id'])) {
    header("Location: dosen_dashboard.php");
    exit;
}

$tugas_id = intval($_GET['tugas_id']);
$pengumpulan = mysqli_query($conn, "
    SELECT p.*, u.email 
    FROM pengumpulan p
    JOIN users u ON p.mahasiswa_id = u.id
    WHERE tugas_id = $tugas_id
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengumpulan Tugas</title>
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
            background-color: rgb(247, 247, 247);
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

        a {
            text-decoration: none;
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
    <a href="dashboard_dosen.php" class="text-center mt-3 shadow-sm">Daftar Kelas</a>
    <a href="tambah_kelas_dosen.php" class="btn mt-3 shadow-sm">Tambah Kelas</a>
    <a href="logout.php" class="text-center mt-3 shadow-sm">Logout</a>
</div>

<div class="content">
    <h3 class="mb-4">Daftar Pengumpulan Tugas</h3>
    <a href="javascript:history.back()" class="btn btn-secondary mb-3">Kembali</a>
    
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nama</th>
                <th>NIM</th>
                <th>Email</th>
                <th>File</th>
                <th>Waktu Submit</th>
                <th>Komentar</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($pengumpulan) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($pengumpulan)) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['nim']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td>
                            <a href="uploads/<?= htmlspecialchars($row['file']) ?>" target="_blank">Lihat</a>
                        </td>
                        <td><?= htmlspecialchars($row['waktu_submit']) ?></td>
                        <td><?= nl2br(htmlspecialchars($row['komentar'])) ?></td>
                    </tr>
                <?php endwhile ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">Belum ada pengumpulan.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
