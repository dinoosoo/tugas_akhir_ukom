<?php
include 'includes/config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data kelas dari database
    $query = mysqli_query($conn, "SELECT * FROM kelas WHERE id = '$id'");
    $data = mysqli_fetch_assoc($query);
} else {
    echo "ID tidak ditemukan!";
    exit();
}
?>

<!-- Form Edit Kelas -->
<form method="POST" action="update_kelas.php">
    <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
    <label for="kelas">Nama Kelas:</label>
    <input type="text" name="kelas" value="<?php echo $data['kelas']; ?>" required>
    <button type="submit" name="update">Update</button>
</form>
