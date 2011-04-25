<?php
require_once 'ImbaConstants.php';

/**
 * This file returns the available navigation options for the modules ($_POST["module"])
 */
$nav = array();
$tmpFile = "";

if (empty($_POST["context"])) {
    echo "NoContext";
    exit;
} elseif ($_POST["context"] == "IMBAdminModules") {
    $tmpFile = "IMBAdminModules/" . $_POST["module"];
} elseif ($_POST["context"] == "IMBAdminGames") {
    $tmpFile = "IMBAdminGames/" . $_POST["game"];
}

$navigationFile = "Ajax/" . $tmpFile . ".Navigation.php";

function returnDefaultModule() {
    require_once 'Controller/ImbaUserContext.php';

    if ($_POST["context"] == "IMBAdminModules") {
        if (ImbaUserContext::getLoggedIn()) {
            echo ImbaConstants::$WEB_DEFAULT_LOGGED_IN_MODULE;
        } else {
            echo ImbaConstants::$WEB_DEFAULT_LOGGED_OUT_MODULE;
        }
    } elseif ($_POST["context"] == "IMBAdminGames") {
        echo ImbaConstants::$WEB_DEFAULT_GAME;
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
            array_push($nav, array("id" => "error", "name" => "Module not found (" . $navigationFile . ")!"));
            echo json_encode($nav);
            break;
        case "name":
            echo "Module not found (" . $navigationFile . ")!";
            break;
        case "comment":
            echo "Module not found (" . $navigationFile . ")!";
            break;
        default:
            echo returnDefaultModule();
            break;
    }
}
?>
