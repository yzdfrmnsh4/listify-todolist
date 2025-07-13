<?php
session_start(); // Mulai sesi

include 'koneksi.php';

// Periksa apakah data dari form telah dikirim
if(isset($_POST['task_name']) && isset($_POST['task_description']) && isset($_POST['tanggal'])) {
    // Ambil data dari form
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    $tanggal = $_POST['tanggal'];
    
    // Jika user sudah login, ambil user_id dari sesi
    if(isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Masukkan data ke dalam database
        $sql = "INSERT INTO task (user_id, task_name, task_description, status, tanggal) VALUES ('$user_id', '$task_name', '$task_description', 'To Do', '$tanggal')";
        if (mysqli_query($conn, $sql)) {
            // Redirect kembali ke halaman ToDo list setelah berhasil menambahkan tugas
            header("Location: mytodo.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        // Jika user belum login, mungkin perlu memberikan pesan atau mengarahkan ke halaman login
        echo "User not logged in.";
    }
} else {
    // Jika data form tidak lengkap, kembalikan pengguna ke halaman sebelumnya atau tampilkan pesan kesalahan
    echo "Form data not complete.";
}
?>
