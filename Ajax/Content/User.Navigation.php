<?php

require_once 'Model/ImbaNavigation.php';

/**
 * Define Navigation
 */
$Navigation = new ImbaContentNavigation();

/**
 * Set module name
 */
$Navigation->setName("Benuterverwaltung");

/**
 * Set tabs
 */
$Navigation->addElement("overview", "Benutzer &Uuml;bersicht");
$Navigation->addElement("myprofile", "Mein Profil Editieren");

?>
