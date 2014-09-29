<?php
require("includes/inc.php");
  if ($_SESSION['username'] != null){
    //Redirect the user to the member area
    header('Location: member.php');
	exit();
  }
  //Check if the user is trying to register
  if ($_GET['do'] == "register"){
    //If they are, process the details they have provided. Else, continue with showing the form
    $username = trim(sanitize($_POST['username']));
    $password = trim(sanitize($_POST['password']));
	$rpassword = trim(sanitize($_POST['rpassword']));
    $email = trim(sanitize($_POST['email']));
	$ppemail = trim(sanitize($_POST['ppemail']));
	$address = trim(sanitize($_POST['address']));
	$city = trim(sanitize($_POST['city']));
	$postal = trim(sanitize($_POST['postal']));
	$state = trim(sanitize($_POST['state']));
	$country = trim(sanitize($_POST['country']));
	$ip = trim(sanitize($_SERVER['REMOTE_ADDR']));
	$date = trim(sanitize(date("Y-m-d")));
	$referral = trim(sanitize($_SESSION['referral']));
    //Check if the username and password are empty
    if ((($username == null) || ($password == null) || ($rpassword == null) || ($email == null) || ($ppemail == null) || ($address == null) || ($city == null) || ($postal == null) || ($country == null))){ //Check for empty fields
      header('Location: register.php?error=field_blank');
	  exit();
    } else {
		if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email) || !eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $ppemail)){ //Check if email is in the valid format
        header('Location: register.php?error=email_invalid');
		exit();
      } else {
		if ($password != $rpassword) {
			header('Location: register.php?error=password');
			exit();
		} else {
			$reg_query = mysql_query("SELECT * FROM users WHERE `username` = '$username'"); //Check if the username is available
			$count = mysql_num_rows($reg_query);
			if ($count != null){
				header('Location: register.php?error=username_exists');
				exit();
			} else {
				if (strlen($username) <= 3 || strlen($username) >= 20) { //Username Length
					header('Location: register.php?error=user_length');
					exit();
				} else {
					if (strlen($password) <= 3 || strlen($password) >= 30) { //Password Length
						header('Location: register.php?error=pass_length');
						exit();
					} else {
						//Create Account
						$code = trim(sanitize(addslashes(rand(11111111,99999999))));
						$insert = mysql_query("INSERT INTO users (username, password, email, ppemail, address, city, postal, state, country, ip, date, code, referral) VALUES ('$username', '" . password($password) . "', '$email', '$ppemail', '$address', '$city', '$postal', '$state', '$country', '$ip', '$date', '$code', '$referral')") or die("Sorry, we couldn't create your account. <a href='register.php'>Go back</a>");
						$lastid =  mysql_insert_id();
						//Send activation email
						$to = $email;
						$subject = "Activate your account";
						$body = "Hello $username,\r\n\r\nThank you for signing up at " .$sitename. ". To confirm your email, click the link below or copy it into your browser.\r\nhttp://" .$domain. "/activate.php?id=$lastid&code=$code";
							mail($to, $subject, $body);
						header('Location: login.php?success=complete');
						exit();
					}
				  }
			    }
		      }
	        }
          }
    } else {
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Test Site</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/bootstrap/css/bootstrap.css" rel="stylesheet">
<?php include "header.php"; ?>
</head>
<body>

<div class="container">

	<form class="form-horizontal" id="register" method='post' action='?do=register'>
	  <fieldset>
	    <legend>Register</legend><br />

    <?php
      //Error Messages
      if ($_GET['error'] == "field_blank"){ echo "<div class='alert alert-error'>Please fill in <b>all</b> the fields.</div>\n"; }
      elseif ($_GET['error'] == "username_exists"){ echo "<div class='alert alert-error'>The username is <b>already</b> in use.</div>\n"; }
      elseif ($_GET['error'] == "email_invalid"){ echo "<div class='alert alert-error'>Please enter a <b>valid</b> email address.</div>\n"; }
	  elseif ($_GET['error'] == "user_length"){ echo "<div class='alert alert-error'>Username must be between <b>3</b> to <b>20</b> characters.</div>\n"; }
	  elseif ($_GET['error'] == "pass_length"){ echo "<div class='alert alert-error'>Password must be between <b>3</b> to <b>30</b> characters.</div>\n"; }
	  elseif ($_GET['error'] == "password"){ echo "<div class='alert alert-error'>Your passwords do not <b>match</b>.</div>\n"; }
    ?>
		
	    <div class="control-group">
	      <label class="control-label" for="input01">Username</label>
	      <div class="controls">
			<input type="text" class="input" id="username" name="username">
				
	     </div>
	</div>
	
	 <div class="control-group">
		<label class="control-label" for="input01">Email</label>
	      <div class="controls">
			<input type="text" class="input" id="email" name="email">
			
	      </div>
	</div>
	
	 <div class="control-group">
		<label class="control-label" for="input01">PayPal Email</label>
	      <div class="controls">
			<input type="text" class="input" id="ppemail" name="ppemail">
			
	      </div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="input01">Password</label>
	      <div class="controls">
			<input type="password" class="input" id="password" name="password">
			
	      </div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="input01">Confirm Password</label>
	      <div class="controls">
			<input type="password" class="input" id="rpassword" name="rpassword">
	      </div>
	</div>

	 <div class="control-group">
		<label class="control-label" for="input01">Address</label>
	      <div class="controls">
			<input type="text" class="input" id="address" name="address">
			
	      </div>
	</div>
	
	 <div class="control-group">
		<label class="control-label" for="input01">City</label>
	      <div class="controls">
			<input type="text" class="input" id="city" name="city">
			
	      </div>
	</div>
	
	 <div class="control-group">
		<label class="control-label" for="input01">Postal Code</label>
	      <div class="controls">
			<input type="text" class="input" id="postal" name="postal">
			
	      </div>
	</div>

	 <div class="control-group">
		<label class="control-label" for="input01">State</label>
	      <div class="controls">
			<input type="text" class="input" id="state" name="state">
			
	      </div>
	</div>
	
	 <div class="control-group">
		<label class="control-label" for="input01">Country</label>
	      <div class="controls">
			<input type="text" class="input" id="country" name="country">
			
	      </div>
	</div>
	
	<div class="control-group">
	 <div class="controls">
		<label class="checkbox">
		<input type="checkbox" name="checkbox" value="check">Agree to ToS</label>
		</div>
</div>
<div class="control-group">

		<label class="control-label" for="input01"></label>
			<button type="submit" class="btn btn-primary" onclick="if(!this.form.checkbox.checked){alert('You must agree to the terms of service first.');return false}">Register</button>
	</div>
	</div>
	
</body>
</html>
<?php
  } // End check register
include "footer.php";
?>

