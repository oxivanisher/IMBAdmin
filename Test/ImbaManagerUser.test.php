<?php

chdir("../");
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaManagerDatabase.php';
require_once 'Model/ImbaUser.php';

/**
 * Prepare Variables
 */
$managerDatabase = new ImbaManagerDatabase("localhost", "imbadmin", "imbadmin", "ua0Quee2");
$managerUser = new ImbaManagerUser($managerDatabase);
$output = "";

/**
 * Select User
 */
$user = new ImbaUser();
$user = $managerUser->selectByOpenId("https://oom.ch/openid/identity/test");

if ($user->getFirstname() == "hans" && $user->getLastname() == "ruedi") {
    $output.= "selectByOpenId geht.\n";    
} else {
    $output.= "Fehler bei selectByOpenId.\n";
}

/**
 * Insert User
 */
$user->setOpenId($user->getOpenId() . "1");
try {
    $managerUser->insert($user);
    $output.= "insert geht.\n";
} catch (Exception $e) {
    $output.= "Fehler bei insert.\n";
}

/**
 * Delete user
 */
try {
    $managerUser->delete($user->getOpenId());
    $output.= "delete geht.\n";
} catch (Exception $e) {
    $output.= "Fehler bei delete.\n";
}


echo "<pre>ImbaManagerUser Test:\n" . $output . "</pre>";
?>
