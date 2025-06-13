<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['kelas_id'])) {
    $kelas_id = intval($_GET['kelas_id']);

    // Ambil semua ID tugas dalam kelas ini
    $tugas_result = mysqli_query($conn, "SELECT id FROM tugas WHERE kelas_id = $kelas_id");

    // Hapus pengumpulan berdasarkan semua tugas_id terkait
    while ($tugas = mysqli_fetch_assoc($tugas_result)) {
        $tugas_id = $tugas['id'];
        mysqli_query($conn, "DELETE FROM pengumpulan WHERE tugas_id = $tugas_id");
    }

    // Hapus tugas-tugasnya
    mysqli_query($conn, "DELETE FROM tugas WHERE kelas_id = $kelas_id");

    // Hapus relasi kelas-mahasiswa
    mysqli_query($conn, "DELETE FROM kelas_mahasiswa WHERE kelas_id = $kelas_id");

    // Hapus kelas itu sendiri
    mysqli_query($conn, "DELETE FROM kelas WHERE id = $kelas_id");
}

header("Location: dashboard_dosen.php");
exit;
