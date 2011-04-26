<?php

header('Access-Control-Allow-Origin: *');
/**
 * start the php session
 */
session_start();

/**
 * Load dependencies
 */
require_once "ImbaConstants.php";
require_once 'Controller/ImbaManagerLog.php';
require_once 'Controller/ImbaManagerOpenID.php';
require_once 'Controller/ImbaManagerOauth.php';
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaUserContext.php';
require_once 'Controller/ImbaSharedFunctions.php';
require_once 'Model/ImbaUser.php';
require_once 'Model/ImbaUserRole.php';

/**
 * Load Auth Managers
 * - OpenID
 * - OAuth
 */
//$tmpPath = getcwd();
$managerOpenId = new ImbaManagerOpenID();
//$managerOauth = new ImbaManagerOauth();

/* chdir("Libs/");
  //require_once "Oauth.php";
  require_once "Zend/Oauth/Consumer.php";

  chdir($tmpPath); */

/**
 * Load the logger
 */
$managerLog = ImbaManagerLog::getInstance();

/**
 * Manager for users
 */
$managerUser = ImbaManagerUser::getInstance();

/**
* Helper for redirects
 */
function redirectMe($url, $line = __LINE__) {
                //header("Location: " . $url);
                echo $line . ": " . $url . "<br /><pre>";
                print_r($GLOBALS);
                echo "</pre>";
                exit;
}
/**
 * OpenID auth logic
 */
if ($_GET["logout"] == true || $_POST["logout"] == true) {
    /**
     * we want to log out
     */
    $log = $managerLog->getNew();
    $log->setModule("Auth");
    $log->setMessage("Logging out (Redirecting)");
    $log->setLevel(2);
    $managerLog->insert($log);

    setcookie(session_id(), "", time() - 3600);
    session_destroy();
    session_write_close();

    if (empty($_POST['imbaSsoOpenIdLogoutReferer'])) {
        $targetUrl = ImbaSharedFunctions::getTrustRoot();
    } else {
        $targetUrl = $_POST['imbaSsoOpenIdLogoutReferer'];
    }
    ImbaUserContext::setImbaErrorMessage("Logging out (Redirecting)");
    redirectMe($targetUrl, __LINE__);
} elseif (!ImbaUserContext::getLoggedIn()) {
    /**
     * we are NOT logged in
     */
    if (empty($_SESSION["IUC_WaitingForVerify"])) {
        /**
         * Determine Authentication method (we also don't have to be verified)
         */
        if (!(empty($_POST['openid']) && (empty($_GET['openid'])))) {
            /**
             * OpenID Authentification
             */
            $authMethod = "openid";
        } else {
            /**
             * Send the User to the registration page
             */
            ImbaUserContext::setImbaErrorMessage("Authentificationmethod not found");
            redirectMe($_SERVER['HTTP_REFERER'], __LINE__);
        }

        /**
         * Save our referer to session if there is none safed till now
         */
        if (empty($_POST['imbaSsoOpenIdLoginReferer'])) {
            if (empty($_SESSION["IUC_redirectUrl"])) {
                ImbaUserContext::setRedirectUrl($_SERVER['HTTP_REFERER']);
            } else {
                ImbaUserContext::setRedirectUrl($_SESSION["IUC_redirectUrl"]);
            }
        } else {
            ImbaUserContext::setRedirectUrl($_POST['imbaSsoOpenIdLoginReferer']);
        }

        /**
         * Do the Authentication
         */
        switch ($authMethod) {

            case "openid":
                if (empty($_POST["openid"]) && (!empty($_GET["openid"]))) {
                    $_POST["openid"] = $_GET["openid"];
                }
                if (!empty($_POST["openid"])) {
                    $_POST["openid"] = trim($_POST["openid"]);
                    $redirectUrl = null;

                    /**
                     * Check if this is a openid (which looks like a URL) or possibly the nickname of the user
                     */
                    if (ImbaSharedFunctions::isValidURL($_POST["openid"])) {
                        /**
                         * This is a possible openid
                         */
                        $openid = $_POST["openid"];
                    } else {
                        /**
                         * Try to lookup the nickname
                         */
                        $securityCounter = 0;
                        $tmpOpenid = null;
                        $allUsers = $managerUser->selectAllUser();
                        foreach ($allUsers as $user) {
                            if (strtolower($user->getNickname()) == strtolower($_POST["openid"])) {
                                $securityCounter++;
                                $tmpOpenid = $user->getOpenId();
                            }
                        }

                        if (($securityCounter == 1) && (!empty($tmpOpenid))) {
                            $openid = $tmpOpenid;
                        } else {
                            throw new Exception(ImbaConstants::$ERROR_OPENID_Auth_OpenID_INVALID_URI);
                        }
                    }

                    /**
                     * try to do the first step of the openid authentication steps
                     */
                    $log = $managerLog->getNew();
                    $log->setModule("Auth");
                    $log->setMessage("Determing Auth style for " . $openid);
                    $log->setLevel(3);
                    $managerLog->insert($log);

                    $log = $managerLog->getNew();
                    $log->setModule("Auth");
                    try {
                        $redirectUrl = $managerOpenId->openidAuth($openid);

                        if (!empty($redirectUrl)) {
                            /**
                             * we got a redirection url as answer. go there now!
                             */
                            $log->setLevel(3);
                            $log->setMessage("Redirecting to " . ImbaSharedFunctions::getDomain($redirectUrl));
                            $managerLog->insert($log);
                            ImbaUserContext::setImbaErrorMessage($log->getMessage());

                            /**
                             * If this is set, the user will be sent to verification next time
                             */
                            //ImbaUserContext::setWaitingForVerify(ImbaSharedFunctions::getReturnTo());
                            ImbaUserContext::setWaitingForVerify(ImbaUserContext::getRedirectUrl());

                            /**
                             * In case the referer is not working, there is a redirecting solution like this:
                             * ImbaUserContext::setAuthReferer($redirectUrl);
                             */
                            redirectMe($redirectUrl, __LINE__);
                        } else {
                            /**
                             * something went wrong. display error end exit
                             */
                            $log->setLevel(0);
                            $log->setMessage("Special Error: Ehhrmm keine URL, weil ehhrmm");
                            $managerLog->insert($log);
                            ImbaUserContext::setImbaErrorMessage($log->getMessage);
                            redirectMe(ImbaUserContext::getRedirectUrl(), __LINE__);
                        }
                    } catch (Exception $ex) {
                        $log->setLevel(1);
                        $log->setMessage("Authentification ERROR: " . $ex->getMessage() . " (" . $openid . ")");
                        $managerLog->insert($log);
                        ImbaUserContext::setImbaErrorMessage($log->getMessage());
                        redirectMe(ImbaUserContext::getRedirectUrl(), __LINE__);
                    }
                } else {
                    $log = $managerLog->getNew();
                    $log->setModule("Auth");
                    $log->setMessage("No OpenId submitted");
                    $log->setLevel(2);
                    $managerLog->insert($log);
                    ImbaUserContext::setImbaErrorMessage($log->getMessage());
                    redirectMe(ImbaUserContext::getRedirectUrl(), __LINE__);
                }
                break;

            /**
             * Default auth type
             */
            default:
                ImbaUserContext::setImbaErrorMessage("No Authtype included");
                true;
        }
        echo __LINE__;
        exit;
    } else {
        /**
         * first step completed. do the verification and actual login
         * we shall go to ImbaUserContext::getWaitingForVerify() after
         * we are finished here.
         */
        $log = $managerLog->getNew();
        $log->setModule("Auth");
        $log->setMessage("Verification starting");
        $log->setLevel(2);
        $managerLog->insert($log);
        ImbaUserContext::setImbaErrorMessage($log->getMessage());

        $log = $managerLog->getNew();
        $log->setModule("Auth");

        try {
            $esc_identity = $managerOpenId->openidVerify();
            if (empty($esc_identity)) {
                throw new Exception("openidVerify failed!");
            }

            ImbaUserContext::setWaitingForVerify(false);

            $log->setLevel(2);
            $log->setMessage("OpenID Verification sucessful");
            $managerLog->insert($log);
            ImbaUserContext::setImbaErrorMessage($log->getMessage());

            $currentUser = $managerUser->selectByOpenId($esc_identity);
            /**
             * Check the status of the user
             */
            if (empty($currentUser)) {
                /**
                 * This is a new user. let him register
                 */
                $log = $managerLog->getNew();
                $log->setModule("Auth");
                $log->setMessage("Registering new user");
                $log->setLevel(2);
                $managerLog->insert($log);

                if (!empty($esc_identity)) {
                    ImbaUserContext::setNeedToRegister(true);
                    ImbaUserContext::setOpenIdUrl($esc_identity);
                }
                ImbaUserContext::setImbaErrorMessage($log->getMessage());
                $tmpUrl = ImbaUserContext::getWaitingForVerify();
                ImbaUserContext::setWaitingForVerify("");
                redirectMe($tmpUrl, __LINE__);
            } elseif ($currentUser->getRole() == 0) {
                /**
                 * this user is banned
                 */
                $log = $managerLog->getNew();
                $log->setModule("Auth");
                $log->setMessage($currentUser->getName() . " is banned but tried to login");
                $log->setLevel(2);
                $managerLog->insert($log);
                throw new Exception("You are Banned!");
            } elseif ($currentUser->getRole() != null) {
                /**
                 * this user is allowed to log in
                 */
                $log = $managerLog->getNew();
                $log->setModule("Auth");
                $log->setMessage($currentUser->getNickname() . " logged in");
                $log->setLevel(2);
                $managerLog->insert($log);

                ImbaUserContext::setLoggedIn(true);
                ImbaUserContext::setOpenIdUrl($esc_identity);
                ImbaUserContext::setUserRole($currentUser->getRole());
                ImbaUserContext::setUserId($currentUser->getId());

                setcookie("ImbaSsoLastLoginName", "", (time() - 3600));
                setcookie("ImbaSsoLastLoginName", $currentUser->getNickname(), (time() + (60 * 60 * 24 * 30)));

                $managerUser->setMeOnline();
                ImbaUserContext::setImbaErrorMessage("Sucessfully logged in with " . $currentUser->getNickname());
            }
            $tmpUrl = ImbaUserContext::getWaitingForVerify();
            ImbaUserContext::setWaitingForVerify("");
            redirectMe($managerOpenId->getTrustRoot(), __LINE__);
        } catch (Exception $ex) {
            if ($ex->getMessage() == "id_res_not_set") {
                $tmpUrl = ImbaUserContext::getWaitingForVerify();
                ImbaUserContext::setWaitingForVerify(false);
                $log->setLevel(1);
                $log->setMessage("Aktuelle OpenID Anfrage ausgelaufen. Bitte nocheinmal von neuen probieren.");
                $managerLog->insert($log);

                //header("Location: " . $_SERVER['PHP_SELF'] . "?openid=" . ImbaUserContext::getOpenIdUrl());
                ImbaUserContext::setImbaErrorMessage($log->getMessage());
                ImbaUserContext::setWaitingForVerify("");
                redirectMe($managerOpenId->getTrustRoot(), __LINE__);
            } else {
                $log->setLevel(1);
                $log->setMessage("OpenID Verification ERROR: " . $ex->getMessage());
                $managerLog->insert($log);
                ImbaUserContext::setImbaErrorMessage($log->getMessage());
                $tmpUrl = ImbaUserContext::getWaitingForVerify();
                ImbaUserContext::setWaitingForVerify("");
                redirectMe($managerOpenId->getTrustRoot(), __LINE__);
            }
        }
    }
} else {
    ImbaUserContext::setWaitingForVerify("");
    /**
     * we are logged in! everithing is ok, we have a running session 
     * and we have a party here
     * - set cookie with logged in openid for autofill login box
     * - redirect back to page
     */
    /**
     * FIXME: we need to check if the session is still good. we get logged in but should fell offline sometimes
     */
    $log = $managerLog->getNew();
    $log->setModule("Auth");
    $log->setMessage("Already logged in with: " . ImbaUserContext::getOpenIdUrl() . ")");
    $log->setLevel(1);
    $managerLog->insert($log);
    $tmpUrl = ImbaUserContext::getWaitingForVerify();
    ImbaUserContext::setWaitingForVerify("");
    redirectMe($tmpUrl, __LINE__);
}
redirectMe(ImbaUserContext::getRedirectUrl(), __LINE__);
?>