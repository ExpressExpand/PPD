<!DOCTYPE HTML>
<html>
<head>
<title>Test Site</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/bootstrap/css/bootstrap.css" rel="stylesheet">
</head>
<body>

<?php
require($_SERVER['DOCUMENT_ROOT']."/includes/inc.php");

    
 
if (!empty($_FILES)) {
    $fileTypes = array('bat'); // File extensions
    $fileParts = pathinfo($_FILES['Filedata']['name']);
	$ext = trim(sanitize($fileParts['extension']));
	$file_name = trim(sanitize($_FILES['Filedata']['name']));
	$file_size = trim(sanitize($_FILES['Filedata']['size']));
	$userid = trim(sanitize($_POST['userid']));
	
    if (!in_array($fileParts['extension'],$fileTypes)) {
		mysql_query("INSERT INTO files (userid, name, ext, size) VALUES ('$userid', '$file_name', '$ext', '$file_size')"); //Upload the file and add to MySQL
		$fileid = mysql_insert_id();
		move_uploaded_file($_FILES['Filedata']['tmp_name'], $_SERVER['DOCUMENT_ROOT']."/uploads/$fileid." .$fileParts['extension']);
        echo '1';
	} else {
        echo 'Error';
    }
}
?>

