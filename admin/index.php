<?php
require("../includes/inc.php");
  if ($_SESSION['username'] != null) {
    $u_query = mysql_query("SELECT * FROM users WHERE `username` = '$_SESSION[username]' LIMIT 1");
    $user = mysql_fetch_array($u_query);
  }
  if ($user['admin'] != 1){
  header('Location: ../login.php');
  exit();
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
	<legend>Home</legend>
<?php include "header.php"; ?>
	<div class="span9">
		<legend>Statistics</legend>
		<?php 
		$query_accounts = mysql_query("SELECT * FROM users");
		$query_count = mysql_num_rows($query_accounts);
			$rs = mysql_query("SELECT SUM(`amount`) AS `total_earnings` FROM `user_earnings`");
			$row = mysql_fetch_assoc($rs);
			$earnings = number_format($row['total_earnings'], 2, '.', ',');
		?>
		<h4>Total Users: <?php echo $query_count; ?></h4>
		<h4>Total Earnings: $<?php echo $earnings ?></h4><br />	
			
			<legend>Last Uploaded</legend>
             <table class="table table-bordered table-condensed">
				<tr>
				   <th>User ID</th>
				   <th>File ID</th>
				   <th>File Name</th>
				   <th>Downloads</th>
				   <th>Earnings</th>
				   <th>Size</th>
				</tr>
					<?php 
				   $id = $user['id']; 
				   $result = mysql_query("SELECT * FROM files ORDER BY id DESC LIMIT 6");
					$row = mysql_fetch_assoc($result);
				   ?>
				<tr>
				   <td><?php echo $row['userid']; ?></td>
				   <td><?php echo $row['id']; ?></td>
				   <td><?php echo $row['name']; ?></td>
				   <td><?php echo $row['downloads']; ?></td>
				   <td>$<?php echo $row['earn']; ?></td>
				   <td><?php echo format_bytes($row['size']); ?></td>
				</tr>
				</table>
				</div>
			</div>
			<?php include "../footer.php"; ?>
