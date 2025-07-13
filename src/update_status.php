<?php
// Sertakan file koneksi ke database
include 'koneksi.php';

// Ambil data dari permintaan POST
$taskId = $_POST['taskId'];
$newStatus = $_POST['newStatus'];

// Perbarui status tugas di database
$query = "UPDATE task_manager SET status='$newStatus' WHERE id=$taskId";
$result = mysqli_query($conn, $query);

// Berikan tanggapan ke klien
if ($result) {
    echo "success";
} else {
    echo "error";
}
?>
