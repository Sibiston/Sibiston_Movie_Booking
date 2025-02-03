<?php
$host = "localhost";
$user = "root";
$pass ="";
$db = "reserveyourseat";
$con = mysqli_connect($host,$user,$pass,$db);
if ($con) {
    echo "<script>console.log('Database connection successful');</script>";
} else {
    echo "<script>console.log('Database connection failed');</script>";
}
?>