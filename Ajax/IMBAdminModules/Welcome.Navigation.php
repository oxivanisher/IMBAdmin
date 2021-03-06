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
$Navigation->setName("Willkommen");
$Navigation->setComment("Dies ist die Willkommens Site.");

/**
 * Set when the module should be displayed (logged in 1/0)
 */
$Navigation->setShowLoggedIn(false);
$Navigation->setShowLoggedOff(false);

/**
 * Set the minimal user role needed to display the module
 */
$Navigation->setMinUserRole(1);

/**
 * Set tabs
 */
$Navigation->addElement("welcome", "&Uuml;bersicht der Module", "Hier siehst du eine einfache &Uml;bersicht der Module.");
$Navigation->addElement("index", "Indexierte &Uuml;bersicht", "Hier siehst du eine komplette &Uml;bersicht der Module.");
?>
