<?php

require_once 'ImbaConstants.php';

/**
 * This file returns the available navigation options for the modules ($_POST["module"])
 */
$nav = array();

if (empty($_POST["context"])) {
    $_POST["context"] = "IMBAdminModules";
}


$navigationFile = "Ajax/" . $_POST["context"] . "/" . $_POST["module"] . ".Navigation.php";

function returnDefaultModule() {
    session_start();
    require_once 'Controller/ImbaUserContext.php';

    if ($_POST["context"] == "IMBAdminModules") {
        if (ImbaUserContext::getLoggedIn()) {
            echo ImbaConstants::$WEB_DEFAULT_LOGGED_IN_MODULE;
        } else {
            echo ImbaConstants::$WEB_DEFAULT_LOGGED_OUT_MODULE;
        }
    } elseif ($_POST["context"] == "IMBAdminGames") {
        if (ImbaUserContext::getLoggedIn()) {
            echo ImbaConstants::$WEB_DEFAULT_LOGGED_IN_GAME;
        } else {
            echo ImbaConstants::$WEB_DEFAULT_LOGGED_OUT_GAME;
        }
    }
}

if (file_exists($navigationFile)) {
    /**
     * load the module file
     */
    require_once $navigationFile;

    switch ($_POST["request"]) {
        case "nav":
            foreach ($Navigation->getElements() as $NavigationEntry) {
                array_push($nav, array("id" => $NavigationEntry, "name" => $Navigation->getElementName($NavigationEntry)));
            }
            echo json_encode($nav);
            break;
        case "name":
            $myName = (string) $Navigation->getName();
            echo $myName;
            break;
        case "comment":
            $myComment = (string) $Navigation->getComment();
            echo $myComment;
            break;
        default:
            echo returnDefaultModule();
            break;
    }
} else {
    switch ($_POST["request"]) {
        case "nav":
            array_push($nav, array("id" => "error", "name" => "Module not found (" . $moduleFile . ")!"));
            echo json_encode($nav);
            break;
        case "name":
            echo "Module not found (" . $moduleFile . ")!";
            break;
        case "comment":
            echo "Module not found (" . $moduleFile . ")!";
            break;
        default:
            echo returnDefaultModule();
            break;
    }
}
?>
