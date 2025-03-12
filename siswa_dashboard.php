<?php
session_start();
if ($_SESSION['role'] != 'siswa') {
    header("Location: index.php");
    exit();
}
include 'koneksi.php';

// Pastikan session nama_siswa sudah di-set
if (!isset($_SESSION['nama_siswa'])) {
    die("Session nama_siswa tidak ditemukan. Silakan login kembali.");
}

$nama_siswa = $_SESSION['nama_siswa']; // Ambil nama siswa dari session

// Query untuk mengambil total tugas
$queryTotalTugas = "SELECT COUNT(*) as total_tugas FROM form_tugas";
$resultTotalTugas = mysqli_query($conn, $queryTotalTugas);
$dataTotalTugas = mysqli_fetch_assoc($resultTotalTugas);
$totalTugas = $dataTotalTugas['total_tugas'];

// Query untuk mengambil total tugas yang belum dikerjakan oleh siswa
$queryTugasBelumSelesai = "
    SELECT COUNT(*) as total_belum_selesai 
    FROM form_tugas 
    WHERE id NOT IN (
        SELECT id_tugas FROM tugas_terkumpul WHERE nama_siswa = '$nama_siswa'
    )
";
$resultTugasBelumSelesai = mysqli_query($conn, $queryTugasBelumSelesai);
$dataTugasBelumSelesai = mysqli_fetch_assoc($resultTugasBelumSelesai);
$totalBelumSelesai = $dataTugasBelumSelesai['total_belum_selesai'];

// Query untuk mengambil total riwayat tugas (tugas yang sudah dikerjakan)
$queryRiwayatTugas = "
    SELECT COUNT(*) as total_riwayat 
    FROM tugas_terkumpul 
    WHERE nama_siswa = '$nama_siswa'
";
$resultRiwayatTugas = mysqli_query($conn, $queryRiwayatTugas);
$dataRiwayatTugas = mysqli_fetch_assoc($resultRiwayatTugas);
$totalRiwayat = $dataRiwayatTugas['total_riwayat'];
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
	<section id="sidebar">
	<a href="#" class="brand"><i class='bx bxs-book icon'></i> Tugas Digital</a>
	<ul class="side-menu">
    <li><a href="siswa_dashboard.php" class="active"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>

    <li>
        <a href="#"><i class='bx bxs-inbox icon'></i> Master Tugas <i class='bx bx-chevron-right icon-right'></i></a>
        <ul class="side-dropdown">
            <li><a href="siswa.php"><i class='bx bx-task'></i> Siswa</a></li>
        </ul>
    </li>

    <li>
        <a href="#"><i class='bx bxs-notepad icon'></i> Manajemen Tugas <i class='bx bx-chevron-right icon-right'></i></a>
        <ul class="side-dropdown">
            <li><a href="tugas.php"><i class='bx bx-task'></i> Tugas</a></li>
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
        <h1>SELAMAT DATANG SISWA, <?php echo $_SESSION['username']; ?>!</h1>
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
                <h2><?php echo $totalBelumSelesai; ?></h2>
                <p>Total Tugas Belum Selesai</p>
            </div>
            <i class='bx bx-task icon'></i>
        </div>
    </div>
    <div class="card">
        <div class="head">
            <div>
                <h2><?php echo $totalRiwayat; ?></h2>
                <p>Total Riwayat Tugas</p>
            </div>
            <i class='bx bx-check-circle icon'></i>
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
