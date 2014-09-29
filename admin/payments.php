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
  $id = trim(sanitize($_GET['id']));
	if ($_GET['do'] == "sent") {
			mysql_query("UPDATE payments SET status = 'Sent' WHERE id='$id'");
			echo "<div class=\"container\"><div class='alert alert-success'>Payment ID: $id has been updated</div></div>";
	} else {
		if ($_GET['do'] == "declined") {
			mysql_query("UPDATE payments SET status = 'Declined' WHERE id='$id'");
			echo "<div class=\"container\"><div class='alert alert-success'>Payment ID: $id has been updated</div></div>";
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
	<legend>Payments</legend>
<?php include "header.php"; ?>
	<div class="span9">
			
			<legend>Pending Payments</legend>
             <table class="table table-bordered table-condensed">
				<tr>
				   <th>User ID</th>
				   <th>Name</th>
				   <th>Amount</th>
				   <th>Type</th>
				   <th>Sort</th>
				   <th>Account #</th>
				   <th>Email</th>
				   <th>Options</th>
				</tr>
					<?php 
				   $id = $user['id']; 
				   $result = mysql_query("SELECT * FROM users WHERE id = '$id'");
					$row = mysql_fetch_assoc($result);
						if ($row['banned'] == 0) {
							$result2 = mysql_query("SELECT * FROM payments WHERE status = 'Pending' ORDER BY type DESC");
								while($row2 = mysql_fetch_array($result2)) {
				   ?>
				<tr>
				   <td><?php echo $row2['userid']; ?></td>
				   <td><?php echo $row2['name']; ?></td>
				   <td>$<?php echo $row2['amount']; ?></td>
				   <td><?php echo $row2['type']; ?></td>
				   <td><?php echo $row2['sort']; ?></td>
				   <td><?php echo $row2['account']; ?></td>
				   <td><?php echo $row2['email']; ?></td>
				   <td><a href="?do=sent&id=<?php echo $row2['id']; ?>" onclick="return confirm('Are you sure you want to change this payment to sent?');">Sent</a> <a href="?do=declined&id=<?php echo $row['id']; ?>&id=<?php echo $row2['id']; ?>" onclick="return confirm('Are you sure you want to decline this payment?');">Declined</a></td>
				</tr>
			
				</div>
			</div>
				<?php 
				}
					}
			include "../footer.php";
				?>
