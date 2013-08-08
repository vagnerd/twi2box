<?php

/// Twi2Box.Me
// HashTags Manager Functions

// GetHashTags()
// Get Hash Tags is very simple function for print functions of user
// Uses for html managers, this is function append --||-- for split layout
//

function GetHashTags($xBoxID) {

	include("config.php");

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$xQuery = "SELECT 2boxid,hashtagid,hashtagname,boxto,action FROM hashtags WHERE 2boxid = \"".$xBoxID."\";";
	$xQueryExec = mysql_query($xQuery, $xDBConn);
	$xCount = 0;

	while (list($boxid, $hashtagid, $hashtagname, $boxto, $action) = mysql_fetch_array($xQueryExec)) {
	
		$GeneralHashTags[$xCount] = "$boxid--||--$hashtagid--||--$hashtagname--||--$boxto--||--$action";
		$xCount++;
	
	}

	return $GeneralHashTags;
	mysql_close($xDBConn);

}

// AddHashTag()
// Add Hash Tag filter for user, this is function is INSANE
// Function get last twiiter msg id for hash tag added...
///

function AddHashTag($xBoxID,$HashTagName,$BoxTo,$Action,$ReplyID,$xToken,$xTokenSecurity) {

	include("config.php");

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$xTwitterConn = new TwitterOAuth($xtConsummerKey, $xtConsummerSecret,$xToken,$xTokenSecurity);
	$results = $xTwitterConn->get("search/tweets", array('count' => '1','result_type' => 'mixed','q' => "$HashTagName"));
	#$json = file_get_contents("http://search.twitter.com/search.json?result_type=mixed&rpp=1&q=" . $HashTagName);
	#$results = json_decode($json)->results; 
	#$results = json_decode($json);
	#var_dump($results[0]);
	#boxlogger("RESULTS=>$results");

	$maxid=$results->search_metadata->{'max_id'};
	$xQuery = "INSERT INTO hashtags VALUES (\"$xBoxID\",\"\",\"$HashTagName\",\"$BoxTo\",\"$Action\",\"".$maxid."\",".$ReplyID.");";
	mysql_query($xQuery);

	boxlogger("Adding new Hash Tag $HashTagName (".$results->{'max_id'}."): $Action $BoxTo to 2boxID: $xBoxID");   

}

// AddAutoReply()
// Add auto reply text for hashtag filter
///

function AddAutoReply($xBoxID,$AutoReplyText) {

	include("config.php");

	#$mysqli = new mysqli('localhost', $xDBUser, $xDBPass,$xDBName);
	#$mysqli->query("INSERT INTO autoreply (2boxid,autoreplytext,count) VALUES (\"$xBoxID\",\"$AutoReplyText\",0);");
	#$id=$mysqli->insert_id;

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$xQuery = "INSERT INTO autoreply (2boxid,autoreplytext,count) VALUES (\"$xBoxID\",\"$AutoReplyText\",0);";
	mysql_query($xQuery);
	$id = mysql_insert_id();

	return($id);
	boxlogger("Adding new AutoReply($id): \"$AutoReplyText\": 2boxID: $xBoxID");   

}

// HashTagAutoReply()
// This is function used by robot for reply espec hashtag filter
// Reply ONLY!!
//

function HashTagAutoReply($xBoxID,$xAutoReplyID,$xTo) {

	if ($xAutoReplyID != 0) {

	        include("config.php");
	        require_once(''.$xlibpath.'/twitteroauth/twitteroauth.php');

	        $xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	        $xDBSelect = mysql_select_db($xDBName);

		$xQuery = "SELECT token,tokensecret FROM users WHERE 2boxid=\"$xBoxID\";";
		$xResult = mysql_query($xQuery);
		$xRow = mysql_fetch_array($xResult);

	        $xTwitterConn = new TwitterOAuth($xtConsummerKey, $xtConsummerSecret, $xRow['token'], $xRow['tokensecret']);

		$xQuery = "SELECT autoreplytext FROM autoreply WHERE 2boxid=\"$xBoxID\" AND autoreplyid=\"$xAutoReplyID\";";
		$xResult = mysql_query($xQuery);
		$xRow = mysql_fetch_array($xResult);

	        $xTwitterConn->post('statuses/update', array('status' => "".$xRow['autoreplytext']." @$xTo"));
		#boxlogger("TWITTER RETURN: $xTwitterConn");

		$xText=$xRow['autoreplytext'];

		$xQuery = "SELECT count FROM autoreply WHERE 2boxid=\"$xBoxID\" AND autoreplyid=\"$xAutoReplyID\";";
		$xResult = mysql_query($xQuery);
		$xRow = mysql_fetch_array($xResult);

		$xCount = $xRow['count'];
		$xCount++;

		$xQuery = "UPDATE autoreply SET count=$xCount WHERE autoreplyid=$xAutoReplyID";
		mysql_query($xQuery);		

	        boxlogger("AutoReply ($xAutoReplyID): $xTo $xText From 2boxid: $xBoxID - COUNT($xCount)");

		return("+");
	}
}


// RemoveHashTag()
// Milk function remove hashtag filters of user
// 

function RemoveHashTag($xBoxID,$HashTagID) {

	include("config.php");

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$xQuery = "DELETE FROM hashtags WHERE 2boxid = \"$xBoxID\" and hashtagid = \"$HashTagID\";";
	mysql_query($xQuery);

	boxlogger("Removing hashtag $HashTagID from 2boxID: $xBoxID ...");

}

?>
