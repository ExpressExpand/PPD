<?php
require("includes/inc.php");
  if ($_SESSION['username'] == null) {
  header('Location: login.php');
  exit();
 }
	if ($_GET['do'] == "delete") {
		$guserid = $_GET['gid'];
		if ($guserid == $user['id']) {
		$deleteid = $_GET['id'];
		$fileext = $_GET['ext'];
		$dquery = mysql_query("DELETE FROM `files` WHERE `id` = '$deleteid'");
		unlink("uploads/".$deleteid.".".$fileext);
			header('Location: files.php?do=success');
			exit();
		} else {
			header('Location: index.php');
			exit();
		}
	}
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

<legend>Uploaded Files</legend>
<?php
if ($_GET['do'] == "success"){ echo "<div class='alert alert-success'>Your <b>file</b> has been successfully deleted.</div>"; }
?>
<table class="table table-bordered table-condensed">
    <tr>
	   <th>ID</th>
       <th>File Name</th>
	   <th>Downloads</th>
	   <th>Views</th>
	   <th>Conversion</th>
	   <th>Earnings</th>
       <th>Size</th>
	   <th>Options</th>
    </tr>
    <tr>
	        <?php 
       $id = $user['id']; 
	   $result = mysql_query("SELECT * FROM files WHERE userid='$id'");
		while($row = mysql_fetch_assoc($result)) {
	   ?>
	   <td><?php echo $row['id']; ?></td>
       <td><?php echo $row['name']; ?></td>
	   <td><?php echo $row['downloads']; ?></td>
	   <td><?php echo $row['views']; ?></td>
	   <td><?php echo round($row['downloads'] / $row['views'] * 100, 2); ?>%</td>
	   <td>$<?php echo $row['earn']; ?></td>
       <td><?php echo format_bytes($row['size']); ?></td>
	   <td><a href="http://<?php echo $domain ?>/download.php?id=<?php echo $row['id']; ?>">URL</a> <a href="?do=delete&gid=<?php echo $row['userid']; ?>&id=<?php echo $row['id']; ?>&ext=<?php echo $row['ext']; ?>" onclick="return confirm('Are you sure you want to delete this file?');">Delete</a></td>
    </tr>
	<?php }
	?>
</body>
</html>

<?php include "footer.php"; ?>