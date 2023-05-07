

<?php

$dbhost = "eu-cdbr-west-03.cleardb.net";
$dbusername = "b970acd7548f7a";
$dbpassword = "688cf7c2";
$dbname = " b3897e115a7553d";
$connection = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbname);
if (!$connection) {
    die("Connection error: " . mysqli_connect_error());
}
