<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$mahasiswa_id = $_SESSION['user_id'];

// Cek apakah data milik mahasiswa dan belum dikumpulkan
$cek = mysqli_query($conn, "SELECT * FROM pengumpulan WHERE id=$id AND mahasiswa_id=$mahasiswa_id");
$data = mysqli_fetch_assoc($cek);

if (!$data) {
    die("Tugas tidak ditemukan atau tidak boleh dihapus.");
}

mysqli_query($conn, "DELETE FROM pengumpulan WHERE id=$id");
header("Location: lihat_tugas_mahasiswa.php");
exit;
?>
