<?php

// Extern Session start
session_start();

require_once 'Model/ImbaUser.php';
require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerDatabase.php';
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaUserContext.php';

require_once 'Model/ImbaNavigation.php';
require_once 'Controller/ImbaSharedFunctions.php';

/**
 * FIXME: please add security to this file!
 * FIXME: load top navigation from database
 */
$topNav = new ImbaTopNavigation();
$topNav->addElement("blog", "Blog / News", "_top", "http://alptroeim.ch/blog/");
$topNav->addElement("forum", "Forum", "_top", "http://alptroeim.ch/forum/");

echo "\nhtmlContent = \" \\\n";
echo "<div id='imbaMenu'><ul class='topnav'>";

foreach ($topNav->getElements() as $nav) {
    echo "<li><a href='" . $topNav->getElementUrl($nav) . "'>" . $topNav->getElementName($nav) . "</a></li>";
}

echo "<li>";
echo "<a id='imbaMenuImbAdmin' href='#'>Auf zum Atem</a>";
echo "<ul class='subnav'>";

if (ImbaUserContext::getLoggedIn()) {
    $contentNav = new ImbaContentNavigation();

    if ($handle = opendir('Ajax/Content/')) {
        $identifiers = array();
        while (false !== ($file = readdir($handle))) {
            if (strrpos($file, ".Navigation.php") > 0) {
                include 'Ajax/Content/' . $file;
                $modIdentifier = str_replace(".Navigation.php", "", $file);
                echo "<li><a href='#' onclick='javascript: loadImbaAdminModule('".$modIdentifier."')'>" . $Navigation->getName($nav) . "</a></li>";
                array_push($identifiers, $modIdentifier);
                $Navigation = null;
            }
        }
        closedir($handle);
    }
} else {
    echo "<li><a href=''>Registrieren</a></li>";
}

echo "</ul>";
echo "</li>";
echo "</li></ul></div>";
echo "\";\ndocument.write(htmlContent);\n\n";

    echo '
      function loadUserProfile(openid){
      var data = {
      action: "module",
      module: "User",
      tabId: "viewprofile",
      openid: openid
      };
      loadImbaAdminTabContent(data);
      }
    ';

?>    