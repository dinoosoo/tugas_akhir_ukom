<?php
session_start();
include 'koneksi.php';

// Cek apakah request POST mengandung data yang diperlukan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id_tugas_terkumpul = $_POST['id'];

    // Ambil nama file dari database
    $query_select = "SELECT file_upload FROM tugas_terkumpul WHERE id = ?";
    $stmt_select = $conn->prepare($query_select);
    $stmt_select->bind_param("i", $id_tugas_terkumpul);
    $stmt_select->execute();
    $stmt_select->store_result();

    if ($stmt_select->num_rows === 1) {
        $stmt_select->bind_result($file_upload);
        $stmt_select->fetch();

        // Hapus file dari folder uploads/
        if (!empty($file_upload)) {
            $file_path = "uploads/" . $file_upload;
            if (file_exists($file_path)) {
                unlink($file_path); // Hapus file
            }
        }

        // Query untuk menghapus data tugas terkumpul
        $query_delete = "DELETE FROM tugas_terkumpul WHERE id = ?";
        $stmt_delete = $conn->prepare($query_delete);
        $stmt_delete->bind_param("i", $id_tugas_terkumpul);

        if ($stmt_delete->execute()) {
            // Jika berhasil, kembalikan ke halaman tugas_terkumpul.php dengan pesan sukses
            header("Location: tugas_terkumpul.php?hapus_success=1");
            exit();
        } else {
            // Jika gagal, tampilkan pesan error
            die("Error saat menghapus data: " . $stmt_delete->error);
        }
    } else {
        // Jika data tidak ditemukan
        die("Data tidak ditemukan.");
    }
} else {
    // Jika tidak ada data yang dikirim, kembalikan ke halaman tugas_terkumpul.php
    header("Location: tugas_terkumpul.php");
    exit();
}
?>