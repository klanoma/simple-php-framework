<?php

require 'includes/master.inc.php';
$BASEDIR = Config::get('basedir');


/**
 * ========================================================================================
 * Routing Stuff
 * ========================================================================================
 */

//remove double slash and force final slash and remove home in url
if(preg_match("/\/\/+/", full_url()) || !preg_match("/\/$/", full_url()) || preg_match("/home/", full_url())){
  header( "Location: http://".trim(preg_replace("/\/\/+/", "/", preg_replace("/home/", "", full_url())), "/")."/", true, 301);
  exit();
}

$delimeter = $BASEDIR;
if($delimeter == ""){
  $delimeter = ".com";
}

$url_parts = explode($delimeter, full_url());
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


//redirect stuff
if(strlen($page['redirect'])>1){
  $redirect = "Location: $page[redirect]";
  header($redirect); exit();
}

//BASEDIR Stuff
foreach($page as $k=>$v){
  $page[$k] = preg_replace("/__BASEDIR__/", $BASEDIR, $v);
}

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
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script> 
    <style type="text/css" media="screen">@import "$BASEDIR/styles/reset.css";</style> 
    <style type="text/css" media="screen">@import "$BASEDIR/styles/style.css";</style> 
    <link rel="shortcut icon" href="$BASEDIR/favicon.ico">

HEADER;

//only include Google Analytics code on production
if($Config->whereAmI() == 'production'){
  echo<<<GA
  
 
GA;
}

echo<<<BODY
  </head> 
  <body class="$page[template]">
    <div id="container">
      <div id="header">
        <div id="logo"><a href="$BASEDIR/"><img src="$BASEDIR/images/template/main-logo.png" alt="Customer Name"></a></div>
          <ul id="nav">

BODY;

$db = Database::getDatabase(); 
$db->query("SELECT * FROM pages WHERE parent_id is null and visible='1' order by sort");
foreach($db->getRows() as $nav_item){
  $active = $nav_item['id'] == $page['id'] ? " class='active'" : "";
  echo "          <li$active><a href=\"$BASEDIR/$nav_item[url]/\">$nav_item[nav_title]</a></li>\n";
}

echo<<<HEADER
        </ul>
      </div><!-- #header -->

HEADER;


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
 $year = date("Y");
 
echo<<<FOOTER
      <div id="footer">
        Copyright &copy; $year Customer Name. All rights reserved.

      </div><!-- #footer -->
    </div><!-- #container -->
  </body>
</html>

FOOTER;

?>
