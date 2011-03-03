<?php

chdir("../");
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaManagerDatabase.php';
require_once 'Model/ImbaUser.php';

/**
 * Prepare Variables
 */
$managerDatabase = ImbaManagerDatabase::getInstance("localhost", "imbadmin", "imbadmin", "ua0Quee2");
$managerUser = new ImbaManagerUser($managerDatabase);
$output = "";

/**
 * Select User
 */
$user = new ImbaUser();
$user = $managerUser->selectByOpenId("https://oom.ch/openid/identity/test");

if ($user->getFirstname() == "hans" && $user->getLastname() == "ruedi") {
    $output.= "selectByOpenId working.\n";
} else {
    $output.= "Error at selectByOpenId.\n";
}

/**
 * Select Users
 */
$users = $managerUser->selectAllUser("http://openid-provider.appspot.com/Steffen.So@googlemail.com");
if (count($users) > 0) {
    $output.= "selectAllUser working.\n";
} else {
    $output.= "Error at selectAllUser.\n";
}

/**
 * Check if json_encode is working in toString()
 */
if ($user->toString() != "{}") {
    $output .= "toString working. Json: " . $user->toString() . "\n";
} else {
    $output .= "Error at Json.\n";
}

/**
 * Insert User
 */
$user->setOpenId($user->getOpenId() . "1");
try {
    $managerUser->insert($user);
    $output.= "insert working.\n";
} catch (Exception $e) {
    $output.= "Error at insert.\n";
}

/**
 * Delete user
 */
try {
    $managerUser->delete($user->getOpenId());
    $output.= "delete working.\n";
} catch (Exception $e) {
    $output.= "Error at delete.\n";
}


echo "<pre>ImbaManagerUser Test:\n" . $output . "</pre>";
?>
