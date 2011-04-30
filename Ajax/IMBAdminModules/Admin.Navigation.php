<?php

session_start();

require_once 'Model/ImbaUser.php';
require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerDatabase.php';
require_once 'Controller/ImbaUserContext.php';

require_once 'Model/ImbaNavigation.php';


/**
 * Define Navigation
 */
$Navigation = new ImbaContentNavigation();

/**
 * Set module name
 */
$Navigation->setName("Administration");
$Navigation->setComment("Hier kann der IMBAdmin konfiguriert werden.");


/**
 * Set when the module should be displayed (logged in 1/0)
 */
$Navigation->setShowLoggedIn(true);
$Navigation->setShowLoggedOff(false);

/**
 * Set the minimal user role needed to display the module
 */
$Navigation->setMinUserRole(3);

/**
 * Set tabs
 */
$Navigation->addElement("user", "Users", "Edit user roles and user details");
$Navigation->addElement("role", "Roles", "Manage Roles");
$Navigation->addElement("game", "Games", "Manage Games");
$Navigation->addElement("gamecategory", "Game Categories", "Manage Game Categories");
$Navigation->addElement("portaloverview", "Portals", "Manage Portal (Site URL)");
$Navigation->addElement("portalentry", "Portal Entries", "Manage Navigation Entries");

?>
