<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="style.css">
	<title>AdminSite</title>
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
		.form-container form .form-group {
			margin-bottom: 15px;
		}
		.form-container form label {
			display: block;
			margin-bottom: 5px;
		}
		.form-container form input {
			width: 100%;
			padding: 8px;
			border: 1px solid #ccc;
			border-radius: 5px;
		}
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
			background-color: #f4f4f4;
		}
		.button-submit {
			background-color: #4CAF50;
			color: white;
			padding: 10px 15px;
			border: none;
			border-radius: 5px;
			cursor: pointer;
		}
		.button-submit:hover {
			background-color: #45a049;
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
				<h3>Input Data</h3>
				<form id="dataForm">
					<div class="form-group">
						<label for="name">Nama</label>
						<input type="text" id="name" name="name" placeholder="Masukkan Nama" required>
					</div>
					<div class="form-group">
						<label for="class">Kelas</label>
						<input type="text" id="class" name="class" placeholder="Masukkan Kelas" required>
					</div>
					<button type="submit" class="button-submit">Submit</button>
				</form>
			</div>

			<!-- Table -->
			<div class="table-container">
				<h3>Data Tersimpan</h3>
				<table>
					<thead>
						<tr>
							<th>Nama</th>
							<th>Kelas</th>
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
	// JavaScript untuk menangani form submission dan menambahkan data ke tabel
	document.getElementById('dataForm').addEventListener('submit', function (event) {
		event.preventDefault(); // Mencegah reload halaman

		// Mengambil nilai input
		const name = document.getElementById('name').value;
		const classValue = document.getElementById('class').value;

		// Menambahkan data ke tabel
		const tableBody = document.getElementById('dataTable');
		const newRow = document.createElement('tr');
		newRow.innerHTML = `<td>${name}</td><td>${classValue}</td>`;
		tableBody.appendChild(newRow);

		// Reset form
		this.reset();
	});
</script>
			</div>
		</main>
	</section>

	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
	<script src="script.js"></script>
</body>
</html>

