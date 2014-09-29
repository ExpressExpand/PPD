<?php
require("includes/inc.php");
  if ($_SESSION['username'] == null) {
  header('Location: login.php');
  exit();
 }

  if ($_SESSION['username'] != null) {
    $u_query = mysql_query("SELECT * FROM users WHERE `username` = '$_SESSION[username]' LIMIT 1");
    $user = mysql_fetch_array($u_query);
  }
   $id = trim(sanitize($user['id'])); 
   $result = mysql_query("SELECT * FROM users WHERE referral='$id'");
   $totalr = mysql_num_rows($result); 
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
		<div class="row-fluid">
		<div class="span6">
<legend>Referrals</legend>
      <h5>Here is your referral link share it around the internet and earn <b>10%</b> of your referrals earnings.</h5>
	  <div class="controls">
		<div class="input-prepend">
			<span class="add-on"><i class="icon-user"></i></span><input type="text" class="input-xlarge" id="input01" value="http://<?php echo $domain; ?>/login.php?ref=<?php echo $user['id']; ?>"><br />
		</div>
		<b>Total Referrals: <?php echo $totalr; ?></b>
      </div><br />
</div>
			<div class="span6">

				<legend>Dynamic Signature</legend>
					<img src="signature.php?id=<?php echo $user['id']; ?>" /><br /><br />
					<h5>Image URL:</h5>
					<input type="text" class="input-xlarge" id="input01" value="http://<?php echo $domain; ?>/signature.php?id=<?php echo $user['id']; ?>"><br />
			</div>
		</div>

<legend>Your Referrals</legend>
<table class="table table-bordered table-condensed">
    <tr>
       <th>Username</th>
	   <th>Date Joined</th>
       <th>Earnings</th>
    </tr>
	<?php
while($row = mysql_fetch_assoc($result)) {
?>
    <tr>
	   <td><?php echo $row['username']; ?></td>
	   <td><?php echo date('m-d-Y', strtotime($row['date'])); ?></td>
       <td>$<?php echo getAllTimeRefStats($user['id']); ?></td>
    </tr>
<?php
}
?>
</div>

</body>
</html>
        <?php
include "footer.php";
	   ?>
	   