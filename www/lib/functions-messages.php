<?php

// Twi2Box.Me
// Messages Functions

// GetMessagesBox()
// Insane function for get mentions and hashtags msgs from userid
// INSANE!!!!
//

function GetMessagesBox($xBoxID,$xBox,$OrderBox,$OrderFlow,$Page) {

	include("config.php");

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);
	$Page=$Page-1; 
	$xStart=$Page*19;

	$xQueryGetMessagesBox = "SELECT id_str,screen_name,text,2boxmsgid,created_at FROM msgmentions WHERE 2boxid = \"".$xBoxID."\" AND box = \"".$xBox."\" ORDER BY $OrderBox $OrderFlow LIMIT $xStart,19;";
	$xQueryGetMessagesBoxExec = mysql_query($xQueryGetMessagesBox, $xDBConn);
	$xCount = 0;

	while (list($id_str, $screen_name, $text, $boxmsgid, $created_at) = mysql_fetch_array($xQueryGetMessagesBoxExec)) {

		$GeneralMessages[$xCount] = "$id_str--||--$screen_name--||--$text--||--$boxmsgid--||--$created_at";
		$xCount++;

	}

	return $GeneralMessages;
	mysql_close($xDBConn);

}

// GetMessage()
// Get Message from id
//

function GetMessage($MessageID) {

	include("config.php");

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$xQuery = "SELECT id_str,screen_name,text FROM msgmentions WHERE id_str = \"".$MessageID."\";";
	$xQueryExec = mysql_query($xQuery, $xDBConn);
	$xQueryResult = mysql_fetch_array($xQueryExec);

	return $xQueryResult;

}

// MoveMessageTo()
// Move message for other box
//

function MoveMessageTo($MessageID,$ToBox) {

	include("config.php");

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$xQuery = "UPDATE msgmentions SET box = \"".$ToBox."\" WHERE 2boxmsgid = \"".$MessageID."\";";
	mysql_query($xQuery);

	boxlogger("Moving $MessageID to $ToBox folder...");

	mysql_close($xDBConn);

}

// CopyMessage()
// Copy (Duplicate) message for other box
//

function CopyMessageTo($MessageID,$ToBox) {

	include("config.php");

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$xQuery = "SELECT 2boxid,id_str,created_at,screen_name,text,2boxmsgid FROM msgmentions WHERE 2boxmsgid = \"$MessageID\";";
	$xQueryExec = mysql_query($xQuery, $xDBConn);
	$xValue = mysql_fetch_array($xQueryExec);

	$xQueryTo = "INSERT INTO msgmentions (2boxid,id_str,created_at,screen_name,text,box) VALUES (\"$xValue[0]\",\"$xValue[1]\",\"$xValue[2]\",\"$xValue[3]\",\"$xValue[4]\",\"$ToBox\");";

	mysql_query($xQueryTo);
	boxlogger("Copying $MessageID to $ToBox folder");

	mysql_close($xDBConn);

}


// ComposeSentMessage()
// This functions is work a round for mother fucker twitter app!!!
// The function send message for twitter and "copy" to sent messages
//

function ComposeSentMessage($Message,$TwitterAccessToken,$TwitterAccessTokenSecret) {
	include("config.php");
	require_once(''.$xlibpath.'/twitteroauth/twitteroauth.php');

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$xTwitterConn = new TwitterOAuth($xtConsummerKey, $xtConsummerSecret, $TwitterAccessToken, $TwitterAccessTokenSecret);
	$xTwitterConn->post('statuses/update', array('status' => "$Message"));
	boxlogger("Posting message $Message");

	sleep(2);

	$BoxID=Get2boxid($TwitterAccessToken);
	$LastPost = $xTwitterConn->get('statuses/user_timeline',array('count' => 1));

	foreach ($LastPost as $x) {
		
		$xIDSTR = $x->id_str;
		$xScreenName = $x->user->screen_name;
		$xText = $x->text;
		$xtDate = strtotime($x->created_at);
		$xDate = date('Y-m-d H:i:s', $xtDate);

		$xQueryNewRecord="INSERT INTO msgmentions VALUES (\"$BoxID\",\"$xIDSTR\",\"$xDate\",\"$xScreenName\",\"$xText\",\"sent\",\"\");";
		mysql_query($xQueryNewRecord);
		boxlogger("Adding message ID $xIDSTR for BoxID $BoxID in Sent Folder");

	}

	mysql_close($xDBConn);

}


?>
