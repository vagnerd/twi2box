<?php

// Includes 
#include("/var/www/twi2box.me/www/lib/config.php");
#include("".$xlibpath."/functions.php");

$hashtag="HashTag";

#$json = file_get_contents("http://search.twitter.com/search.json?result_type=recent&rpp=100&q=" . $hashtag);
while (1) {
	$json = file_get_contents("http://search.twitter.com/search.json?result_type=recent&rpp=1&q=" . $hashtag);
	$results = json_decode($json)->results;

	echo "from: ".$results[0]->from_user." text: ".$results[0]->text."\n";
	sleep(1);
}


?>
