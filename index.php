<?php

require 'includes/master.inc.php';
$BASEDIR = Config::get('basedir');


/**
 * ========================================================================================
 * Routing Stuff
 * ========================================================================================
 */

//remove double slash and force final slash
if(preg_match("/\/\/+/", full_url()) || !preg_match("/\/$/", full_url())){
  header( "Location: http://".trim(preg_replace("/\/\/+/", "/", full_url()), "/")."/", true, 301);
  exit();
}

$url_parts = explode($BASEDIR, full_url());
$url_array = explode("/", trim($url_parts[1], "/"));
/**
 * @todo: support child pages
 */
$url_lookup = $url_array[0]=="" ? "home" : $url_array[0];


$db = Database::getDatabase(); 
$db->query("SELECT * FROM pages WHERE url='".addslashes($url_lookup)."' limit 1");
if(!$page = $db->getRows()){
  //page does not exist
  header("HTTP/1.0 404 Not Found");
  
  $db = Database::getDatabase(); 
  $db->query("SELECT * FROM pages WHERE url='404' limit 1");
  if(!$page = $db->getRows()){
    
    //No 404 page?
    die("Pages table must have a 404 row");
  }
}
$page = $page[0];

//we're all good
header("HTTP/1.0 200 OK");


/**
 * ========================================================================================
 * Header
 * ========================================================================================
 */
echo<<<HEADER

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
 
<html> 
  <head>
    <title>$page[browser_title] | Project Name</title> 
    <meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1"> 
    <meta name="keywords" content=""> 
    <meta name="description" content=""> 
    <script type="text/javascript" src="$BASEDIR/scripts/jquery-1.4.4.min.js"></script> 
    <style type="text/css" media="screen">@import "$BASEDIR/styles/reset.css";</style> 
    <style type="text/css" media="screen">@import "$BASEDIR/styles/style.css";</style> 
    <link rel="shortcut icon" href="$BASEDIR/favicon.ico">

HEADER;

if($BASEDIR -= "/"){
echo<<<GOOOGLE_ANALYTICS



GOOOGLE_ANALYTICS;
}

echo<<<END_HEADER
  </head> 
  <body>

    <div id="header">
      Header

    </div>

END_HEADER;


/**
 * ========================================================================================
 * Template
 * ========================================================================================
 */

echo<<<TEMPLATE_HEAD
    <div id="content" class="$page[template]">

TEMPLATE_HEAD;

include("templates/".$page['template'].".php");

echo<<<TEMPLATE_FOOT
    </div>

TEMPLATE_FOOT;

/**
 * ========================================================================================
 * Footer
 * ========================================================================================
 */
 
echo<<<FOOTER
    <div id="footer">
      Footer

    </div>

  </body>
</html>

FOOTER;


?>
