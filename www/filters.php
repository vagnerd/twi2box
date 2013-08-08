<?php

// Config
//
include("./lib/config.php");

include("".$xlibpath."/functions-generic.php");
include("".$xlibpath."/functions-folders.php");
include("".$xlibpath."/functions-filters.php");

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

$BoxID=Get2boxid($xTwitterAccessToken['oauth_token']);
$Filters=GetFilters($BoxID);
$Boxes=explode(",", GetFolders($BoxID));

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
      <h1>Filters</h1>
   
      <table style="width:100%; border-spacing:0;">
      <tr><th></th>
	<th>Name</th>
	<th>Filter</th>
	<th>Action</th>
	<th>Destination</th>
	<th>E-mail</th>
      </tr>

   <form method="post" action="mgr-filters.php">

	<?php

	foreach ($Filters as $Filter) {
		$Filter=explode("--||--", $Filter);
		if ($Filter[6] == "1") {
			$NotifyEmail="Yes"; }
		else {
      			$NotifyEmail="No"; }

		echo "<tr><td><INPUT TYPE=\"checkbox\" NAME=\"filter[]\" VALUE=\"$Filter[1]\"></td>\n";
		echo "<td>$Filter[2]</td>\n";
		echo "<td>$Filter[3]</td>\n";
		echo "<td>$Filter[5]</td>\n";
		echo "<td>$Filter[4]</td>\n";
		echo "<td>$NotifyEmail</td></tr>\n";

	}

	?>
	</table>

   <input type="submit" value="Delete" name="acao">
   <input type="submit" value="New Filter" name="acao">

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
