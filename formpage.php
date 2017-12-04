<?php
session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);  
ini_set('display_errors' , 1);

include ("functions.php");

gatekeeper();
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="styles.css">
		<h1 class="stnrd">Account Actions</h1>
	</head>
<script>
	function appear(){
		ptr1 = document.getElementById("choice");
		vis  = document.getElementById("visibility");
		mail = document.getElementById("mail");
		subject = document.getElementById("subject");
		message = document.getElementById("message");

		if(ptr1.value == 'D' || ptr1.value == 'W'){
			vis.style.display = "block";
		}
		else{
			vis.style.display = "none";
		}
		if(ptr1.value == 'S'){
			
		}
		if(mail.checked == true){
			subject.style.display = "block";
			message.style.display = "block";
		}
		else{
			subject.style.display = "none";
			message.style.display = "none";
		}
	}
</script>
<form action="formpagehandler.php">
	<fieldset id="fieldset"><center>
	<font size="6">Current Balance: <?php echo $_SESSION["cur_balance"]; ?></font><br /><br />
	<select name="choice" id="choice" onchange="appear();">
		<option value="">Choose One</option>
		<option value="S">Show</option>
		<option value="D">Deposit</option>
		<option value="W">Withdraw</option>
	</select><br /><br /><br />
			
	<div id="visibility" style="display: none">
		<input type="number" name="amount" id="amount" step="0.1" min="0" max="2000" placeholder="Amount">
	</div>
			
	<input type="checkbox" name="mail" id="mail" onchange="appear();"> Mail copy<br />
	<input type="text" name="subject" id="subject" style="display: none" placeholder="Enter subject" autocomplete="off"><br/>
	<input type="text" name="message" id="message" style="display: none" placeholder="Enter message" autocomplete="off"><br/>
	<input type="submit" style="padding: 3%">
	</center></fieldset>
</form>
    <center><form action="logout.php">
		<input type="submit" value="Logout">
	</form></center>
	<img src="corgi.jpg" class="image">
</html>
<!-- Code by Nicolas Andrew Laing (11/20/17) -->