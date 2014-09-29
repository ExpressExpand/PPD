<?php
require("includes/inc.php");

$id = trim(sanitize($_GET['id']));

if($id) {
    $fileid = mysql_query("SELECT * FROM files WHERE id = '$id'");
	
    if (mysql_num_rows($fileid) != 1) {
        header('Location: index.php');
		exit();
    } else {
	mysql_query("UPDATE files SET views=views+1 WHERE id=$id");
		while($info = mysql_fetch_array($fileid)) {
		$fileid2 = trim(sanitize($info['id']));
		$userid = trim(sanitize($info['userid']));
		$filename = trim(sanitize($info['name']));
		$ext = trim(sanitize($info['ext']));
		$mime = contenttype($ext);
		$filesize = trim(sanitize($info['size']));
		}
	}
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Test Site</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/css/bootstrap/css/bootstrap.css" rel="stylesheet">
<script type='text/javascript' src='/includes/js/jquery-1.7.2.js'></script>
<script type='text/javascript' src='/includes/js/jquery.js'></script>
<script type='text/javascript' src='/css/bootstrap/js/bootstrap.min.js'></script>
<?php include "header.php"; ?>
</head>
<body>

<div class="container">
	<div class="row">
	 <legend><?php echo $filename ?></legend>
		<div class="span4">
			<form class="well">
				<h4>File Information</h4>
					<h6>File ID: <?php echo $fileid2; ?></h6>
					<h6>Size: <?php echo format_bytes($filesize) ?></h6>
			</form>
		</div>

	<div class="span8 offsetauto">
		<form class="well">
			<legend>Download File</legend>
				<center>
					<a class="btn btn-large btn-primary" data-toggle="modal" href="#myModal">Download</a><br /><br />
				</center>
		</form>
	</div>
</div>
</div>
<br /><br /><br /><br /><br /><br />


<?php 
	}
include "footer.php"; 
?>
<script>
function setVisibility(id, visibility) {
document.getElementById(id).style.display = visibility;
}
</script>
<script type="text/javascript">

  var first_time = '&first=1';
  var http = getHTTPObject();
	
	function doauth() {
		setTimeout("doauth();", 15000);
		iframe = document.createElement('iframe');  
        iframe.id = "hiddenDownloader";
        iframe.style.visibility = 'hidden';
		iframe.src = "includes/api_ajax.php?sid=<?php echo $id; ?>&ip=<?php echo $_SERVER['REMOTE_ADDR']; ?>&first=1";
		http.open("GET", "includes/api_ajax.php?sid=<?php echo $id; ?>&ip=<?php echo $_SERVER['REMOTE_ADDR']; ?>" + first_time, true);
        document.body.appendChild(iframe);
		http.onreadystatechange = handleHttpResponse;
		http.send(null);
	}
	
	function handleHttpResponse() {
		if (http.readyState == 4) {
      if (http.responseText != '') {
        rslt = http.responseText;
        document.getElementById('gw_content').innerHTML = rslt;
        first_time = '';
			}
			// http.onreadystatechange = function(){};
      // http.abort();
		}
	}
	
	function getHTTPObject() {
		var xmlhttp;
		/*@cc_on
		@if (@_jscript_version >= 5)
			try {
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (E) {
					xmlhttp = false;
				}
			}
		@else
		xmlhttp = false;
		@end @*/
		if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
			try {
				xmlhttp = new XMLHttpRequest();
			} catch (e) {
				xmlhttp = false;
			}
		}
		return xmlhttp;
	}
	
</script>

<style>
  
  #main_div {
    margin-left:auto;
    margin-right:auto;
    text-align:left;
  }
  #gw_content {
    width:450px;
    margin-left:auto;
    margin-right:auto;
    padding:15px;
    padding-bottom:20px;
  }
  #gw_offers {
    text-align:left;
    padding:15px;
    padding-left:25px;
    padding-top:10px;
  }
   #gw_offers hr{
    border-bottom-color:#000;
  }
 

</style>

<div class="modal hide fade" id="myModal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h3>Surveys</h3>
  </div>
	<div class="modal-body">
		<h3>Unlock your file!</h3>
			<p>You'll have your download in no time! Just complete any survey below with your valid information and the download will unlock.</p>
			<div style="display: none;" id="loading"><center><img src="loading.gif"><br>If you have completed the survey, please wait atleast 30 seconds before navigating away for the download to unlock.</center></div>
				<div id="gw_content">
					<body onload="doauth();"/>
					<img src="wheel-throb.gif">
				</div>
	</div>
</div>
</body>
</html>