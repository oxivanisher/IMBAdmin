<?php

/**
 * get start time of script
 */
$m_time = explode(" ", microtime());
$m_time = $m_time[0] + $m_time[1];
$starttime = $m_time;

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
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaUserContext.php';
require_once 'Controller/ImbaSharedFunctions.php';
require_once 'Model/ImbaUser.php';
require_once 'Model/ImbaUserRole.php';

/**
 * PAPE policy URIs
 */
global $pape_policy_uris;
$pape_policy_uris = array(
        //PAPE_AUTH_MULTI_FACTOR_PHYSICAL,
        //PAPE_AUTH_MULTI_FACTOR,
        //PAPE_AUTH_PHISHING_RESISTANT
);

/**
 * Prepare variables and objects
 */
$managerOpenId = new ImbaManagerOpenID();

/**
 * Load the logger
 */
$managerLog = ImbaManagerLog::getInstance();

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

    //ImbaSharedFunctions::killCookies();
    setcookie(session_id(), "", time() - 3600);
    session_destroy();
    session_write_close();

    header("location: " . ImbaConstants::$WEB_SITE_PATH . "/" . ImbaConstants::$WEB_ENTRY_INDEX_FILE);
} elseif (!ImbaUserContext::getLoggedIn()) {
    /**
     * we are NOT logged in
     */
    if ($_GET["authDone"] != true) {
        if (!empty($_GET["openid"])) {
            $redirectUrl = null;
            $formHtml = null;
            $openid = $_GET["openid"];
            /**
             * try to do the first step of the openid authentication steps
             */
            $log = $managerLog->getNew();
            $log->setModule("Auth");
            $log->setMessage("Determing Auth style for " . $openid);
            $log->setLevel(2);
            $managerLog->insert($log);

            $log = $managerLog->getNew();
            $log->setModule("Auth");

            try {
                $managerOpenId->openidAuth($openid, $pape_policy_uris, $redirectUrl, $formHtml);

                if (!empty($redirectUrl)) {
                    /**
                     * we got a redirection url as answer. go there now!
                     */
                    $log->setLevel(2);
                    $log->setMessage("Redirecting to: " . $redirectUrl);
                    $managerLog->insert($log);
                    header("Location: " . $redirectUrl);
                } elseif (!empty($formHtml)) {
                    /**
                     * we get a html form as answer. display it
                     * TODO: make it autosubmit
                     */
                    $log->setLevel(2);
                    $log->setMessage("Redirecting trough HTML form");
                    $managerLog->insert($log);
                    
                    $smarty = ImbaSharedFunctions::newSmarty();
                    $smarty->assign('siteTitle', ImbaConstants::$CONTEXT_SITE_TITLE);
                    $smarty->assign('formHtml', $formHtml);
/*                    echo "<html><head><title>" . ImbaConstants::$CONTEXT_SITE_TITLE . " redirecting...</title></head>";
                    echo "<body onload='submitForm()'><h2>Redirecting...</h2>";
                    echo "Please submit the following form:<br />";
                    echo $formHtml;
                    echo "<script type='text/javascript'>document.openid_message.submit();</script>";
                    echo "</body></html>"; */
                    $smarty->display('ImbaRedirect.tpl');
                } else {
                    /**
                     * something went wrong. display error end exit
                     */
                    $log->setLevel(0);
                    $log->setMessage("Special Error: Ehhrmm keine URL, weil ehhrmm");
                    $managerLog->insert($log);
                    exit;
                }
            } catch (Exception $ex) {
                $log->setLevel(1);
                $log->setMessage("openidAuth ERROR: " . $ex->getMessage() . " (" . $openid . ")");
                $managerLog->insert($log);
                echo $log->getMessage();
            }
        } else {
            $log = $managerLog->getNew();
            $log->setModule("Auth");
            $log->setMessage("No OpenId submitted");
            $log->setLevel(2);
            $managerLog->insert($log);

            header("location: index.html");
        }
    } else {
        /**
         * first step completed. do the verification
         */
        $log = $managerLog->getNew();
        $log->setModule("Auth");
        $log->setMessage("Verification starting");
        $log->setLevel(2);
        $managerLog->insert($log);

        $log = $managerLog->getNew();
        $log->setModule("Auth");

        try {
            $esc_identity = $managerOpenId->openidVerify();

            $log->setLevel(2);
            $log->setMessage("OpenID Verification sucessful");
            $managerLog->insert($log);

            $userManager = ImbaManagerUser::getInstance();
            $currentUser = $userManager->selectByOpenId($esc_identity);

            /**
             * Check the status of the user
             */
            if ($currentUser == null) {
                /**
                 * This is a new user. let him register
                 */
                $log = $managerLog->getNew();
                $log->setModule("Auth");
                $log->setMessage("Registering new user");
                $log->setLevel(2);
                $managerLog->insert($log);

                ImbaUserContext::setNeedToRegister(true);
                ImbaUserContext::setOpenIdUrl($esc_identity);
                
                header("location: " . ImbaConstants::$WEB_ENTRY_INDEX_FILE);
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
                $userManager->setMeOnline();
            }

            header("location: " . $_SERVER["PHP_SELF"]);
        } catch (Exception $ex) {
            $log->setLevel(1);
            $log->setMessage("OpenID Verification ERROR: " . $ex->getMessage());
            $managerLog->insert($log);
            echo $log->getMessage();
        }
    }
} else {
    /**
     * we are logged in! everithing is ok and we have a party here
     * - set cookie with logged in openid for autofill login box
     * - redirect back to page
     */
    /**
     * FIXME: we need to check if the session is still good. we get logged in but should fell offline sometimes
     */
    $log = $managerLog->getNew();
    $log->setModule("Auth");
    $log->setMessage("Final redirection (Logged in with: " . ImbaUserContext::getOpenIdUrl() . ")");
    $log->setLevel(1);
    $managerLog->insert($log);

    setcookie("ImbaSsoLastLoginName", "", (time() - 3600));
    setcookie("ImbaSsoLastLoginName", $_SESSION["IUC_openIdUrl"], (time() + (60 * 60 * 24 * 30)));
    header("location: " . ImbaConstants::$WEB_ENTRY_INDEX_FILE);
}

// generate runtime output
$m_time = explode(" ", microtime());
$totaltime = (($m_time[0] + $m_time[1]) - $starttime);
echo "<br /><br /><a href='" . ImbaConstants::$WEB_SITE_PATH . "/" . ImbaConstants::$WEB_ENTRY_INDEX_FILE . "'>Back to Index</a>";
echo "<hr /><center>Page loading took:" . round($totaltime, 3) . " seconds</center><br /><br /></div>";
echo "</body>\n</html>";
?>