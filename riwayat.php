<?php
session_start();
include 'koneksi.php';

// Cek role pengguna
$role = $_SESSION['role'];

// Query untuk mengambil data tugas
if ($role == 'admin') {
    // Jika role admin, ambil semua data tugas
    $query = "
        SELECT 
            tugas_terkumpul.id AS id_tugas_terkumpul,
            tugas_terkumpul.file_upload,
            tugas_terkumpul.waktu_pengumpulan AS waktu_dikumpulkan,
            tugas_terkumpul.feedback,
            form_tugas.judul,
            form_tugas.deskripsi,
            form_tugas.mata_pelajaran,
            form_tugas.kelas,
            form_tugas.waktu_pengumpulan AS batas_waktu,
            guru.nama_guru,
            tugas_terkumpul.nama_siswa
        FROM tugas_terkumpul
        INNER JOIN form_tugas ON tugas_terkumpul.id_tugas = form_tugas.id
        INNER JOIN guru ON form_tugas.mata_pelajaran = guru.mapel
    ";
} else {
    // Jika role siswa, ambil data tugas hanya untuk siswa yang login
    $nama_siswa = $_SESSION['nama_siswa'];
    $query = "
        SELECT 
            tugas_terkumpul.id AS id_tugas_terkumpul,
            tugas_terkumpul.file_upload,
            tugas_terkumpul.waktu_pengumpulan AS waktu_dikumpulkan,
            tugas_terkumpul.feedback,
            form_tugas.judul,
            form_tugas.deskripsi,
            form_tugas.mata_pelajaran,
            form_tugas.kelas,
            form_tugas.waktu_pengumpulan AS batas_waktu,
            guru.nama_guru,
            tugas_terkumpul.nama_siswa
        FROM tugas_terkumpul
        INNER JOIN form_tugas ON tugas_terkumpul.id_tugas = form_tugas.id
        INNER JOIN guru ON form_tugas.mata_pelajaran = guru.mapel
        WHERE tugas_terkumpul.nama_siswa = '$nama_siswa'
    ";
}

// Jalankan query
$result = mysqli_query($conn, $query);

// Cek apakah query berhasil dijalankan
if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .badge-tepat {
            background-color: #28a745;
            color: white;
        }

        .badge-terlambat {
            background-color: #dc3545;
            color: white;
        }
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
        <div class="container mt-4">
            <h3>Riwayat Tugas</h3>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Tugas yang Telah Dikumpulkan</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr style="text-align:center;">
                                            <th>No</th>
                                            <?php if ($role == 'admin') : ?>
                                              <th>Nama Siswa</th>
                                            <?php endif; ?>
                                            <th>Nama Tugas</th>
                                            <th>Guru</th>
                                            <th>Tanggal Pengumpulan</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (mysqli_num_rows($result) > 0) : ?>
                                            <?php $no = 1; ?>
                                            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                                <?php
                                                // Hitung status pengumpulan
                                                $waktu_dikumpulkan = new DateTime($row['waktu_dikumpulkan']);
                                                $batas_waktu = new DateTime($row['batas_waktu']);
                                                $status = ($waktu_dikumpulkan <= $batas_waktu) ? 'Tepat Waktu' : 'Terlambat';
                                                ?>
                                                <tr style="text-align:center;">
                                                    <td><?= $no++; ?></td>
                                                    <?php if ($role == 'admin') : ?>
                                                    <td><?= htmlspecialchars($row['nama_siswa']); ?></td>
                                                    <?php endif; ?>
                                                    <td><?= htmlspecialchars($row['judul']); ?></td>
                                                    <td><?= htmlspecialchars($row['nama_guru']); ?></td>
                                                    <td><?= htmlspecialchars($row['waktu_dikumpulkan']); ?></td>
                                                    <td>
                                                        <?php if ($status == 'Tepat Waktu') : ?>
                                                            <span class="badge badge-tepat">Tepat Waktu</span>
                                                        <?php else : ?>
                                                            <span class="badge badge-terlambat">Terlambat</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal<?= $row['id_tugas_terkumpul']; ?>">Detail</button>
                                                        <a href="uploads/<?= htmlspecialchars($row['file_upload']); ?>" download class="btn btn-sm btn-success">Download</a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">
                                                    <i class="fas fa-exclamation-circle fa-2x text-danger"></i><br>
                                                    Tidak ada tugas yang ditemukan.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</section>

<?php
// Reset pointer result ke awal
mysqli_data_seek($result, 0);
?>

<?php while ($row = mysqli_fetch_assoc($result)) : ?>
    <!-- Modal untuk Detail Tugas -->
    <div class="modal fade" id="detailModal<?= $row['id_tugas_terkumpul']; ?>" tabindex="-1" aria-labelledby="detailModalLabel<?= $row['id_tugas_terkumpul']; ?>" aria-hidden="true" data-bs-backdrop="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel<?= $row['id_tugas_terkumpul']; ?>">Detail Tugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nama Siswa:</strong> <?= htmlspecialchars($row['nama_siswa']); ?></p>
                            <p><strong>Judul Tugas:</strong> <?= htmlspecialchars($row['judul']); ?></p>
                            <p><strong>Deskripsi Tugas:</strong> <?= htmlspecialchars($row['deskripsi']); ?></p>
                            <p><strong>Nama Guru:</strong> <?= htmlspecialchars($row['nama_guru']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Mata Pelajaran:</strong> <?= htmlspecialchars($row['mata_pelajaran']); ?></p>
                            <p><strong>Kelas:</strong> <?= htmlspecialchars($row['kelas']); ?></p>
                            <p><strong>Tanggal Pengumpulan:</strong> <?= htmlspecialchars($row['waktu_dikumpulkan']); ?></p>
                            <p><strong>Feedback:</strong> <?= htmlspecialchars($row['feedback']); ?></p>
                            <p><strong>File:</strong> 
                                <a href="uploads/<?= htmlspecialchars($row['file_upload']); ?>" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Lihat File
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
<?php endwhile; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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
</body>
</html>