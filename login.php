<?php
session_start();
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $nama = $_POST['nama'] ?? null;

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($query);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['login'] = true;
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['id'];

        if ($user['role'] === 'dosen' && !empty($nama)) {
            mysqli_query($conn, "UPDATE users SET nama='$nama' WHERE id=" . $user['id']);
        }

        if ($user['role'] == 'dosen') {
            header("Location: dashboard_dosen.php");
        } elseif ($user['role'] == 'mahasiswa') {
            header("Location: dashboard_mahasiswa.php");
        }
        exit;
    } else {
        $error = "Email atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <script>
        function cekEmailDosen() {
            const email = document.getElementById('email').value;
            const namaField = document.getElementById('namaField');
            if (email.endsWith('@utm.com')) {
                namaField.style.display = 'block';
            } else {
                namaField.style.display = 'none';
            }
        }
    </script>
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
        <h2 class="mb-5 fw-bold">LOGIN</h2>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <div class="mb-3 text-start">
                <input type="email" name="email" class="form-control" id="email" oninput="cekEmailDosen()" required placeholder="email">
            </div>
            <div class="mb-3 text-start" id="namaField" style="display: none;">
                <input type="text" name="nama" class="form-control" placeholder="Nama Dosen">
            </div>
            <div class="mb-4 text-start">
                <input type="password" name="password" class="form-control" required placeholder="password">
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form><br>
        <div class="mt-3">
            Belum punya akun? <a href="register.php">Daftar</a>
        </div>
    </div>
</body>
</html>
