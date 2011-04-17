<?php

require_once 'Model/ImbaNavigation.php';

$topNav = new ImbaTopNavigation();

/**
 * Set up the top navigation
 */

//PROTOTYPE CODE START - please replace with superior solution. all flames to esc for his moobness...
/* original
$topNav->addElement("blog", "News", "_top", "http://alptroeim.ch/blog/", "Zu Unserem Blog");
$topNav->addElement("forum", "Forum", "_top", "http://alptroeim.ch/forum/", "Zu unserem Forum");
*/
switch ($_SERVER[HTTP_HOST]) {
        case "www.oom.ch": //OOM
	case "oom.ch":
                $topNav->addElement("blog", "Blog", "_top", "https://oom.ch/blog/", "OOM Blog");
		$topNav->addElement("wiki", "Wiki", "_top", "https://oom.ch/wiki/", "OOM Wiki");
		break;
	case "b.oom.ch": //EVE
		$topNav->addElement("forum", "Forum", "_top", "http://b.oom.ch/forum/", "the Dudez Forum");
		$topNav->addElement("killboard", "Killboard", "_top", "http://b.oom.ch/kb/", "the Dudez Killboard");
		break;
	case "www.alptroeim.ch": //WOW
        case "alptroeim.ch":
        default:
		$topNav->addElement("blog", "News", "_top", "http://alptroeim.ch/blog/", "Zu Unserem Blog");
		$topNav->addElement("forum", "Forum", "_top", "http://alptroeim.ch/forum/", "Zu unserem Forum");
		break;	
}
//PROTOTYPE CODE END

?>
