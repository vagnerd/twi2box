<?php

// Config
//
include("./lib/config.php");

include("".$xlibpath."/functions-generic.php");
include("".$xlibpath."/functions-messages.php");

// MAIN
//
session_start();
if (!isset($_SESSION['access_token']))
   header("Location: ./clearsessions.php");

$xOption = $_POST['acao'];

if ($xOption == "Delete") {
   foreach ($_POST["msgid"] as $MSGID) {
      MoveMessageTo($MSGID,"trash");
   }
}

if ($xOption == "Move") {
   foreach ($_POST["msgid"] as $MSGID) {
      MoveMessageTo($MSGID,$_POST['boxto']);
   }
}

if ($xOption == "Copy") {
   foreach ($_POST["msgid"] as $MSGID) {
      CopyMessageTo($MSGID,$_POST['boxto']);
   }
}

header("Location: ./index.php");

?>
