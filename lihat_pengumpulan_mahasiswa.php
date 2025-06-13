<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: login.php");
    exit;
}

$mahasiswa_id = $_SESSION['user_id'];

// Ambil nama mahasiswa untuk header
$mahasiswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama FROM users WHERE id = $mahasiswa_id"));

$query = mysqli_query($conn, "
    SELECT p.*, t.judul
    FROM pengumpulan p
    JOIN tugas t ON p.tugas_id = t.id
    WHERE p.mahasiswa_id = $mahasiswa_id
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tugas yang Dikumpulkan</title>
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
    <a href="lihat_pengumpulan_mahasiswa.php" class="btn mt-3 shadow-sm active">Lihat Pengumpulan</a>
    <a href="logout.php" class="text-center mt-3 shadow-sm">Logout</a>
</div>

<div class="content">
    <div class="card p-4 card-class">
        <h3 class="mb-4">Tugas yang Sudah Dikumpulkan</h3>

        <?php if (mysqli_num_rows($query) > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Judul Tugas</th>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>File</th>
                            <th>Waktu Submit</th>
                            <th>Komentar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['judul']) ?></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['nim']) ?></td>
                                <td><a href="uploads/<?= $row['file'] ?>" target="_blank"><?= htmlspecialchars($row['file']) ?></a></td>
                                <td><?= $row['waktu_submit'] ?></td>
                                <td><?= htmlspecialchars($row['komentar']) ?></td>
                                <td>
                                    <a href="edit_pengumpulan_mahasiswa.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="hapus_pengumpulan_mahasiswa.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus tugas ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">Belum ada tugas yang dikumpulkan.</div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
