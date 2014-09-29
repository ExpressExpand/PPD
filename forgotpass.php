<?php
require("includes/inc.php");
  if ($_SESSION['username'] != null){
    //Redirect the user to the member area
    header('Location: member.php');
	exit();
  }
    if ($_GET['do'] == "reset") {
      //If they are, process the details they have provided. Else, continue with showing the form
      $username = trim(sanitize($_POST['username']));
      $email = trim(sanitize($_POST['email']));
      //Check if the username and password are empty
      if (($username == null) || ($email == null)) {
        header('Location: forgotpass.php?error=field');
		exit();
      } else {
        $query_accounts = mysql_query("SELECT * FROM users WHERE username = '$username' LIMIT 1");
        $query_count = mysql_num_rows($query_accounts);
        if ($query_count == 0) { 
          header('Location: forgotpass.php?error=username');
		  exit();
		} else {
			while($cemail = mysql_fetch_array($query_accounts)) {
				if ($cemail['email'] != $email) {
					header('Location: forgotpass.php?error=email');
					exit();	
		} else {
		$random_password = md5(uniqid(rand()));
		$emailpassword = substr($random_password, 0, 8);
		$npass2 = trim(sanitize(md5(password($emailpassword))));
		mysql_query("UPDATE users SET password='$npass2' WHERE username = '$username'") or die(mysql_error());
			$to = $email;
			$subject = "Password Reset";
			$body = "Hello $username,\r\n\r\nYou have requested your password to be reseted you can login with your new password below.\r\nNew Password:<b>".$npass."</b>";
				mail($to, $subject, $body);
			header('Location: login.php?success=pass_sent');
			exit();
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
    <?php
      //Messages
      if ($_GET['error'] == "username"){ echo "<div class='alert alert-error'>The username you provided could not be found.</div>"; }
	  elseif ($_GET['error'] == "email"){ echo "<div class='alert alert-error'>The email you provided could not be found.</div>"; }
	  elseif ($_GET['error'] == "field"){ echo "<div class='alert alert-error'>Please fill in all the fields.</div>"; }
    ?>
      
	<form class="form-horizontal" id="reset" method='post' action='?do=reset'>
	  <fieldset>
	    <legend>Password Reset</legend><br />
	<h5>You can reset your password by entering your username and email you registered with below.</h5><br />
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
		<label class="control-label" for="input01"></label>
	      <div class="controls">
	       <button type="submit" class="btn btn-primary">Reset</button>
	       
	      </div>
	</div>
<?php 
	}
include "footer.php"; 
?>
</div>

</body>
</html>