<? 

$SUBID='NICHE1'; // DEFINE the SUB ID for all tracking links

function GetIP()
{
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key)
    {
        if (array_key_exists($key, $_SERVER) === true)
        {
            foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip)
            {
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false)
                {
                    return $ip;
                }
            }
        }
    }
}

$userIP=mysql_real_escape_string(GetIP());

// CALL YOUR API URL
// Recommended to use optimize=EPC and oType=2 for Best Earnings
// REPLACE URL BELOW WITH YOUR URL OR ENTER YOUR PUB ID AND API KEY
$OfferFeed = simplexml_load_file("http://www.adworkmedia.com/api/index.php?pubID=12995&apiID=on7k4izb6rmouwd4afi19oc5d3jx9lkiqpj6ipnu&campDetails=true&optimize=EPC&oType=1&maxCampaigns=5&userIP=".$userIP);

foreach ($OfferFeed as $singleOffer) { ?>
	 <center><a style="font-size:14px;" href="<?php echo $singleOffer->url;?><?=$SUBID;?>" title="<?php $singleOffer->conversion_point; ?>" target="_blank"><b><?php echo stripslashes($singleOffer->teaseText);?></b></a><br /></center>
<? } 
// MORE DETAILS can be added - See the full API Documentation here: https://www.adworkmedia.com/publisher/index.php?option=tools&section=api_reporting
?>

