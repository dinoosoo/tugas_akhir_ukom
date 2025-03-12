<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]); // Password dalam bentuk plain text

    // Cek apakah username ada di tabel login
    $sql = "SELECT * FROM login WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Bandingkan password dalam bentuk plain text
        if ($password === $user['password']) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Ambil nama berdasarkan role
            if ($user['role'] == "siswa") {
                // Ambil nama siswa dari tabel siswa
                $sql_siswa = "SELECT nama_siswa FROM siswa WHERE nama_siswa = ?";
                $stmt_siswa = $conn->prepare($sql_siswa);
                $stmt_siswa->bind_param("s", $username); // Username adalah nama_siswa
                $stmt_siswa->execute();
                $result_siswa = $stmt_siswa->get_result();

                if ($result_siswa->num_rows === 1) {
                    $siswa = $result_siswa->fetch_assoc();
                    $_SESSION['nama_siswa'] = $siswa['nama_siswa']; // Simpan nama siswa di session
                }
            } elseif ($user['role'] == "guru") {
                // Ambil nama guru dari tabel guru
                $sql_guru = "SELECT nama_guru FROM guru WHERE nama_guru = ?";
                $stmt_guru = $conn->prepare($sql_guru);
                $stmt_guru->bind_param("s", $username); // Username adalah nama_guru
                $stmt_guru->execute();
                $result_guru = $stmt_guru->get_result();

                if ($result_guru->num_rows === 1) {
                    $guru = $result_guru->fetch_assoc();
                    $_SESSION['nama_guru'] = $guru['nama_guru']; // Simpan nama guru di session
                }
            }

            // Arahkan berdasarkan role
            if ($user['role'] == "guru") {
                header("Location: guru_dashboard.php");
            } elseif ($user['role'] == "siswa") {
                header("Location: siswa_dashboard.php");
            } elseif ($user['role'] == "admin") {
                header("Location: admin_dashboard.php"); // Arahkan ke halaman admin
            }
            exit();
        } else {
            $error = "Password Anda salah!";
        }
    } else {
        // Pesan jika username tidak ditemukan
        $error = "Akun Anda tidak ada, silakan daftar dulu!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tugas Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #e3f2fd;
            margin: 0;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h4 class="mb-3"><i class="bx bx-book"></i> Tugas Digital</h4>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"> <?php echo $error; ?> </div>
    <?php endif; ?>
<form method="POST" action="">
    <div class="mb-3 text-start">
        <label for="username" class="form-label">Nama</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bx bx-user"></i></span>
            <input type="text" name="username" id="username" class="form-control" placeholder="masukkan nama lengkap" required>
        </div>
    </div>
    <div class="mb-3 text-start">
        <label for="password" class="form-label">Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bx bx-lock"></i></span>
            <input type="password" name="password" id="password" class="form-control" required>
            <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                <i class="bx bx-hide"></i>
            </button>
        </div>
    </div>
    <button type="submit" class="btn btn-primary w-100">Masuk</button>
</form>
    <div class="mt-3">
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</div>

<script>
    document.getElementById("togglePassword").addEventListener("click", function() {
        let passwordInput = document.getElementById("password");
        let icon = this.querySelector("i");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.replace("bx-hide", "bx-show");
        } else {
            passwordInput.type = "password";
            icon.classList.replace("bx-show", "bx-hide");
        }
    });
</script>

<!-- Tambahkan CSS Boxicons -->
<link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
