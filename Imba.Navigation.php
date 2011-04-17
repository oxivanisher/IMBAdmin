<?php

require_once 'Model/ImbaNavigation.php';

$topNav = new ImbaTopNavigation();

/**
 * Set up the top navigation
 */

#PROTOTYPE CODE START - please replace with superior solution. esc
/* 
$topNav->addElement("blog", "News", "_top", "http://alptroeim.ch/blog/", "Zu Unserem Blog");
$topNav->addElement("forum", "Forum", "_top", "http://alptroeim.ch/forum/", "Zu unserem Forum");
*/
switch ($_SERVER[SERVER_NAME]) {
    	#OOM
	case "www.oom.ch"||"oom.ch":
                $topNav->addElement("blog", "Blog", "_top", "https://oom.ch/blog/", "OOM Blog");
		$topNav->addElement("wiki", "Wiki", "_top", "https://oom.ch/wiki/", "OOM Wiki");
		break;
	#EVE 
	case "b.oom.ch":
		$topNav->addElement("forum", "Forum", "_top", "http://b.oom.ch/forum/", "the Dudez Forum");
		$topNav->addElement("killboard", "Killboard", "_top", "http://b.oom.ch/kb/", "the Dudez Killboard");
		break;
	#WOW 
	case "alptroeim.ch"||"www.alptroeim.ch":
		echo "SERVER alptroeim.ch";
		$topNav->addElement("blog", "News", "_top", "http://alptroeim.ch/blog/", "Zu Unserem Blog");
		$topNav->addElement("forum", "Forum", "_top", "http://alptroeim.ch/forum/", "Zu unserem Forum");
		break;	
	default:
		echo $_SERVER[SERVER_NAME];
}


?>
