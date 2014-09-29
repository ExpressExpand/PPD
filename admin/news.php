<?php
require("../includes/inc.php");
  if ($_SESSION['username'] != null) {
    $u_query = mysql_query("SELECT * FROM users WHERE `username` = '$_SESSION[username]' LIMIT 1");
    $user = mysql_fetch_array($u_query);
  }
  if ($user['admin'] != 1) {
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
<?php
	if ($_GET['do'] == "delete") { //Delete News
		$id = trim(sanitize($_GET['id']));
			$dquery = mysql_query("DELETE FROM `news` WHERE `id` = '$id'");
			echo "<div class=\"container\"><div class='alert alert-success'>News ID: <b>$id</b> has been deleted</div></div>";
	} else {
		if ($_GET['do'] == "addnews") { //Add News
			$title = trim(sanitize($_POST['title']));
			$date = trim(sanitize(date("Y:m:d H:i:s")));
			$body = trim(sanitize($_POST['body']));
				mysql_query("INSERT INTO news (title, publish, body) VALUES ('$title', '$date', '$body')");
					echo "<div class=\"container\"><div class='alert alert-success'><b>$title</b> has been created.</div></div>";
	} else {
		if ($_GET['do'] == "save") { //Save News
			$id = trim(sanitize($_GET['id']));
			$title = trim(sanitize($_POST['title']));
			$body = trim(sanitize($_POST['body']));
				mysql_query("UPDATE news SET title = '$title' WHERE id = '$id'") or die(mysql_error());
				mysql_query("UPDATE news SET body = '$body' WHERE id = '$id'") or die(mysql_error());
			echo "<div class=\"container\"><div class='alert alert-success'><b>$title</b> has been saved.</div></div>";
	} else {
		if ($_GET['do'] == "edit") { //Edit News
			$id = trim(sanitize($_GET['id']));
				$result = mysql_query("SELECT * FROM news WHERE id = '$id'");
					while($row = mysql_fetch_assoc($result)) {
			?>
<div class="container">
	<div class="span9">
		<legend>Editing: <?php echo $row['title']; ?></legend>	
			<form class="form-horizontal" id="login" method='POST' action="?do=save&id=<?php echo $id; ?>">
				  <fieldset>
					<div class="control-group">
					  <label class="control-label" for="input01">Title</label>
					  <div class="controls">
						<input type="text" class="input" id="title" name="title" value="<?php echo $row['title'] ?>" onblur="if (this.value == '') {this.value = '<?php echo $row['title'] ?>';}" onfocus="if (this.value == '<?php echo $row['title'] ?>';) {this.value = '';}">
						
					  </div>
				</div>
				
				 <div class="control-group">
					<label class="control-label" for="input01">Body</label>
					  <div class="controls">
						<textarea class="input" style="width:450px; height:200px;" id="body" name="body"><?php echo $row['body']; ?></textarea>
					  </div>
				</div>
				<div class="control-group">
					<label class="control-label" for="input01"></label>
					  <div class="controls">
					   <button type="submit" class="btn btn-inverse">Save</button>
					   
					  </div>
				</div>
					</fieldset>
			</form>
			<?php
				}
			}
		}
	}
}
?>
</div>
</div>
<div class="container">
	<legend>News</legend>
<?php include "header.php"; ?>

	<div class="span9">
		<legend>Create News</legend>	
			<form class="form-horizontal" id="login" method='POST' action='?do=addnews'>
				  <fieldset>
					<div class="control-group">
					  <label class="control-label" for="input01">Title</label>
					  <div class="controls">
						<input type="text" class="input" id="title" name="title">
						
					  </div>
				</div>
				
				 <div class="control-group">
					<label class="control-label" for="input01">Body</label>
					  <div class="controls">
						<textarea class="input" style="width:450px; height:200px;" id="body" name="body"></textarea>
					  </div>
				</div>
				<div class="control-group">
					<label class="control-label" for="input01"></label>
					  <div class="controls">
					   <button type="submit" class="btn btn-inverse">Create</button>
					   
					  </div>
				</div>
				</fieldset>
			</form>
			<legend>Created News</legend>
             <table class="table table-bordered table-condensed">
				<tr>
				   <th>Title</th>
				   <th>Date</th>
				   <th>Options</th>
				</tr>
					<?php 
				   $result = mysql_query("SELECT * FROM news ORDER BY publish DESC");
					while($row = mysql_fetch_assoc($result)) {
				   ?>
				<tr>
				   <td><?php echo $row['title']; ?></td>
				   <td><?php echo date('m-d-Y', strtotime($row['publish'])); ?></td>
				   <td><a href="?do=edit&id=<?php echo $row['id']; ?>">Edit</a> <a href="?do=delete&id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this news article?');">Delete</a></td>
				</tr>
				<?php }
				?>
				</div>
			</div>
	</body>
	</html>
					<?php 
					include "../footer.php";
					?>
