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
$Navigation->setName("EVE Online");
$Navigation->setComment("Dies ist ein Modul f&uuml;r EVE Online.");


/**
 * Set when the module should be displayed (logged in 1/0)
 */
$Navigation->setShowLoggedIn(true);
$Navigation->setShowLoggedOff(true);

/**
 * Set the minimal user role needed to display the module
 */
$Navigation->setMinUserRole(0);

/**
 * Set tabs
 */
$Navigation->addElement("overview", "First", "Standart Ansicht");
$Navigation->addElement("settings", "Seccond", "Zweites Tab");
$Navigation->addElement("lintel", "lintel", "Dritter Tab");

?>
