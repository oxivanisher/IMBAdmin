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
$Navigation->setName("Mitglieder");
$Navigation->setComment("Hier kannst du dich &uuml;ber Mitglieder informieren sowie das eigene Profil editiert werden.");

/**
 * Set when the module should be displayed (logged in 1/0)
 */
$Navigation->setShowLoggedIn(true);
$Navigation->setShowLoggedOff(false);

/**
 * Set the minimal user role needed to display the module
 */
$Navigation->setMinUserRole(1);

/**
 * Set tabs
 */
$Navigation->addElement("overview", "Mitglieder &Uuml;bersicht", "Hier kannst du alles &uuml;ber unsere anderen Mitglieder erfahren.");
$Navigation->addElement("editmyprofile", "Mein Profil Editieren", "Hier kannst du dein Profil editieren.");
$Navigation->addElement("editmygames", "Meine Spiele Editieren", "Hier kannst du deine Spiele editieren.");
$Navigation->addElement("viewmyprofile", "Mein Profil Ansehen", "Hier kannst du dein Profil so ancheuen.");
?>
