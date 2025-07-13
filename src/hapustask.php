<?php
// Sertakan file koneksi ke database
include 'koneksi.php';

// Periksa apakah parameter 'id' ada dalam URL
if (isset($_GET['id'])) {
    // Dapatkan ID tugas dari URL dan bersihkan
    $task_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Query SQL untuk menghapus tugas berdasarkan ID
    $query = "DELETE FROM task_manager WHERE id = $task_id";

    // Jalankan query
    if (mysqli_query($conn, $query)) {
        // Jika penghapusan berhasil, kembalikan ke halaman sebelumnya
        header("Location: mytask.php");
        exit();
    } else {
        // Jika terjadi kesalahan, tampilkan pesan kesalahan atau lakukan penanganan yang sesuai
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // Jika parameter 'id' tidak tersedia dalam URL, lakukan penanganan yang sesuai
    echo "ID tugas tidak ditemukan.";
}
?>
