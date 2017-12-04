<?php
// connection variables
$user = "root";
$password = "";
$project = "nal9";
$driver = "MySQL ODBC 5.3 ANSI Driver";
$host = "localhost";


$connection = "Driver=$driver; Server=$host; DATABASE=$project"; // connection string
$conn = odbc_connect($connection, $user, $pass); // establish odbc connection
?>