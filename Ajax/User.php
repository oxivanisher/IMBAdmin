<?php

// Extern Session start
session_start();

require_once 'Model/ImbaUser.php';
require_once 'ImbaConfig.php';
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaManagerMessage.php';
require_once 'Controller/ImbaUserContext.php';

/**
 * are we logged in?
 */
if (ImbaUserContext::getLoggedIn()) {
    $managerUser = ImbaManagerUser::getInstance();
    $managerMessage = ImbaManagerMessage::getInstance();

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

                $msgCount = $managerMessage->selectMessagesCount($user->getId());

                if ($msgCount > $msgCountMax || $msgCountMax == -1)
                    $msgCountMax = $msgCount;
                if ($msgCount < $msgCountMin || $msgCountMin == -1)
                    $msgCountMin = $msgCount;

                array_push($result, array("name" => $user->getNickname(), "id" => $user->getId(), "fontsize" => "8", "color" => $color, "msgCount" => $msgCount));
            }
        }

        $hundredPercent = $msgCountMax - $msgCountMin;
        if ($hundredPercent == 0)
            $hundredPercent = $msgCountMax;

        foreach ($result as $key => $user) {
            $tmpMsgCount = $user["msgCount"] - $msgCountMin;
            if ($tmpMsgCount < 1) {
                $tmpMsgCount = 1;
            }
            $tmpPercent = round(100 / $hundredPercent * $tmpMsgCount, 0);
            $result[$key]["fontsize"] = min(20, round(6 / 100 * $tmpPercent) + 8);
            $result[$key]["fontsize"] = max(8, $result[$key]["fontsize"]);
        }

        // now comes the magic with the font size        
        echo json_encode($result);
    }

    /**
     * Gets a list of users as JSON
     */ else if (isset($_POST['loaduserlist'])) {
        $users = $managerUser->selectAllUserButme(ImbaUserContext::getOpenIdUrl());
        $result = array();
        foreach ($users as $user) {
            array_push($result, array("name" => $user->getNickname(), "openid" => $user->getOpenId(), "lastonline" => $user->getLastonline()));
        }

        echo json_encode($result);
    }

    /**
     * Gets a list of users as JSON, with starting like
     */ else if (isset($_POST['loaduser']) && isset($_POST['startwith'])) {
        if (trim($_POST['startwith']) != "") {
            $users = $managerUser->selectAllUserStartWith($_POST['startwith']);
            $result = array();
            foreach ($users as $user) {
                array_push($result, array("user" => true, "name" => $user->getNickname(), "id" => $user->getId()));
            }

            echo json_encode($result);
        }
    }

    /**
     * Return currently logged in User
     */ else if (isset($_POST['returnmyself'])) {
        $user = $managerUser->selectMyself();
        echo json_encode(array("name" => $user->getNickname(), "openid" => $user->getOpenId()));
    }

    /**
     * Returns the Nickname
     */ else {
        $user = $managerUser->selectMyself();
        echo $user->getNickname();
    }
} elseif (ImbaUserContext::getNeedToRegister()) {
    echo "Need to register";
} else {
    echo "Not logged in";
}
?>
