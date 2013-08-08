<?php

// Config
//
include("./lib/config.php");

include("".$xlibpath."/functions-generic.php");
include("".$xlibpath."/functions-hashtags.php");
include("".$xlibpath."/functions-folders.php");

// LIB Headers
//
require_once("".$xlibpath."/twitteroauth/twitteroauth.php");

// MAIN
//
session_start();
if (!isset($_SESSION['access_token']))
   header("Location: ./clearsessions.php");

$xTwitterAccessToken = $_SESSION['access_token'];
$BoxID=Get2boxid($xTwitterAccessToken['oauth_token']);

$xOption = $_POST['acao'];

if ($xOption == "Delete") {
   foreach ($_POST["hashtag"] as $HashTag) {
      RemoveHashTag($BoxID,$HashTag);
      header("Location: ./hashtags.php");
   }
}

if ($xOption == "New Hashtag") {
   header("Location: ./newhashtag.php");
}

if ($xOption == "Add Hashtag") {
   if (isset($_POST['autoreplytext']) && $_POST['autoreplytext'] != "") {
	$xAddID=AddAutoReply($BoxID,$_POST['autoreplytext']);
	AddHashTag($BoxID,$_POST['hashtagname'],$_POST['boxto'],$_POST['action'],$xAddID,$xTwitterAccessToken['oauth_token'], $xTwitterAccessToken['oauth_token_secret']);
	header("Location: ./hashtags.php");
   } else {
   	AddHashTag($BoxID,$_POST['hashtagname'],$_POST['boxto'],$_POST['action'],0,$xTwitterAccessToken['oauth_token'], $xTwitterAccessToken['oauth_token_secret']);
	header("Location: ./hashtags.php");
   }
}

#header("Location: ./filters.php");
?>
