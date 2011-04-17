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
$Navigation->setName("Maintenance");
$Navigation->setComment("Hier werden Wartungsarbeten am System durchgef&uuml;hrt.");


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
$Navigation->addElement("log", "Log", "View and clar log");
$Navigation->addElement("statistics", "Statistics", "Statistics");
$Navigation->addElement("maintenance", "Maintenance Jobs", "System maintenance jobs");
$Navigation->addElement("settings", "Settings", "System wide settings");
$Navigation->addElement("sites", "Sites", "Multidomain Site settings");

?>
