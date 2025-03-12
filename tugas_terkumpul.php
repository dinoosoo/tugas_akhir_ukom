<?php
session_start();
$user_role = $_SESSION['role'] ?? 'guru'; // Default ke guru jika role tidak ada
include 'koneksi.php';

// Pastikan session nama_guru sudah di-set
if ($user_role == 'guru' && !isset($_SESSION['nama_guru'])) {
    die("Session nama_guru tidak ditemukan. Silakan login kembali.");
}

// Query dasar untuk mengambil data tugas dengan JOIN
$query = "
    SELECT 
        tugas_terkumpul.id AS id_tugas_terkumpul,
        tugas_terkumpul.nama_siswa,
        tugas_terkumpul.file_upload,
        tugas_terkumpul.waktu_pengumpulan,
        tugas_terkumpul.feedback,
        form_tugas.judul,
        form_tugas.mata_pelajaran,
        form_tugas.kelas,
        siswa.nama_siswa AS nama_siswa_lengkap,
        siswa.kelas AS kelas_siswa,
        guru.nama_guru
    FROM tugas_terkumpul
    INNER JOIN form_tugas ON tugas_terkumpul.id_tugas = form_tugas.id
    INNER JOIN siswa ON tugas_terkumpul.nama_siswa = siswa.nama_siswa
    INNER JOIN guru ON form_tugas.mata_pelajaran = guru.mapel
";

// Tambahkan kondisi berdasarkan role
if ($user_role == 'guru') {
    // Jika role guru, filter tugas berdasarkan guru yang login
    $nama_guru = $_SESSION['nama_guru']; // Ambil nama guru dari session
    $query .= " WHERE guru.nama_guru = '$nama_guru'";
} elseif ($user_role == 'admin') {
    // Jika role admin, tidak perlu filter (ambil semua data)
} else {
    // Jika role tidak valid, redirect atau tampilkan pesan error
    die("Role tidak valid.");
}

// Jalankan query
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
        <!-- Dashboard sesuai role -->
        <?php if ($_SESSION['role'] === 'admin') : ?>
            <li><a href="admin_dashboard.php" class="active"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>
        <?php elseif ($_SESSION['role'] === 'guru') : ?>
            <li><a href="guru_dashboard.php" class="active"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>
        <?php elseif ($_SESSION['role'] === 'siswa') : ?>
            <li><a href="siswa_dashboard.php" class="active"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>
        <?php endif; ?>

        <!-- Menu Master Tugas -->
            <li>
                <a href="#"><i class='bx bxs-inbox icon'></i> Master Tugas <i class='bx bx-chevron-right icon-right'></i></a>
                <ul class="side-dropdown">
                    <?php if ($_SESSION['role'] === 'admin') : ?>
                        <li><a href="guru.php"><i class='bx bx-task'></i> Guru</a></li>
                    <?php endif; ?>
                    <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'guru') : ?>
                    <li><a href="kelas.php"><i class='bx bx-task'></i> Kelas</a></li>
                    <?php endif; ?>
                    <li><a href="siswa.php"><i class='bx bx-task'></i> Siswa</a></li>
                </ul>
            </li>

        <!-- Menu Manajemen Tugas -->
            <li>
                <a href="#"><i class='bx bxs-notepad icon'></i> Manajemen Tugas <i class='bx bx-chevron-right icon-right'></i></a>
                <ul class="side-dropdown">
                    <li><a href="tugas.php"><i class='bx bx-task'></i> Tugas</a></li>
                    <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'guru') : ?>
                        <li><a href="tugas_terkumpul.php"><i class='bx bx-task'></i> Tugas Terkumpul</a></li>
                    <?php endif; ?>
                </ul>
            </li>

        <!-- Menu Riwayat Tugas -->
        <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'siswa') : ?>
            <li><a href="riwayat.php"><i class='bx bxs-chart icon'></i> Riwayat Tugas</a></li>
        <?php endif; ?>

        <!-- Menu Logout -->
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
                                    <td><?= htmlspecialchars($row['nama_siswa_lengkap']); ?></td>
                                    <td><?= htmlspecialchars($row['judul']); ?></td>
                                    <td><?= htmlspecialchars($row['mata_pelajaran']); ?></td>
                                    <td><?= htmlspecialchars($row['kelas_siswa']); ?></td>
                                    <td><?= htmlspecialchars($row['waktu_pengumpulan']); ?></td>
                                    <td>
                                        <a href="uploads/<?= htmlspecialchars($row['file_upload']); ?>" target="_blank" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    </td>
                                    <td>
                                        <textarea class="form-control" readonly><?= htmlspecialchars($row['feedback']); ?></textarea>
                                    </td>
                                    <td>
                                    <?php if (empty($row['feedback'])) : ?>
                                    <span class="badge bg-warning status">Belum Dinilai</span>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#nilaiModal" onclick="setNilaiModal(<?= $row['id_tugas_terkumpul']; ?>)">
                                    <i class="fas fa-check"></i> Nilai
                                    </button>
                                    <form method="POST" action="hapus_tugas.php" style="display:inline-block;" onsubmit="return confirm('Yakin ingin hapus tugas ini?');">
                                    <input type="hidden" name="id" value="<?= $row['id_tugas_terkumpul']; ?>">
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

<!-- modal nilai -->
 <!-- Modal untuk Input Nilai dan Feedback -->
<div class="modal fade" id="nilaiModal" tabindex="-1" aria-labelledby="nilaiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nilaiModalLabel">Nilai Tugas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="proses_nilai.php" method="POST">
                <div class="modal-body">
                    <!-- Input ID Tugas Terkumpul -->
                    <input type="hidden" name="id_tugas_terkumpul" id="id_tugas_terkumpul">

                    <!-- Input Feedback -->
                    <div class="mb-3">
                        <label for="feedback" class="form-label">Feedback</label>
                        <textarea class="form-control" id="feedback" name="feedback" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="submit_nilai" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
    function setNilaiModal(id) {
        // Set nilai id_tugas_terkumpul di modal
        document.getElementById('id_tugas_terkumpul').value = id;
    }
</script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
	<script src="script.js"></script>
</body>
</html>
