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
$Navigation->setName("Registrieren");
$Navigation->setComment("In diesem Module registrieren sich neue Benutzer.");

/**
 * Set when the module should be displayed (logged in 1/0)
 */
$Navigation->setShowLoggedIn(false);
$Navigation->setShowLoggedOff(true);

/**
 * Set the minimal user role needed to display the module
 */
$Navigation->setMinUserRole(0);

/**
 * Set tabs
 */
$Navigation->addElement("reg", "Registration", "Hier kannst du dich registrieren.");
?>
