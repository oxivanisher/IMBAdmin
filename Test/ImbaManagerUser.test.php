<?php

chdir("../");
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaManagerUserRole.php';
require_once 'Controller/ImbaManagerDatabase.php';
require_once 'Controller/ImbaUserContext.php';
require_once 'Model/ImbaUser.php';
require_once 'Model/ImbaUserRole.php';

/**
 * Test login
 */
/**
 * Fucking hell, am I dirty!
 */
session_start();
ImbaUserContext::setLoggedIn(true);
ImbaUserContext::setOpenIdUrl("http://openid-provider.appspot.com/Steffen.So@googlemail.com");
ImbaUserContext::setUserRole(9);

/**
 * Prepare Variables
 */
$managerUser = ImbaManagerUser::getInstance();
$managerRole = ImbaManagerUserRole::getInstance();
$output = "";

/**
 * Select User
 */
$user = new ImbaUser();
$user = $managerUser->selectByOpenId("http://openid-provider.appspot.com/Steffen.So@googlemail.com");

if ($user->getFirstname() == "Steffen" && $user->getLastname() == "Sommer") {
    $output.= "selectByOpenId working.\n";
} else {
    $output.= "Error at selectByOpenId.\n";
}

/**
 * Select Users
 */
$users = $managerUser->selectAllUserButme("http://openid-provider.appspot.com/Steffen.So@googlemail.com");
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
 * Update User
 */
try {
    $user->setFirstname("TestFirstname");
    $managerUser->update($user);
    $output.= "update working.\n";
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

/**
 * Role update test
 */
try {
    $role = $managerRole->selectById(1);

    $roleOldName = $role->getName();
    $role->setName($role->getName() . " Test");
    $managerRole->update($role);

    $role->setName($roleOldName);
    $managerRole->update($role);
    $output.= "Role update working.\n";
} catch (Exception $e) {
    $output.= "Error at delete.\n";
}

/**
 * User starts with
 */
try {
    $users = $managerUser->selectAllUserStartWith(ImbaUserContext::getOpenIdUrl(), "Aggra");
    
    var_dump($users);
    
    $output.= "Usermanager starts with working.\n";
} catch (Exception $e) {
    $output.= "Error at delete.\n";
}

echo "<pre>ImbaManagerUser Test:\n" . $output . "</pre>";
?>
