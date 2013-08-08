<?php

/// Twi2Box.Me
// Filters Manager Functions

// GetFilters()
// Get Filters to return in array
// This is function explode informations with --||-- for split layout HTML

function GetFilters($xBoxID) {

	include("config.php");

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$xQuery = "SELECT 2boxid,filterid,filtername,filter,boxto,action,notifyemail FROM filters WHERE 2boxid = \"".$xBoxID."\";";
	$xQueryExec = mysql_query($xQuery, $xDBConn);

	$xCount = 0;

	while (list($boxid, $filterid, $filtername, $filter, $boxto, $action, $notifyemail) = mysql_fetch_array($xQueryExec)) {
		$GeneralFilters[$xCount] = "$boxid--||--$filterid--||--$filtername--||--$filter--||--$boxto--||--$action--||--$notifyemail";
		$xCount++;
	}

	return $GeneralFilters;
	mysql_close($xDBConn);

}

// AddFilter()
// Very simple routine for Add  filter in box account

function AddFilter($xBoxID,$FilterName,$Filter,$BoxTo,$Action,$NotifyEmail) {
 
	include("config.php");

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);

	$xQuery = "INSERT INTO filters VALUES (\"$xBoxID\",\"\",\"$FilterName\",\"$Filter\",\"$BoxTo\",\"$Action\",\"$NotifyEmail\");";
	mysql_query($xQuery);

	boxlogger("Adding new filter $FilterName: \"$Filter\" $Action $BoxTo to 2boxID: $xBoxID - NotifyEmail: $NotifyEmail");

	mysql_close($xDBConn);

}

// RemoveFilter()
// Very simple routine for Remove filter of box account

function RemoveFilter($xBoxID,$FilterID) {

	include("config.php");

	$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
	$xDBSelect = mysql_select_db($xDBName);
	
	$xQueryRemoveFilter = "DELETE FROM filters WHERE 2boxid = \"$xBoxID\" and filterid = \"$FilterID\";";
	mysql_query($xQueryRemoveFilter);
	
	boxlogger("Removing filter $FilterID from 2boxID: $xBoxID ...");
	
	mysql_close($xDBConn);
}

?>
