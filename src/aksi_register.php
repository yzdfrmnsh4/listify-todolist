<?php

require 'koneksi.php';

if(isset($_POST['fullname']) && isset($_POST['email']) && isset($_POST['password'])) {
    // Ambil data dari form register
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Masukkan data ke database
    $sql = "INSERT INTO tuser (fullname, email, password) VALUES ('$fullname','$email', '$password')";

    if (mysqli_query($conn, $sql)) {
        header("Location: login.php");
    } else {
        echo "Pendaftaran gagal : " . mysqli_error($conn);
    }
} else {
    echo "Harap lengkapi semua field!";
}

?>