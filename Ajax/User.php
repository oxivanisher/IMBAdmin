<?php

// Extern Session start
session_start();

require_once 'Model/ImbaUser.php';
require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerDatabase.php';
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaManagerMessage.php';
require_once 'Controller/ImbaUserContext.php';

/**
 * are we logged in?
 */
if (ImbaUserContext::getLoggedIn()) {
    $managerDatabase = ImbaManagerDatabase::getInstance(ImbaConfig::$DATABASE_HOST, ImbaConfig::$DATABASE_DB, ImbaConfig::$DATABASE_USER, ImbaConfig::$DATABASE_PASS);
    $managerUser = new ImbaManagerUser($managerDatabase);
    $managerMessage = new ImbaManagerMessage($managerDatabase);

    /**
     * Gets a list of online users as JSON
     */
    if (isset($_POST['loadusersonlinelist'])) {
        $users = $managerUser->selectAllUserButme(ImbaUserContext::getOpenIdUrl());
        $result = array();
        $msgCountMin = -1;
        $msgCountMax = -1;

        foreach ($users as $user) {
            if (date("d-m-Y") == date("d-m-Y", $user->getLastonline())) {
                // Setting the color, depending on time
                // < 5 min => lime
                // < 10min => orange
                // < 30min => yellow
                // default => white
                $timediff = date("U") - $user->getLastonline();

                if ($timediff <= (5 * 60)) {
                    $color = "lime";
                } else if ($timediff <= (10 * 60)) {
                    $color = "orange";
                } else if ($timediff <= (20 * 60)) {
                    $color = "yellow";
                } else if ($timediff <= (30 * 60)) {
                    $color = "white";
                } else {
                    $color = "gray";
                }

                $msgCount = $managerMessage->selectMessagesCount(ImbaUserContext::getOpenIdUrl(), $user->getOpenId());

                if ($msgCount > $msgCountMax || $msgCountMax == -1)
                    $msgCountMax = $msgCount;
                if ($msgCount < $msgCountMin || $msgCountMin == -1)
                    $msgCountMin = $msgCount;

                array_push($result, array("name" => $user->getNickname(), "openid" => $user->getOpenId(), "fontsize" => "8", "color" => $color, "msgCount" => $msgCount));
            }
        }

        $hundredPercent = $msgCountMax - $msgCountMin;        
        if ($hundredPercent == 0) $hundredPercent = $msgCountMax;
            
        foreach ($result as $key => $user) {
            $tmpMsgCount = $user["msgCount"] - $hundredPercent;
            $tmpPercent = round(100 / $hundredPercent * $tmpMsgCount, 0);
            $result[$key]["fontsize"] = min(20, round(6 / 100 * $tmpPercent) + 8);
        }
        
        // now comes the magic with the font size        
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
