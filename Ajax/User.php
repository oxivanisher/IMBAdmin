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
if (ImbaUserContext::getLoggedIn()) {
    $managerDatabase = ImbaManagerDatabase::getInstance(ImbaConfig::$DATABASE_HOST, ImbaConfig::$DATABASE_DB, ImbaConfig::$DATABASE_USER, ImbaConfig::$DATABASE_PASS);
    $managerUser = new ImbaManagerUser($managerDatabase);

    /**
     * Gets a list of online users as JSON
     */
    if (isset($_POST['loadusersonlinelist'])) {
        $users = $managerUser->selectAllUserButme(ImbaUserContext::getOpenIdUrl());
        $result = array();
        foreach ($users as $user) {
            // TODO: Wann werden der User als online in der Liste angezeigt
            // wie groß wird er angezeigt und
            // in welcher Farbe wird er angezeigt
            if (date("d-m-Y") == date("d-m-Y", $user->getLastonline())) {
                $fontsize = rand(6, 20);
                $color = sprintf("#%x%x%x", rand(0,15), rand(0,15), rand(0,15));
                array_push($result, array("name" => $user->getNickname(), "openid" => $user->getOpenId(), "fontsize" => $fontsize, "color" => $color));
            }
        }

        echo json_encode($result);
    }

    /**
     * Gets a list of users as JSON
     */
    if (isset($_POST['loaduserlist'])) {
        $users = $managerUser->selectAllUserButme(ImbaUserContext::getOpenIdUrl());
        $result = array();
        foreach ($users as $user) {
            array_push($result, array("name" => $user->getNickname(), "openid" => $user->getOpenId(), "lastonline" => $user->getLastonline()));
        }

        echo json_encode($result);
    }

    /**
     * Gets a list of users as JSON, with starting like
     */
    if (isset($_POST['loaduser']) && isset($_POST['startwith'])) {
        $users = $managerUser->selectAllUserStartWith(ImbaUserContext::getOpenIdUrl(), $_POST['startwith']);
        $result = array();
        foreach ($users as $user) {
            array_push($result, array("name" => $user->getNickname(), "openid" => $user->getOpenId()));
        }

        echo json_encode($result);
    }
} else {
    echo "Not logged in";
}
?>
