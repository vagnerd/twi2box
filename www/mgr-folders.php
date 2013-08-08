<?php

// Config
//
include("./lib/config.php");

include("".$xlibpath."/functions-generic.php");
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
   foreach ($_POST["folder"] as $Folder) {
      RemoveFolder($BoxID,$Folder);
   }
}

if ($xOption == "New Folder") {
   AddFolder($BoxID,$_POST['newfolder']);
}

header("Location: ./folders.php");

?>
