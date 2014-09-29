<?php
require("includes/inc.php");
  if ($_SESSION['username'] == null) {
  header('Location: login.php');
  exit();
  }
	$id = trim(sanitize($user['id']));
	$amount = trim(sanitize(number_format(getCurrentStats($user['id']) + getCurrentRefStats($user['id']), 2, '.', ',')));
  if ($_GET['do'] == "paypal" && $amount > 19.99) {
  $name = trim(sanitize($_POST['fullname']));
   $paypal = trim(sanitize($_POST['paypal']));
		mysql_query("INSERT INTO payments (userid, name, amount, type, email, status) VALUES ('$id', '$name', '$amount', 'PayPal', '$paypal', 'Pending')");
		mysql_query("UPDATE user_earnings SET pay = '1' WHERE userid='$id'");
		mysql_query("UPDATE ref_earnings SET pay = '1' WHERE userid='$id'");
		header('Location: payments.php?do=success');
	} else {
		if ($_GET['do'] == "alertpay" && $amount > 19.99) {
		$name = trim(sanitize($_POST['fullname']));
	    $alertpay = trim(sanitize($_POST['alertpay']));
			mysql_query("INSERT INTO payments (userid, name, amount, type, email, status) VALUES ('$id', '$name', '$amount', 'AlertPay', '$alertpay', 'Pending')");
			mysql_query("UPDATE user_earnings SET pay = '1' WHERE userid='$id'");
			mysql_query("UPDATE ref_earnings SET pay = '1' WHERE userid='$id'");
			header('Location: payments.php?do=success');
		} else {
			 if ($_GET['do'] == "moneybookers" && $amount > 24.99) {
		$name = trim(sanitize($_POST['fullname']));
	    $moneybookers = trim(sanitize($_POST['moneybookers']));
			mysql_query("INSERT INTO payments (userid, name, amount, type, email, status) VALUES ('$id', '$name', '$amount', 'MoneyBookers', '$moneybookers', 'Pending')");
			mysql_query("UPDATE user_earnings SET pay = '1' WHERE userid='$id'");
			mysql_query("UPDATE ref_earnings SET pay = '1' WHERE userid='$id'");
			header('Location: payments.php?do=success');
			} else {
				if ($_GET['do'] == "wire" && $amount > 49.99) {
					$name = trim(sanitize($_POST['fullname']));
					$sort = trim(sanitize($_POST['sort']));
					$account = trim(sanitize($_POST['account']));
						mysql_query("INSERT INTO payments (userid, name, amount, type, sort, account, status) VALUES ('$id', '$name', '$amount', 'Wire Transfer', '$sort', '$account', 'Pending')");
						mysql_query("UPDATE user_earnings SET pay = '1' WHERE userid='$id'");
						mysql_query("UPDATE ref_earnings SET pay = '1' WHERE userid='$id'");
						header('Location: payments.php?do=success');
	 } else {
	   $result = mysql_query("SELECT * FROM payments WHERE userid='$id'");
	   $totalr = mysql_num_rows($result); 
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

<script>

function toggleLayer( whichLayer )
{
  var elem, vis;
  if( document.getElementById ) // this is the way the standards work
    elem = document.getElementById( whichLayer );
  else if( document.all ) // this is the way old msie versions work
      elem = document.all[whichLayer];
  else if( document.layers ) // this is the way nn4 works
    elem = document.layers[whichLayer];
  vis = elem.style;
  // if the style.display value is blank we try to figure it out here
  if(vis.display==''&&elem.offsetWidth!=undefined&&elem.offsetHeight!=undefined)
    vis.display = (elem.offsetWidth!=0&&elem.offsetHeight!=0)?'block':'none';
  vis.display = (vis.display==''||vis.display=='block')?'none':'block';
}


function showinfo(type){
	if(type == 1){
		toggleLayer('paypal');
		document.getElementById('wire_transfer').style.display='none';
		document.getElementById('alertpay').style.display='none';
		document.getElementById('moneybookers').style.display='none';
	} else if(type == 2){
		toggleLayer('alertpay');
		document.getElementById('wire_transfer').style.display='none';
		document.getElementById('paypal').style.display='none';
		document.getElementById('moneybookers').style.display='none';
	} else if(type == 3){
		toggleLayer('moneybookers');
		document.getElementById('wire_transfer').style.display='none';
		document.getElementById('paypal').style.display='none';
		document.getElementById('alertpay').style.display='none';
	} else if(type == 4){
		toggleLayer('wire_transfer');
		document.getElementById('paypal').style.display='none';	
		document.getElementById('alertpay').style.display='none';
		document.getElementById('moneybookers').style.display='none';
	}
}

function changeselect(find){
	for (var i=0; i < document.request_form.method.length; i++) {
		if (document.request_form.method[i].value == find) {
			document.request_form.method[i].selected = true;
		}
	}
}
</script>

<legend>Payments</legend>
	<?php
		if ($_GET['do'] == "success"){ echo "<div class='alert alert-success'>Your payment has been added to the pending list.</div>"; }
	?>
      <h5>You can cash out your earnings here once you reach $20.00.</h5>
	  <h5>All payments are sent on NET30 this is temporary.</h5>
	  <div class="controls">
        
		<h4>Current Earnings: $<?php echo number_format(getCurrentStats($user['id']) + getCurrentRefStats($user['id']), 2, '.', ','); ?></h4><br />
			<?php
				if (number_format(getCurrentStats($user['id']) + getCurrentRefStats($user['id']), 2, '.', ',') > 19.99) {
			?>
			<tr>
			<td height="25" align="center" valign="middle"><select name="method" onChange='showinfo(this.value);'>
				<option value="1" >PayPal</option>
                <option value="2" >Payza (AlertPay)</option>
				<option value="3" >MoneyBookers</option>
                <option value="4" >UK Bank Wire Transfer (>50$)</option>
            </select></td>
          </tr>
		  <div id="paypal" style="display:inline">
		  <form class="form-horizontal" id="account" method='post' action='?do=paypal'>
		  				 <div class="control-group">
		<label class="control-label" for="input01">Full Name</label>
	      <div class="controls">
	        <input type="text" class="input" id="fullname" name="fullname">
	       
	      </div>
	</div>
	 <div class="control-group">
		<label class="control-label" for="input01">PayPal Email</label>
	      <div class="controls">
	        <input type="text" class="input" id="paypal" name="paypal">
	       
	      </div>
	</div>
	 <div class="control-group">
		<label class="control-label" for="input01"></label>
	      <div class="controls">
	       <button type="submit" class="btn btn-primary">Request Payment</button>
	       
	      </div>
	</div>
					</form>
				</div>
			<div id="alertpay" style="display:none">
			<form class="form-horizontal" id="account" method='post' action='?do=alertpay'>
							 <div class="control-group">
		<label class="control-label" for="input01">Full Name</label>
	      <div class="controls">
	        <input type="text" class="input" id="fullname" name="fullname">
	       
	      </div>
	</div>
	 <div class="control-group">
		<label class="control-label" for="input01">AlertPay Email</label>
	      <div class="controls">
	        <input type="text" class="input" id="alertpay" name="alertpay">
	       
	      </div>
	</div>
	 <div class="control-group">
		<label class="control-label" for="input01"></label>
	      <div class="controls">
	       <button type="submit" class="btn btn-primary">Request Payment</button>
	       
	      </div>
	</div>
					</form>
				</div>
			<div id="moneybookers" style="display:none">
			<form class="form-horizontal" id="account" method='post' action='?do=moneybookers'>
				 <div class="control-group">
		<label class="control-label" for="input01">Full Name</label>
	      <div class="controls">
	        <input type="text" class="input" id="fullname" name="fullname">
	       
	      </div>
	</div>
	 <div class="control-group">
		<label class="control-label" for="input01">MoneyBookers Email</label>
	      <div class="controls">
	        <input type="text" class="input" id="moneybookers" name="moneybookers">
	       
	      </div>
	</div>
	 <div class="control-group">
		<label class="control-label" for="input01"></label>
	      <div class="controls">
	       <button type="submit" class="btn btn-primary">Request Payment</button>
	       
	      </div>
	</div>
					</form>
				</div>
			<div id="wire_transfer" style="display:none">
				<form class="form-horizontal" id="account" method='post' action='?do=wire'>
					<p><b>Wire Transfers are only avaliable for the UK.</b></p>
	 <div class="control-group">
		<label class="control-label" for="input01">Full Name:</label>
	      <div class="controls">
	        <input type="text" class="input" id="full" name="fullname">
	       
	      </div>
	</div>
	 <div class="control-group">
		<label class="control-label" for="input01">Sort Code:</label>
	      <div class="controls">
	        <input type="text" class="input" id="sort" name="sort">
	       
	      </div>
	</div>
	 <div class="control-group">
		<label class="control-label" for="input01">Account Number:</label>
	      <div class="controls">
	        <input type="text" class="input" id="account" name="account">
	       
	      </div>
	</div>
	 <div class="control-group">
		<label class="control-label" for="input01"></label>
	      <div class="controls">
	       <button type="submit" class="btn btn-primary">Request Payment</button>
	       
	      </div>
	</div>
					</form>
			</div>
			<?php
				}
			?>
      </div><br />

<legend>Your Payments</legend>
<table class="table table-bordered table-condensed">
    <tr>
       <th>ID</th>
	   <th>Amount</th>
       <th>Status</th>
    </tr>
        <?php 
		while($row = mysql_fetch_assoc($result)) {
	   ?>
    <tr>
	   <td><?php echo $row['id']; ?></td>
	   <td>$<?php echo $row['amount'] ?></td>
       <td><?php echo $row['status']; ?></td>
    </tr>
        <?php
				}
			}
		}
	}
}
	   ?>
</table>
	<h6>Your payment amount may change if there was any reversed leads.</h6>
	<?php include "footer.php"; ?>
</body>
</html>
	   