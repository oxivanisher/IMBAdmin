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
require_once 'Controller/ImbaManagerAuthRequest.php';
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
 * Manager for users
 */
$managerUser = ImbaManagerUser::getInstance();

/**
 * Manager for AuthRequests
 */
$managerAuthRequest = ImbaManagerAuthRequest::getInstance();

/**
 * private function to write to logs
 */
function writeAuthLog($message, $level = 3) {
    /**
     * Load the logger
     */
    $managerLog = ImbaManagerLog::getInstance();

    $log = $managerLog->getNew();
    $log->setModule("ImbaAuth");
    $log->setMessage($message);
    $log->setLevel($level);
    $managerLog->insert($log);
    ImbaUserContext::setImbaErrorMessage($message);
    return $message;
}

/**
 * Kill our session and go to URL, function
 */
function killAndRedirect($targetUrl) {
    setcookie(session_id(), "", time() - 3600);
    session_destroy();
    session_write_close();
    header("Location: " . $targetUrl);
}

/**
 * Redirect with domain magic
 */
function redirectTo($line, $url, $message = "") {
    $myDomain = ImbaSharedFunctions::getDomain($url);
    /**
     * Discover if we need to do the html redirect and make it so
     */
//if (ImbaSharedFunctions::getDomain($_SERVER['HTTP_REFERER']) != ImbaSharedFunctions::getDomain($url)) {
    if (!headers_sent()) {
        $smarty = ImbaSharedFunctions::newSmarty();
        $smarty->assign("redirectUrl", $url);
        $smarty->assign("redirectDomain", $myDomain);
        $smarty->assign("internalCode", $line);
        $smarty->assign("internalMessage", $message);
        $smarty->display("ImbaAuthRedirect.tpl");
        exit;
    } else {
        header("Location: " . $url);
        exit;
    }


    /**
     * In case the referer is not working, there is a redirecting solution like this:
     * ImbaUserContext::setAuthReferer($redirectUrl);
     */
}

/**
 * OpenID auth logic
 */
if ($_GET["logout"] == true || $_POST["logout"] == true) {
    /**
     * we want to log out
     */
    writeAuthLog("Logging out (Redirecting)", 2);

    if (empty($_POST['imbaSsoOpenIdLogoutReferer'])) {
        $targetUrl = ImbaSharedFunctions::getTrustRoot();
    } else {
        $targetUrl = $_POST['imbaSsoOpenIdLogoutReferer'];
    }

    killAndRedirect($targetUrl);
    exit;
} elseif (!ImbaUserContext::getLoggedIn()) {
    /**
     * we are NOT logged in
     */
    if (empty($_SESSION["IUC_WaitingForVerify"])) {
        /**
         * Save our referer to session if there is none safed till now
         */
        if ($_POST['imbaSsoOpenIdLoginReferer'] != "") {
            ImbaUserContext::setRedirectUrl($_POST['imbaSsoOpenIdLoginReferer']);
        } else {
            if ($_SESSION["IUC_redirectUrl"] == "") {
                ImbaUserContext::setRedirectUrl($_SERVER['HTTP_REFERER']);
            }
        }

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
            if (empty($_SERVER['HTTP_REFERER'])) {
                $tmpUrl = ImbaSharedFunctions::getTrustRoot();
            } else {
                $tmpUrl = $_SERVER['HTTP_REFERER'];
            }
            $tmpMsg = writeAuthLog("Authentificationmethod not found");
            /* header("Location: " . $_SERVER['HTTP_REFERER']); */
            redirectTo(__LINE__, $tmpUrl, $tmpMsg);
            exit;
        }

        /**
         * Do the Authentication
         */
        switch ($authMethod) {
            /**
             * Determine the authentification method
             */
            case "openid":
                if (empty($_POST["openid"]) && (!empty($_GET["openid"]))) {
                    $_POST["openid"] = $_GET["openid"];
                }
                if (!empty($_POST["openid"])) {
                    $_POST["openid"] = trim($_POST["openid"]);
                    $redirectUrl = null;

                    /**
                     * Get all users
                     */
                    $allUsers = $managerUser->selectAllUser();

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
                     * Discovering our user
                     */
                    $myUser = $managerUser->selectByOpenId($openid);

                    /**
                     * Saving our authrequest into the database
                     */
                    $authRequest = $managerAuthRequest->getNew();
                    $authRequest->setUserId($myUser->getId());
                    $authRequest->setHash(ImbaSharedFunctions::getRandomString());
                    $authRequest->setRealm(ImbaSharedFunctions::getTrustRoot());
                    $authRequest->setReturnTo(ImbaSharedFunctions::getReturnTo($authRequest->getHash()));
                    $authRequest->setType($authMethod);
                    $authRequest->setDomain($_POST['imbaSsoOpenIdLoginReferer']);
                    $managerAuthRequest->insert($authRequest);

                    /**
                     * try to do the first step of the openid authentication steps
                     */
                    writeAuthLog("Determing Auth style for " . $openid);

                    try {
                        $redirectUrl = $managerOpenId->openidAuth($openid, $authRequest->getHash(), $authRequest->getRealm(), $authRequest->getReturnTo());

                        if (!empty($redirectUrl)) {
                            /**
                             * we got a redirection url as answer. go there now!
                             */
                            $tmpMsg = writeAuthLog("OpenIdAuth redirecting to: " . $redirectUrl);

                            /**
                             * If this is set, the user will be sent to verification next time
                             */
//ImbaUserContext::setWaitingForVerify(ImbaSharedFunctions::getReturnTo());
                            ImbaUserContext::setWaitingForVerify(ImbaUserContext::getRedirectUrl());

                            redirectTo(__LINE__, $redirectUrl, $tmpMsg);
                            exit;
                        } else {
                            /**
                             * something went wrong. display error end exit
                             */
                            $tmpMsg = writeAuthLog("Special Error: Ehhrmm keine URL, weil ehhrmm", 0);
//header("Location: " . ImbaUserContext::getRedirectUrl());
                            redirectTo(__LINE__, ImbaUserContext::getRedirectUrl(), $tmpMsg);
                            exit;
                        }
                    } catch (Exception $ex) {
                        $tmpMsg = writeAuthLog("Authentification ERROR: " . $ex->getMessage() . " (" . $openid . ")", 1);
//header("Location: " . ImbaUserContext::getRedirectUrl());
                        redirectTo(__LINE__, ImbaUserContext::getRedirectUrl(), $tmpMsg);
                        exit;
                    }
                } else {
                    $tmpMsg = writeAuthLog("No OpenId submitted", 2);
//header("Location: " . ImbaUserContext::getRedirectUrl());
                    redirectTo(__LINE__, ImbaUserContext::getRedirectUrl(), $tmpMsg);
                    exit;
                }
                break;

            /**
             * Default auth type
             */
            default:
                $tmpMsg = writeAuthLog("No Authtype submitted");
                true;
        }

        /* header("Location: " . $_SERVER['HTTP_REFERER']); */
        redirectTo(__LINE__, $_SERVER['HTTP_REFERER'], $tmpMsg);
        exit;
    } else {
        /**
         * first step completed. do the verification and actual login
         * we shall go to the saved realm in the database after
         * we are finished here.
         */
        /**
         * Convert imbaHash from possible GET and POST to local var (proxy...)
         */
        if (!empty($_GET['imbaHash'])) {
            $imbaHash = $_GET['imbaHash'];
            unset($_GET['imbaHash']);
        } else if (!empty($_POST['imbaHash'])) {
            $imbaHash = $_POST['imbaHash'];
            unset($_POST['imbaHash']);
        } else {
//echo "blablal"; print_r($GLOBALS); exit;
            /**
             * We have no imbaHash, this is not good! kill yourself and go back where you came from
             */
            $tmpMsg = writeAuthLog("Morpheus, help! Forwarding to: " . ImbaSharedFunctions::getTrustRoot());
            /* header("Location: " . $_SERVER['HTTP_REFERER']); */
            ImbaUserContext::setWaitingForVerify("");
            redirectTo(__LINE__, ImbaSharedFunctions::getTrustRoot(), $tmpMsg);
            exit;
//throw new Exception("There was an error in descovering your auth request! Please reload the website.");
        }

        /**
         * Get the stored data for the current authrequest from the database
         */
        $authRequest = $managerAuthRequest->select($imbaHash);

        writeAuthLog("Verification starting", 2);
        try {
            $esc_identity = $managerOpenId->openidVerify($authRequest->gethash(), $authRequest->getRealm(), $authRequest->getReturnTo());
            if (empty($esc_identity)) {
                print_r($GLOBALS); exit;
                throw new Exception("OpenIdVerify failed! No Openid recieved from the OpenId Manager.");
            }
            writeAuthLog("OpenID Verification sucessful", 2);

            $currentUser = $managerUser->selectByOpenId($esc_identity);
            /**
             * Check the status of the user
             */
            if (empty($currentUser)) {
                /**
                 * This is a new user. let him register
                 */
                writeAuthLog("Registering new user", 2);

                if (!empty($esc_identity)) {
                    ImbaUserContext::setNeedToRegister(true);
                    ImbaUserContext::setOpenIdUrl($esc_identity);
                }
            } elseif ($currentUser->getRole() == 0) {
                /**
                 * this user is banned
                 */
                writeAuthLog($currentUser->getName() . " is banned but tried to login", 1);
                throw new Exception("You are Banned!");
            } elseif ($currentUser->getRole() != null) {
                /**
                 * this user is allowed to log in
                 */
                $tmpMsg = writeAuthLog($currentUser->getNickname() . " logged in", 1);

                ImbaUserContext::setLoggedIn(true);
                ImbaUserContext::setOpenIdUrl($esc_identity);
                ImbaUserContext::setUserRole($currentUser->getRole());
                ImbaUserContext::setUserId($currentUser->getId());

                setcookie("ImbaSsoLastLoginName", "", (time() - 3600));
                setcookie("ImbaSsoLastLoginName", $currentUser->getNickname(), (time() + (60 * 60 * 24 * 30)));

                $managerUser->setMeOnline();
                ImbaUserContext::setImbaErrorMessage("Du bist angemeldet als " . $currentUser->getNickname());
            }
            $myDomain = $authRequest->getDomain();
            if (!empty($myDomain)) {
                /* header("Location: " . $myDomain); */
                $managerAuthRequest->delete($imbaHash);
                redirectTo(__LINE__, $myDomain, $tmpMsg);
                exit;
            } else {
                $tmpUrl = ImbaUserContext::getWaitingForVerify();
                ImbaUserContext::setWaitingForVerify("");
                /* header("Location: " . $tmpUrl); */
                redirectTo(__LINE__, $tmpUrl, $tmpMsg);
                exit;
            }
        } catch (Exception $ex) {
            $esc_identity = $managerOpenId->getOpenId();

            $tmpUrl = ImbaUserContext::getWaitingForVerify();
            ImbaUserContext::setWaitingForVerify("");

            if ($ex->getMessage() == "id_res_not_set") {
                $tmpMsg = writeAuthLog("Aktuelle OpenID Anfrage ausgelaufen. Bitte nocheinmal von neuen probieren. (Hash: " . $imbaHash . ")");
            } else {
                $tmpMsg = writeAuthLog("Unnamed OpenID Verification ERROR (Hash: " . $imbaHash . "): " . $ex->getMessage(), 1);
            }
            $myDomain = $authRequest->getDomain();
            if (!empty($myDomain)) {
                /* header("Location: " . $authRequest->getDomain()); */
                //$managerAuthRequest->delete($imbaHash);
                redirectTo(__LINE__, $myDomain, $tmpMsg);
                exit;
            } else {
                $tmpUrl = ImbaUserContext::getWaitingForVerify();
                ImbaUserContext::setWaitingForVerify("");
                //$managerAuthRequest->delete($imbaHash);
                /* header("Location: " . $tmpUrl); */
                redirectTo(__LINE__, $tmpUrl, $tmpMsg);
                exit;
            }
        }
    }
    echo "we shall not get here ...";
    exit;
} else {
    ImbaUserContext::setWaitingForVerify("");
    /**
     * we are logged in! everithing is ok, we have a running session 
     * and we have a party here
     * - set cookie with logged in openid for autofill login box
     * - redirect back to page
     */
    writeAuthLog("Already logged in with: " . ImbaUserContext::getOpenIdUrl() . ")", 1);
    $tmpUrl = ImbaUserContext::getWaitingForVerify();
    $tmpMsg = ImbaUserContext::setWaitingForVerify("");
    /* header("Location: " . $tmpUrl); */
    redirectTo(__LINE__, $tmpUrl, $tmpMsg);
    exit;
}
/* header("Location: " . ImbaUserContext::getRedirectUrl()); */
redirectTo(__LINE__, ImbaUserContext::getRedirectUrl(), "We should never have gone so far...");
exit;
?>