<?php
session_start();
$user_role = $_SESSION['role'] ?? 'siswa'; // Gunakan role dari session atau default ke siswa
date_default_timezone_set('Asia/Jakarta'); // Set the timezone
include 'koneksi.php';

// Cek role user
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'siswa'; // Default siswa

// Ambil data guru
$guru_result = mysqli_query($conn, "SELECT * FROM tugas_digital.guru");
$guru_data = [];
while ($guru = mysqli_fetch_assoc($guru_result)) {
    $guru_data[$guru['mapel']] = $guru['nama_guru'];
}

/////////////////////////////////
// 1. Tambah Data Tugas
/////////////////////////////////
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tambah"])) {
    $judul = mysqli_real_escape_string($conn, $_POST["judul"]);
    $deskripsi = mysqli_real_escape_string($conn, $_POST["deskripsi"]);
    $mapel = mysqli_real_escape_string($conn, $_POST["mapel"]);
    $kelas = mysqli_real_escape_string($conn, $_POST["kelas"]);
    $waktu = $_POST["waktu"];

    $query = "INSERT INTO form_tugas (judul, deskripsi, mata_pelajaran, kelas, waktu_pengumpulan) 
              VALUES ('$judul', '$deskripsi', '$mapel', '$kelas', '$waktu')";

    if (mysqli_query($conn, $query)) {
        header("Location: tugas.php");
    } else {
        echo "Error saat menambah data: " . mysqli_error($conn);
    }
}

/////////////////////////////////
// 2. Edit Data Tugas
/////////////////////////////////
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit"])) {
    $id = $_POST["id"];
    $judul = mysqli_real_escape_string($conn, $_POST["judul"]);
    $deskripsi = mysqli_real_escape_string($conn, $_POST["deskripsi"]);
    $mapel = mysqli_real_escape_string($conn, $_POST["mapel"]);
    $kelas = mysqli_real_escape_string($conn, $_POST["kelas"]);
    $waktu = $_POST["waktu"];

    $query = "UPDATE form_tugas SET judul='$judul', deskripsi='$deskripsi', mata_pelajaran='$mapel', kelas='$kelas', waktu_pengumpulan='$waktu' WHERE id='$id'";

    if (mysqli_query($conn, $query)) {
        header("Location: tugas.php");
    } else {
        echo "Error saat mengedit data: " . mysqli_error($conn);
    }
}

/////////////////////////////////
// 3. Hapus Data Tugas
/////////////////////////////////
if (isset($_POST["hapus"])) {
    $id = $_POST["id"];
    $query = "DELETE FROM form_tugas WHERE id='$id'";

    if (mysqli_query($conn, $query)) {
        header("Location: tugas.php");
    } else {
        echo "Error saat menghapus data: " . mysqli_error($conn);
    }
}

// Pagination Setup
$limit = 10; // jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$start = ($page - 1) * $limit;

// Hitung total data
$total_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM form_tugas");
$total_row = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_row / $limit);

// Ambil data sesuai halaman
$result = mysqli_query($conn, "SELECT * FROM form_tugas LIMIT $start, $limit");
$no = $start + 1;



/////////////////////////////////
// 5. Proses Kumpul Tugas (Siswa)
/////////////////////////////////
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tugas_terkumpul'])) {
    $id_tugas = $_POST['tugas_id']; // Ambil ID tugas yang dikumpulkan
    $nama_siswa = $_SESSION['nama_siswa']; // Ambil nama siswa dari session
    $waktu_pengumpulan = date('Y-m-d H:i:s');

    // Proses upload file
    $file_name = $_FILES['file_upload']['name'];
    $file_tmp = $_FILES['file_upload']['tmp_name'];
    $upload_dir = "uploads/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_path = $upload_dir . basename($file_name);

    if (move_uploaded_file($file_tmp, $file_path)) {
        // Simpan ke database dengan ID tugas yang benar
        $query = "INSERT INTO tugas_terkumpul (id_tugas, nama_siswa, file_upload, waktu_pengumpulan) 
                  VALUES ('$id_tugas', '$nama_siswa', '$file_name', '$waktu_pengumpulan')";

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Tugas berhasil dikumpulkan!'); window.location.href='tugas.php';</script>";
        } else {
            echo "Error saat menyimpan tugas terkumpul: " . mysqli_error($conn);
        }
    } else {
        echo "Gagal mengupload file.";
    }
}

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
                    <li><a href="kelas.php"><i class='bx bx-task'></i> Kelas</a></li>
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

        <div class="container mt-4">
        <h2 class="text-start mb-3">Daftar Tugas</h2>
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Data Tugas</h5>
            </div>
            <div class="card-body">
                <?php if ($user_role == 'guru'): ?>
                    <div class="mb-3">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahTugas">
                            <i class="bx bx-plus"></i> Tambah Tugas
                        </button>
                    </div>
                <?php endif; ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered border">
                        <thead class="bg-secondary text-white text-center">
                            <tr>
                                <?php if ($user_role == 'guru'): ?>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Deskripsi</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Kelas</th>
                                    <th>Waktu Pengumpulan</th>
                                    <th>Aksi</th>
                                <?php else: ?>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Deskripsi</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Nama Guru</th>
                                    <th>Waktu Pengumpulan</th>
                                    <th>Status</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = mysqli_query($conn, "SELECT * FROM form_tugas LIMIT $start, $limit");
                            $no = 1;
                            
                            while ($row = mysqli_fetch_assoc($result)) {
                                $nama_guru = isset($guru_data[$row['mata_pelajaran']]) ? $guru_data[$row['mata_pelajaran']] : 'Tidak Diketahui';
                                echo "<tr style='text-align:center;'>
                                        <td>{$no}</td>
                                        <td>{$row['judul']}</td>
                                        <td>{$row['deskripsi']}</td>
                                        <td>{$row['mata_pelajaran']}</td>";

                                if ($user_role === 'guru') {
                                    echo "<td>{$row['kelas']}</td>
                                        <td>{$row['waktu_pengumpulan']}</td>
                                        <td>
                                            <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editTugas{$row['id']}'>
                                                <i class='bx bx-edit'></i>
                                            </button>
                                            <form method='POST' style='display:inline-block;' onsubmit='return confirm(\"Yakin ingin hapus?\");'>
                                                <input type='hidden' name='id' value='{$row['id']}'>
                                                <button type='submit' name='hapus' class='btn btn-danger btn-sm'>
                                                    <i class='bx bx-trash'></i> Hapus
                                                </button>
                                            </form>
                                        </td>";
                                } else {
                                    // Cek apakah siswa sudah mengumpulkan tugas ini
                                    $id_tugas = $row['id'];
                                    $nama_siswa = $_SESSION['nama_siswa'] ?? ''; // Ambil nama siswa dari session
                                    $query_kumpul = "SELECT * FROM tugas_terkumpul WHERE id_tugas = '$id_tugas' AND nama_siswa = '$nama_siswa'";
                                    $result_kumpul = mysqli_query($conn, $query_kumpul);
                                    $sudah_kumpul = mysqli_num_rows($result_kumpul) > 0;

                                    echo "<td>{$nama_guru}</td>
                                        <td>{$row['waktu_pengumpulan']}</td>
                                        <td>";
                                    if ($sudah_kumpul) {
                                        echo "<span class='text-success'>Selesai</span>";
                                    } else {
                                        echo "<button class='btn btn-success btn-sm' data-bs-toggle='modal' data-bs-target='#kumpulModal{$row['id']}'>
                                                <i class='bx bx-upload'></i> Kumpul
                                            </button>";
                                    }
                                    echo "</td>";
                                }
                                echo "</tr>";

                                // Modal untuk Kumpul Tugas
                                if ($user_role !== 'guru') {
                                    echo "
                                    <div class='modal fade' id='kumpulModal{$row['id']}' tabindex='-1' aria-labelledby='kumpulModalLabel{$row['id']}' aria-hidden='true'>
                                        <div class='modal-dialog'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                    <h5 class='modal-title' id='kumpulModalLabel{$row['id']}'>Kumpul Tugas - {$row['judul']}</h5>
                                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                </div>
                                                <form action='tugas.php' method='POST' enctype='multipart/form-data'>
                                                    <div class='modal-body'>
                                                        <input type='hidden' name='tugas_id' value='{$row['id']}'>
                                                        <div class='mb-3'>
                                                            <label for='fileUpload{$row['id']}' class='form-label'>Upload Tugas</label>
                                                            <input type='file' class='form-control' id='fileUpload{$row['id']}' name='file_upload' accept='image/*,application/pdf' required>
                                                        </div>
                                                    </div>
                                                    <div class='modal-footer'>
                                                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Batal</button>
                                                        <button type='submit' name='tugas_terkumpul' class='btn btn-primary'>Kirim Tugas</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>";
                                }

                                // Modal untuk Edit Tugas (Hanya untuk Guru)
                                if ($user_role == 'guru') {
                                    echo "
                                    <div class='modal fade' id='editTugas{$row['id']}' tabindex='-1'>
                                        <div class='modal-dialog'>
                                            <div class='modal-content'>
                                                <div class='modal-header bg-primary text-white'>
                                                    <h5>Edit Tugas</h5>
                                                    <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                                </div>
                                                <div class='modal-body'>
                                                    <form method='POST'>
                                                        <input type='hidden' name='id' value='{$row['id']}'>
                                                        <div class='mb-3'>
                                                            <label>Judul</label>
                                                            <input type='text' name='judul' class='form-control' value='{$row['judul']}' required>
                                                        </div>
                                                        <div class='mb-3'>
                                                            <label>Deskripsi</label>
                                                            <textarea name='deskripsi' class='form-control' required>{$row['deskripsi']}</textarea>
                                                        </div>
                                                        <div class='mb-3'>
                                                            <label>Mata Pelajaran</label>
                                                            <input type='text' name='mapel' class='form-control' value='{$row['mata_pelajaran']}' required>
                                                        </div>
                                                        <div class='mb-3'>
                                                            <label>Kelas</label>
                                                            <select name='kelas' class='form-control' required>
                                                                <option value=''>-- Pilih Kelas --</option>";
                                                                $kelas_result = mysqli_query($conn, "SELECT * FROM kelas");
                                                                while ($kelas = mysqli_fetch_assoc($kelas_result)) {
                                                                    $selected = ($kelas['kelas'] == $row['kelas']) ? "selected" : "";
                                                                    echo "<option value='{$kelas['kelas']}' $selected>{$kelas['kelas']}</option>";
                                                                }
                                                    echo "</select>
                                                        </div>
                                                        <div class='mb-3'>
                                                            <label>Waktu Pengumpulan</label>
                                                            <input type='datetime-local' name='waktu' class='form-control' value='{$row['waktu_pengumpulan']}' required>
                                                        </div>
                                                        <div class='modal-footer'>
                                                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Batal</button>
                                                            <button type='submit' name='edit' class='btn btn-primary'>Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
                                }
                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>
<!-- Pagination -->
<div class="mt-3 text-center">
    <nav>
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page - 1 ?>">Sebelumnya</a>
                </li>
            <?php endif; ?>

            <?php
            // Buat max 5 tombol halaman tampil (misal 1 2 3 4 5)
            $range = 2; // jumlah range sebelum dan sesudah
            $start_page = max(1, $page - $range);
            $end_page = min($total_pages, $page + $range);

            for ($i = $start_page; $i <= $end_page; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page + 1 ?>">Berikutnya</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>


            </div>
        </div>
    </div>

<?php if ($user_role == 'guru'): ?>
<!-- Modal Tambah Tugas -->
<div class='modal fade' id='tambahTugas' tabindex='-1'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header bg-primary text-white'>
                <h5>Tambah Tugas</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
            </div>
            <div class='modal-body'>
                <form method='POST'>
                    <div class='mb-3'>
                        <label>Judul</label>
                        <input type='text' name='judul' class='form-control' required>
                    </div>
                    <div class='mb-3'>
                        <label>Deskripsi</label>
                        <textarea name='deskripsi' class='form-control' required></textarea>
                    </div>
                    <div class='mb-3'>
                        <label>Mata Pelajaran</label>
                        <select name='mapel' class='form-control' required>
                            <option value=''>-- Pilih Mata Pelajaran --</option>
                            <?php
                            foreach ($guru_data as $mapel => $nama_guru) {
                                echo "<option value='{$mapel}'>{$mapel}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class='mb-3'>
                        <label>Kelas</label>
                        <select name='kelas' class='form-control' required>
                            <option value=''>-- Pilih Kelas --</option>
                            <?php
                            $kelas_result = mysqli_query($conn, "SELECT * FROM kelas");
                            while ($kelas = mysqli_fetch_assoc($kelas_result)) {
                                echo "<option value='{$kelas['kelas']}'>{$kelas['kelas']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class='mb-3'>
                        <label>Waktu Pengumpulan</label>
                        <input type='datetime-local' name='waktu' class='form-control' required>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Batal</button>
                        <button type='submit' name='tambah' class='btn btn-primary'>Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.addEventListener('change', function () {
                const fileName = this.files[0] ? this.files[0].name : 'Belum ada file dipilih';
                alert('File dipilih: ' + fileName);
            });
        });
    });
</script> -->

<script>
    document.getElementById('btnTambahTugas').addEventListener('click', function() {
        document.getElementById('formTugas').style.display = 'block';
        this.style.display = 'none';
    });
    
    document.getElementById('btnBatal').addEventListener('click', function() {
        document.getElementById('formTugas').style.display = 'none';
        document.getElementById('btnTambahTugas').style.display = 'block';
    });
</script>

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
