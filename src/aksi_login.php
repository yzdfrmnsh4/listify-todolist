<?php

include 'koneksi.php';
session_start();

// menerima data yang di inpiutkan pada form login
$email = $_POST['email'];
$password = $_POST['password'];

// var_dump($email, $password);
// die("connection failed:" .mysqli_connect_error());

$query = "SELECT * FROM tuser WHERE email ='$email' AND password = '$password'";
$login = mysqli_query($conn, $query);


$cek = mysqli_num_rows($login);


if ($cek>0) {
    $data = mysqli_fetch_assoc($login);
    $_SESSION['user_id'] = $data['id'];
    $_SESSION['fullname'] = $data['fullname'];
    $_SESSION['email'] = $data['email'];

    echo"<script>window.location.href='home.php';</script>";
    
   
}else {
    $message = "Username atau Password Salah!";
    // echo "<script>alert('$message')</script>";
    echo "<script> window.location.href='./login.php';alert('$message');</script>";
    exit();

}
?>