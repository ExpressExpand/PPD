<?php
require_once("includes/inc.php");

if ($_SESSION['username'] != null) {
	$u_query = mysql_query("SELECT * FROM users WHERE `username` = '" . $_SESSION['username'] . "' LIMIT 1");
    $user = mysql_fetch_array($u_query);
?>
<!DOCTYPE HTML>
<html>
<head>
<body>
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
	<a class="brand" href="index.php"><?php echo $sitename ?></a>
<ul class="nav">
<?php
$page = basename($_SERVER['REQUEST_URI']);
?>
<li <? if ($page == "member.php") { echo "class=\"active\""; } ?> ><a href="/member.php">Home</a></li> 
<li <? if ($page == "member.php?page=account") { echo "class=\"active\""; } ?> ><a href="/member.php?page=account">Account</a></li> 
<li <? if ($page == "files.php") { echo "class=\"active\""; } ?> ><a href="/files.php">Files</a></li>
<li <? if ($page == "upload.php") { echo "class=\"active\""; } ?> ><a href="/upload.php">Upload</a></li>
<li <? if ($page == "payments.php") { echo "class=\"active\""; } ?> ><a href="/payments.php">Payments</a></li>
<li <? if ($page == "referrals.php") { echo "class=\"active\""; } ?> ><a href="/referrals.php">Referrals</a></li>
<?php if ($user['admin'] == 1) { //Check if user is admin ?>
<li <? if ($page == "admin") { echo "class=\"active\""; } ?>><a href="/admin">Admin Panel</a></li>
<?php 
}
?>

<li><a href="/member.php?page=logout">Logout</b></a></li> 
</ul>
  </div>
 </div>
</div>
<?php
} else {
?>
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
	<a class="brand" href="index.php"><?php echo $sitename ?></a>
<ul class="nav">
<?php
$page = basename($_SERVER['REQUEST_URI']);
?>
<li <? if ($page == "login.php") { echo "class=\"active\""; } ?> ><a href="index.php">Home</a></li> 
<li><a href="login.php">Login</a></li> 
<li <? if ($page == "register.php") { echo "class=\"active\""; } ?> ><a href="register.php">Register</a></li> 
<?php
}
?>
</ul>
  </div>
 </div>
</div>
</div>
</body>
</head>
</html>