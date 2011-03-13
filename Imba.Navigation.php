<?php

require_once 'Model/ImbaNavigation.php';

$topNav = new ImbaTopNavigation();

/**
 * Set up the top navigation
 */
$topNav->addElement("blog", "News", "_top", "http://alptroeim.ch/blog/", "Zu Unserem Blog");
$topNav->addElement("forum", "Forum", "_top", "http://alptroeim.ch/forum/", "Zu unserem Forum");
?>
