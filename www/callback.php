<?php

// Includes 
include("./lib/config.php");
include("".$xlibpath."/functions-generic.php");
require_once(''.$xlibpath.'/twitteroauth/twitteroauth.php');

// Start Twitter Session
   session_start();
   $xTwitterConn = new TwitterOAuth($xtConsummerKey, $xtConsummerSecret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
   $xTwitterAccessToken = $xTwitterConn->getAccessToken($_REQUEST['oauth_verifier']);
   $_SESSION['access_token'] = $xTwitterAccessToken;

// Logging
   boxlogger("Callback/Session - oauth_token: ".$_SESSION['oauth_token']."");
   boxlogger("Callback/Session - oauth_token_secret: ".$_SESSION['oauth_token_secret']."");
   boxlogger("Callback/Session - access_token-oauth_token: ".$_SESSION['access_token']['oauth_token']."");
   boxlogger("Callback/Session - access_token-oauth_token_secret: ".$_SESSION['access_token']['oauth_token_secret']."");

// Check Register
   $xToken=$_SESSION['access_token']['oauth_token'];
   $xTokenSecret=$_SESSION['access_token']['oauth_token_secret'];
   $xUserInfo=$xTwitterConn->get('account/verify_credentials');
   $xUserScreenName=strtolower($xUserInfo->screen_name);
   $xUserTwitterID=$xUserInfo->id;
   $xLastMessageID=0;
   $xtLastMessageID = $xTwitterConn->get("statuses/mentions_timeline", array('count' => '1'));
   foreach ($xtLastMessageID as $x) {
      $xLastMessageID = $x->id_str;
   }

   CheckRegister($xUserTwitterID,$xUserScreenName,$xLastMessageID,$xToken,$xTokenSecret);

// Unset Sessions
  unset($_SESSION['oauth_token']);
  unset($_SESSION['oauth_token_secret']);

  if ($xTwitterConn->http_code == 200) {
     boxlogger("Callback/Session - Login: Successful for ".$xUserTwitterID ."/".$xUserScreenName."");
     header("Location: $xappurl/");
  } else {
       header("Location: $xappurl/auth.php");
    }

?>
