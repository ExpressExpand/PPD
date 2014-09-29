<?php
require_once("includes/session.inc.php");
  if ($_SESSION['username'] != null){
    //Redirect the user to the member area
    header('Location: member.php');
	exit();
  } else {
    //Redirect the user to the login page
    header('Location: login.php');
	exit();
  }
?>

