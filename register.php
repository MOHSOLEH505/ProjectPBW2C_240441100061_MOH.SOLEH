<?php
session_start();
include "koneksi.php";

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (str_ends_with($email, '@utm.com')) {
        $role = 'dosen';
    } elseif (str_ends_with($email, '@student.utm.com')) {
        $role = 'mahasiswa';
    } else {
        $pesan = "<div class='alert alert-danger'>Email harus menggunakan domain @utm.com atau @student.utm.com</div>";
    }

    if (empty($pesan)) {
        $cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if (mysqli_num_rows($cek) > 0) {
            $pesan = "<div class='alert alert-danger'>Email sudah digunakan!</div>";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (email, password, role) VALUES ('$email', '$hashed', '$role')";
            if (mysqli_query($conn, $sql)) {
                $pesan = "<div class='alert alert-success'>Registrasi berhasil. <a href='login.php'>Login di sini</a></div>";
            } else {
                $pesan = "<div class='alert alert-danger'>Gagal registrasi: " . mysqli_error($conn) . "</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom,rgb(0, 60, 255),rgb(255, 255, 255));
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-box {
            background: rgb(255, 255, 255);
            padding: 70px 80px;
            border-radius: 12px;
            width: 100%;
            max-width: 430px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.25);
            text-align: center;
        }

        .register-box h2 {
            font-family: 'Montserrat', sans-serif;
            font-weight: bold;
            margin-bottom: 45px;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="register-box">
        <h2 class="mb-4">REGISTRASI</h2><br>
        <?= $pesan ?>
        <form method="POST">
            <div class="mb-3 text-start">
                <input type="email" name="email" class="form-control" required placeholder="email">
            </div>
            <div class="mb-4 text-start">
                <input type="password" name="password" class="form-control" required placeholder="password">
            </div>
            <button type="submit" class="btn btn-primary w-100">Daftar</button>
        </form><br>
        <div class="mt-3">
            Sudah punya akun? <a href="login.php">Login</a>
        </div>
    </div>
</body>
</html>
