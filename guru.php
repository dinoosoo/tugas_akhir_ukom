<?php
session_start();
include 'koneksi.php';
$role = $_SESSION['role'];
// Proses pengiriman form tambah guru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah'])) {
    $nama_guru = $_POST['nama_guru'];
    $mapel = $_POST['mapel'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $nip = $_POST['nip'];

    // Query untuk menyimpan data guru ke database
    $sql = "INSERT INTO guru (nama_guru, mapel, jenis_kelamin, nip) 
            VALUES ('$nama_guru', '$mapel', '$jenis_kelamin', '$nip')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Data guru berhasil disimpan!'); window.location.href='guru.php';</script>";
    } else {
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
    }
}

// Proses edit data guru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama_guru = $_POST['nama_guru'];
    $mapel = $_POST['mapel'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $nip = $_POST['nip'];

    // Query untuk mengupdate data guru
    $sql = "UPDATE guru SET 
            nama_guru='$nama_guru', 
            mapel='$mapel', 
            jenis_kelamin='$jenis_kelamin', 
            nip='$nip' 
            WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Data guru berhasil diupdate!'); window.location.href='guru.php';</script>";
    } else {
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
    }
}

// Proses hapus data guru
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // Query untuk menghapus data guru
    $sql = "DELETE FROM guru WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Data guru berhasil dihapus!'); window.location.href='guru.php';</script>";
    } else {
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
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
                    <?php if ($_SESSION['role'] === 'admin') : ?>
        <li><a href="mapel.php"><i class='bx bx-task'></i> Mata Pelajaran</a></li>
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
            <i class='bx bx-menu toggle-sidebar'></i>
            <form action="#">
                <div class="form-group">
                    <input type="text" placeholder="Search...">
                    <i class='bx bx-search icon'></i>
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
                                            <tr style='text-align:center'>
                                                <th>No</th>
                                                <th>Nama Guru</th>
                                                <th>Mapel</th>
                                                <th>Jenis Kelamin</th>
                                                <th>NIP</th>
                                                <?php if ($role === 'guru') : ?>
                                                    <th>Aksi</th>
                                                <?php endif; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                         $no = 1;
                                         while ($row = $result->fetch_assoc()) {
                                         echo "<tr style='text-align:center'>
                                         <td>{$no}</td>
                                         <td>{$row['nama_guru']}</td>
                                         <td>{$row['mapel']}</td>
                                         <td>{$row['jenis_kelamin']}</td>
                                         <td>{$row['nip']}</td>";

                                        // Jika pengguna adalah guru, tampilkan tombol edit dan hapus
                                       if ($role === 'admin') {
                                        echo "<td>
                                        <button class='btn btn-sm btn-warning edit-btn' data-bs-toggle='modal' data-bs-target='#editModal{$row['id']}'>
                                        <i class='bi bi-pencil-square'></i> Edit
                                        </button>
                                        <a href='guru.php?hapus={$row['id']}' class='btn btn-sm btn-danger delete-btn' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>
                                        <i class='bi bi-trash'></i> Hapus
                                        </a>
                                        </td>";
                                          }
                                      echo "</tr>";
                                                // Modal untuk Edit Data
                                                echo "
                                                <div class='modal fade' id='editModal{$row['id']}' tabindex='-1' aria-labelledby='editModalLabel{$row['id']}' aria-hidden='true'>
                                                    <div class='modal-dialog'>
                                                        <div class='modal-content'>
                                                            <div class='modal-header'>
                                                                <h5 class='modal-title' id='editModalLabel{$row['id']}'>Edit Guru</h5>
                                                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                            </div>
                                                            <form action='guru.php' method='POST'>
                                                                <div class='modal-body'>
                                                                    <input type='hidden' name='id' value='{$row['id']}'>
                                                                    <div class='mb-3'>
                                                                        <label for='nama_guru' class='form-label'>Nama Guru</label>
                                                                        <input type='text' class='form-control' id='nama_guru' name='nama_guru' value='{$row['nama_guru']}' required>
                                                                    </div>
                                                                    <div class='mb-3'>
                                                                        <label for='mapel' class='form-label'>Mata Pelajaran</label>
                                                                        <input type='text' class='form-control' id='mapel' name='mapel' value='{$row['mapel']}' required>
                                                                    </div>
                                                                    <div class='mb-3'>
                                                                        <label for='jenis_kelamin' class='form-label'>Jenis Kelamin</label>
                                                                        <select class='form-select' id='jenis_kelamin' name='jenis_kelamin' required>
                                                                            <option value='Laki - laki'" . ($row['jenis_kelamin'] == 'Laki - laki' ? ' selected' : '') . ">Laki-laki</option>
                                                                            <option value='Perempuan'" . ($row['jenis_kelamin'] == 'Perempuan' ? ' selected' : '') . ">Perempuan</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class='mb-3'>
                                                                        <label for='nip' class='form-label'>NIP</label>
                                                                        <input type='text' class='form-control' id='nip' name='nip' value='{$row['nip']}' required>
                                                                    </div>
                                                                </div>
                                                                <div class='modal-footer'>
                                                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Batal</button>
                                                                    <button type='submit' name='edit' class='btn btn-primary'>Simpan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>";
                                                $no++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom kanan: Form Tambah Guru -->
                    <?php if ($role === 'admin') : ?>
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
    <select class="form-select" id="mapel" name="mapel" required>
        <option value="">-- Pilih Mata Pelajaran --</option>
        <?php
        // Ambil data dari tabel mapel_es
        $queryMapel = "SELECT * FROM mapel";
        $resultMapel = $conn->query($queryMapel);
        while ($rowMapel = $resultMapel->fetch_assoc()) {
            echo "<option value='{$rowMapel['mapel']}'>{$rowMapel['mapel']}</option>";
        }
        ?>
    </select>
</div>

                                    <div class="mb-3">
                                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                            <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                            <option value="Laki - laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nip" class="form-label">NIP</label>
                                        <input type="text" class="form-control" id="nip" name="nip" required>
                                    </div>
                                    <button type="submit" name="tambah" class="btn btn-primary">Tambah Guru</button>
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
