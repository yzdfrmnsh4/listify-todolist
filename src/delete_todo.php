<?php
session_start(); // Mulai sesi

include 'koneksi.php';

// Periksa apakah data dari form telah dikirim
if (isset($_POST['delete_task_id'])) {
    $task_id = $_POST['delete_task_id'];

    // Hapus data dari database
    $sql = "DELETE FROM task WHERE task_id = '$task_id'";
    if (mysqli_query($conn, $sql)) {
        // Redirect kembali ke halaman ToDo list setelah berhasil menghapus tugas
        header("Location: mytodo.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
?>
