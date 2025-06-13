<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['tugas_id'])) {
    die("Tugas tidak ditemukan.");
}

$tugas_id = intval($_GET['tugas_id']);
$mahasiswa_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $nim = $_POST['nim'];
    $komentar = $_POST['komentar'];
    $file = $_FILES['file']['name'];
    $tmp = $_FILES['file']['tmp_name'];

    // Pastikan folder uploads/ ada
    $folder = "uploads/";
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $target_path = $folder . basename($file);
    move_uploaded_file($tmp, $target_path);

    // Cek apakah sudah pernah upload
    $cek = mysqli_query($conn, "SELECT * FROM pengumpulan WHERE tugas_id=$tugas_id AND mahasiswa_id=$mahasiswa_id");
    if (mysqli_num_rows($cek) > 0) {
        echo "<div class='alert alert-success'>Tugas berhasil di upload.</div>";
    } else {
        mysqli_query($conn, "INSERT INTO pengumpulan (tugas_id, mahasiswa_id, nama, nim, file, komentar) 
            VALUES ($tugas_id, $mahasiswa_id, '$nama', '$nim', '$file', '$komentar')");
        header("Location: kumpulkan_tugas_mahasiswa.php");
        exit;
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Upload Tugas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h3>Upload Tugas</h3>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>NIM</label>
            <input type="text" name="nim" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>File Tugas</label>
            <input type="file" name="file" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Komentar (Opsional)</label>
            <textarea name="komentar" class="form-control" rows="3" placeholder="Tuliskan sesuatu jika perlu..."></textarea>
        </div>
        <button class="btn btn-primary">Upload</button>
    </form>
</body>
</html>
