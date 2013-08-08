<?php

// Config
//
include("./lib/config.php");
include("".$xlibpath."/functions-generic.php");

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

$Email = $_POST['email'];

UpdateSettings($BoxID,$Email);

header("Location: ./settings.php");
?>
