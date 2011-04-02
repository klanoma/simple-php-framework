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
  redirect("http://".trim(preg_replace("/\/\/+/", "/", preg_replace("/home/", "", full_url())), "/")."/", 301);
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

include("templates/header.php");

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

include("templates/footer.php"); 

?>
