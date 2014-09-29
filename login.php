<?php
require("includes/inc.php");

$_SESSION['referral'] = $_GET['ref']; 
  if ($_SESSION['username'] != null) {
    //Redirect the user to the member area
    header('Location: member.php');
	exit();
  } else {
    //Check if the user is trying to login
    if ($_GET['do'] == "login") {
      //If they are, process the details they have provided. Else, continue with showing the form
      $username = trim(sanitize($_POST['username']));
      $password = trim(sanitize($_POST['password']));
      //Check if the username and password are empty
      if (($username == null) || ($password == null)){
        header('Location: login.php?error=details_wrong');
		exit();
      } else {
        $query_accounts = mysql_query("SELECT * FROM users WHERE username = '$username' LIMIT 1");
        $query_count = mysql_num_rows($query_accounts);
        if ($query_count == 0){ // User not found
          header('Location: login.php?error=details_wrong');
		  exit();
		} else {
			while($accounts = mysql_fetch_array($query_accounts)) {
			if ($accounts['active'] == 0) { //Check if account is active
				header('Location: login.php?error=activate');
				exit();
			} else {
				$reason = $accounts['reason'];
				if ($accounts['banned'] == 1) {
				header('Location: login.php?error=banned');	
				exit();
			} else {
				 if ($accounts['password'] == password($password)){ // Check if the password matches the user's password
					$_SESSION['username'] = $username; // The password is correct, start a session for the user
					header('Location: member.php');
					exit();
				} else {
				header('Location: login.php?error=details_wrong'); // Incorrect password
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
<link href="/css/bootstrap/css/bootstrap.css" rel="stylesheet">
<?php include "header.php"; ?>
</head>
<body>


	<div class="container">
		<div class="hero-unit">
	<h1>Upload, Share, Earn</h1>
	<p>It's simple upload your files, share it over the web, and get paid for each download.</p>
	<em>Get started by <a href="register.php">registering</a> or login below</em>
	</div>
      
	<form class="form-horizontal" id="login" method='post' action='?do=login'>
	  <fieldset>
	    <legend>Login</legend><br />
    <?php
      //Messages
      if ($_GET['error'] == "details_wrong"){ echo "<div class='alert alert-error'>Your <b>username</b> or <b>password</b> was incorrect</div>"; }
	  elseif ($_GET['error'] == "activate"){ echo "<div class='alert alert-info'>Please <b>activate</b> your account.</div>"; }
	  elseif ($_GET['error'] == "activating"){ echo "<div class='alert alert-error'>Sorry, we couldn't activate your account.</div>"; }
	  elseif ($_GET['error'] == "banned"){ echo "<div class='alert alert-error'><b>Your account has been banned</b></div>"; } //ADD REASON
      elseif ($_GET['success'] == "logout"){ echo "<div class='alert alert-success'><b>You have successfully logged out</b></div>"; }
	  elseif ($_GET['success'] == "activated"){ echo "<div class='alert alert-success'>Your account has been activated you may now login.</div>"; }
      elseif ($_GET['success'] == "complete"){ echo "<div class='alert alert-info'>You are now registered, please <b>activate</b> your account by visiting your email.</div>"; }
      elseif ($_GET['success'] == "pass_sent"){ echo "<div class='alert alert-success'>Your new password has been sent to your email.</div>"; }
	?>
	    <div class="control-group">
	      <label class="control-label" for="input01">Username</label>
	      <div class="controls">
			<div class="input-prepend">
				<span class="add-on"><i class="icon-user"></i></span><input type="text" class="input" id="username" name="username">
	        </div>
		  </div>
	</div>
	
	 <div class="control-group">
		<label class="control-label" for="input01">Password</label>
	      <div class="controls">
			<div class="input-prepend">
				<span class="add-on"><i class="icon-pencil"></i></span><input type="password" class="input" id="password" name="password">
			</div>
	       	<h6><a href="forgotpass.php">Forgot your password?</a></h6>
	      </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="input01"></label>
	      <div class="controls">
	       <button type="submit" class="btn btn-inverse">Login</button>
	       
	      </div>
	</div>
	</fieldset>
	</form>
</div>
</body>
</html>
<?php
    } // End Check Login
  } // End check if logged in
include "footer.php";
?>