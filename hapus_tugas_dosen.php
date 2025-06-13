<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'dosen') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id']) && isset($_GET['kelas_id'])) {
    $id = intval($_GET['id']);
    $kelas_id = intval($_GET['kelas_id']);

    // Hapus tugas
    mysqli_query($conn, "DELETE FROM tugas WHERE id = $id");

    header("Location: lihat_tugas_dosen.php?kelas_id=$kelas_id");
    exit;
}
?>
