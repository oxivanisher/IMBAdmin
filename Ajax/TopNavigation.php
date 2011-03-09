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
 * Load the database
 */
$managerDatabase = ImbaManagerDatabase::getInstance(ImbaConfig::$DATABASE_HOST, ImbaConfig::$DATABASE_DB, ImbaConfig::$DATABASE_USER, ImbaConfig::$DATABASE_PASS);
$managerUser = new ImbaManagerUser($managerDatabase);

/**
 * FIXME: please add security to this file!
 */
/**
 * are we logged in?
 */
$topNav = new ImbaTopNavigation();
$topNav->addElement("blog", "Blog / News", "_top", "http://alptroeim.ch/blog/");
$topNav->addElement("forum", "Forum", "_top", "http://alptroeim.ch/forum/");


echo "<div id='imbaMenu'><ul class='topnav'>";

foreach ($topNav->getElements() as $nav) {
    echo "<li><a href='" . $topNav->getElementUrl($nav) . "'>" . $topNav->getElementName($nav) . "</a></li>";
}

echo "<li>";
echo "<a id='imbaMenuImbAdmin' href='#'>Auf zum Atem</a>";
echo "<ul class='subnav'>";

if (ImbaUserContext::getLoggedIn()) {
    $contentNav = new ImbaContentNavigation();

    /**
     * fixme find files
     */
    if ($handle = opendir('Ajax/Content/')) {
        while (false !== ($file = readdir($handle))) {
            if (strrpos($file, ".Navigation.php") > 0) {
                include 'Ajax/Content/' . $file;
                foreach ($Navigation->getElements() as $nav) {
                    echo "<li><a href='" . str_replace(".Navigation.php","", $file) . "'>" . $Navigation->getName($nav) . "</a></li>";
                }
                $Navigation = null;
            }
        }
        closedir($handle);
    }

//    $contentNav->addElement("User", "Benutzer");
//    $contentNav->addElement("Admin", "Administration");
//    $smarty_navs = array();
} else {
    echo "<li><a href=''>Registrieren</a></li>";
}

echo "</ul>";
echo "</li>";
echo "</li></ul></div>";
?>    