<?php

/// Twi2Box.Me
// Folder Manager Functions

// GetBoxes()
// Get Folders to return in array

function GetFolders($xBoxID) {

	include("config.php");

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$xQuery = "SELECT boxes FROM users WHERE 2boxid = \"".$xBoxID."\";";
	$xQueryExec = mysql_query($xQuery, $xDBConn);
	$xQueryResult = mysql_fetch_array($xQueryExec);

	return $xQueryResult[0];

	mysql_close($xDBConn);

}

// AddFolder()
// Very simple routine for Add folder in box account

function AddFolder($xBoxID,$NewFolder) {

	include("config.php");

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$xBoxes=GetFolders($xBoxID);
	$xBoxes.=",".$NewFolder."";

	$xQuery = "UPDATE users SET boxes = \"".$xBoxes."\" WHERE 2boxid = \"".$xBoxID."\";";
	mysql_query($xQuery);
	mysql_close($xDBConn);

	boxlogger("Adding new folder $NewFolder to 2boxID: $xBoxID ...");   

}

// RemoveFolder()
// Very simple routine for Remove folder of box account

function RemoveFolder($xBoxID,$RemoveFolder) {

	include("config.php");

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$Boxes=explode(",", GetFolders($xBoxID));
	
	foreach ($Boxes as $Box) {

		if ($Box <> $RemoveFolder) {
  			$Folders.="$Box,";
		}

	}

	$Folders=substr($Folders,0,-1);
	$xQuery = "UPDATE users set boxes = \"$Folders\" WHERE 2boxid = \"$xBoxID\";";

	mysql_query($xQuery);
	mysql_close($xDBConn);

	boxlogger("Removing $RemoveFolder from 2boxID: $xBoxID ..."); 

}

?>
