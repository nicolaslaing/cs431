<html>
	<head>
		<link rel="stylesheet" href="styles.css">
	</head>
</html>
<?php
session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);  
ini_set('display_errors' , 1);

include ("functions.php");

gatekeeper();
//****************************************************************************************
//  GLOBALS
$bad = false;
$user = $_SESSION["user"];
//****************************************************************************************
// INSTANTIATION
echo "<h1 class='stnrd'>Action Executed</h1>"; // HTML
echo "<fieldset id='fieldset'>"; // HTML
// DEPOSIT
if($_GET["choice"] == 'D'){
	getdata("amount", $amount);
	if($bad){
		exit("Unable to deposit.");
	}
	deposit($user, $amount);
}
// WITHDRAW
if($_GET["choice"] == 'W'){
	getdata("amount", $amount);
	if($bad){
		exit("Unable to withdraw.");
	}
	withdraw($user, $amount);
}
// SHOW
if(!isset($_GET["mail"]) && $_GET["choice"] == 'S'){
	show($user, $out);
	echo "<a href='formpage.php'>Back to Account Actions</a>";
}
// MAIL
if(isset($_GET["mail"]) && $_GET["choice"] == 'S'){
	show($user, $out);
	mailer($user, $out);
	echo "<br /><a href='formpage.php'>Back to Account Actions</a>";
}


// Don't redirect if choice is "Show"; otherwise you won't be able to read the information fast enough
if($_GET["choice"] != 'S'){
	redirect("<br /><p class='stnrd'>Returning to Account Actions</p></fieldset><br /><img src='corgi.jpg' class='image'>", "formpage.php", "3");
}
?>
<!-- Code by Nicolas Andrew Laing (11/20/17) -->