<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$dosen = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama FROM users WHERE id = $user_id"));

if (!isset($_GET['id'])) {
    header("Location: dashboard_dosen.php");
    exit;
}

$id = intval($_GET['id']);
$tugas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tugas WHERE id = $id"));
$kelas_id = $tugas['kelas_id'];
$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $deadline = $_POST['deadline'];
    $file = $tugas['file'];

    if ($_FILES['file']['name']) {
        $target_dir = "uploads/";
        $file = $target_dir . basename($_FILES["file"]["name"]);
        move_uploaded_file($_FILES["file"]["tmp_name"], $file);
    }

    $query = "UPDATE tugas SET 
              judul='$judul', deskripsi='$deskripsi', file='$file', deadline='$deadline' 
              WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        $pesan = "<div class='alert alert-success'>Tugas berhasil diupdate. <a href='lihat_tugas_dosen.php?kelas_id=$kelas_id'>Kembali</a></div>";
    } else {
        $pesan = "<div class='alert alert-danger'>Gagal mengupdate tugas.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Tugas</title>
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
    <a href="dashboard_dosen.php" class="text-center mt-3 shadow-sm active">Edit Tugas</a>
    <a href="logout.php" class="text-center mt-3 shadow-sm">Logout</a>
</div>

<div class="content">
    <h3 class="mb-4">Edit Tugas</h3>
    <?= $pesan ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Judul Tugas</label>
            <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($tugas['judul']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" required><?= htmlspecialchars($tugas['deskripsi']) ?></textarea>
        </div>
        <div class="mb-3">
            <label>File (kosongkan jika tidak diganti)</label>
            <input type="file" name="file" class="form-control">
            <?php if ($tugas['file']) echo "<small>File saat ini: <a href='{$tugas['file']}'>Lihat</a></small>"; ?>
        </div>
        <div class="mb-3">
            <label>Deadline</label>
            <input type="datetime-local" name="deadline" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($tugas['deadline'])) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="lihat_tugas_dosen.php?kelas_id=<?= $kelas_id ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>

</body>
</html>
