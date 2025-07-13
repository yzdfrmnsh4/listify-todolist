<?php
include 'koneksi.php';

$id = $_POST['id'];
$taskname = $_POST['task_name'];
$start = $_POST['start_time'];
$end = $_POST['end_time'];
$status = $_POST['status'];
$deskripsi = $_POST['description'];


$query = "UPDATE task_manager SET task_name = '$taskname', start_time = '$start', end_time = '$end', status = '$status', description = '$deskripsi'  WHERE id = '$id'";

$sql = mysqli_query($conn, $query);

session_start();

// if ($sql) {
//     $_SESSION["alertType"]="success";
//     $_SESSION["alertMessage"]="Data berhasil di UPDATE!";
// }else {
//     $_SESSION["alertType"]="danger";
//     $_SESSION["alertMessage"]="Gagal melakuakn UPDATE!";
// }

header("Location: mytask.php");
exit();

var_dump($query);
die;

?>