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
$_SESSION[IUC_loggedIn] = "";


/**
 * Load dependencies
 */
require_once 'Constants.php';
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
$managerDatabase = new ImbaManagerDatabase(ImbaConstants::$DATABASE_HOST, ImbaConstants::$DATABASE_DB, ImbaConstants::$DATABASE_USER, ImbaConstants::$DATABASE_PASS);

/**
 * OpenID auth logic
 */
if (!ImbaUserContext::getLoggedIn()) {
    $redirectUrl = null;
    $formHtml = null;
    // TODO: evtl. braucht $policy_uris content und muss ein array sein
    $openid = $_GET["openid"];
    try {
        $managerOpenId->openidAuth($openid, $pape_policy_uris, $redirectUrl, $formHtml);
    } catch (Exception $ex) {
        echo "ERROR: " . $ex->getMessage();
    }
}
?>