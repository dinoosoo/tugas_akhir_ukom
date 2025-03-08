<?php
session_start();
$user_role = $_SESSION['role'] ?? 'siswa'; // Gunakan role dari session atau default ke siswa

// Koneksi langsung ke database
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "tugas_digital";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Query untuk mengambil data tugas
$query = "SELECT * FROM tugas_terkumpul"; 
$result = mysqli_query($conn, $query);

// Cek apakah query berhasil dijalankan
if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}

// Untuk nomor urut
$no = 1;

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


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
		<li><a href="kelas.php"><i class='bx bx-task'></i> Kelas</a></li>
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
    <li><a href="logout.php"><i class='bx bx-log-out icon'></i> Logout</a></li>

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

		<!-- Form Tambah Tugas -->
<div class="container mt-4">
    <h2 class="text-start mb-3"><i class="fas fa-tasks"></i> Tugas Terkumpul</h2>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> Daftar Tugas yang Sudah Dikumpulkan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <!-- Tambahin table-bordered untuk garis tabel -->
                <table class="table table-bordered table-striped table-hover text-center">
                    <thead class="bg-secondary text-white">
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Judul Tugas</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Tanggal Pengumpulan</th>
                            <th>File</th>
                            <th>Feedback</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
					<?php if (mysqli_num_rows($result) > 0) { ?>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['nama_siswa']); ?></td>
            <td><?= htmlspecialchars($row['judul']); ?></td>
            <td><?= htmlspecialchars($row['mata_pelajaran']); ?></td>
            <td><?= htmlspecialchars($row['kelas']); ?></td>
            <td><?= htmlspecialchars($row['tanggal_pengumpulan']); ?></td>
            <td>
                <a href="<?= $row['file_path']; ?>" target="_blank" class="btn btn-primary btn-sm">
                    <i class="fas fa-eye"></i> Lihat
                </a>
            </td>
            <td><textarea class="form-control" placeholder="Masukkan feedback"></textarea></td>
            <td>
                <span class="badge bg-warning status">Belum Dinilai</span>
                <form method="POST" action="hapus_tugas.php" style="display:inline-block;" onsubmit="return confirm('Yakin ingin hapus tugas ini?');">
                    <input type="hidden" name="id" value="<?= $row['id']; ?>">
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button>
                </form>
            </td>
        </tr>
    <?php } ?>
<?php } else { ?>
    <tr>
        <td colspan="9" class="text-center text-muted">
            <i class="fas fa-exclamation-circle fa-2x text-danger"></i><br>
            Tugas tidak ada.
        </td>
    </tr>
<?php } ?>

                    </tbody>
                </table>

                <?php mysqli_close($conn); ?>
            </div>
        </div>
    </div>
</div>

<script>
    function lihatTugas(fileUrl) {
        window.open(fileUrl, '_blank');
    }

    function beriNilai(button) {
        // Cari elemen status dalam baris tabel yang sesuai
        let row = button.closest("tr");
        let status = row.querySelector(".status");

        // Ubah status menjadi "Sudah Dinilai"
        status.innerText = "Sudah Dinilai";
        status.classList.remove("bg-warning");
        status.classList.add("bg-success");
    }
</script>




	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
	<script src="script.js"></script>
</body>
</html>
