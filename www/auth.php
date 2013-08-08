<?php

// Config
//

include("./lib/config.php");

// LIB Headers
//
include("".$xlibpath."/logger.php");

// Session Start
// 

session_start();

require_once(''.$xlibpath.'/twitteroauth/twitteroauth.php');

$xTwitterConn = new TwitterOAuth($xtConsummerKey,$xtConsummerSecret);
$xTwitterReqToken = $xTwitterConn->getRequestToken($xtCallBack);

$_SESSION['oauth_token'] = $token = $xTwitterReqToken['oauth_token'];
$_SESSION['oauth_token_secret'] = $xTwitterReqToken['oauth_token_secret'];

//boxlogger($_SESSION['oauth_token']);
//boxlogger($_SESSION['oauth_token_secret']);
//boxlogger("--".$_SESSION['access_token']['oauth_token']."--");
//boxlogger("--".$_SESSION['access_token']['oauth_token_secret']."--");

// Check Connection
// 
if ($xTwitterConn->http_code == 200) {
   echo "Staring connection in APP";
   $xTwitterPermUrl = $xTwitterConn->getAuthorizeURL($token);
   header("Location: $xTwitterPermUrl");
} else {
   echo "Twitter Connection Problem";
}

?>
