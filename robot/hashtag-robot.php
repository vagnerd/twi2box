<?php

// Includes 
include("/var/www/twi2box.me/www/lib/config.php");
include("".$xlibpath."/functions-generic.php");
include("".$xlibpath."/functions-hashtags.php");
include("".$xlibpath."/functions-robot.php");
require_once(''.$xlibpath.'/twitteroauth/twitteroauth.php');

GetLastHashTags();

?>
