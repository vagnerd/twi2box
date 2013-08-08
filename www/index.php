<?php

// Config
//
include("./lib/config.php");

include("".$xlibpath."/functions-generic.php");
include("".$xlibpath."/functions-folders.php");
include("".$xlibpath."/functions-messages.php");

// LIB Headers
//
//include("".$xlibpath."/logger.php");
require_once("".$xlibpath."/twitteroauth/twitteroauth.php");

// MAIN
//
session_start();

if (!isset($_SESSION['access_token'])) {
   header("Location: ./auth.php");
}

$xTwitterAccessToken = $_SESSION['access_token'];
$xTwitterConn = new TwitterOAuth($xtConsummerKey, $xtConsummerSecret, $xTwitterAccessToken['oauth_token'], $xTwitterAccessToken['oauth_token_secret']);

$BoxID=Get2boxid($xTwitterAccessToken['oauth_token']);
$Boxes=explode(",", GetFolders($BoxID));

if (!isset($_GET["box"]) || empty($_GET["box"])) { $GetBox="inbox"; }
else { $GetBox=$_GET["box"]; }

if (!isset($_GET["page"]) || empty($_GET["page"])) { $Page=1; }
else { $Page=$_GET["page"]; }

if  (!isset($_GET["order"]) || empty($_GET["order"])) { $OrderBox="created_at"; }
else { $OrderBox = $_GET["order"]; }

if  (!isset($_GET["flow"]) || empty($_GET["flow"])) { $OrderFlow="DESC"; }
elseif ($_GET["flow"] == "DESC") { $OrderFlow = "ASC"; }
elseif ($_GET["flow"] == "ASC") { $OrderFlow = "DESC"; }

?>

<!DOCTYPE HTML>
<html>

<head>
  <title>Twi2box.ME</title>
  <meta name="description" content="website description" />
  <meta name="keywords" content="website keywords, website keywords" />
  <meta http-equiv="content-type" content="text/html; charset=windows-1252" />
  <link rel="stylesheet" type="text/css" id="theme" href="css/style.css" />
  <!-- modernizr enables HTML5 elements and feature detects -->
  <script type="text/javascript" src="js/modernizr-1.5.min.js"></script>
</head>

<body>
  <div id="main">
    <header>
      <div id="logo">
        <div id="logo_text">
          <!-- class="logo_colour", allows you to change the colour of the text -->
          <h1><a href="index.php">Twi2box.<span class="logo_colour">me</span></a></h1>
          <h2>Simple & Fast Twitter Box!</h2>
        </div>
        <!-- form method="post" action="#" id="search">
          <input class="search" type="text" name="search_field" value="search....." onclick="javascript: document.forms['search'].search_field.value=''" />
          <input name="search" type="image" style="float: right;border: 0; margin: 20px 0 0 0;" src="images/search.png" alt="search" title="search" />
        </form!-->
      </div>
      <nav>
        <ul class="sf-menu" id="nav">
          <li class="current"><a href="index.php">Home</a></li>
          <li><a href="compose.php">Send</a></li>

          <li><a href="#">Folders</a>
          <ul>

          <?php
		foreach ($Boxes as $Box) {
			echo "<li><a href=\"./index.php?box=".$Box."\">".$Box."</a></li>";
		}
	  ?>

          </ul></li>

	<li><a href="#">Reports</a>
 	  <ul>
	  <li><a href="#">#HashTag's</a></li>
	  <li><a href="#">Mentions</a></li>
	</ul></li>

          <li><a href="#">Settings</a>
            <ul>
              <li><a href="settings.php">General Settings</a></li>
              <li><a href="filters.php">Filter Settings</a></li>
              <li><a href="hashtags.php">#Hashtag's Filter Settings</a></li>
              <li><a href="folders.php">Folder Settings</a></li>
            </ul>
          </li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
    </header>

    <div id="site_content">
      <div class="content">
      <?php echo "<h1>$GetBox</h1>"; ?>
   
      <table style="width:100%; border-spacing:0;">
      <tr><th></th>
	<th><a href="index.php?box=<?php if (isset($_GET['box'])) { echo $_GET["box"]; } ?>&order=created_at&flow=<?php echo $OrderFlow; ?>&page=<?php if (isset($_GET["page"])) { echo $_GET["page"]; } ?>">Date</a></th>
	<th><a href="index.php?box=<?php if (isset($_GET['box'])) { echo $_GET["box"]; } ?>&order=screen_name&flow=<?php echo $OrderFlow; ?>&page=<?php  if (isset($_GET["page"])) { echo $_GET["page"]; } ?>">From</a></th>
	<th>Message</th>
      </tr>

   <form method="post" action="mgr.php">

      <?php

	$MessagesOfBox = GetMessagesBox($BoxID,$GetBox,$OrderBox,$OrderFlow,$Page);
	foreach ($MessagesOfBox as $xMessage) {
		$xMessage=explode("--||--", $xMessage);
		$xDate=date("Y M d H:i", strtotime($xMessage[4]));
  		echo "<tr><td><INPUT TYPE=\"checkbox\" NAME=\"msgid[]\" VALUE=\"$xMessage[3]\"></td>\n";
		echo "<td style=\"width:17%;\">$xDate</td>\n";
		echo "<td>$xMessage[1]</td>\n";
		echo "<td>$xMessage[2]</td></tr>\n";
	}
	?>
	</table>
    <h6>

	<?php
		$x=1;
		$xIndex=MakeIndex($BoxID,$GetBox);
		while ($x <= $xIndex) {
			#echo "<a href=\"index.php?box=".$_GET["box"]."&order=".$_GET["order"]."&flow=".$_GET["flow"]."&page=$x\">   $x  </a>";

			echo "<a href=\"index.php?box=";
			if (isset($_GET["box"])) {
				echo $_GET["box"];
			}

			echo "&order=";
			if (isset($_GET["order"])) {
				echo $_GET["order"];
			}

			echo "&flow=";
			if (isset($_GET["flow"])) {
				echo $_GET["flow"];
			}

			echo "&page=$x\">   $x  </a>";
			$x++;
		}

	?>
   </h6>

   <input type="submit" value="Delete" name="acao">
   <input type="submit" value="Move" name="acao">
   <input type="submit" value="Copy" name="acao">

   <select name="boxto" id="boxto">
   <?php 
 
   foreach ($Boxes as $Box) {
      echo "<option value=\"$Box\">$Box</option>";
   }
   
   ?>
   </select>
   </form>

    <footer>
      <p>Copyright &copy; Twi2Box ME! | http://twi2box.me </a></p>
      <p>Twi2Box.ME Beta Version</p>
    </footer>

  </div>
  <!-- javascript at the bottom for fast page loading -->
  <script type="text/javascript" src="js/jquery.js"></script>
  <script type="text/javascript" src="js/jquery.easing-sooper.js"></script>
  <script type="text/javascript" src="js/jquery.sooperfish.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('ul.sf-menu').sooperfish();
    });
  </script>
</body>
</html>
