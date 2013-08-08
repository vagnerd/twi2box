<?php

/// Twi2Box.Me
// Generic Functions

// boxlogger()
// Generic function for loggin application...
//

function boxlogger($xlog) { 
	
	include("config.php");

	$datenow = date("Y/m/d H:i");

	$flog = fopen("/var/www/twi2box.me/logs/twi2box.log", 'a');

	fwrite($flog, "$datenow - $xlog\n");
	fclose($flog);

}

// Get2boxid()
// Get user id from token id user
//

function Get2boxid($xTokenID) {

	include("config.php");

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$xQuery = "SELECT 2boxid FROM users WHERE token = \"".$xTokenID."\";";
	$xQueryExec = mysql_query($xQuery, $xDBConn);
	$xQueryResult = mysql_fetch_array($xQueryExec);
	return $xQueryResult[0];

	mysql_close($xDBConn);

}

// Get2boxid()
// Get user id from token id user
//

function GetTokens($xBoxID) {

	include("config.php");

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$xQuery = "SELECT token,tokensecret FROM users WHERE 2boxid = \"".$xBoxID."\";";
	$xQueryExec = mysql_query($xQuery, $xDBConn);
	$xQueryResult = mysql_fetch_array($xQueryExec);
	return $xQueryResult;

	mysql_close($xDBConn);

}


// GetEmailUser()
// GetEmail user from user id
//

function GetEmailUser($xBoxID) {

	include("config.php");

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$xQuery = "SELECT email FROM users WHERE 2boxid = \"".$xBoxID."\";";
	$xQueryExec = mysql_query($xQuery, $xDBConn);
	$xQueryResult = mysql_fetch_array($xQueryExec);

	return $xQueryResult[0];
	mysql_close($xDBConn);

}

// SendNotifyEmail()
// Newba function for send email user
//

function SentNotifyEmail($xMailTo,$xText) {
	$xSubject="[2box] Notificação de filtro";
	$xMessage=$xText;
	$xHeaders='From: 2box@2box.fistaile.com' . "\r\n" .
		'Reply-To: 2box@2box.fistaile.com' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();

	mail($xMailTo, $xSubject, $xMessage, $xHeaders);
}

// NotifyEmail()
// This is function check filter depends of e-mail
//

function NotifyEmail($xFilterID) {
	include("config.php");

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$xQuery = "SELECT notifyemail FROM filters WHERE filterid = \"".$xFilterID."\";";
	$xQueryExec = mysql_query($xQuery, $xDBConn);
	$xQueryResult = mysql_fetch_array($xQueryExec);

	return $xQueryResult[0];

	mysql_close($xDBConn);

}

// UpdateSettings()
// Update settings for users
//

function UpdateSettings($xBoxID,$xEmail) {

	include("config.php");

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$xQuery = "UPDATE users SET email = \"".$xEmail."\" WHERE 2boxid = \"".$xBoxID."\";";

	mysql_query($xQuery);
	boxlogger("Updating E-mail: $xEmail to 2boxID: $xBoxID ...");   

}

// MakeIndex()
// Make Index for pagination....
//

function MakeIndex($xBoxID,$xBox) {

	include("config.php");

	$TotalRegisters=TotalMessages($xBoxID,$xBox);
	$xTotal=ceil($TotalRegisters/19);

	return $xTotal;
}

// TotalMessages()
// Get Total messages from user id
//

function TotalMessages($xBoxID,$xBox) {

	include("config.php");

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$xQuery = "SELECT count(*) FROM msgmentions WHERE 2boxid = \"".$xBoxID."\" AND box = \"".$xBox."\";";
	$xQuery = mysql_query($xQuery, $xDBConn);
	$xQueryResult = mysql_fetch_array($xQuery);

	return $xQueryResult[0];
}

// CheckMessagesFolder()
// Function check messages in folder for dont remove email of settings user
//

function CheckMessagesFolder($xBoxID,$Folder) {

	include("config.php");

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$xQueryMessagesFolder = "SELECT 2boxmsgid FROM msgmentions WHERE 2boxid = \"$xBoxID\" and box = \"$Folder\" LIMIT 1;";
	$xQueryMessagesFolderExec = mysql_query($xQueryMessagesFolder, $xDBConn);
	$xQueryMessagesFolderResult = mysql_fetch_array($xQueryMessagesFolderExec);

	if ($xQueryMessagesFolderResult) {
		return 1;
	}

	else {
		return 0;
	}

	mysql_close($xDBConn);

} 

// CheckRegister()
// Mother Fucka Function for check user register in database

function CheckRegister($xUserTwitterID,$xUserScreenName,$xLastMessageID,$xToken,$xTokenSecret) {

	include("config.php");

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$xQueryTwitterID = "SELECT twitterid FROM users WHERE twitterid = '$xUserTwitterID'";
	$xQueryTwitterIDExec = mysql_query($xQueryTwitterID, $xDBConn);
	$xQueryTwitterIDResult = mysql_fetch_array($xQueryTwitterIDExec);

	if ($xQueryTwitterIDResult) {
		$xQueryUpdate = "UPDATE users SET screenname=\"".$xUserScreenName."\",token=\"".$xToken."\",tokensecret=\"".$xTokenSecret."\" WHERE twitterid=\"".$xUserTwitterID."\";";
		mysql_query($xQueryUpdate);
		boxlogger("Updating TwitterID: ".$xUserTwitterID."");
	}

	else {
		$xQueryInsert = "INSERT INTO users (2boxid,twitterid,screenname,startmsgid,token,tokensecret,boxes) VALUES ('',\"$xUserTwitterID\",\"$xUserScreenName\",\"$xLastMessageID\",\"$xToken\",\"$xTokenSecret\",\"inbox,sent,trash\");";
		mysql_query($xQueryInsert);
		boxlogger("Inserting TwitterID: ".$xUserTwitterID."");
	}

	mysql_close($xDBConn);

}

function AddTwitterProfile($x2BoxID,$xPFscreenname,$xPFid_str,$xPFimage_url,$xPFlocation,$xPFcreated,$xPFlang,$xPFfollowers,$xPFdesc,$xPFgeo,$xPFpreotected,$xPFverified,$xHashtag) {

	include("config.php");
	require_once(''.$xlibpath.'/twitteroauth/twitteroauth.php');
	
        // AddTwitterProfile($BoxID,$xProfile['id'],$xProfile['image_url'],$xProfile['location'],$xProfile['created'],$xProfile['lang'],$xProfile['followers'],$xProfile['desc'],$xProfile['geo'],$xProfile['preotected'],$xProfile['verified'],$xHashtag[3]);

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	boxlogger("[ROBOT-PROFILE] Checking user ".$xPFscreenname." in database profiles");

	$xQuery = "SELECT screen_name FROM twitterprofiles WHERE screen_name=\"".$xPFscreenname."\" and tags LIKE '%".$xHashtag."%';";
	$xQueryExec = mysql_query($xQuery, $xDBConn);
	$xQueryResult = mysql_fetch_array($xQueryExec);

	if (!isset($xQueryResult["tid"])) {
		$xQuery = "SELECT tags FROM twitterprofiles WHERE screen_name=\"".$xPFscreenname."\";";
		$xQueryExec = mysql_query($xQuery, $xDBConn);
		$xQueryResult = mysql_fetch_array($xQueryExec);
		if (isset($xQueryResult["tags"])) {
			$xHashtag = "".$xQueryResult["tags"].":$xHashtag";
			boxlogger("[ROBOT-PROFILE] Updating profile ".$xPFscreenname." TAGS: ".$xHashtag."");
			$xQuery="UPDATE twitterprofiles SET tags=\"".$xHashtag."\" WHERE screen_name=\"".$xPFscreenname."\"";
		} else {

			$xCreated = strtotime($xPFcreated);
			$xCreated = date('Y-m-d H:i:s', $xCreated);

			boxlogger("[ROBOT-PROFILE] Adding ".$xPFscreenname." in database profiles");
			$xQuery="INSERT INTO twitterprofiles (2boxid,tid,screen_name,image_url,location,created_at,lang,followers,description,geo_enabled,protected,verified,tags) VALUES
				(\"".$x2BoxID."\",\"".$xPFid_str."\",\"".$xPFscreenname."\",\"".$xPFimage_url."\",\"".$xPFlocation."\",\"".$xCreated."\",
				\"".$xPFlang."\",\"".$xPFfollowers."\",\"".$xPFdesc."\",\"".$xPFgeo."\",\"".$xPFpreotected."\",\"".$xPFverified."\",\"".$xHashtag."\");";

		}

		mysql_query($xQuery, $xDBConn);

	}
}


?>
