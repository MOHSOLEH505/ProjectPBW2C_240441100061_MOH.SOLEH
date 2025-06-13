<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$mahasiswa_id = $_SESSION['user_id'];

// Cek apakah tugas pengumpulan tersebut milik mahasiswa ini
$cek = mysqli_query($conn, "SELECT * FROM pengumpulan WHERE id=$id AND mahasiswa_id=$mahasiswa_id");
$data = mysqli_fetch_assoc($cek);

if (!$data) {
    die("Data tidak ditemukan atau bukan milik Anda.");
}

// Update waktu_submit agar ditandai sebagai dikumpulkan (manual atau biarkan default)
$now = date('Y-m-d H:i:s');
mysqli_query($conn, "UPDATE pengumpulan SET waktu_submit='$now' WHERE id=$id");

header("Location: lihat_tugas_mahasiswa.php?status=success");
exit;
?>
