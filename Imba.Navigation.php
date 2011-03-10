<?php

require_once 'Model/ImbaNavigation.php';

$topNav = new ImbaTopNavigation();

/**
 * Set up the top navigation
 */
$topNav->addElement("blog", "Blog / News", "_top", "http://alptroeim.ch/blog/");
$topNav->addElement("forum", "Forum", "_top", "http://alptroeim.ch/forum/");
?>
