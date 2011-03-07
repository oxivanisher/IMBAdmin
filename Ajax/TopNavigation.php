<?php

// Extern Session start

require_once 'ImbaConstants.php';
require_once 'Model/ImbaUser.php';
require_once 'Model/ImbaNavigation.php';
require_once 'Controller/ImbaSharedFunctions.php';
require_once 'Controller/ImbaManagerDatabase.php';
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaUserContext.php';

/**
 * Load the database
 */
$managerDatabase = ImbaManagerDatabase::getInstance(ImbaConfig::$DATABASE_HOST, ImbaConfig::$DATABASE_DB, ImbaConfig::$DATABASE_USER, ImbaConfig::$DATABASE_PASS);
$managerUser = new ImbaManagerUser($managerDatabase);

/**
 *  put full path to Smarty.class.php
 */
require('Libs/smarty/libs/Smarty.class.php');
$smarty = new Smarty();

/**
 * Set smarty dirs
 */
$smarty->setTemplateDir('Templates');
$smarty->setCompileDir('Libs/smarty/templates_c');
$smarty->setCacheDir('Libs/smarty/cache');
$smarty->setConfigDir('Libs/smarty/configs');

/**
 * FIXME: please add security to this file!
 */
/**
 * are we logged in?
 */
if (ImbaUserContext::getLoggedIn()) {
    $topNav = new ImbaContentNavigation();

    $topNav->addElement("User", "Benutzer");
    $topNav->addElement("Admin", "Administration");

    $smarty_navs = array();
    foreach ($topNav->getElements() as $nav) {
        $navEntry = $topNav->getElementIdentifier($nav);
        array_push($smarty_navs, array(
            "url" => ImbaSharedFunctions::genAjaxWebLink($_POST["mod_user"], "", $topNav->getElementIdentifier($Identifier)),
            "name" => $topNav->getElementName($navEntry)
        ));
    }
    $smarty->assign('navs', $smarty_navs);
}

$smarty->display('ImbaTopNavigation.tpl');
?>
