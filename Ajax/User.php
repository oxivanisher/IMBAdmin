<?php

// Extern Session start
session_start();

require_once 'Model/ImbaUser.php';
require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerDatabase.php';
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaUserContext.php';

/**
 * are we logged in?
 */
//DEBUG ONLY!!!!!!
//ImbaUserContext::setLoggedIn(true);
//ImbaUserContext::setOpenIdUrl("http://openid-provider.appspot.com/Steffen.So@googlemail.com");
//if (true) {

if (ImbaUserContext::getLoggedIn()) {
    $managerDatabase = ImbaManagerDatabase::getInstance(ImbaConfig::$DATABASE_HOST, ImbaConfig::$DATABASE_DB, ImbaConfig::$DATABASE_USER, ImbaConfig::$DATABASE_PASS);
    $managerUser = new ImbaManagerUser($managerDatabase);

    /**
     * Gets a list of users as JSON
     */
    if (isset($_POST['loaduserlist'])) {
        $users = $managerUser->selectAllUser(ImbaUserContext::getOpenIdUrl());
        $result = array();
        foreach ($users as $user) {
            array_push($result, array("name" => $user->getNickname(), "openid" => $user->getOpenId()));
        }

        echo json_encode($result);
    }
}
else {
    echo "Not logged in";
}
?>
