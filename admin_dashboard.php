<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}
include 'koneksi.php';

// Ambil jumlah tugas dari tabel form_tugas
$resultTugas = mysqli_query($conn, "SELECT COUNT(*) as total_tugas FROM form_tugas");
$dataTugas = mysqli_fetch_assoc($resultTugas);
$totalTugas = $dataTugas['total_tugas'];

// Ambil jumlah guru dari tabel guru
$resultGuru = mysqli_query($conn, "SELECT COUNT(*) as total_guru FROM guru");
$dataGuru = mysqli_fetch_assoc($resultGuru);
$totalGuru = $dataGuru['total_guru'];

// Ambil jumlah siswa dari tabel siswa
$resultSiswa = mysqli_query($conn, "SELECT COUNT(*) as total_siswa FROM siswa");
$dataSiswa = mysqli_fetch_assoc($resultSiswa);
$totalSiswa = $dataSiswa['total_siswa'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
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
	<title>Tuga Digital</title>
</head>
<body>
	
	<!-- SIDEBAR -->
<!-- SIDEBAR -->
<section id="sidebar">
	<a href="#" class="brand"><i class='bx bxs-book icon'></i> Tugas Digital</a>
	<ul class="side-menu">
    <li><a href="admin_dashboard.php" class="active"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>

    <li>
        <a href="#"><i class='bx bxs-inbox icon'></i> Master Tugas <i class='bx bx-chevron-right icon-right'></i></a>
        <ul class="side-dropdown">
		<li><a href="kelas.php"><i class='bx bx-task'></i> Kelas</a></li>
        <a href="mapel.php"><i class='bx bx-task'></i> Mata Pelajaran</a></li>
            <li><a href="guru.php"><i class='bx bx-task'></i> Guru</a></li>
            <li><a href="siswa.php"><i class='bx bx-task'></i> Siswa</a></li>
        </ul>
    </li>

    <li>
        <a href="#"><i class='bx bxs-notepad icon'></i> Manajemen Tugas <i class='bx bx-chevron-right icon-right'></i></a>
        <ul class="side-dropdown">
            <li><a href="tugas.php"><i class='bx bx-task'></i> Tugas</a></li>
			<li><a href="tugas_terkumpul.php"><i class='bx bx-task'></i> Tugas Terkumpul</a></li>
        </ul>
    </li>

    <li><a href="riwayat.php"><i class='bx bxs-chart icon'></i> Riwayat Tugas</a></li>
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
        <h1>SELAMAT DATANG <?php echo $_SESSION['username']; ?>!</h1>
<!-- Konten khusus siswa -->
			<ul class="breadcrumbs">
				<li><a href="#">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Dashboard</a></li>
			</ul>
			<!-- MAIN -->
<div class="info-data">
    <div class="card">
        <div class="head">
            <div>
                <h2><?php echo $totalTugas; ?></h2>
                <p>Total Tugas</p>
            </div>
            <i class='bx bx-book icon'></i>
        </div>
    </div>
    <div class="card">
        <div class="head">
            <div>
                <h2><?php echo $totalGuru; ?></h2>
                <p>Total Guru</p>
            </div>
            <i class='bx bx-user icon'></i>
        </div>
    </div>
    <div class="card">
        <div class="head">
            <div>
                <h2><?php echo $totalSiswa; ?></h2>
                <p>Total Siswa</p>
            </div>
            <i class='bx bx-group icon'></i>
        </div>
    </div>
</div>
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
