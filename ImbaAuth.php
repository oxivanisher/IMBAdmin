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
require_once 'Controller/ImbaLogger.php';
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
$managerLog = ImbaLogger::getInstance();

/**
 * OpenID auth logic
 */
if ($_GET["logout"] == true || $_POST["logout"] == true) {
    /**
     * we want to log out
     */
    ImbaSharedFunctions::killCookies();
    setcookie(session_id(), "", time() - 3600);
    session_destroy();
    session_write_close();

    $log = $managerLog->getNew();
    $log->setModule("Auth");
    $log->setMessage("Logging out");
    $log->setLevel(2);
    $managerLog->insert($log);

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
            $log->setMessage("Redirecting");
            $log->setLevel(2);
            $managerLog->insert($log);

            try {
                $managerOpenId->openidAuth($openid, $pape_policy_uris, $redirectUrl, $formHtml);

                if (!empty($redirectUrl)) {
                    /**
                     * we got a redirection url as answer. go there now!
                     */
                    header("Location: " . $redirectUrl);
                } elseif (!empty($formHtml)) {
                    /**
                     * we get a html form as answer. display it
                     * TODO: make it autosubmit
                     */
                    echo "<html><head><title>" . ImbaConstants::$CONTEXT_SITE_TITLE . " redirecting...</title></head>";
                    echo "<body onload='submitForm()'><h2>Redirecting...</h2>";
                    echo "Please submit the following form:<br />";
                    echo $formHtml;
                    echo "<script type='text/javascript'>document.openid_message.submit();</script>";
                    echo "</body></html>";
                } else {
                    /**
                     * something went wrong. display error end exit
                     */
                    echo "Ehhrmm keine URL, weil ehhrmm.";
                    exit;
                }
            } catch (Exception $ex) {
                echo "openidAuth ERROR: " . $ex->getMessage() . " (" . $openid . ")";
            }
        }
    } else {
        /**
         * first step completed. do the verification
         */
        $log = $managerLog->getNew();
        $log->setModule("Auth");
        $log->setMessage("Verification");
        $log->setLevel(2);
        $managerLog->insert($log);

        try {
            $managerOpenId->openidVerify();
            header("location: " . $_SERVER["PHP_SELF"]);
        } catch (Exception $ex) {
            echo "openidVerify ERROR: " . $ex->getMessage();
        }
    }
} else {
    /**
     * we are logged in! everithing is ok and we have a party here
     * - set cookie with logged in openid for autofill login box
     * - redirect back to page
     */
    $log = $managerLog->getNew();
    $log->setModule("Auth");
    $log->setMessage("Logged in");
    $log->setLevel(2);
    $managerLog->insert($log);

    setcookie("ImbaSsoLastLoginName", "", (time() - 3600));
    setcookie("ImbaSsoLastLoginName", $_SESSION["IUC_openIdUrl"], (time() + (60 * 60 * 24 * 30)));
    header("location: index.html");
}

// generate runtime output
$m_time = explode(" ", microtime());
$totaltime = (($m_time[0] + $m_time[1]) - $starttime);
echo "<br /><br /><a href='" . ImbaConstants::$WEB_SITE_PATH . "/" . ImbaConstants::$WEB_ENTRY_INDEX_FILE . "'>Back to Index</a>";
echo "<hr /><center>Page loading took:" . round($totaltime, 3) . " seconds</center><br /><br /></div>";
echo "</body>\n</html>";
?>