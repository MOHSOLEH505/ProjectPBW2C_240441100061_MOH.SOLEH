<?php
$conn = mysqli_connect("localhost", "root", "", "pengelolaan");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
