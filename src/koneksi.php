<?php

$host = "localhost";
$user = "root";
$password = "";
$db_name = "dbproject";


$conn = mysqli_connect($host, $user, $password, $db_name);


if (!$conn) {
    die("connection failed:" .mysqli_connect_error());
}

?>