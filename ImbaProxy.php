<?php
header('Access-Control-Allow-Origin: *');
session_start();

/**
 * Determine which is our facility
 */
$set['facility'] = "";
if (empty($_POST['facility'])) {
    if (!empty($_GET['facility'])) {
        $set['facility'] = $_GET['facility'];
        unset($_GET['facility']);
    }
} else {
    $set['facility'] = $_POST['facility'];
    unset($_POST['facility']);
}

/**
 * Are we in debug mode?
 */
$set['debug'] = false;
if (empty($_POST['proxyDebug'])) {
    if (!empty($_GET['proxyDebug'])) {
        $set['debug'] = true;
        unset($_GET['proxyDebug']);
    }
} else {
    $set['debug'] = true;
    unset($_POST['proxyDebug']);
}

/**
 * Determine which file is our target $requestUrl
 */
$set['requestUrl'] = "";
if (empty($set['facility'])) {
    echo "ERROR: No facility recieved";
    exit;
} else {
    require_once 'ImbaConstants.php';
    require_once 'Controller/ImbaSharedFunctions.php';
    if ($set['facility'] == "ajax") {
        $set['requestUrl'] = ImbaSharedFunctions::getTrustRoot() . ImbaConstants::$WEB_AJAX_MAIN_FILE;
    } elseif ($set['facility'] == "auth") {
        $set['debug'] = true;
        $set['requestUrl'] = ImbaSharedFunctions::getTrustRoot() . ImbaConstants::$WEB_AUTH_MAIN_PATH;
    } else {
        echo "ERROR: Unknown facility (" . $set['facility'] . ")";
        exit;
    }
}
if (empty($_POST) && (!empty($_GET))) {
    $_POST = $_GET;
}


/**
 * generate cookie data for sending
 */
$set['cookieSendData'] = "";
if (!empty($_SESSION['IUC_cookieStore'])) {
    foreach ($_SESSION['IUC_cookieStore'] as $key => $value) {
        $set['cookieSendData'] .= $key . "=" . $value . ";";
    }
}

/**
 * Set Cookie File
 */
$set['cookieFile'] = ImbaSharedFunctions::getTmpPath() . "/ImbaSession" . $_COOKIE['PHPSESSID'];

/**
 * Create Post var
 */
$set['postvars'] = '';
while ($element = current($_POST)) {
    $set['postvars'] .= key($_POST) . '=' . $element . '&';
    next($_POST);
}


/**
 * Curl Magic
 */
//$headers = ($_POST['headers']);
//$mimeType =($_POST['mimeType']) ? $_POST['mimeType'] : $_GET['mimeType'];
//Start the Curl session
$session = curl_init($set['requestUrl']);

curl_setopt($session, CURLOPT_POST, true);
curl_setopt($session, CURLOPT_POSTFIELDS, $set['postvars']);

if (empty($set['cookieSendData'])) {
    curl_setopt($session, CURLOPT_COOKIEJAR, $set['cookieFile']);
} else {
    curl_setopt($session, CURLOPT_COOKIEFILE, $set['cookieFile']);
}
curl_setopt($session, CURLOPT_HEADER, true);
curl_setopt($session, CURLOPT_FOLLOWLOCATION, true);
//curl_setopt($ch, CURLOPT_TIMEOUT, 4);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

// Make the call
$set['response'] = curl_exec($session);
$set['returnHeaders'] = curl_getinfo($session);
curl_close($session);

if ($mimeType != "") {
    // The web service returns XML. Set the Content-Type appropriately
    //header("Content-Type: ".$mimeType);
}

/**
 * Compute return
 */
function displayDebug($set) {
    echo "Error:";
    echo "<h2>Debug Info:</h2>";
    echo "debug: " . $set['debug'] . "<br />";
    echo "cookieFile: " . $set['cookieFile'] . "<br />";
    echo "cookieSendData: " . $set['cookieSendData'] . "<br />";
    echo "postvars: " . $set['postvars'] . "<br />";
    echo "requestUrl: " . $set['requestUrl'] . "<br />";
    echo "facility: " . $set['facility'] . "<br />";
    echo "response:" . $set['response'] . "<br />";
    echo "<h3>returnHeaders:</h3><pre>" . print_r($set['returnHeaders']) . "</pre><br />";
    echo "<h3>GLOBALS:</h3><pre>" . print_r($GLOBALS) . "</pre>";
}

function returnError($set) {
    echo "Error:Proxy not working";
}

if ($set['debug']) {
    displayDebug($set);
} elseif ($set['response']) {
    echo $set['returnHeaders'];
    echo $set['response'];
} else {
    returnError($set);
}















/**
 * REMEMBER!
 * we have to do also a logout!
 */
/* logout cookie and session destroy
 * 
  setcookie(session_id(), "", time() - 3600);
  session_destroy();
  session_write_close();
 * 
 */

//FIXME: we possibly need a routing php script here! http://stackoverflow.com/questions/2106090/cross-domain-ajax-and-php-sessions
// for accessing ourself. we can find out when to direct with $_POST['imbaSsoOpenIdLoginReferer'] is = $_SERVER['SERVER_NAME']
// and then use curl to redirect our request
//ImbaSharedFunctions::getDomain();
//FIXME: load allowed hosts from portal aliases
//$allowedHosts = array('b.oom.ch', 'alptroeim.ch', 'localhost');
//$proxy = new AjaxProxy(ImbaConstants::$WEB_AJAX_MAIN_FILE, $allowedHosts, FALSE);
//$proxy->execute();




/* STEP 2. visit the homepage to set the cookie properly */
/*
  $url = ImbaSharedFunctions::getTrustRoot() . "/" . ImbaConstants::$WEB_AJAX_PROXY_FILE;
  $ch = curl_init ($url);
  curl_setopt ($ch, CURLOPT_COOKIEJAR, );
  curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
  $output = curl_exec ($ch);
 */
//echo ImbaSharedFunctions::getTrustRoot() . "/". ImbaConstants::$WEB_AUTH_MAIN_PATH; exit;
?>
