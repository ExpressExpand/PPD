<?php
require("includes/inc.php");

  if ($_SESSION['username'] == null){
  //Redirect the user to the login area if they're not logged in
  header('Location: login.php');
  exit();
  }
  //Check if the user is trying to logout
  if ($_GET['page'] == "logout") {
    //Unset the session and redirect the user to the login page
    unset($_SESSION['username']);
    header('Location: login.php?success=logout');
	exit();
  }
  //Check if the user is trying to view their account
  if ($_GET['page'] == "account_save") {
    //Post variables
    $email = trim(sanitize($_POST['email']));
	$ppemail = trim(sanitize($_POST['ppemail']));
    $password = trim(sanitize($_POST['password']));
    // Check if the email field is blank
    if ($email == null || $ppemail == null){
      header('member.php?page=account&error=email_blank');
	  exit();
    } else {
      //Check if email is in the valid format
      if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email) || !eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $ppemail)){
        // Email is invalid
        header('Location: member.php?page=account&error=email_invalid');
		exit();
      } else {
        //Update account
        $update_email = mysql_query("UPDATE users SET email='$email', ppemail='$ppemail' WHERE `username` = '$user[username]'") or die("Couldn't update mysql database");
        //Update their password if it wasn't blank
        if ($password != null){
          $update_password = mysql_query("UPDATE users SET password = '" . password($password) . "' WHERE `username` = '$user[username]'") or die("Couldn't update the password");
        }
        header('Location: member.php?page=account&success=updated');
		exit();
      }
    }
}
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
	   
	if ($_GET['page'] == "account") {
	  ?>
	<form class="form-horizontal" id="account" method='post' action='member.php?page=account_save'>
	  <fieldset>
	    <legend>Edit Account</legend><br />
      <?php		
		//Messages
		if ($_GET['error'] == "email_blank"){ echo "<div class='alert alert-error'>Please do not leave the email fields <b>blank</b>.</div>"; }
		elseif ($_GET['error'] == "email_invalid"){ echo "<div class='alert alert-error'>Please enter a <b>valid</b> email format.</div>"; }
		elseif ($_GET['success'] == "updated"){ echo "<div class='alert alert-success'>Your account details have been successfully updated.</div>"; }
      ?>
		
	    <div class="control-group">
	      <label class="control-label" for="input01">Email</label>
	      <div class="controls">
	        <input type="text" class="input" id="email" name="email" value="<?php echo $user['email'] ?>" onblur="if (this.value == '') {this.value = '<?php echo $user['email'] ?>';}" onfocus="if (this.value == '<?php echo $user['email'] ?>';) {this.value = '';}">
	        
	      </div>
	</div>
	
	 <div class="control-group">
		<label class="control-label" for="input01">PayPal Email</label>
	      <div class="controls">
	        <input type="text" class="input" id="ppemail" name="ppemail" value="<?php echo $user['ppemail'] ?>" onblur="if (this.value == '') {this.value = '<?php echo $user['ppemail'] ?>';}" onfocus="if (this.value == '<?php echo $user['ppemail'] ?>';) {this.value = '';}">
	       
	      </div>
	</div>
	
	 <div class="control-group">
		<label class="control-label" for="input01">Password</label>
	      <div class="controls">
	        <input type="password" class="input" id="password" name="password">
			<p class="help-block">Leave blank if you do not wish to change your password.</p>
	      </div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="input01"></label>
	      <div class="controls">
	       <button type="submit" class="btn btn-primary"><i class="icon-pencil icon-white"></i> Update</button>
	       
	      </div>
	</div>
	  <?php
        } else {
		
		?>
	<div class="row-fluid">
		<div class="span8">
			<legend>News</legend>
		<?php
		   $result = mysql_query("SELECT * FROM news ORDER BY publish DESC LIMIT 3");
			while($news = mysql_fetch_array($result)) {

				echo "<h3>".$news['title']."</h3>";
				echo "<h6>".date('m-d-Y', strtotime($news['publish']))."</h6><br />";
				echo html_entity_decode(nl2br($news['body']))."<hr>";				
			} 
		?>
		</div>
			<div class="span4 offset9">
			<legend>Earnings</legend>
				<div class="well">
					<b>Today:</b> $<?php echo number_format(getTodayStats($user['id']) + getTodayRefStats($user['id']), 2, '.', ','); ?></br />
					<b>Yesterday:</b> $<?php echo number_format(getYesterdayStats($user['id']) + getYesterdayRefStats($user['id']), 2, '.', ','); ?></br />
					<b>MTD:</b> $<?php echo number_format(getMonthStats($user['id']) + getMonthRefStats($user['id']), 2, '.', ','); ?></br />
					<b>Last Month:</b> $<?php echo number_format(getLastMonthStats($user['id']) + getLastMonthRefStats($user['id']), 2, '.', ','); ?></br />
					<b>All Time:</b> $<?php echo number_format(getAllTimeStats($user['id']) + getAllTimeRefStats($user['id']), 2, '.', ','); ?>
				</div>
		
		
			<legend>Top 10 Earners</legend>
				<div class="well">
				<?php 
				$first_day = date("Y:m:01 00:00:00");
				$last_day = date("Y:m:31 23:59:59");
				$rs = mysql_query("SELECT userid, SUM(`amount`) AS earn FROM user_earnings WHERE date >= '$first_day' and date <= '$last_day' and status = '1' GROUP BY userid ORDER BY amount DESC LIMIT 10 ") or die(mysql_error());
					while($data = mysql_fetch_array($rs)) {
					$position += 1;
						echo "<b><pre>$position. ".getUsername($data['userid'])."     ".$data['earn']."</b></pre><br />";
					} 
				if (mysql_num_rows($rs) == 0) {
					echo "<b>No Top Earners</b>";
				}
				?>
				</div>
			</div>
		</div>
		
	<?php
		}
		  include "footer.php"; 
	   ?>
</body>
</html>

