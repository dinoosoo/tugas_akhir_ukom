<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $role = $_POST["role"];

    // **Cek apakah username sudah ada di tabel login**
    $check_sql = "SELECT * FROM login WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Username sudah digunakan! Silakan gunakan username lain.";
    } else {
        // **Cek apakah nama sudah ada di tabel siswa atau guru**
        $check_nama_siswa = "SELECT * FROM siswa WHERE nama_siswa = ?";
        $check_nama_guru = "SELECT * FROM guru WHERE nama_guru = ?";

        $stmt_siswa = $conn->prepare($check_nama_siswa);
        $stmt_siswa->bind_param("s", $username);
        $stmt_siswa->execute();
        $result_siswa = $stmt_siswa->get_result();

        $stmt_guru = $conn->prepare($check_nama_guru);
        $stmt_guru->bind_param("s", $username);
        $stmt_guru->execute();
        $result_guru = $stmt_guru->get_result();

        // **Jika nama ditemukan di tabel siswa atau guru, lanjutkan registrasi**
        if ($result_siswa->num_rows > 0 || $result_guru->num_rows > 0) {
            // **Simpan ke tabel login**
            $sql = "INSERT INTO login (username, password, role) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $password, $role);

            if ($stmt->execute()) {
                header("Location: index.php?register_success=1");
                exit();
            } else {
                $error = "Terjadi kesalahan, coba lagi.";
            }
        } else {
            // **Jika nama tidak ditemukan di tabel siswa atau guru, tampilkan error**
            $error = "Nama tidak ditemukan! Anda tidak dapat mendaftar.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Tugas Digital</title>
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: url('logo.jpeg') no-repeat center center;
    background-size: cover;
    margin: 0;
}
        .register-container {
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
<div class="register-container">
    <h4 class="mb-3"><i class="bx bx-user-plus"></i> Daftar Akun</h4>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"> <?php echo $error; ?> </div>
    <?php endif; ?>

    <form method="POST" action="">
        <!-- Username dengan ikon -->
        <div class="mb-3 text-start position-relative">
            <label for="username" class="form-label"><i class="bx bx-user"></i> Nama</label>
            <input type="text" name="username" id="username" class="form-control ps-4" placeholder="masukkan nama lengkap" required>
        </div>

        <!-- Password dengan ikon dan toggle mata -->
        <div class="mb-3 text-start position-relative">
            <label for="password" class="form-label"><i class="bx bx-lock"></i> Password</label>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control" required>
                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                    <i class="bx bx-hide"></i>
                </button>
            </div>
        </div>

        <!-- Dropdown Role dengan ikon -->
        <div class="mb-3 text-start">
            <label class="form-label"><i class="bx bx-user-circle"></i> Daftar sebagai</label>
            <select name="role" class="form-control" required>
                <option value="siswa">Siswa</option>
                <option value="guru">Guru</option>
            </select>
        </div>

        <!-- Tombol Submit -->
        <button type="submit" class="btn btn-primary w-100"><i class="bx bx-check-circle"></i> Daftar</button>
    </form>

    <div class="mt-3">
        <p>Sudah punya akun? <a href="index.php">Login</a></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Script untuk toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('bx-show');
        this.querySelector('i').classList.toggle('bx-hide');
    });
</script>
</body>
</html>