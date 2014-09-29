<?php
require("../includes/inc.php");
  $pubid = 123456; //Get it from the Adscend Media API Page
  $key = '123456'; //Get it from the Adscend Media API Page
  $user_subid = $_GET['sid']; // (optional) unique ID for this user. this subid is used for checking if the user has completed an offer, and also is appended to offer links. if a subid is not given, the user's IP address will be used for completion checks
  $user_ip = getIP();
  if($_GET['ip']!=''){$user_ip = $_GET['ip'];}
  $date_range = 1; // the number of days to look back through when checking if a lead exists, or getting a count/sum of leads. Enter 0 to perform a search against all leads, regardless of their age. (Using 0 would only be appropriate if you are tracking by a SubID that is permanently assoiciated with a specific user, such as an account number.) If you're requiring only one offer to unlock content, you can consider the date range setting to be the period of time for which the content will be unlocked (with 0 being forever).
  
  
  // first we'll check if a lead exists for the given subid
  // if a lead exists, we'll return some custom content. otherwise we'll return a list of offers 
  
  $mode = 'leads_count';
  $post_data = "pubid=$pubid&key=$key";
  $post_data .= "&user_ip=$user_ip&user_subid=$user_subid&date_range=$date_range";
  $post_data .= "&mode=$mode";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"http://adscendmedia.com/api-get.php?$post_data");
  curl_setopt($ch, CURLOPT_TIMEOUT, 60);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $api_result = curl_exec($ch);
  $num_leads = json_decode($api_result);
  
  if ($num_leads > 0) {
	$mode = 'leads_payout';
	$post_data2 = "pubid=$pubid&key=$key";
	$post_data2 .= "&user_ip=$user_ip&user_subid=$user_subid&date_range=$date_range";
	$post_data2 .= "&mode=$mode";
	$ch2 = curl_init();
	curl_setopt($ch2, CURLOPT_URL,"http://adscendmedia.com/api-get.php?$post_data2");
	curl_setopt($ch2, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
	$api_result2 = curl_exec($ch2);
	$payout = json_decode($api_result2);
	
	$query = mysql_query("SELECT * FROM downloads WHERE `fileid` = '$user_subid' and `ip` = '$user_ip'"); //Check if person witht he same ip already downloaded.
	$count = mysql_num_rows($query);
		if ($count == 0) {
	$total = trim(sanitize(number_format($payout * 0.80, 2, '.', ',')));
	$rtotal = trim(sanitize(number_format($total * 0.10, 2, '.', ',')));
		$q = mysql_query("SELECT * FROM files WHERE id='$user_subid' LIMIT 1");
			while($r = mysql_fetch_array($q)) {
			$userid = trim(sanitize($r['userid']));
			$fileid2 = trim(sanitize($r['id']));
			$filename = trim(sanitize($r['name']));
			$ext = trim(sanitize($r['ext']));
			$mime = contenttype($ext);
			$filesize = trim(sanitize($r['size']));
			$date = trim(sanitize(date("Y:m:d H:i:s")));
					$href = mysql_query("SELECT * from users WHERE id='$userid' LIMIT 1");
					while($gref = mysql_fetch_array($href)) {
					$refid = $gref['referral'];
					if ($refid >= 1) { 
					header('Cache-Control: public');
					header('Content-Transfer-Encoding: binary');
					header("Content-Type: ".$mime);
					header('Content-Disposition: attachment; filename="'.$filename.'"');
					header('Content-Length: ' . $filesize);
					ob_clean();
					flush();
					readfile("../uploads/".$fileid2.".".$ext); 
					mysql_query("INSERT INTO downloads (fileid, ip, date) VALUES ('$fileid2', '$user_ip', '$date')");
					mysql_query("INSERT INTO user_earnings (userid, amount, date, status) VALUES ('$userid', '$total', '$date', '1')");
					mysql_query("INSERT INTO ref_earnings (userid, refid, amount, date, status) VALUES ('$userid', '$refid', '$rtotal', '$date', '1')"); //$userid gave 10% of $total to $refid
					mysql_query("UPDATE files SET earn=earn+$total WHERE id=$fileid2");
					mysql_query("UPDATE files SET downloads=downloads+1 WHERE id=$fileid2");
					} else {
					header('Cache-Control: public');
					header('Content-Transfer-Encoding: binary');
					header("Content-Type: ".$mime);
					header('Content-Disposition: attachment; filename="'.$filename.'"');
					header('Content-Length: ' . $filesize);
					ob_clean();
					flush();
					readfile("../uploads/".$fileid2.".".$ext); 
					mysql_query("INSERT INTO user_earnings (userid, amount, date, status) VALUES ('$userid', '$total', '$date', '1')");
					mysql_query("INSERT INTO downloads (fileid, ip, date) VALUES ('$fileid2', '$user_ip', '$date')");
					mysql_query("UPDATE files SET downloads=downloads+1 WHERE id=$fileid2");
					mysql_query("UPDATE files SET earn=earn+$total WHERE id=$fileid2");
					}
				}
			}	
		}
	}
  // after the first time this page is queried, index.php drops the 'first' paramater from the query string
  // we'll retrieve the offer list only if 'first' exists, since there's no need to retrieve the list again and again
  if (!isset($_GET['first'])){
    exit();
  }
  
  
  $mode = 'offers'; // available modes are: 'offers' -- return a list of offers , 'leads_count' -- returns the number of leads on the specified subid (or IP) , 'leads_payout' -- returns the total commissions earned by leads from the specified subid (or IP). Note: when mode is leads_count or leads_payout, the offer filters (below) are ignored
  $incentive = 1; // 1 for virtual/soft incent ; 2 for points incent (incentives that have a real-world value but are not cash) ; 3 for offers allowing cash incentives ; 0 only if you are offering NO incentive to the user
  $gateway_mode = 1; // 1 to return only gateway offers, and to return the short/gateway name for the offers rather than their nomal name. So for example an offer with the normal name of "[Submit] RewardDeliveryCenter - iPod Touch" has a gateway name of "Want a free Apple iPod Touch?". Also, while points and cash incent offers would be allowable to use for virtual/soft incent, they typically do not convert as well and are not used for our gateways, so setting this option to 1 will generally exclude those offers. Enabling this option forces $incentive=1. The type of offer (free, cell, or trial) will be provided in the variable named 'type'. 
  $free_offers = 1; // Note: this option applies only when $gateway_mode = 1. (at least one of the three options needs to be set to 1)
  $cell_offers = 1; // Note: this option applies only when $gateway_mode = 1. (at least one of the three options needs to be set to 1)
  $trial_offers = 0; // Note: this option applies only when $gateway_mode = 1. (at least one of the three options needs to be set to 1)
  $only_instant = 1; // 1 to only include offers that report coompletions instantly ; 0 for all offers (you should notify users of the reporting time, which is provided in the offer data)
  $min_payout = 0.00; // minimum payout per lead
  $max_cost_to_user = 500.00; // note: any non-zero value will include offers that have a cost of 'Varies'
  $include_completed = 0;  // set to 1 include offers that already have leads (for the subid $user_subid or for the ip if no subid). set to 2 to ONLY return offers that have leads. the array of offers will indicate (with completed=1) which offers were completed (without this option, or without $user_subid specified, any offer that was completed by the user's IP in the past 30 days will not be included in the returned offer list). Note: setting only applies when pulling incent offers
  $include_completed_ignore_ip = 0;
  $creative = 0; // 0 if you don't need images (creatives) ; 1 to retrieve an array of all banners ; otherwise specify a width of image that you want, for example 120. this will return the url of the smallest image with that width (eg 120x60 and not a 120x600). if no image of that width exists, an empty string will be returned. note that there is virtually always a 120x60 creative, although this may be a default/placeholder image, which you can detect and substitute if you wish (the URL to check for is http://adscendmedia.com/creat/default.gif)
  $category = 0; // 0 for any/all ;  other categories (subject to change) are: 1 Biz-Opp , 3 Computers/Technology , 4 Dating , 5 Education/Employment , 6 Entertainment/Games/Music , 7 Credit/Debt/Financial , 8 Freebies/Surveys , 9 Health/Beauty/Diet/Fitness , 10 Home/Family/Pets , 11 Other , 12 Mobile/Cellular , 13 Seasonal , 14 Shopping/Seen On TV , 15 Sweepstakes/Contests , 16 Insurance/Warranty
  $quantity = 5; // number of offers to fetch. 0 for all (note: may be hundreds!)
  $sort = 'epc'; // available options are currently 'epc', 'name', or 'random'. Note: the epc option is currently only for gateway_mode. If epc is chosen and gateway_mode = 0 then the sort method will be changed to name. "Random" mode is not truly random -- it merely sorts the offers by an arbitrary parameter, and will be the same order each time.
  $not_associative = 0; // set to 1 to return data as an indexed array, instead of the default associative array. associative arrays are easiest to work with but are not supported by javascript, so if you plan on working with the data in javascript you will need to set this to 1. (an example of an associative array would be $array['variable'], whereas an indexed array would be $array[0]. refer to our pdf documentation for more info)

  $post_data = "pubid=$pubid&key=$key";
  $post_data .= "&user_ip=$user_ip&user_subid=$user_subid";
  $post_data .= "&mode=$mode";
  $post_data .= "&incent=$incentive&gateway_mode=$gateway_mode&only_instant=$only_instant&include_completed=$include_completed&include_completed_ignore_ip=$include_completed_ignore_ip";
  $post_data .= "&quantity=$quantity&min_payout=$min_payout&max_cost=$max_cost_to_user&free_offers=$free_offers&cell_offers=$cell_offers&trial_offers=$trial_offers";
  $post_data .= "&creative=$creative&category=$category&sort=$sort";
  $post_data .= "&not_assoc=$not_associative";
  //$post_data .= "&simulate_country=US"; // for testing purposes only. see the offers seen by users from other countries
  
  //  echo('http://adscendmedia.com/api-get.php?'.$post_data."\r\n<br /><br /><br />\r\n\r\n");
  curl_setopt($ch, CURLOPT_URL,"http://adscendmedia.com/api-get.php?$post_data");
  curl_setopt($ch, CURLOPT_TIMEOUT, 60);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $api_result = curl_exec($ch);
  $arrOffers = json_decode($api_result, true); // decode the data into a usable php array
    


function getIP(){
  if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")){
      $ip = getenv("HTTP_CLIENT_IP");
  }else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")){
      $ip = getenv("HTTP_X_FORWARDED_FOR");
  }else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")){
      $ip = getenv("REMOTE_ADDR");
  }else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")){
      $ip = $_SERVER['REMOTE_ADDR'];
  }else{
      $ip = 0;
  }
  $pos = strpos($ip,',');
  if ($pos !== false){
    $tempArr = explode(',',$ip);
    $ip = $tempArr[0];
  }
  if($ip=='::1'){$ip='192.168.1.1';}
  return($ip);
}

  
?>
  
  
	<div id="gw_offers"><center>
    <?php
    if (is_array($arrOffers)) {
      foreach ($arrOffers AS &$offer){ ?>
        <b><font size="2"><a onclick="setVisibility('loading', '');" onmouseout="tooltip.hide();" href="<?php echo $offer['url'];?>" target="_blank" class="gw_offer"><?php echo $offer['name'];?></a><br /></font></b>

      <?php }
    } else {      
      echo($arrOffers);
    } ?>
	</center>
	</div>
	