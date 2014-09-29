<?php
require("includes/inc.php");

$userid = $_GET['id'];

header("Content-Type: image/png");
$im = imagecreatefrompng('signature.png');
$background_color = imagecolorallocate($im, 0, 0, 0);
$text_color = imagecolorallocate($im, 255, 255, 255);
$font = 'ar.otf';
//User Stats
imagettftext($im, 17, 0, 140, 51, $text_color, $font, "$".getTodayStats($userid));
imagettftext($im, 17, 0, 140, 81, $text_color, $font, "$".getYesterdayStats($userid)."");
imagettftext($im, 17, 0, 140, 111, $text_color, $font, "$".getMonthStats($userid)."");
//Referral Stats
imagettftext($im, 17, 0, 260, 51, $text_color, $font, "$".getTodayRefStats($userid)."");
imagettftext($im, 17, 0, 260, 81, $text_color, $font, "$".getYesterdayRefStats($userid)."");
imagettftext($im, 17, 0, 260, 111, $text_color, $font, "$".getMonthRefStats($userid)."");
//Leads
imagettftext($im, 17, 0, 379, 51, $text_color, $font, getTodayDownStats($userid)."");
imagettftext($im, 17, 0, 379, 81, $text_color, $font, getYesterdayDownStats($userid)."");
imagettftext($im, 17, 0, 379, 111, $text_color, $font, getMonthDownStats($userid)."");
imagepng($im);
imagedestroy($im);
?>