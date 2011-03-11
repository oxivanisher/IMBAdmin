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
require_once 'Controller/ImbaManagerDatabase.php';
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
$managerDatabase = ImbaManagerDatabase::getInstance(ImbaConfig::$DATABASE_HOST, ImbaConfig::$DATABASE_DB, ImbaConfig::$DATABASE_USER, ImbaConfig::$DATABASE_PASS);

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
             * try to do the forst step of the openid authentication
             */
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
        try {
            $managerOpenId->openidVerify($managerDatabase);
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
    setcookie("ImbaSsoLastLoginName", $_SESSION["IUC_openIdUrl"]);
    header("location: index.html");
}

// generate runtime output
$m_time = explode(" ", microtime());
$totaltime = (($m_time[0] + $m_time[1]) - $starttime);
echo "<br /><br /><a href='" . ImbaConstants::$WEB_SITE_PATH . "/" . ImbaConstants::$WEB_ENTRY_INDEX_FILE . "'>Back to Index</a>";
echo "<hr /><center>Page loading took:" . round($totaltime, 3) . " seconds</center><br /><br /></div>";
echo "</body>\n</html>";
?>