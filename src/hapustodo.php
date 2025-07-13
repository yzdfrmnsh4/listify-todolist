<?php
include 'koneksi.php';

// Pastikan parameter task_id telah diterima
if(isset($_GET['id'])) {
    // Tangani penghapusan tugas berdasarkan task_id
    $task_id = $_GET['id'];
    
    // Query untuk menghapus tugas dari database
    $sql = "DELETE FROM task WHERE task_id=$task_id";

    // Lakukan query penghapusan
    if (mysqli_query($conn, $sql)) {
        // Jika berhasil, arahkan kembali ke halaman coba.php
        header("Location: mytodo.php");
        exit();
    } else {
        // Jika terjadi kesalahan, tampilkan pesan error
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    // Jika parameter task_id tidak ditemukan, tampilkan pesan error
    echo "Task ID not provided.";
}
?>
