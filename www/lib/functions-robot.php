<?php

/// Twi2Box.Me
// Robot Mother Fucka! Functions

// GetLastMentions()
// Insane Function for getting last mentions of profiles
// Last mention detected via id from users table (startmsgid)
// Flow: Get Last Mention -> Add in DB (Mention/ID) -> Update ID startmsgid
// GetLastMention require FilterMsg for score mention of filters!!!
//

function GetLastMentions() {

	include("config.php");
	require_once(''.$xlibpath.'/twitteroauth/twitteroauth.php');
	boxlogger("[ROBOT] Getting Last Mentions...");

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$xQueryGetUsers = "SELECT 2boxid,token,tokensecret,startmsgid FROM users ORDER BY 2boxid ;";
	$xQueryGetUsersResult = mysql_query($xQueryGetUsers);

	while($xUser = mysql_fetch_array($xQueryGetUsersResult)) {

		$xTwitterConn = new TwitterOAuth($xtConsummerKey, $xtConsummerSecret, $xUser[1], $xUser[2]);
		$xLastInfos = $xTwitterConn->get("statuses/mentions_timeline", array('count' => '300', 'since_id' => ''.$xUser[3].''));
		$BoxID = $xUser[0];
		boxlogger("[ROBOT] Checking ID $BoxID: LastmsgID: $xUser[3]");

		foreach ($xLastInfos as $x) {

			// Twiit Data
			$xIDSTR = $x->id_str;
			$xScreenName = $x->user->screen_name;
			$xText = $x->text;
			$xtDate = strtotime($x->created_at);
			$xDate = date('Y-m-d H:i:s', $xtDate);

                        // Profile Data
                        $xProfile['id'] = $x->user->{'id_str'};
                        $xProfile['image_url'] = $x->user->{'profile_image_url'};
                        $xProfile['location'] = $x->user->{'location'};
                        $xProfile['created'] = $x->user->{'created_at'};
                        $xProfile['lang'] = $x->user->{'lang'};
                        $xProfile['followers'] = $x->user->{'followers_count'};
                        $xProfile['desc'] = $x->user->{'description'};
                        $xProfile['geo'] = $x->user->{'geo_enabled'};
                        $xProfile['preotected'] = $x->user->{'protected'};
                        $xProfile['verified'] = $x->user->{'verified'};

                        AddTwitterProfile($BoxID,$xScreenName,$xProfile['id'],$xProfile['image_url'],$xProfile['location'],$xProfile['created'],$xProfile['lang'],$xProfile['followers'],$xProfile['desc'],$xProfile['geo'],$xProfile['preotected'],$xProfile['verified'],"mention");

			if ($xIDSTR <> "") {

				$xFiltered=FilterMsg($BoxID,$xText);
				$xQueryUpdateStartMSGID="UPDATE users SET startmsgid=\"$xIDSTR\" WHERE 2boxid = \"$BoxID\";";
            
				mysql_query($xQueryUpdateStartMSGID);
				boxlogger("[ROBOT] Updating last message ID $xIDSTR to BoxID $BoxID");

				if ("".$xFiltered['action']."" == "move") {

					$xQueryNewRecord="INSERT INTO msgmentions VALUES (\"$BoxID\",\"$xIDSTR\",\"$xDate\",\"$xScreenName\",\"$xText\",\"".$xFiltered['boxto']."\",\"\");";
					boxlogger("[ROBOT] Add new MsgID $xIDSTR to $BoxID to folder: ".$xFiltered['boxto']." - FilterID: ".$xFiltered['filterid']."...");

					if ($xFiltered['notifyemail'] == 1) {

						SentNotifyEmail(GetEmailUser($BoxID),"$xDate - $xScreenName - $xText - ".$xFiltered['boxto'].""); 
						boxlogger("[ROBOT] Sending notify email to ".GetEmailUser($BoxID)." MsgID: $xIDSTR From BoxID $BoxID");

					}
				}

				elseif ("".$xFiltered['action']."" == "copy") {

					$xQueryNewRecord="INSERT INTO msgmentions VALUES (\"$BoxID\",\"$xIDSTR\",\"$xDate\",\"$xScreenName\",\"$xText\",\"inbox\",\"\");";
					boxlogger("[ROBOT] Add new MsgID $xIDSTR to $BoxID to folder: inbox - FilterID: ".$xFiltered['filterid']." ...");

					$xQueryNewRecordCopy="INSERT INTO msgmentions VALUES (\"$BoxID\",\"$xIDSTR\",\"$xDate\",\"$xScreenName\",\"$xText\",\"".$xFiltered['boxto']."\",\"\");";
					boxlogger("[ROBOT] Copying new MsgID $xIDSTR to $BoxID to folder: ".$xFiltered['boxto']." - FilterID: ".$xFiltered['filterid']." ...");

					mysql_query($xQueryNewRecordCopy);

					if ($xFiltered['notifyemail'] == 1) {

						SentNotifyEmail(GetEmailUser($BoxID),"$xDate - $xScreenName - $xText - ".$xFiltered['boxto']."");
						boxlogger("[ROBOT] Sending notify email to ".GetEmailUser($BoxID)." MsgID: $xIDSTR From BoxID $BoxID");
				
					}
				}

				mysql_query($xQueryNewRecord);

         }
      }

   }

}


// GetLastHasgTags()
// Mother Fucka Function for get last hashtags in twitter web service
// This is function search hash tags in data base and consumer in twitter web service
// in associate twitter id's... the funcion similir at GetLastMentions...
//

function GetLastHashTags() {

	include("config.php");
	require_once(''.$xlibpath.'/twitteroauth/twitteroauth.php');
	boxlogger("[ROBOT] Getting Last HashTags...");

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$xQueryGetHashTags = "SELECT 2boxid,hashtagid,hashtagname,boxto,action,lasthashtagid,autoreplyid FROM hashtags ORDER BY 2boxid ;";
	$xQueryGetHashTagsResult = mysql_query($xQueryGetHashTags);

	while($xHashTag = mysql_fetch_array($xQueryGetHashTagsResult)) {

		$BoxID = $xHashTag[0];
		boxlogger("[ROBOT] Checking ID $BoxID: LasthashtagID: $xHashTag[5] HashTag: $xHashTag[2]");

		$xTokens=GetTokens($BoxID);

                $xTwitterConn = new TwitterOAuth($xtConsummerKey, $xtConsummerSecret, $xTokens[0], $xTokens[1]);
                $results = $xTwitterConn->get("search/tweets", array('count' => '100', 'since_id' => ''.$xHashTag[5].'','type' => 'mixed','q' => ''.$xHashTag[2].''));

		#$json = file_get_contents("http://search.twitter.com/search.json?result_type=mixed&rpp=100&since_id=".$xHashTag[5]."&q=" . $xHashTag[2]);
		#$results = json_decode($json)->results; 

		foreach ($results->statuses as $result) {
			$xIDSTR = $result->id_str;
			$CheckID=CheckHashTagInDB("$BoxID","$xIDSTR");

			if ($CheckID == "1") {

				// Profile Data
				$xProfile['id'] = $result->user->{'id_str'};
				$xProfile['image_url'] = $result->user->{'profile_image_url'};
				$xProfile['location'] = $result->user->{'location'};
				$xProfile['created'] = $result->user->{'created_at'};
				$xProfile['lang'] = $result->user->{'lang'};
				$xProfile['followers'] = $result->user->{'followers_count'};
				$xProfile['desc'] = $result->user->{'description'};
				$xProfile['geo'] = $result->user->{'geo_enabled'};
				$xProfile['preotected'] = $result->user->{'protected'};
				$xProfile['verified'] = $result->user->{'verified'};

				// Twiit DATA
				$xScreenName = $result->user->{'screen_name'};
				$xText = $result->text;
				$xtDate = strtotime($result->created_at);
				$xDate = date('Y-m-d H:i:s', $xtDate);
				$xQueryUpdateStartHashTagID="UPDATE hashtags SET lasthashtagid=\"$xIDSTR\" WHERE hashtagid = \"$xHashTag[1]\";";

				// AutoReply
				$MarkReply=HashTagAutoReply($BoxID,$xHashTag[6],$xScreenName);

				// User Profile Database
				// $xtAdd=AddTwitterProfile($BoxID,$xScreenName,$xHashTag[3]);
				AddTwitterProfile($BoxID,$xScreenName,$xProfile['id'],$xProfile['image_url'],$xProfile['location'],$xProfile['created'],$xProfile['lang'],$xProfile['followers'],$xProfile['desc'],$xProfile['geo'],$xProfile['preotected'],$xProfile['verified'],$xHashTag[3]);

				if (!isset($MarkReply)) { $MarkReply=""; }

				if ("".$xHashTag[4]."" == "move") {

					$xQueryNewRecord="INSERT INTO msgmentions VALUES (\"$BoxID\",\"$xIDSTR\",\"$xDate\",\"$xScreenName\",\"$MarkReply $xText\",\"".$xHashTag[3]."\",\"\");";
					boxlogger("[ROBOT] Add new HashTagID $xIDSTR to $BoxID to folder: ".$xHashTag[3]." ...");
					boxlogger("[ROBOT] Updating last hashtag ID $xIDSTR to BoxID $BoxID"); 

					mysql_query($xQueryUpdateStartHashTagID);
				}

				elseif ("".$xHashTag[4]."" == "copy") {
			
					boxlogger("[ROBOT] Add new HashTagID $xIDSTR to $BoxID to folder: inbox ...");
					$xQueryNewRecord="INSERT INTO msgmentions VALUES (\"$BoxID\",\"$xIDSTR\",\"$xDate\",\"$xScreenName\",\"$MarkReply $xText\",\"inbox\",\"\");";

					boxlogger("[ROBOT] Copying new HashTagID $xIDSTR to $BoxID to folder: ".$xHashTag[3]." ...");
					$xQueryNewRecordCopy="INSERT INTO msgmentions VALUES (\"$BoxID\",\"$xIDSTR\",\"$xDate\",\"$xScreenName\",\"$MarkReply $xText\",\"".$xHashTag[3]."\",\"\");";

					mysql_query($xQueryNewRecordCopy);

					boxlogger("[ROBOT] Updating last hashtag ID $xIDSTR to BoxID $BoxID");
					mysql_query($xQueryUpdateStartHashTagID);
				}

				mysql_query($xQueryNewRecord);
			}
		}
	}
}

// CheckHashTagInDB()
// WHATA FUCK? Function
//

function CheckHashTagInDB($xBoxID,$xHashTagID) {

	include("config.php");

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$xQuery="SELECT id_str FROM msgmentions WHERE id_str='$xHashTagID' AND 2boxid='$xBoxID';";
	$xQueryExec = mysql_query($xQuery);
	$xQueryReturn = mysql_fetch_array($xQueryExec);

	if ($xQueryReturn) {
		return "0";
	}

	else {
		return "1";
	}
}

// FilterMSG()
// Insane function for scoring mention of filters from user id
// The funtion used in GetLastMentions for associate box user
//

function FilterMsg($BoxID,$Text) {

	include("config.php");
	require_once(''.$xlibpath.'/twitteroauth/twitteroauth.php');

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$xQueryGetFilters = "SELECT 2boxid,filterid,filtername,filter,boxto,action FROM filters WHERE 2boxid = \"".$BoxID."\";";
	$xQueryGetFiltersExec = mysql_query($xQueryGetFilters, $xDBConn);

	while (list($boxid, $filterid, $filtername, $filter, $boxto, $action) = mysql_fetch_array($xQueryGetFiltersExec)) {
		
		$xWordsFilter=explode(" ", $filter);
		$xHits=0;

		foreach ($xWordsFilter as $FindWord) {

			if (stripos($Text, $FindWord) !== false) {

				$xHits++;
			}
		}

		$xConc[$filterid]="$xHits $filterid";

	}

	boxlogger("[ROBOT] Filter text $Text FROM ID -> $BoxID");
	if (isset($xConc)) { 
		$xBestFilter=explode(" ", max($xConc));
	} else {
		$xBestFilter[0]=0;
	}

	if ($xBestFilter[0] <> 0) {

		$xQueryGetBestFilter = "SELECT boxto,action,notifyemail,filterid FROM filters WHERE filterid = \"$xBestFilter[1]\";";
		$xQueryGetBestFilterExec = mysql_query($xQueryGetBestFilter, $xDBConn);
		$xQueryGetBestFilterResult = mysql_fetch_array($xQueryGetBestFilterExec);
	}

	else {

		$xQueryGetBestFilterResult[0]="inbox";
		$xQueryGetBestFilterResult['boxto']="inbox";
		$xQueryGetBestFilterResult[1]="move";
		$xQueryGetBestFilterResult['action']="move";
		$xQueryGetBestFilterResult[2]="0";
		$xQueryGetBestFilterResult['notifyemail']="0";
		$xQueryGetBestFilterResult[3]="0";
		$xQueryGetBestFilterResult['filterid']="0";
	}

	return $xQueryGetBestFilterResult;
	mysql_close($xDBConn);
}






?>
