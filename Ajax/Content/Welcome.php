<?php

session_start();

require_once 'Model/ImbaUser.php';
require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerDatabase.php';
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaManagerRole.php';
require_once 'Controller/ImbaUserContext.php';
require_once 'Controller/ImbaSharedFunctions.php';

/**
 * are we logged in?
 */
if (ImbaUserContext::getLoggedIn()) {
    /**
     * create a new smarty object
     */
    $smarty = ImbaSharedFunctions::newSmarty();

    /**
     * Load the database
     */
    $managerDatabase = ImbaManagerDatabase::getInstance(ImbaConfig::$DATABASE_HOST, ImbaConfig::$DATABASE_DB, ImbaConfig::$DATABASE_USER, ImbaConfig::$DATABASE_PASS);
    $managerUser = new ImbaManagerUser($managerDatabase);


    $contentNav = new ImbaContentNavigation();

    switch ($_POST["request"]) {

        case "index":
            $navOptions = array();
            if ($handle = opendir('Ajax/Content/')) {
                $identifiers = array();
                while (false !== ($file = readdir($handle))) {
                    if (strrpos($file, ".Navigation.php") > 0) {
                        include 'Ajax/Content/' . $file;
                        if (ImbaUserContext::getUserRole() >= $Navigation->getMinUserRole()) {
                            $showMe = false;
                            if (ImbaUserContext::getLoggedIn() && $Navigation->getShowLoggedIn()) {
                                $showMe = true;
                            } elseif ((!ImbaUserContext::getLoggedIn()) && $Navigation->getShowLoggedOff()) {
                                $showMe = true;
                            }

                            if ($showMe) {
                                $modIdentifier = trim(str_replace(".Navigation.php", "", $file));

                                $modNavs = array();
                                foreach ($Navigation->getElements() as $element) {
                                    print_r($element);
                                    /*array_push($modNavs, array(
                                        "module" => $modIdentifier,
                                        "identifier" => $element->getIdentifier($nav),
                                        "name" => $element->getName($nav),
                                        "comment" => $element->getComment($nav),
                                    ));*/
                                }

                                array_push($navOptions, array(
                                    "identifier" => $modIdentifier,
                                    "name" => $Navigation->getName($nav),
                                    "comment" => $Navigation->getComment($nav),
                                    "options" => $modNavs
                                ));
                                $Navigation = null;
                            }
                        }
                    }
                }
                closedir($handle);
            }
            $smarty->assign('topnavs', $navOptions);
            $smarty->display('ImbaWebWelcomeIndex.tpl');
            break;

        default:
            $myself = $managerUser->selectMyself();
            $smarty->assign('nickname', $myself->getNickname());
            $navOptions = array();
            if ($handle = opendir('Ajax/Content/')) {
                $identifiers = array();
                while (false !== ($file = readdir($handle))) {
                    if (strrpos($file, ".Navigation.php") > 0) {
                        include 'Ajax/Content/' . $file;
                        if (ImbaUserContext::getUserRole() >= $Navigation->getMinUserRole()) {
                            $showMe = false;
                            if (ImbaUserContext::getLoggedIn() && $Navigation->getShowLoggedIn()) {
                                $showMe = true;
                            } elseif ((!ImbaUserContext::getLoggedIn()) && $Navigation->getShowLoggedOff()) {
                                $showMe = true;
                            }

                            if ($showMe) {
                                $modIdentifier = trim(str_replace(".Navigation.php", "", $file));
                                array_push($navOptions, array("identifier" => $modIdentifier,
                                    "name" => $Navigation->getName($nav),
                                    "comment" => $Navigation->getComment($nav)
                                ));
                                $Navigation = null;
                            }
                        }
                    }
                }
                closedir($handle);
            }
            $smarty->assign('navs', $navOptions);
            $smarty->display('ImbaWebWelcomeOverview.tpl');
            break;
    }
} else {
    echo "Not logged in";
}
?>
