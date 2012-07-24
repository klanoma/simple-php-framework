<?php

$page_title = $page['url'] != "home" ? "$page[browser_title] | Customer Name | City" : "Customer Name | City | Phone";

echo<<<HEADER

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
  <head>
    <title>$page_title</title>
    <meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1">
    <meta name="keywords" content="$page[meta_keywords]">
    <meta name="description" content="$page[meta_description]">

    <!-- Javascript -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>

    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="$BASEDIR/styles/reset.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="$BASEDIR/styles/style.css" media="screen" />

    <!--Favicon -->
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

?>