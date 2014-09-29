<?php
require('includes/inc.php');

    $id = trim(sanitize($_GET['id']));
    $code = trim(sanitize($_GET['code']));
    
	if($id&&$code) {    
	
		$check = mysql_query("SELECT * FROM users WHERE id='$id' AND code='$code' ") or die(mysql_error());
		$checknum = mysql_num_rows($check);
  
			if($checknum == 1) {
				$activate = mysql_query("UPDATE users SET active='1' WHERE id='$id'") or die(mysql_error());
				header('Location: login.php?success=activated'); // Incorrect password
				exit();
			} else
				header('Location: login.php?error=activating'); // Incorrect password
				exit();
		} else
			echo"Error. Please report this to the site administrator.";
?>