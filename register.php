<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db   = "tugas_digital";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
    $role = $_POST["role"];

    // **Cek apakah username sudah ada**
    $check_sql = "SELECT * FROM login WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Username sudah digunakan! Silakan gunakan username lain.";
    } else {
        // **Simpan ke database jika username belum ada**
        $sql = "INSERT INTO login (username, password, role) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $role);

        if ($stmt->execute()) {
            header("Location: login.php?register_success=1");
            exit();
        } else {
            $error = "Terjadi kesalahan, coba lagi.";
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
            background-color: #e3f2fd;
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
            <label for="username" class="form-label"><i class="bx bx-user"></i> Username</label>
            <input type="text" name="username" id="username" class="form-control ps-4" required>
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
        <p>Sudah punya akun? <a href="login.php">Login</a></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
