<?php

//find the top-most parent
$superparent = get_superparent($db, $page);

echo<<<TEMPLATE

<div id="left_column">
  <h1>$superparent[nav_title]</h1>
  <div id="sub-nav">

TEMPLATE;

$db->query("SELECT * FROM pages WHERE parent_id=".$db->quote($superparent['id'])." and visible='1' order by sort");
foreach($db->getRows() as $nav_item){
  $active = $nav_item['id'] == $page['id'] ? " class='active'" : "";
  echo "    <a$active href=\"$BASEDIR/$superparent[url]/$nav_item[url]/\">$nav_item[nav_title]</a>\n";
}

echo<<<TEMPLATE
  </div>

</div>
<div id="main-content">
  $page[content]
</div>
<div class="clear"></div>


TEMPLATE;

?>