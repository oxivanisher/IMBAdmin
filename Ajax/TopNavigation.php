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
 *  put full path to Smarty.class.php
 */
//require('Libs/smarty/libs/Smarty.class.php');
//$smarty = new Smarty();

/**
 * Set smarty dirs
 */
//$smarty->setTemplateDir('Templates');
//$smarty->setCompileDir('Libs/smarty/templates_c');
//$smarty->setCacheDir('Libs/smarty/cache');
//$smarty->setConfigDir('Libs/smarty/configs');

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

    $contentNav->addElement("User", "Benutzer");
    $contentNav->addElement("Admin", "Administration");


    $smarty_navs = array();
    foreach ($contentNav->getElements() as $nav) {
        $moduleConfigFile = "Ajax/Content/" . $contentNav->getElementIdentifier($nav) . ".Navigation.php";
        if (file_exists($moduleConfigFile)) {
            include $moduleConfigFile;

            echo "<li><a href='" . $contentNav->getElementIdentifier($nav) . "'>" . $contentNav->getElementName($nav) . "</a></li>";
        }
//        array_push($smarty_navs, array(
//            "url" => ImbaSharedFunctions::genAjaxWebLink($_POST["module"], "", $navEntry->getIdentifier()),
//            "name" => $navEntry->getName($navEntry)
//        ));
    }
//    $smarty->assign('navs', $smarty_navs);
} else {
    echo "<li><a href=''>Registrieren</a></li>";
//    $smarty->assign('navs', array(
//        array(
//            "url" => ImbaSharedFunctions::genAjaxWebLink($_POST["module"], "register", "User"),
//            "name" => "Registrieren"
//        )
//    ));
}

echo "</ul>";

echo "</li>";

echo "</li></ul></div>";

//$smarty->display('ImbaTopNavigation.tpl');
?>    