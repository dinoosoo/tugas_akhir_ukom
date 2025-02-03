<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "tugas_digital";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name']) && isset($_POST['class'])) {
        $nama = $_POST['name'];
        $kelas = $_POST['class'];

        $sql = "INSERT INTO kelas (nama, kelas) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nama, $kelas);

        if ($stmt->execute()) {
            echo "Data berhasil disimpan";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Data tidak lengkap!";
    }
}

// Ambil data dari database untuk ditampilkan di tabel
$sql = "SELECT nama, kelas FROM kelas";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="style.css">
	<title>Kelas</title>
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
		border-radius: 10px;
		background-color: #f4f7fa;
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
	}
	.form-container h3, .table-container h3 {
		color: #2c3e50;
		font-size: 24px;
		margin-bottom: 15px;
	}
	.form-container form .form-group {
		margin-bottom: 15px;
	}
	.form-container form label {
		display: block;
		margin-bottom: 5px;
		color: #2c3e50;
		font-weight: bold;
	}
	.form-container form input {
		width: 100%;
		padding: 10px;
		border: 1px solid #ccc;
		border-radius: 5px;
		font-size: 16px;
	}
	/* Tombol submit disesuaikan ke kanan dan warna hijau */
	.form-container form .button-container {
		display: flex;
		justify-content: flex-end;
	}
	.button-submit {
		background-color: #4CAF50; /* Hijau */
		color: white;
		padding: 12px 20px;
		border: none;
		border-radius: 5px;
		cursor: pointer;
		font-size: 16px;
	}
	.button-submit:hover {
		background-color: #45a049; /* Warna hijau gelap */
	}

	/* Styling untuk tabel */
	.table-container table {
		width: 100%;
		border-collapse: collapse;
		margin-top: 20px;
	}
	.table-container table th, .table-container table td {
		padding: 12px 15px;
		border: 1px solid #ddd;
		text-align: left;
	}
	.table-container table th {
		background-color: #3498db;
		color: white;
		font-weight: bold;
	}
	.table-container table td {
		background-color: #f9f9f9;
	}
	.table-container table tr:nth-child(even) td {
		background-color: #ecf0f1;
	}
	.table-container table tr:hover td {
		background-color: #d6eaf8;
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
	<!-- Membungkus tombol submit dengan div untuk mengaturnya ke kanan -->
	<div class="button-container">
		<button type="submit" class="button-submit">Submit</button>
	</div>
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
    <?php
        // Ambil dan tampilkan data dari database
        $sql = "SELECT nama, kelas FROM kelas";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Menampilkan data yang ada di database
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["nama"] . "</td><td>" . $row["kelas"] . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='2'>Tidak ada data</td></tr>";
        }
    ?>
</tbody>

				</table>
			</div>
		</div>
	</main>
</section>

<script>
document.getElementById('dataForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Mencegah reload halaman

    // Mengambil nilai input
    const name = document.getElementById('name').value;
    const kelas = document.getElementById('class').value;

    // Kirim data ke server menggunakan AJAX
    const formData = new FormData(this);
    
    fetch('kelas.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // Menampilkan pesan pop-up dengan teks singkat
        alert("Data berhasil disimpan");

        // Menambahkan data ke tabel
        const tableBody = document.getElementById('dataTable');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `<td>${name}</td><td>${kelas}</td>`;
        tableBody.appendChild(newRow);

        // Reset form setelah submit
        this.reset();
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

