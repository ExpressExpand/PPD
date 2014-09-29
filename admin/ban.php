<?php
require("../includes/inc.php");
  if ($_SESSION['username'] != null) {
    $u_query = mysql_query("SELECT * FROM users WHERE `username` = '$_SESSION[username]' LIMIT 1");
    $user = mysql_fetch_array($u_query);
  }
  if ($user['admin'] != 1){
  //Redirect the user to the login area if they're not logged in
  header('Location: ../login.php');
  exit();
  }
	if ($_GET['do'] == "ban") {
		$userid = trim(sanitize($_POST['userid']));
		$reason = trim(sanitize($_POST['reason']));
			$href = mysql_query("SELECT * from users WHERE id='$userid' LIMIT 1");
			while($gref = mysql_fetch_array($href)) {
			$refid = $gref['referral'];
			if ($refid >= 1) {
				mysql_query("UPDATE users SET banned = '1' WHERE id='$userid'");	
				mysql_query("UPDATE users SET reason = '$reason' WHERE id='$userid'");
				mysql_query("UPDATE user_earnings SET status = '0' WHERE userid='$userid'");			
				mysql_query("UPDATE ref_earnings SET status = '0' WHERE userid='$refid'");
			} else {
				mysql_query("UPDATE users SET banned = '1' WHERE id='$userid'");	
				mysql_query("UPDATE users SET reason = '$reason' WHERE id='$userid'");
				mysql_query("UPDATE user_earnings SET status = '0' WHERE userid='$userid'");	
			}
		}
	}
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Test Site</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/css/bootstrap/css/bootstrap.css" rel="stylesheet">
<?php include "../header.php"; ?>
</head>
<body>

<div class="container">
<legend>Ban User</legend>
<?php include "header.php"; ?>
	<div class="span3">
		<form class="form-horizontal" id="ban" method='post' action='?do=ban'>
	  <fieldset>
	    <div class="control-group">
	      <label class="control-label" for="input01">User ID</label>
	      <div class="controls">
	        <input type="text" class="input" id="userid" name="userid">
	        
	      </div>
	</div>
	
	 <div class="control-group">
		<label class="control-label" for="input01">Reason</label>
	      <div class="controls">
	        <input type="text" class="input" id="reason" name="reason">
	      </div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="input01"></label>
	      <div class="controls">
	       <button type="submit" class="btn btn-inverse">Ban</button>
	       
	      </div>
	</div>
</div>
</div>
</body>
</html>
<?php include "../footer.pho"; ?>