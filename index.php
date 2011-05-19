<?php

require 'includes/master.inc.php';
$BASEDIR = WEB_ROOT;


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

$url_lookup = $url_array[0]=="" ? "home" : $url_array[0];
unset($url_array[0]);
$url_bits_remaining = $url_array;

//is there a page?
$db = Database::getDatabase(); 
$db->query("SELECT * FROM pages WHERE url='".addslashes($url_lookup)."' limit 1");
if(!$page = $db->getRows()){
  //this page does not exist
  $page = throw_404();
} else {
  $page = $page[0];
}

//does this page support children?
if(count($url_bits_remaining) > 0){
  
  //does this page have a child page?
  $db->query("SELECT * FROM pages WHERE url=".$db->quote($url_bits_remaining[1])." and parent_id=".$db->quote($page['id'])." limit 1");
  if($sub_page = $db->getRows()){
    $page = $sub_page[0];
  } else {
  
    //does it have a non-page child
    $related_object = get_template($db, $page, $url_bits_remaining);
    if(count($url_bits_remaining) > 0 && $related_object === false){
      //page does not exist
      $page = throw_404();
    }
  }
}

//redirect stuff
if(strlen($page['redirect'])>1){
  $redirect = 'Location: ' . $page[redirect];
  header($redirect); exit();
}

//BASEDIR Stuff
foreach($page as $k=>$v){
  $page[$k] = preg_replace("/__BASEDIR__/", $BASEDIR, $v);
}

if(isset($related_object['template'])){
  $page['template'] = $related_object['template'];
}

//we're all good
header("HTTP/1.0 200 OK");

ob_start();

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

ob_flush();
?>
