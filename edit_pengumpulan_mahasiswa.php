<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;
$mahasiswa_id = $_SESSION['user_id'];

// Ambil nama mahasiswa untuk header
$mahasiswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama FROM users WHERE id = $mahasiswa_id"));

$result = mysqli_query($conn, "SELECT * FROM pengumpulan WHERE id=$id AND mahasiswa_id=$mahasiswa_id");
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Tugas tidak ditemukan atau tidak boleh diakses.");
}

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $komentar = mysqli_real_escape_string($conn, $_POST['komentar']);
    $file_sql = "";

    if (!empty($_FILES['file']['name'])) {
        $target_dir = "uploads/";
        $original_name = basename($_FILES["file"]["name"]);
        $file_name = time() . "_" . $original_name;
        $target_path = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_path)) {
            // Hapus file lama
            if (!empty($data['file']) && file_exists("uploads/" . $data['file'])) {
                unlink("uploads/" . $data['file']);
            }
            $file_sql = ", file='$file_name'";
        } else {
            $pesan = "<div class='alert alert-danger'>Gagal mengunggah file baru.</div>";
        }
    }

    $query = "
        UPDATE pengumpulan 
        SET nama='$nama', nim='$nim', komentar='$komentar', waktu_submit=NOW() $file_sql 
        WHERE id=$id AND mahasiswa_id=$mahasiswa_id
    ";

    if (mysqli_query($conn, $query)) {
        $pesan = "<div class='alert alert-success'>Tugas berhasil diperbarui. <a href='lihat_pengumpulan_mahasiswa.php'>Kembali</a></div>";
    } else {
        $pesan = "<div class='alert alert-danger'>Gagal memperbarui tugas.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Tugas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
        <h3 class="mb-4">Edit Pengumpulan Tugas</h3>
        <?= $pesan ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>NIM</label>
                <input type="text" name="nim" value="<?= htmlspecialchars($data['nim']) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Komentar</label>
                <textarea name="komentar" class="form-control"><?= htmlspecialchars($data['komentar']) ?></textarea>
            </div>
            <div class="mb-3">
                <label>Ganti File Tugas (Opsional)</label>
                <input type="file" name="file" class="form-control">
            </div>
            <button class="btn btn-warning">Simpan Perubahan</button>
            <a href="lihat_pengumpulan_mahasiswa.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

</body>
</html>
