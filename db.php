<?php

$dbhost = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "faculty_eval";
$connection = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbname);
if (!$connection) {
    die("Connection error: " . mysqli_connect_error());
}