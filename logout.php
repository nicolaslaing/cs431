<html>
	<head>
		<link rel="stylesheet" href="styles.css">
	</head>
	
</html>
<?php
session_start();
include("functions.php");
$_SESSION = array();
session_destroy();

if(!isset($_SESSION["logged"])){
  $message = "<h1 class='stnrd'><p class='win'>You have successfully logged out.</p></h1><img src='corgi.jpg' class='image'>";
  $target = "index.html";
  $delay = "2";
  redirect($message, $target, $delay);
}
else{
  $message = "<h1 class='stnrd'><p class='lose'>There was an issue with logging out.</p></h1><img src='corgi.jpg' class='image>";
  echo $message;
}
?>
<!-- Code by Nicolas Andrew Laing (11/20/17) -->