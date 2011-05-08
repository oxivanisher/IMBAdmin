<?php

session_start();

require_once 'Model/ImbaUser.php';
require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaManagerUserRole.php';
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
    $managerUser = ImbaManagerUser::getInstance();


    $contentNav = new ImbaContentNavigation();

    switch ($_POST["request"]) {

        case "index":
            $navOptions = array();
            if ($handle = opendir('Ajax/IMBAdminModules/')) {
                $identifiers = array();
                while (false !== ($file = readdir($handle))) {
                    if (strrpos($file, ".Navigation.php") > 0) {
                        include 'Ajax/IMBAdminModules/' . $file;
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
                                    //print_r($element);
                                    array_push($modNavs, array(
                                        "module" => $modIdentifier,
                                        "identifier" => $element,
                                        "name" => $Navigation->getElementName($element),
                                        "comment" => $Navigation->getElementComment($element),
                                    ));
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
            $smarty->display('IMBAdminModules/WelcomeIndex.tpl');
            break;

        default:
            $myself = $managerUser->selectMyself();
            $allUsers = $managerUser->selectAllUser();
            $smarty->assign('nickname', $myself->getNickname());
            $smarty->assign("today", date("d") . "." . date("m") . " " . date("Y"));
            $smarty->assign("thrustRoot", urlencode(ImbaSharedFunctions::getTrustRoot()));
            /*
             * ToDo:
             * $events
             * $todo
             * $today
             * $myName
             * 
             */

            /*
             * Fill Navigation $navs
             */
            $navOptions = array();
            if ($handle = opendir('Ajax/IMBAdminModules/')) {
                $identifiers = array();
                while (false !== ($file = readdir($handle))) {
                    if (strrpos($file, ".Navigation.php") > 0) {
                        include 'Ajax/IMBAdminModules/' . $file;
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

            /**
             * Fill $birthdays
             */
            $return = "";
            $birthdays = array();
            $todayMagicNumber = (date("n") * 31) + date("j");
            foreach ($allUsers as $user) {
                $magicNumber = ($user->getBirthmonth() * 31) + $user->getBirthday();
                $birthdayStr = $user->getNickname() . ": " . $user->getBirthday() . "." . $user->getBirthmonth() . " (" . (date("Y") - $user->getBirthyear()) . ")<br />";
                if ($magicNumber > 0) {
                    $birthdays[$magicNumber] .= $birthdayStr;
                }
            }
            $count = 0;
            ksort($birthdays);
            foreach ($birthdays as $birthday => $string) {
                if ($birthday >= $todayMagicNumber) {
                    if ($todayMagicNumber == $birthday) {
                        $return .= "<b>" . $string . "</b>";
                    } else {
                        $return .= $string;
                    }
                    $count++;
                    if ($count > 2) {
                        break;
                    }
                }
            }
            $smarty->assign("birthdays", $return);

            /**
             * Fill $newMembers
             */
            $return = "";
            $newUsers = array();
            foreach ($allUsers as $user) {
                $newUsers[$user->getId()] = $user->getNickname();
            }
            krsort($newUsers);
            $count = 0;
            foreach ($newUsers as $id => $nickName) {
                if ($count > 2) {
                    break;
                } else {
                    $return .= $nickName . "<br />";
                    $count++;
                }
            }
            $smarty->assign("newMembers", $return);

            /**
             * Display the site
             */
            $smarty->display('IMBAdminModules/WelcomeOverview.tpl');
            break;
    }
} else {
    echo "Not logged in";
}
?>
