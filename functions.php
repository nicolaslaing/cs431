<html>
	<head>
		<link rel="stylesheet" href="styles.css">
	</head>
	<!-- Hide authenticating statement after 1000 milliseconds -->
	<script>
		function hide(){
			document.getElementById("auth").style.display = "none";
		};
		setTimeout(hide, 1000);
	</script>
</html>	
<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);  
ini_set('display_errors' , 1);

include("account431.php");

//$db = mysqli_connect($host, $user, $pass, $project);

//mysqli_select_db($db, $project); 
//****************************************************************************************
// AUTHORIZATION
function auth($user, $pass, &$result){
	global $db;
	$hash = sha1($pass);
	
	$s = "select * from A where user='$user' and pass='$pass' and hash='$hash'";
	$result = odbc_exec($db, $s);
	
	$numRows = odbc_num_rows($result);
	
	print "<p id='auth' style='text-align: center; font-size: 150%'>Authenticating...</p><br />";
	if($numRows != 0){
		return true;
	}
	else{
		return false;
	}
}
//****************************************************************************************
// REDIRECT
function redirect($message, $target, $delay){
	echo $message;
	
	header("refresh: $delay, url = $target");
	
	exit();
}
//****************************************************************************************
// GATEKEEPER
function gatekeeper(){
	// check if $_SESSION["logged"] = true
	if(!isset($_SESSION["logged"])){
		$message = "<p class='stnrd'>Undefined Login.</p>";
		$target = "index.html";
		$delay = 3;
		redirect($message, $target, $delay);
	}
}
//****************************************************************************************
// GETDATA FUNCTION
function getdata($name, &$result){
	global $db, $bad;
	$n = $_GET[$name];
	
	if (!isset($n)){
		echo "<br />$name is not defined.";
		$bad = true;
		return;
	}
	if (empty($n)){
		echo "<br />$name is empty<br />";
		$bad = true;
		return;
	}
	$temp = $n;
	$temp = addslashes($temp);
	$result = $temp;
	return $result;
;}
//****************************************************************************************
// DEPOSIT FUNCTION
function deposit ($user, $amount){
	global $db;
	
	$s = "select * from A where user='$user'";
	//$result = mysqli_query($db,$s);
	$result = odbc_exec($db,$s);
	$bal = $_SESSION["cur_balance"];
	$newbal = $bal + $amount;
	
	if($amount > 2000){
		echo "Cannot deposit more than $2000.00 per transaction."; // max="2000" HTML attribute
	}
	
	$up = "UPDATE A SET `init_balance` = $bal WHERE user='$user'"; 
	//mysqli_query($db, $up);
	odbc_exec($db, $up);
	
	$up = "UPDATE A SET `cur_balance` = $newbal WHERE user='$user'";
	//mysqli_query($db, $up);
	odbc_exec($db, $up);

	
	$i = "INSERT INTO T VALUES('$user', 'D', $amount, NOW())";
	//mysqli_query($db, $i);
	odbc_exec($db, $i);
	
	//update session
	$_SESSION["cur_balance"] = $_SESSION["cur_balance"] + $amount;
	
	print "<h1>Deposited \$$amount</h1>";
	return;
;}
//****************************************************************************************
// WITHDRAW FUNCTION
function withdraw ($user, $amount){
	global $db;

	$s = "select * from A where user='$user'";
	//$result = mysqli_query($db, $s);
	$result = odbc_exec($db, $s);
	$bal = $_SESSION["cur_balance"];
	$newbal = $bal - $amount;
	
	if($amount > 2000){
		echo "Cannot withdraw more than $2000.00 per transaction."; // max="2000" HTML attribute
	}
	
	//Overdraw check
	if($_SESSION["cur_balance"] < $amount){
		$message = "<center><font size=\"4\">Amount is larger than balance.<br />Insufficient Funds.</font></center>";
		$target = "formpage.php";
		$delay = 3;
		redirect($message, $target, $delay);
		return;
	}
	if($bal <= 0){
		print "Negative Funds.";
		return;
	}
	else{
		$up = "UPDATE A SET `init_balance` = $bal WHERE user='$user'";
		//mysqli_query($db, $up);
		odbc_exec($db, $up);
		
		$up = "UPDATE A SET `cur_balance` = $newbal WHERE user='$user'";
		//mysqli_query($db, $up);
		odbc_exec($db, $up);
	
		$i = "INSERT INTO T VALUES('$user', 'W', $amount, NOW())";
		//mysqli_query($db, $i);
		odbc_exec($db, $i);
		
		//update session
		$_SESSION["cur_balance"] = $_SESSION["cur_balance"] - $amount;

		print "<h1>Withdrawn \$$amount</h1>";
		return;
	}
;}
//****************************************************************************************
// SHOW FUNCTION
function show($user, &$out){
	global $db;
	
	$tableA = "select * from A where user='$user'";
	$out = "<br /><br />SQL statement is:<br />$tableA<br /><br />";
	//$a = mysqli_query($db, $tableA);
	$a = odbc_exec($db, $tableA);
	
	$tableT = "select * from T where user='$user' order by date DESC"; 
	//$t = mysqli_query($db, $tableT);
	$t = odbc_exec($db, $tableT);
	
	//while ($row = mysqli_fetch_array($t, MYSQLI_ASSOC)){
		//$cell = $row["user"];
		$cell = odbc_result($a, "user");
		//$mail = $row["mail"];
		$mail = odbc_result($a, "mail");
		//$addr = $row["address"];
		$addr = odbc_result($a, "address");
		//$phone = $row["cell"];
		$phone = odbc_result($a, "cell");
		
		// Set init_balance and cur_balance to ZERO if there are no transactions for the user (Useful when deleting rows from DB)
		// MUST LOG OUT TO SEE cur_balance AFFECTED
		$numrowT = odbc_num_rows($t);
		if($numrowT <= 0){
			$s = "UPDATE A SET `init_balance` = 0.00,`cur_balance` = 0.00 WHERE user='$user'";
			//(mysqli_query($db, $s)) or die(mysqli_error($db));
			odbc_exec($db, $s);
		}
		
		//$initBal = $row["init_balance"];
		$initBal = odbc_result($a, "init_balance");
		//$bal  = $row["cur_balance"];
		$bal = odbc_result($a, "cur_balance");
		
		print "<br /><br /><font size='5'>Account Summary</font>";
		$out .= "User is $cell<br />";
		$out .= "Email: $mail<br />";
		$out .= "Address: $addr<br />";
		$out .= "Cellphone: $phone<br />";
		$out .= "Initial Balance: \$$initBal<br />";
		$out .= "Current Balance: \$$bal<br /><br />";

	
	while ($row = odbc_fetch_array($t)){

		//$type  = $row[ "type" ];
		$type = odbc_result($t, "type");
		//$amount  = $row[ "amount" ];
		$amount = odbc_result($t, "amount");
		//$date  = $row[ "date" ];
		$date = odbc_result($t, "date");
		
		$out .= "Type is $type<br />";
		$out .= "Amount is \$$amount<br />";
		$out .= "Date is $date<br /><br />";
	}
	echo $out;
;}
//****************************************************************************************
// MAILER FUNCTION
function mailer($user, $out){
	global $db;
	
	date_default_timezone_set ("America/New_York");
	
	$tableSel = "select * from A where user='$user'";
	$t = odbc_exec($db, $tableSel);
	
	//while ( $row = mysqli_fetch_array($t, MYSQLI_ASSOC) ) {
		//$email  = $row[ "mail" ];
		$email = odbc_result($t, "mail");
		$subject = $_GET["subject"] . " (" . date("Y-m-d G:i:s") . ")";
		$out = "Message from HTML:<br />";
		$out .= $_GET["message"];
			
		mail($email, $subject, $out);
			
		print "Email Sent.";
	
;}
?>
<!-- Code by Nicolas Andrew Laing (11/20/17) -->
