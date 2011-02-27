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
require_once "Constants.php";
require_once 'Controller/ImbaManagerDatabase.php';
require_once 'Controller/ImbaManagerOpenID.php';
require_once 'Controller/ImbaUserContext.php';
require_once 'Controller/ImbaSharedFunctions.php';
require_once 'Model/ImbaUser.php';
require_once 'Model/ImbaUserRole.php';

/**
 * PAPE policy URIs
 */
global $pape_policy_uris;
$pape_policy_uris = array(
    PAPE_AUTH_MULTI_FACTOR_PHYSICAL,
    PAPE_AUTH_MULTI_FACTOR,
    PAPE_AUTH_PHISHING_RESISTANT
);

/**
 * Prepare variables and objects
 */
$managerOpenId = new ImbaManagerOpenID();
$managerDatabase = new ImbaManagerDatabase(ImbaConfig::$DATABASE_HOST, ImbaConfig::$DATABASE_DB, ImbaConfig::$DATABASE_USER, ImbaConfig::$DATABASE_PASS);

/**
 * OpenID auth logic
 */
if ($_GET["logout"] == true) {
    ImbaSharedFunctions::killCookies();
    setcookie(session_id(), "", time() - 3600);
    session_destroy();
    session_write_close();
    header("location: " . $_SERVER["PHP_SELF"]);
} elseif (!ImbaUserContext::getLoggedIn()) {
    if ($_GET["authDone"] != true) {
        if (!empty($_GET["openid"])) {
            $redirectUrl = null;
            $formHtml = null;
            $openid = $_GET["openid"];
            try {
                $managerOpenId->openidAuth($openid, $pape_policy_uris, $redirectUrl, $formHtml);
                if (!empty($redirectUrl)) {
                    header("Location: " . $redirectUrl);
                } else {
                    echo "Ehhrmm keine URL, weil ehhrmm.";
                }
            } catch (Exception $ex) {
                echo "ERROR: " . $ex->getMessage();
            }
        }
    } else {
        try {
            $managerOpenId->openidVerify($managerDatabase);
            header("location: " . $_SERVER["PHP_SELF"]);
        } catch (Exception $ex) {
            echo "ERROR: " . $ex->getMessage();
        }
    }
} else {
    echo "logged in! :DD";
    echo "<br /><a href='?logout=true'>Logout</a>";
}
?>

<br />
<a href="http://turak/IMBAdmin/?openid=https://oom.ch/openid/identity/oxi">Cernu</a>
<br />
<a href="http://sampit-pc/IMBAdmin/?openid=http://openid-provider.appspot.com/Steffen.So@googlemail.com">Aggravate</a>
<br />

<?php
// generate runtime output
$m_time = explode(" ", microtime());
$totaltime = (($m_time[0] + $m_time[1]) - $starttime);
echo "<hr /><center>Page loading took:" . round($totaltime, 3) . " seconds</center><br /><br /></div>";
echo "</body>\n</html>";
?>
