<?php
  // Function to sanitize data wrapped in sanitize() -> Prevent SQLi attacks
  function sanitize($data) {
    if(is_array($data)) {
      foreach($data as $key => $contents) {
      $data[$key] = sanitize($contents);
    }
    return $data;
    } else {
      $data = trim($data);
      if(get_magic_quotes_gpc()) {
      $data = stripslashes($data);
    }
    $data = mysql_real_escape_string(htmlspecialchars($data));
    return $data;
    }
  }
  
  function getUsername($userid) {
	$rs = mysql_query("SELECT username FROM `users` WHERE id = '$userid'") or die(mysql_error());
		$row = mysql_fetch_array($rs);
		$username = $row['username'];
			return $username;
  }
  
  // Function to encrypt passwords
  function password($password) {
    global $key;
    return hash_hmac('sha512', $password, $key);
  }
  
  function format_bytes($a_bytes) {
    if ($a_bytes < 1024) {
        return $a_bytes .'bytes';
    } elseif ($a_bytes < 1048576) {
        return round($a_bytes / 1024, 2) .'kb';
    } else {
        return round($a_bytes / 1048576, 2) . 'mb';
	}
}

	function contenttype($ext) {
		$mime_types = array();
		$mime_types['ai']    ='application/postscript';
		$mime_types['asx']   ='video/x-ms-asf';
		$mime_types['au']    ='audio/basic';
		$mime_types['avi']   ='video/x-msvideo';
		$mime_types['bmp']   ='image/bmp';
		$mime_types['css']   ='text/css';
		$mime_types['doc']   ='application/msword';
		$mime_types['eps']   ='application/postscript';
		$mime_types['exe']   ='application/octet-stream';
		$mime_types['gif']   ='image/gif';
		$mime_types['htm']   ='text/html';
		$mime_types['html']  ='text/html';
		$mime_types['ico']   ='image/x-icon';
		$mime_types['jpe']   ='image/jpeg';
		$mime_types['jpeg']  ='image/jpeg';
		$mime_types['jpg']   ='image/jpeg';
		$mime_types['js']    ='application/x-javascript';
		$mime_types['mid']   ='audio/mid';
		$mime_types['mov']   ='video/quicktime';
		$mime_types['mp3']   ='audio/mpeg';
		$mime_types['mpeg']  ='video/mpeg';
		$mime_types['mpg']   ='video/mpeg';
		$mime_types['pdf']   ='application/pdf';
		$mime_types['pps']   ='application/vnd.ms-powerpoint';
		$mime_types['ppt']   ='application/vnd.ms-powerpoint';
		$mime_types['ps']    ='application/postscript';
		$mime_types['pub']   ='application/x-mspublisher';
		$mime_types['qt']    ='video/quicktime';
		$mime_types['rtf']   ='application/rtf';
		$mime_types['svg']   ='image/svg+xml';
		$mime_types['swf']   ='application/x-shockwave-flash';
		$mime_types['tif']   ='image/tiff';
		$mime_types['tiff']  ='image/tiff';
		$mime_types['txt']   ='text/plain';
		$mime_types['wav']   ='audio/x-wav';
		$mime_types['wmf']   ='application/x-msmetafile';
		$mime_types['xls']   ='application/vnd.ms-excel';
		$mime_types['zip']   ='application/zip';
	if(array_key_exists($ext,$mime_types)) {
		$mimetype = $mime_types[$ext];
	} else { 
		$mimetype = 'application/force-download'; 
	}
	return $mimetype;
}
	//Get User Earnings Functions
	
	function getCurrentStats($userid) {
        $rs = mysql_query("SELECT SUM(`amount`) AS `total_earnings` FROM `user_earnings` WHERE userid = '$userid' and status = '1' and pay = '0'") or die(mysql_error());
        $row = mysql_fetch_assoc($rs);
        $earnings = number_format($row['total_earnings'], 2, '.', ',');
        return $earnings;
	}
	
	function getTodayStats($userid) {
        $today = date("Y:m:d 00:00:00");
        $rs = mysql_query("SELECT SUM(`amount`) AS `total_earnings`, date(date) FROM `user_earnings` WHERE date >= '$today' and userid = '$userid' and status = '1' group by DAY(date)") or die(mysql_error());
        $row = mysql_fetch_assoc($rs);
        $earnings = number_format($row['total_earnings'], 2, '.', ',');
        return $earnings;
	}
	
	function getYesterdayStats($userid) {
		$today = date("Y:m:d 00:00:00");
        $yesterday = date("Y:m:d", mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
        $rs = mysql_query("SELECT SUM(`amount`) AS `total_earnings`, date(date) FROM `user_earnings` WHERE date >= '$yesterday' and date <= '$today' and userid = '$userid' and status = '1' group by DAY(date)") or die(mysql_error());
        $row = mysql_fetch_assoc($rs);
        $earnings = number_format($row['total_earnings'], 2, '.', ',');
        return $earnings;
	}

	function getMonthStats($userid) {
        $first_day = date("Y:m:01 00:00:00");
        $last_day = date("Y:m:31 23:59:59");
        $rs = mysql_query("SELECT SUM(`amount`) AS `total_earnings`, date(date) FROM `user_earnings` WHERE date >= '$first_day' and date <= '$last_day' and userid = '$userid' and status = '1' GROUP BY MONTH(date)");
        $row = mysql_fetch_assoc($rs);
        $earnings = number_format($row['total_earnings'], 2, '.', ',');
        return $earnings;		
	}

	function getLastMonthStats($userid) {
        $first_day = date("Y:m:01 00:00:00", mktime(0, 0, 0, date("m")-1, date("d"), date("Y")));
        $last_day = date("Y:m:31 23:59:59", mktime(0, 0, 0, date("m")-1, date("d"), date("Y")));
        $rs = mysql_query("SELECT SUM(`amount`) AS `total_earnings`, date(date) FROM `user_earnings` WHERE date >= '$first_day' and date <= '$last_day' and userid = '$userid' and status = '1' GROUP BY MONTH(date)");
        $row = mysql_fetch_assoc($rs);
        $earnings = number_format($row['total_earnings'], 2, '.', ',');
        return $earnings;		
	}
	
	function getAllTimeStats($userid) {
        $rs = mysql_query("SELECT SUM(`amount`) AS `total_earnings` FROM `user_earnings` WHERE userid = '$userid' and status = '1'");
        $row = mysql_fetch_assoc($rs);
        $earnings = number_format($row['total_earnings'], 2, '.', ',');
        return $earnings;		
	}
	
	//Get Referral Earnings Functions
	
	function getCurrentRefStats($userid) {
        $rs = mysql_query("SELECT SUM(`amount`) AS `total_earnings` FROM `ref_earnings` WHERE refid = '$userid' and status = '1' and pay = '0'") or die(mysql_error());
        $row = mysql_fetch_assoc($rs);
        $earnings = number_format($row['total_earnings'], 2, '.', ',');
        return $earnings;
	}
	
	function getTodayRefStats($userid) {
        $today = date("Y:m:d 00:00:00");
        $rs = mysql_query("SELECT SUM(`amount`) AS `total_earnings`, date(date) FROM `ref_earnings` WHERE date >= '$today' and refid = '$userid' and status = '1' group by DAY(date)") or die(mysql_error());
        $row = mysql_fetch_assoc($rs);
        $earnings = number_format($row['total_earnings'], 2, '.', ',');
        return $earnings;
	}
	
	function getYesterdayRefStats($userid) {
		$today = date("Y:m:d 00:00:00");
        $yesterday = date("Y:m:d", mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
        $rs = mysql_query("SELECT SUM(`amount`) AS `total_earnings`, date(date) FROM `ref_earnings` WHERE date >= '$yesterday' and date <= '$today' and refid = '$userid' and status = '1' group by DAY(date)") or die(mysql_error());
        $row = mysql_fetch_assoc($rs);
        $earnings = number_format($row['total_earnings'], 2, '.', ',');
        return $earnings;
	}

	function getMonthRefStats($userid) {
        $first_day = date("Y:m:01 00:00:00");
        $last_day = date("Y:m:31 23:59:59");
        $rs = mysql_query("SELECT SUM(`amount`) AS `total_earnings`, date(date) FROM `ref_earnings` WHERE date >= '$first_day' and date <= '$last_day' and refid = '$userid' and status = '1' GROUP BY MONTH(date)");
        $row = mysql_fetch_assoc($rs);
        $earnings = number_format($row['total_earnings'], 2, '.', ',');
        return $earnings;		
	}

	function getLastMonthRefStats($userid) {
        $first_day = date("Y:m:01 00:00:00", mktime(0, 0, 0, date("m")-1, date("d"), date("Y")));
        $last_day = date("Y:m:31 23:59:59", mktime(0, 0, 0, date("m")-1, date("d"), date("Y")));
        $rs = mysql_query("SELECT SUM(`amount`) AS `total_earnings`, date(date) FROM `ref_earnings` WHERE date >= '$first_day' and date <= '$last_day' and refid = '$userid' and status = '1' GROUP BY MONTH(date)");
        $row = mysql_fetch_assoc($rs);
        $earnings = number_format($row['total_earnings'], 2, '.', ',');
        return $earnings;		
	}
	
	function getAllTimeRefStats($userid) {
        $rs = mysql_query("SELECT SUM(`amount`) AS `total_earnings` FROM `ref_earnings` WHERE refid = '$userid' and status = '1'");
        $row = mysql_fetch_assoc($rs);
        $earnings = number_format($row['total_earnings'], 2, '.', ',');
        return $earnings;		
	}
	
	//Get Download Stats
	
	function getTodayDownStats($userid) {
        $today = date("Y:m:d 00:00:00");
		$query = mysql_query("SELECT date(date) FROM `user_earnings` WHERE date >= '$today' and userid = '$userid' and status = '1'") or die(mysql_error());
        $rs = mysql_num_rows($query);
        return $rs;
	}
	
	function getYesterdayDownStats($userid) {
		$today = date("Y:m:d 00:00:00");
        $yesterday = date("Y:m:d", mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
        $query = mysql_query("SELECT date(date) FROM `user_earnings` WHERE date >= '$yesterday' and date <= '$today' and userid = '$userid' and status = '1'") or die(mysql_error());
		$rs = mysql_num_rows($query);
        return $rs;
	}

	function getMonthDownStats($userid) {
        $first_day = date("Y:m:01 00:00:00");
        $last_day = date("Y:m:31 23:59:59");
        $query = mysql_query("SELECT date(date) FROM `user_earnings` WHERE date >= '$first_day' and date <= '$last_day' and userid = '$userid' and status = '1'");
        $rs = mysql_num_rows($query);
        return $rs;		
	}

	function getLastMonthDownStats($userid) {
        $first_day = date("Y:m:01 00:00:00", mktime(0, 0, 0, date("m")-1, date("d"), date("Y")));
        $last_day = date("Y:m:31 23:59:59", mktime(0, 0, 0, date("m")-1, date("d"), date("Y")));
        $query = mysql_query("SELECT date(date) FROM `user_earnings` WHERE date >= '$first_day' and date <= '$last_day' and userid = '$userid' and status = '1' GROUP BY MONTH(date)");
		$rs = mysql_num_rows($query);
        return $rs;		
	}
	
	function getAllTimeDownStats($userid) {
        $query = mysql_query("SELECT * FROM `user_earnings` WHERE userid = '$userid' and status = '1'");
		$rs = mysql_num_rows($query);
        return $rs;		
	}
?>