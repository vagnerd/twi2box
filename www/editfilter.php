<?php

// Config
//
include("./lib/config.php");
include("".$xlibpath."/functions.php");

// LIB Headers
//
//include("".$xlibpath."/logger.php");
require_once("".$xlibpath."/twitteroauth/twitteroauth.php");

// MAIN
//
session_start();
if (!isset($_SESSION['access_token']))
   header("Location: ./clearsessions.php");

$xTwitterAccessToken = $_SESSION['access_token'];

$FilderID=$_POST['filterid'];
$BoxID=Get2boxid($xTwitterAccessToken['oauth_token']);
$Boxes=explode(",", GetBoxes($BoxID));

echo "<form method='post' action='mgr-filters.php'>\n";
echo "Nome: <input type=\"text\" name=\"filtername\"><br>\n";
echo "Filter: <input type=\"text\" name=\"filter\"><br>\n";
echo "Acao: <select name=\"action\" id=\"action\">";
echo "<option value=\"move\">mover</option>";
echo "<option value=\"copy\">copiar</option>";
echo "</select>";
echo "To: <select name=\"moveto\" id=\"moveto\">";
foreach ($Boxes as $Box) {
   echo "<option value=\"$Box\">$Box</option>";
}

echo "</select><br>\n";
echo "<br>";
echo "<input type=\"submit\" value=\"Adicionar\" name=\"acao\"><br><hr>\n";

echo "<a href=\"./filters.php\">Voltar</a>";






?>
