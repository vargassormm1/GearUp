<?php

$host = "localhost";
$dbusername = "teamfour";
$dbpassword = "teamfourpass";
$db = "teamfourdatabase";

$conn = mysqli_connect($host, $dbusername, $dbpassword, $db);

if(!$conn) {
    die("Connot connect to Database" . mysqli_connect_error());
}


