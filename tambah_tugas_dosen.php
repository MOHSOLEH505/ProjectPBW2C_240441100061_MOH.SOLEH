<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['kelas_id'])) {
    header("Location: dashboard_dosen.php");
    exit;
}

$user_id = $_SESSION['user_id'];
// Ambil nama dosen untuk header
$ambilNama = mysqli_query($conn, "SELECT nama FROM users WHERE id = $user_id");
$dataDosen = mysqli_fetch_assoc($ambilNama);
$nama_dosen = $dataDosen['nama'] ?? '';

$kelas_id = intval($_GET['kelas_id']);
$pesan = "";

// Ambil nama kelas
$kelas_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama FROM kelas WHERE id = $kelas_id"));
$nama_kelas = $kelas_info['nama'] ?? 'Kelas';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $deadline = $_POST['deadline'];
    $file_name = "";

    if (!empty($_FILES['file']['name'])) {
        $target_dir = "uploads/";
        $original_name = basename($_FILES["file"]["name"]);
        $file_name = time() . "_" . $original_name;
        $target_path = $target_dir . $file_name;

        if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_path)) {
            $file_name = "";
            $pesan = "<div class='alert alert-danger'>Gagal mengunggah file.</div>";
        }
    }

    $query = "INSERT INTO tugas (kelas_id, judul, deskripsi, file, deadline) 
              VALUES ($kelas_id, '$judul', '$deskripsi', '$file_name', '$deadline')";

    if (mysqli_query($conn, $query)) {
        $pesan = "<div class='alert alert-success'>Tugas berhasil ditambahkan. <a href='lihat_tugas_dosen.php?kelas_id=$kelas_id'>Lihat Tugas</a></div>";
    } else {
        $pesan = "<div class='alert alert-danger'>Gagal menambahkan tugas.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .header {
            background-color: #007bff;
            padding: 15px 30px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .header h4 {
            color: white;
        }

        .sidebar {
            position: fixed;
            top: 60px;
            left: 0;
            width: 250px;
            height: calc(100vh - 60px);
            background-color:rgb(244, 244, 244);
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
            max-width: 700px;
            margin: auto;
        }
    </style>
</head>
<body>

<div class="header d-flex justify-content-between align-items-center">
    <h4 class="text-center mb-0">Selamat Datang, <strong><?= htmlspecialchars($nama_dosen) ?></strong></h4>
        <img src="wwws.png" alt="Logo" style="height: 50px; margin-right: 15px;">
    </div>
</div>

<div class="sidebar">
    <a href="dashboard_dosen.php" class="text-center mt-3 shadow-sm">Daftar Kelas</a>
    <a href="tambah_kelas_dosen.php" class="text-center mt-3 shadow-sm">Tambah Kelas</a>
    <a href="tambah_tugas_dosen.php" class="text-center mt-3 shadow-sm active">Tambah Tugas</a>
    <a href="logout.php" class="text-center mt-3 shadow-sm">Logout</a>
</div>

<div class="content">
    <h3 class="mb-4 text-center"><b>Tambah Tugas - <?= htmlspecialchars($nama_kelas) ?></b></h3>

    <div class="form-box">
        <?= $pesan ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Judul Tugas</label>
                <input type="text" name="judul" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label>File (opsional)</label>
                <input type="file" name="file" class="form-control">
            </div>
            <div class="mb-3">
                <label>Deadline</label>
                <input type="datetime-local" name="deadline" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Tambah Tugas</button>
            <a href="dashboard_dosen.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

</body>
</html>
