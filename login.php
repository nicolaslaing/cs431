<html>
	<head>
		<link rel="stylesheet" href="styles.css">
	</head>
	<!-- Show result of authenticating after 1000 milliseconds -->
	<script>
		function show(){
			document.getElementById("show").style.display = "block";
		};
		setTimeout(show, 1000);
	</script>
</html>	
<?php
//****************************************************************************************
// SESSION START - ERROR REPORT - FUNCTION INCLUSION - DATABASE CONNECTION
session_set_cookie_params(0, "/~nal9/it202/A2/", "web.njit.edu");
session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);  
ini_set('display_errors' , 1);


include ("account431.php");
include ("functions.php");

//$db = mysqli_connect($host, $user, $pass, $project);

if (mysqli_connect_errno())
  {
	  echo "<h1 class='stnrd'>Failed to connect to MySQL: " . mysqli_connect_error();
	  exit();
  }
print "<h1 class='stnrd'>Successfully connected to MySQL.</h1><br /><br />";

//mysqli_select_db($db, $project ); 
//****************************************************************************************
// GLOBALS
$bad = false;
//****************************************************************************************
// INITIALIZATION
getdata("user", $user);
getdata("pass", $pass);
getdata("delay", $delay);
if($bad){
	exit("UNDEFINED/EMPTY ERROR");
}
if(!auth($user, $pass, $result)){
																	
	$message = "<fieldset id='show' style='display: none'><p id='fieldset' class='lose'>Please enter a valid username or password.</p></fieldset><img src='corgi.jpg' class='image'>";
	$target = "index.html";
	redirect($message, $target, $delay+1);
}

$_SESSION["logged"] = true;
$_SESSION["user"] = $user;

$_SESSION["cur_balance"] = odbc_result($result, "cur_balance");
//$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
//$_SESSION["cur_balance"] = $row["cur_balance"];
												
$message = "<fieldset id='show' style='display: none'><p class='win'>" . session_id() . "<br /><br />Logged in. Transferring to formpage.php</p></fieldset><img src='corgi.jpg' class='image'>";
$target = "formpage.php"; // direct to PHP page to deposit/withdraw/show/mail
redirect($message, $target, $delay+1);
?>
<!-- Code by Nicolas Andrew Laing (11/20/17) -->
