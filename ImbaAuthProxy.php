<?php
session_start();
//print_r($GLOBALS); exit;
//require_once 'Libs/ajax-proxy/src/proxy.php';
require_once 'ImbaConstants.php';
require_once 'Controller/ImbaSharedFunctions.php';

$cookieFile = ImbaSharedFunctions::getTmpPath() . "/ImbaSession" . $_COOKIE['PHPSESSID'];

//FIXME: we possibly need a routing php script here! http://stackoverflow.com/questions/2106090/cross-domain-ajax-and-php-sessions
// for accessing ourself. we can find out when to direct with $_POST['imbaSsoOpenIdLoginReferer'] is = $_SERVER['SERVER_NAME']
// and then use curl to redirect our request
//ImbaSharedFunctions::getDomain();
//FIXME: load allowed hosts from portal aliases
//$allowedHosts = array('b.oom.ch', 'alptroeim.ch', 'localhost');
//$proxy = new AjaxProxy(ImbaConstants::$WEB_AJAX_MAIN_FILE, $allowedHosts, FALSE);
//$proxy->execute();


/**
 * REMEMBER!
 * we have to do also a logout!
 */



/* STEP 2. visit the homepage to set the cookie properly */

  $url = ImbaSharedFunctions::getTrustRoot() . "/" . ImbaConstants::$WEB_AJAX_PROXY_FILE;
  $ch = curl_init ($url);
  curl_setopt ($ch, CURLOPT_COOKIEJAR, );
  curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
  $output = curl_exec ($ch);

//echo ImbaSharedFunctions::getTrustRoot() . "/". ImbaConstants::$WEB_AUTH_MAIN_PATH; exit;
$url = ImbaSharedFunctions::getTrustRoot() . "/" . ImbaConstants::$WEB_AUTH_MAIN_PATH;
//$url = "http://alptroeim.ch/IMBAdmin/ImbaAjax.php";
$headers = ($_POST['headers']) ? $_POST['headers'] : $_GET['headers'];
//$mimeType =($_POST['mimeType']) ? $_POST['mimeType'] : $_GET['mimeType'];
//Start the Curl session
$session = curl_init($url);

// If it's a POST, put the POST data in the body
if ($_POST) {
    $postvars = '';
    while ($element = current($_POST)) {
        $postvars .= key($_POST) . '=' . $element . '&';
        next($_POST);
    }
    curl_setopt($session, CURLOPT_POST, true);
    curl_setopt($session, CURLOPT_POSTFIELDS, $postvars);
}

//curl_setopt($session, CURLOPT_COOKIEJAR, ImbaSharedFunctions::getTmpPath() . "/ImbaSession" . $_COOKIE['PHPSESSID']);
curl_setopt($session, CURLOPT_COOKIEFILE, $cookieFile);
//curl_setopt($session, CURLOPT_COOKIEFILE, "/tmp/cookieFileName");
// Don't return HTTP headers. Do return the contents of the call
curl_setopt($session, CURLOPT_HEADER, ($headers == "true") ? true : false);

curl_setopt($session, CURLOPT_FOLLOWLOCATION, true);
//curl_setopt($ch, CURLOPT_TIMEOUT, 4);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

// Make the call
$response = curl_exec($session);

if ($mimeType != "") {
    // The web service returns XML. Set the Content-Type appropriately
    //header("Content-Type: ".$mimeType);
}
if ($response) {
echo $response;
} else {
    echo "ERROR: \n<pre>";
    echo "cookie file: " . $cookieFile . "\n";
    print_r($GLOBALS);
    echo "</pre>";
}

curl_close($session);
?>
