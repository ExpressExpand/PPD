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
	if ($_GET['do'] == "delete") {
		$fileid = trim(sanitize($_POST['fileid']));
		$result = mysql_query("SELECT * FROM files WHERE id='$fileid'");
		while($row = mysql_fetch_assoc($result)) {
			$ext = $row['ext'];
			$dquery = mysql_query("DELETE FROM `files` WHERE `id` = '$fileid'");
			unlink("../uploads/".$fileid.".".$ext);
			echo "<div class=\"container\"><div class='alert alert-success'>File ID: $fileid has been deleted.</div></div>";
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
<legend>Delete Files</legend>
<?php include "header.php"; ?>
	<div class="span3">
		<form class="form-horizontal" id="ban" method='post' action='?do=delete'>
	  <fieldset>
	    <div class="control-group">
	      <label class="control-label" for="input01">File ID</label>
	      <div class="controls">
	        <input type="text" class="input" id="fileid" name="fileid">
	        
	      </div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="input01"></label>
	      <div class="controls">
	       <button type="submit" class="btn btn-inverse">Delete</button>
	       
	      </div>
	</div>
</div>
</div>
</body>
</html>
<?php include "../footer.php"; ?>
