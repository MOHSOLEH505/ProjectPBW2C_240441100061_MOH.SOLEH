<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil nama dosen
$dosen = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama FROM users WHERE id = $user_id"));

if (!isset($_GET['kelas_id'])) {
    header("Location: dashboard_dosen.php");
    exit;
}

$kelas_id = intval($_GET['kelas_id']);
$tugas = mysqli_query($conn, "SELECT * FROM tugas WHERE kelas_id = $kelas_id");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Tugas</title>
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
    <a href="lihat_tugas_dosen.php" class="text-center mt-3 shadow-sm active">Lihat Tugas</a>
    <a href="logout.php" class="text-center mt-3 shadow-sm">Logout</a>
</div>

<div class="content">
    <h3 class="mb-4">Daftar Tugas</h3>
    <a href="dashboard_dosen.php" class="btn btn-secondary mb-3">Kembali</a>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Deadline</th>
                    <th>File</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($tugas)) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                    <td><?= $row['deadline'] ?></td>
                    <td>
                        <?php if ($row['file']) : ?>
                            <a href="<?= $row['file'] ?>" target="_blank">Lihat file</a>
                        <?php else : ?>
                            Tidak ada file
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit_tugas_dosen.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="hapus_tugas_dosen.php?id=<?= $row['id'] ?>&kelas_id=<?= $kelas_id ?>" 
                           onclick="return confirm('Yakin ingin menghapus tugas ini?')" 
                           class="btn btn-danger btn-sm">Hapus</a>
                        <a href="lihat_pengumpulan_dosen.php?tugas_id=<?= $row['id'] ?>" class="btn btn-info btn-sm">Lihat Pengumpulan</a>
                    </td>
                </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
