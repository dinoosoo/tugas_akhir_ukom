<?php
$role = $_SESSION['role']; // Ambil role dari session
?>

<section id="sidebar">
    <a href="#" class="brand"><i class='bx bxs-book icon'></i> Tugas Digital</a>
    <ul class="side-menu">
        <li><a href="<?php echo $role == 'guru' ? 'guru_dashboard.php' : 'siswa_dashboard.php'; ?>" class="active"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>
        <li class="divider" data-text="main">Main</li>

        <?php if ($role == 'guru') { ?>
            <li><a href="kelola_tugas.php"><i class='bx bxs-edit icon'></i> Kelola Tugas</a></li>
            <li><a href="riwayat_tugas.php"><i class='bx bxs-folder icon'></i> Riwayat Tugas</a></li>
        <?php } else if ($role == 'siswa') { ?>
            <li><a href="tugas.php"><i class='bx bxs-upload icon'></i> Kumpul Tugas</a></li>
            <li><a href="riwayat_saya.php"><i class='bx bxs-folder-open icon'></i> Riwayat Saya</a></li>
        <?php } ?>

        <li><a href="logout.php"><i class='bx bxs-log-out icon'></i> Logout</a></li>
    </ul>
</section>
