<?php

require_once 'Model/ImbaNavigation.php';

/**
 * Define Navigation
 */
$Navigation = new ImbaContentNavigation();
$Navigation->setName("Benuterverwaltung");
$Navigation->addElement("overview", "Benutzer &Uuml;bersicht");
$Navigation->addElement("myprofile", "Mein Profil Editieren");
?>
