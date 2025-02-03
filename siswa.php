<?php
// Koneksi ke database
$host = "localhost";
$user = "root"; 
$pass = ""; 
$db   = "tugas_digital";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Proses penyimpanan data jika ada permintaan POST (AJAX)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_siswa = mysqli_real_escape_string($conn, $_POST['name']);
    $kelas = mysqli_real_escape_string($conn, $_POST['class']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['gender']);
    $nisn = mysqli_real_escape_string($conn, $_POST['nisn']);

    $query = "INSERT INTO siswa (nama_siswa, kelas, jenis_kelamin, nisn) 
              VALUES ('$nama_siswa', '$kelas', '$jenis_kelamin', '$nisn')";

    if (mysqli_query($conn, $query)) {
        echo json_encode([
            "status" => "success",
            "data" => [
                "name" => $nama_siswa,
                "class" => $kelas,
                "gender" => $jenis_kelamin,
                "nisn" => $nisn
            ]
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
    exit;
}

// Ambil data siswa dari database
$result = mysqli_query($conn, "SELECT * FROM siswa");
?>



<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="style.css">
	<title>Siswa</title>
    <style>
    /* Styling tambahan untuk tata letak form dan tabel */
    .container {
        display: flex;
        gap: 20px;
        margin-top: 20px;
        justify-content: space-between;
    }
    .form-container, .table-container {
        width: 48%;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        height: 500px; /* Menetapkan tinggi tetap */
        overflow: hidden; /* Menyembunyikan overflow dari form dan tabel */
    }
    .form-container {
        overflow-y: auto; /* Menambahkan scroll pada form jika terlalu panjang */
    }
    .table-container {
        overflow-y: auto; /* Menambahkan scroll pada tabel jika data terlalu banyak */
    }
    .form-container h3, .table-container h3 {
        color: #333;
        font-size: 1.4em;
        margin-bottom: 15px;
    }
    .form-container form .form-group {
        margin-bottom: 20px;
    }
    .form-container form label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }
    .form-container form input,
    .form-container form select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        background-color: #fff;
        font-size: 1em;
    }
    .form-container form input:focus,
    .form-container form select:focus {
        border-color: #4CAF50;
        outline: none;
    }
    .table-container table {
    width: 100%;
    border-collapse: collapse; /* Menyatukan border tabel */
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #ddd; /* Menambahkan border pada tabel */
}

.table-container table th,
.table-container table td {
    padding: 12px;
    text-align: left;
    border: 1px solid #ddd; /* Menambahkan border pada th dan td */
}

.table-container table th {
    background-color: #007BFF; /* Mengubah warna header tabel menjadi biru */
    color: white;
    font-weight: bold;
}

.table-container table tr:nth-child(even) {
    background-color: #f1faff; /* Warna latar belakang baris genap biru muda */
}

.table-container table tr:hover {
    background-color: #e0f7ff; /* Efek hover dengan warna biru lebih terang */
}
    .button-submit {
        background-color: #4CAF50;
        color: white;
        padding: 12px 18px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 1.1em;
        transition: background-color 0.3s ease;
    }
    /* Styling untuk tombol submit agar berada di sebelah kanan */
.form-container form {
    display: flex;
    flex-direction: column;
    align-items: flex-start; /* Menjaga elemen tetap teratur di kiri */
}

.button-submit {
    align-self: flex-end; /* Menempatkan tombol di sebelah kanan */
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
			<!-- Main content -->
			<div class="info-data">
				<!-- Add content here -->
                <!-- Main content -->
		<div class="container">
			<!-- Form -->
			<div class="form-container">
				<h3>Input Data Siswa</h3>
				<form id="dataForm">
					<div class="form-group">
						<label for="name">Nama Siswa</label>
						<input type="text" id="name" name="name" placeholder="Masukkan Nama Siswa" required>
					</div>
					<div class="form-group">
						<label for="class">Kelas</label>
						<input type="text" id="class" name="class" placeholder="Masukkan Kelas" required>
					</div>
					<div class="form-group">
						<label for="gender">Jenis Kelamin</label>
						<select id="gender" name="gender" required>
							<option value="Laki-Laki">Laki-Laki</option>
							<option value="Perempuan">Perempuan</option>
						</select>
					</div>
					<div class="form-group">
						<label for="nisn">NISN</label>
						<input type="text" id="nisn" name="nisn" placeholder="Masukkan NISN" required>
					</div>
					<button type="submit" class="button-submit">Submit</button>
				</form>
			</div>

			<!-- Table -->
			<div class="table-container">
				<h3>Data Siswa Tersimpan</h3>
				<table>
					<thead>
						<tr>
							<th>Nama Siswa</th>
							<th>Kelas</th>
							<th>Jenis Kelamin</th>
							<th>NISN</th>
						</tr>
					</thead>
					<tbody id="dataTable">
						<!-- Data akan ditambahkan di sini -->
					</tbody>
				</table>
			</div>
		</div>
	</main>
</section>

<script>
document.getElementById('dataForm').addEventListener('submit', function (event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('siswa.php', { 
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) 
    .then(data => {
        if (data.status === 'success') {
            alert('Data berhasil disimpan!');

            // Tambahkan data baru ke tabel tanpa reload
            const table = document.getElementById('dataTable');
            const newRow = table.insertRow();
            
            newRow.innerHTML = `
                <td>${data.data.name}</td>
                <td>${data.data.class}</td>
                <td>${data.data.gender}</td>
                <td>${data.data.nisn}</td>
            `;

            // Reset form setelah submit
            document.getElementById('dataForm').reset();
        } else {
            alert('Terjadi kesalahan: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});
</script>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="script.js"></script>
</body>
</html>
