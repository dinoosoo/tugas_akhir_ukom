<?php
// Koneksi ke database
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'tugas_digital';

$koneksi = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$koneksi) {
    die(json_encode(['success' => false, 'message' => 'Koneksi database gagal: ' . mysqli_connect_error()]));
}

// Proses penyimpanan data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mengambil data dari form
    $name = mysqli_real_escape_string($koneksi, $_POST['name']);
    $subject = mysqli_real_escape_string($koneksi, $_POST['subject']);
    $gender = mysqli_real_escape_string($koneksi, $_POST['gender']);

    // Query untuk menyimpan data ke tabel guru
    $query = "INSERT INTO guru (nama_guru, mata_pelajaran, jenis_kelamin) VALUES ('$name', '$subject', '$gender')";

    // Eksekusi query dan cek apakah berhasil
    if (mysqli_query($koneksi, $query)) {
        echo json_encode([
            'success' => true,
            'name' => $name,
            'subject' => $subject,
            'gender' => $gender
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($koneksi)]);
    }
    exit;
}

// Ambil data dari database untuk ditampilkan di tabel
$result = mysqli_query($koneksi, "SELECT * FROM guru");
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="style.css">
	<title>Guru</title>
    <style>
		/* Styling tambahan untuk tata letak form dan tabel */
.container {
    display: flex;
    gap: 20px;
    margin-top: 20px;
}

.form-container, .table-container {
    width: 50%;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background-color: #fff;
}

/* Form styling */
.form-container h3 {
    font-size: 1.5em;
    color: #333;
    margin-bottom: 20px;
}

.form-container form .form-group {
    margin-bottom: 20px;
}

.form-container form label {
    display: block;
    margin-bottom: 8px;
    font-size: 1em;
    color: #333;
}

.form-container form input,
.form-container form select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    background-color: #f9f9f9;
    font-size: 1em;
}

.form-container form input:focus,
.form-container form select:focus {
    border-color: #4CAF50; /* Highlight on focus */
    outline: none;
}

/* Styling untuk tombol submit */
.button-submit {
    background-color: #4CAF50;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px; /* Menambahkan jarak di atas tombol */
    margin-left: auto; /* Mengatur tombol ke sebelah kanan */
    display: block; /* Membuat tombol menjadi blok agar bisa diberi margin */
    width: fit-content; /* Membuat tombol menyesuaikan ukuran kontennya */
}

.button-submit:hover {
    background-color: #45a049;
}


/* Tabel styling */
.table-container h3 {
    font-size: 1.5em;
    color: #333;
    margin-bottom: 20px;
}

/* Tabel dengan warna biru */
.table-container table {
    width: 100%;
    border-collapse: collapse;
}

.table-container table th,
.table-container table td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}

.table-container table th {
    background-color: #007bff; /* Warna biru untuk header */
    color: white; /* Warna teks putih di header */
}

.table-container table td {
    background-color: #f2f2f2; /* Warna latar belakang pada data tabel */
}

.table-container table tr:nth-child(even) td {
    background-color: #e6f0ff; /* Memberikan warna biru muda pada baris genap */
}

.table-container table tr:hover td {
    background-color: #cce5ff; /* Memberikan warna biru muda saat baris di-hover */
}


	</style>
</head>
<body>
	
	<!-- SIDEBAR -->
	<section id="sidebar">
	<a href="#" class="brand"><i class='bx bxs-book icon'></i> Tugas Digital</a>
	<ul class="side-menu">
	<li><a href="index.php" class="active"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>
	<li class="divider" data-text="main">Main</li>
	<li>
				<a href="#"><i class='bx bxs-inbox icon'></i> Master Tugas <i class='bx bx-chevron-right icon-right'></i></a>
				<ul class="side-dropdown">
					<li><a href="kelas.php">Kelas</a></li>
					<li><a href="guru.php">Guru</a></li>
					<li><a href="siswa.php">Siswa</a></li>
				</ul>
			</li>
			<li>
				<a href="#"><i class='bx bxs-notepad icon' ></i> Manejemen Tugas <i class='bx bx-chevron-right icon-right' ></i></a>
				<ul class="side-dropdown">
					<li><a href="tugas.php">Tugas</a></li>
					<li><a href="tugas_terkumpul.php">Tugas Terkumpul</a></li>
				</ul>
			</li>
			<li><a href="charts.php"><i class='bx bxs-chart icon' ></i> Charts</a></li>
			<li><a href="tables.php"><i class='bx bx-table icon' ></i> Tables</a></li>
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
			<a href="#" class="nav-link">
				<i class='bx bxs-bell icon' ></i>
			</a>
			<a href="#" class="nav-link">
				<i class='bx bxs-message-square-dots icon' ></i>
			</a>
			<span class="divider"></span>
			<div class="profile">
				<img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixid=MnwxMjA3fDB8MHxzZWFyY2h8NHx8cGVvcGxlfGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="">
				<ul class="profile-link">
					<li><a href="logout.php"><i class='bx bxs-log-out-circle' ></i> Logout</a></li>
				</ul>
			</div>
		</nav>
		<main>
			<h1 class="title">Dashboard</h1>
			<ul class="breadcrumbs">
				<li><a href="#">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Dashboard</a></li>
			</ul>
			<div class="container">
		<!-- Form Container -->
		<div class="form-container">
			<h3>Input Data Guru</h3>
			<form id="dataForm">
				<div class="mb-3">
					<label for="name" class="form-label">Nama Guru</label>
					<input type="text" id="name" name="name" class="form-control" placeholder="Masukkan Nama Guru" required>
				</div>
				<div class="mb-3">
					<label for="subject" class="form-label">Mata Pelajaran</label>
					<input type="text" id="subject" name="subject" class="form-control" placeholder="Masukkan Mata Pelajaran" required>
				</div>
				<div class="mb-3">
					<label for="gender" class="form-label">Jenis Kelamin</label>
					<select id="gender" name="gender" class="form-select" required>
						<option value="" disabled selected>Pilih Jenis Kelamin</option>
						<option value="Laki-laki">Laki-laki</option>
						<option value="Perempuan">Perempuan</option>
					</select>
				</div>
				<button type="submit" class="button-submit btn btn-primary">Submit</button>
			</form>
		</div>

        <!-- Table -->
        <!-- Tabel untuk menampilkan data guru -->
<div class="table-container">
    <h3>Data Guru Tersimpan</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Guru</th>
                <th>Mata Pelajaran</th>
                <th>Jenis Kelamin</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['nama_guru'] . "</td>";
                echo "<td>" . $row['mata_pelajaran'] . "</td>";
                echo "<td>" . $row['jenis_kelamin'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>


<script>
document.getElementById('dataForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Mencegah reload halaman

    const formData = new FormData(this);

    fetch('guru.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // Mengubah response menjadi JSON
    .then(data => {
        if (data.success) {
            // Tambahkan data baru ke tabel
            const tableBody = document.getElementById('dataTable');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `<td>${data.name}</td><td>${data.subject}</td><td>${data.gender}</td>`;
            tableBody.appendChild(newRow);

            // Reset form setelah berhasil submit
            document.getElementById('dataForm').reset();

            alert("Data berhasil disimpan!");
        } else {
            alert("Gagal menyimpan data: " + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});
</script>


			</div>
		</main>
	</section>

	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
	<script src="script.js"></script>
</body>
</html>

