<?php 
require("includes/inc.php"); 
  if ($_SESSION['username'] == null) {
    //Redirect the user to the login page
    header('Location: login.php');
	exit();
  }
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Test Site</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/css/bootstrap/css/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/css/uploadify.css">
<?php include "header.php"; ?>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/includes/uploadify/jquery.uploadify-3.1.min.js"></script>
</head>
<body>

    <script type="text/javascript">
	
    $(function() {
        $('#file_upload').uploadify({
			'fileSizeLimit' : '40MB',
			'buttonImage' : '/includes/uploadify/browse-btn.png',
            'swf'      : '/includes/uploadify/uploadify.swf',
            'uploader' : '/includes/uploadify/uploadify.php',
			'cancelImg' : '/includes/uploadify/cancel.png',
			'method'      : 'POST',
			'multi'       : true,
			'formData' : { 'userid' : '<?php echo $user['id']; ?>' },
			'auto'      : true
        });
    });
	
    </script>		

<div class="container">
	
    <?php
      //Error Messages
      if ($_GET['success'] == "uploaded"){ echo "<div class='alert alert-success'>Your file has been uploaded</div>\n"; }
      elseif ($_GET['error'] == "size"){ echo "<div class='alert alert-error'>Sorry, your file must be under 40mb</div>\n"; }
	  elseif ($_GET['error'] == "no_file"){ echo "<div class='alert alert-error'>Please choose a file to upload</div>\n"; }
	  elseif ($_GET['error'] == "file_type"){ echo "<div class='alert alert-error'>You may only upload .jpg .jpeg .gif .png</div>\n"; }
    ?>
	
		<legend>Prohibbited Content</legend>
	<h6>1. Copyrighted video such as TV shows or movies</h6>
	<h6>2. Any content that infringes on the copyrights of others</h6>
	<h6>3. Adult content (nudity, etc)</h6>
	<h6>4. Fake or misleading content</h6>
	<h6>5. Any pages that imply the availability of the above</h6>
	<h6>6. Abuse of social media platforms</h6><br />
		<form class="well">
			<legend>Upload</legend>
				<input type="file" name="file_upload" id="file_upload" />
			<h6>Maximum File Size: 40MB</h6>
		</form>
</body>
</html>
<?php include "footer.php"; ?>


