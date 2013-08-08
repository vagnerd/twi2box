<?php

// Config
//
include("./lib/config.php");

include("".$xlibpath."/functions-generic.php");
include("".$xlibpath."/functions-filters.php");

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

if ($_POST['notifyemail'] == '1') {
   $NotifyEmail = 1;
} else {
   $NotifyEmail = 0;
}

$xOption = $_POST['acao'];

echo $_POST['action'];

if ($xOption == "Delete") {
   foreach ($_POST["filter"] as $Filter) {
      RemoveFilter($BoxID,$Filter);
      header("Location: ./filters.php");
   }
}

if ($xOption == "New Filter") {
   header("Location: ./newfilter.php");
}

if ($xOption == "Add Filter") {
   AddFilter($BoxID,$_POST['filtername'],$_POST['filter'],$_POST['boxto'],$_POST['action'],$NotifyEmail);
   header("Location: ./filters.php");
}

#header("Location: ./filters.php");
?>
