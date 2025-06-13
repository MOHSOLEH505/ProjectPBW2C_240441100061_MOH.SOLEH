<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id']);
$mahasiswa_id = $_SESSION['user_id'];

$result = mysqli_query($conn, "SELECT * FROM pengumpulan WHERE id = $id AND mahasiswa_id = $mahasiswa_id");
$data = mysqli_fetch_assoc($result);

if ($data) {
    if (file_exists("uploads/" . $data['file'])) {
        unlink("uploads/" . $data['file']);
    }

    mysqli_query($conn, "DELETE FROM pengumpulan WHERE id = $id AND mahasiswa_id = $mahasiswa_id");
}

header("Location: lihat_pengumpulan_mahasiswa.php");
exit;
?>
