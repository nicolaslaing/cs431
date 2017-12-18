<?php
// connection variables
$user = "root";
$pass = "";
$project = "nal9";
$driver = "MySQL ODBC 5.3 ANSI Driver";
$host = "localhost";


$connection = "Driver=$driver; Server=$host; DATABASE=$project"; // connection string
$db = odbc_connect($connection, $user, $pass); // establish odbc connection
?>
