<?php

include("config.php");


$xDBConn = mysql_connect('localhost', $xDBUser, $xDBPass);
$xDBSelect = mysql_select_db($xDBName);

?>
