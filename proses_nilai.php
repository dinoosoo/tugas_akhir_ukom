<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_nilai'])) {
    $id_tugas_terkumpul = $_POST['id_tugas_terkumpul'];
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);

    // Query untuk update feedback
    $query = "UPDATE tugas_terkumpul SET feedback = '$feedback' WHERE id = '$id_tugas_terkumpul'";

    if (mysqli_query($conn, $query)) {
        // Jika berhasil, kembalikan ke halaman tugas.php dengan pesan sukses
        header("Location: tugas_terkumpul.php?feedback_success=1");
        exit();
    } else {
        // Jika gagal, tampilkan pesan error
        die("Error: " . mysqli_error($conn));
    }
} else {
    // Jika tidak ada data yang dikirim, kembalikan ke halaman tugas.php
    header("Location: tugas_terkumpul.php");
    exit();
}
?>