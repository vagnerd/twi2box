<?php

$BOXID=17;

$xDBConn = mysql_connect('127.0.0.1', '', '');
$xDBSelect = mysql_select_db('2boxdb');

$xx="select DISTINCT id_str from msgmentions where 2boxid=".$BOXID."";
$xr=mysql_query($xx);

while($Ax = mysql_fetch_array($xr)) {

	$xa="select id_str,2boxmsgid from msgmentions where 2boxid=".$BOXID." and id_str='".$Ax["id_str"]."'";
	$ar=mysql_query($xa);
	$num_rows = mysql_num_rows($ar);
	if ($num_rows > 1) {
		$y=1;
		while($Ix = mysql_fetch_array($ar)) {
			if ($y < $num_rows) {
				echo "Removing ID ".$Ix['2boxmsgid']."\n";
				mysql_query("DELETE FROM msgmentions where 2boxid=".$BOXID." and 2boxmsgid='".$Ix['2boxmsgid']."'"); 
				$y++;
			}
		}
	}

}

?>
