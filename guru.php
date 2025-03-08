<?php
session_start();
$user_role = $_SESSION['role'] ?? 'siswa'; // Gunakan role dari session atau default ke siswa

// Contoh role user setelah login
// $_SESSION['role'] = 'guru'; // Jika login sebagai guru
// $_SESSION['role'] = 'siswa'; // Jika login sebagai siswa

// Koneksi ke database
$host = "localhost";
$username = "root";
$password = "";
$dbname = "tugas_digital";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_guru = $_POST['nama_guru'];
    $mapel = $_POST['mapel'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $nip = $_POST['nip'];

    // Query untuk menyimpan data guru ke database
    $sql = "INSERT INTO guru (nama_guru, mapel, jenis_kelamin, nip) 
            VALUES ('$nama_guru', '$mapel', '$jenis_kelamin', '$nip')";

    if ($conn->query($sql) === TRUE) {
        echo "Data guru berhasil disimpan!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Ambil data guru dari database
$result = $conn->query("SELECT * FROM guru");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
/* Sidebar Background - Terang */
.side-menu {
    background-color: #f5f5f5; /* Warna terang */
    padding: 20px;
    min-height: 100vh;
    border-right: 1px solid #ddd;
}

/* Perbesar teks menu utama */
.side-menu li a {
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    color: #333; /* Teks gelap */
    transition: background 0.3s, color 0.3s;
    border-radius: 8px;
}

/* Hover dan Active - Warna Silver */
.side-menu li a:hover, 
.side-menu li a.active {
    background-color: #c0c0c0; /* Silver */
    color: #000; /* Teks hitam */
}

/* Perbesar dan ubah warna ikon */
.side-menu li a .icon {
    font-size: 24px;
    color: #666; /* Soft Grey untuk ikon */
    transition: color 0.3s;
}

/* Hover efek untuk ikon */
.side-menu li a:hover .icon, 
.side-menu li a.active .icon {
    color: #000; /* Ikon hitam saat hover/active */
}

/* Dropdown menu */
.side-dropdown {
    margin-left: 20px;
    background-color: #eaeaea; /* Lebih terang untuk dropdown */
    border-radius: 6px;
    padding: 5px 0;
}

.side-dropdown li a {
    font-size: 16px;
    padding: 10px 20px;
    color: #555; /* Light Grey */
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Hover dropdown dengan warna silver */
.side-dropdown li a:hover {
    background-color: #c0c0c0; /* Silver */
    color: #000;
}


    </style>
    <title>Tugas Digital</title>
</head>
<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand"><i class='bx bxs-book icon'></i> Tugas Digital</a>
        <ul class="side-menu">
            <li><a href="siswa_dashboard.php" class="active"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>
            <li>
    <a href="#"><i class='bx bxs-inbox icon'></i> Master Tugas <i class='bx bx-chevron-right icon-right'></i></a>
    <ul class="side-dropdown">
        <?php if ($user_role != 'siswa') { ?>
            <li><a href="kelas.php"><i class='bx bx-task'></i> Kelas</a></li>
        <?php } ?>
        <li><a href="guru.php"><i class='bx bx-task'></i> Guru</a></li>
        <li><a href="siswa.php"><i class='bx bx-task'></i> Siswa</a></li>
    </ul>
</li>

            <li>
            <a href="#"><i class='bx bxs-notepad icon' ></i> Manejemen Tugas <i class='bx bx-chevron-right icon-right' ></i></a>
				<ul class="side-dropdown">
					<li><a href="tugas.php">Tugas</a></li>
					<li><a href="tugas_terkumpul.php">Tugas Terkumpul</a></li>
                    <li><a href="tugas_terkumpul.php"><i class='bx bx-task'></i> Tugas Terkumpul</a></li>
				</ul>
			</li>
			<li><a href="riwayat.php"><i class='bx bxs-chart icon' ></i> Riwayat Tugas</a></li>
			<li><a href="#" onclick="confirmLogout(event)"><i class='bx bx-log-out icon'></i> Logout</a></li>

		</ul>
	</section>
    <!-- SIDEBAR -->

    <!-- NAVBAR -->
	<section id="content">
		<nav>
			<i class='bx bx-menu toggle-sidebar' ></i>
			<form action="#">
				<div class="form-group">
					<input type="text" placeholder="Search...">
					<i class='bx bx-search icon' ></i>
				</div>
			</form>
			
			<span class="divider"></span>
		</nav>
		<main>

            <div class="container mt-4">
                <h3>Daftar Guru</h3>

                <div class="row">
                    <!-- Kolom kiri: Tabel -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Data Guru</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Guru</th>
                                                <th>Mapel</th>
                                                <th>Jenis Kelamin</th>
                                                <th>NIP</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $no = 1;
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>
                                                            <td>{$no}</td>
                                                            <td>{$row['nama_guru']}</td>
                                                            <td>{$row['mapel']}</td>
                                                            <td>{$row['jenis_kelamin']}</td>
                                                            <td>{$row['nip']}</td>
                                                            <td>";

                                                    // Tampilkan tombol Edit hanya untuk Guru
                                                    if ($user_role === 'guru') {
                                                        // Form atau tombol khusus guru
                                                        echo "<!-- Session Role: " . ($_SESSION['role'] ?? 'Not Set') . " -->";
                                                        echo "<!-- User Role: $user_role -->";
                                                    
                                                        echo "<a href='edit_guru.php?id={$row['id']}' class='btn btn-sm btn-warning'><i class='bi bi-pencil-square'></i> Edit</a>";
                                                    } else {
                                                        echo "-"; // Siswa hanya melihat tanda "-"
                                                    }

                                                    echo    "</td>
                                                        </tr>";
                                                    $no++;
                                                }
                                            ?>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom kanan: Form -->
                                <?php if ($_SESSION['role'] === 'guru'): ?>
                <!-- Kolom kanan: Form hanya untuk Guru -->
                <div class="col-md-6 mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Tambah Guru</h5>
                        </div>
                        <div class="card-body">
                            <form action="guru.php" method="POST">
                                <div class="mb-3">
                                    <label for="nama_guru" class="form-label">Nama Guru</label>
                                    <input type="text" class="form-control" id="nama_guru" name="nama_guru" required>
                                </div>
                                <div class="mb-3">
                                    <label for="mapel" class="form-label">Mata Pelajaran</label>
                                    <input type="text" class="form-control" id="mapel" name="mapel" required>
                                </div>
                                <div class="mb-3">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="nip" class="form-label">NIP</label>
                                    <input type="text" class="form-control" id="nip" name="nip" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Tambah Guru</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

                </div>
            </div>

        </main>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="script.js"></script>
</body>
<script>
function confirmLogout(event) {
    event.preventDefault(); // Mencegah link langsung berjalan

    // Tampilkan dialog konfirmasi
    let confirmation = confirm("⚠️ Apakah Anda yakin ingin keluar dari akun ini?\n\nPastikan semua tugas sudah dikumpulkan!");

    if (confirmation) {
        // Jika user klik OK, arahkan ke logout.php
        window.location.href = "logout.php";
    }
    // Jika user klik Cancel, tidak terjadi apa-apa
}
</script>

</html>

<?php
$conn->close();
?>
