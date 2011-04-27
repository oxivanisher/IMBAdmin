<?php

/*
  print_r(($_POST['headers']));
  print_r($GLOBALS);
  print_r(apache_request_headers());
  exit;
  //print_r($GLOBALS); exit;
 * 
 * 
 */
header('Access-Control-Allow-Origin: *');

require_once 'ImbaConstants.php';
require_once 'Controller/ImbaSharedFunctions.php';

session_start();
$mySession = false;
if (!empty($_COOKIE['secSession'])) {
    $mySession = $_COOKIE['secSession'];
} elseif (!empty($_POST['secSession'])) {
    $mySession = $_POST['secSession'];
} elseif (!empty($_COOKIE['PHPSESSID'])) {
    $mySession = $_COOKIE['PHPSESSID'];
}
if (empty($_SESSION['debugMode'])) {
    $_SESSION['debugMode'] = false;
}

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
 * Toggle debug mode 
 */
if ($_POST['toggleProxyDebug'] == "true") {
    if ($_SESSION['debugMode'] == "false") {
        $_SESSION['debugMode'] = "true";
        echo "Proxy Debug Enabled";
    } else {
        $_SESSION['debugMode'] = "false";
        echo "Proxy Debug Disabled";
    }
    echo "<br /><b>Somehow, I dont work!</b>";
    session_write_close();
    exit;
}

/**
 * Set debug mode depending on session
 */
if ($_SESSION['debugMode'] == "true") {
    $set['debug'] = "true";
} else {
    $set['debug'] = "false";
}

/**
 * Determine which file is our target $requestUrl
 */
if (!($_GET["logout"] == false && $_POST["logout"] == false)) {
    $set['facility'] = "logout";
}
$set['requestUrl'] = "";
if (empty($set['facility'])) {
    echo "ERROR:No facility recieved";
    exit;
} else {
    if ($set['facility'] == "ajax") {
        $set['requestUrl'] = ImbaSharedFunctions::getTrustRoot() . ImbaConstants::$WEB_AJAX_MAIN_PATH;
    } elseif ($set['facility'] == "auth") {
        $set['requestUrl'] = ImbaSharedFunctions::getTrustRoot() . ImbaConstants::$WEB_AUTH_MAIN_PATH;
    } elseif ($set['facility'] == "logout") {
        $set['requestUrl'] = ImbaSharedFunctions::getTrustRoot() . ImbaConstants::$WEB_AUTH_MAIN_PATH . "?logout=true";
    } elseif ($set['facility'] == "test") {
        $set['requestUrl'] = ImbaSharedFunctions::getTrustRoot() . "Tools/ProxySessionTest.php";
    } else {
        echo "Error:Unknown facility (" . $set['facility'] . ")";
        exit;
    }
}
if (empty($_POST) && (!empty($_GET))) {
    $_POST = $_GET;
}

/**
 * Set Cookie File Path with one session magic
 */
if ($mySession != false) {
    $set['cookieFilePath'] = ImbaSharedFunctions::getTmpPath() . "/ImbaSession-" . $mySession;
} else {
    $set['cookieFilePath'] = false;
}

/**
 * Create Post var
 */
$set['postvars'] = '';
while ($element = current($_POST)) {
    $set['postvars'] .= key($_POST) . '=' . $element . '&';
    next($_POST);
}

/**
 * Helper function for headers if we are not on a apache server
 * (who does such a thing??)
 */
if (!function_exists('apache_request_headers')) {

    function apache_request_headers() {
        $headers = array();
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))))] = $value;
            }
        }
        return $headers;
    }

}

/**
 * Prepare for possible ajax/jquery X-Requested-With:XMLHttpRequest
 */
$requestHeaders = array();
/* foreach (apache_request_headers() as $name => $value) {
  array_push($requestHeaders, $name . ": " . $value);
  if ($name == "X-Requested-With") {
  array_push($requestHeaders, $name . ": " . $value);
  $_POST['b'] = "b";
  }
 *
  } */
if (!empty($_POST['addToHeader:'])) {
    array_push($requestHeaders, $_POST['addToHeader:']);
}

/**
 * Curl Magic
 */
//$headers = ($_POST['headers']);
//$mimeType =($_POST['mimeType']) ? $_POST['mimeType'] : $_GET['mimeType'];
//Start the Curl session
$session = curl_init($set['requestUrl']);

if (!empty($set['cookieFilePath'])) {
    curl_setopt($session, CURLOPT_COOKIEJAR, $set['cookieFilePath']);
    curl_setopt($session, CURLOPT_COOKIEFILE, $set['cookieFilePath']);
}
curl_setopt($session, CURLOPT_HEADER, true);
curl_setopt($session, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

if ($requestHeaders) {
    curl_setopt($session, CURLOPT_HTTPHEADER, $requestHeaders);
}
curl_setopt($session, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
curl_setopt($session, CURLOPT_REFERER, $_SERVER["HTTP_REFERER"]);

curl_setopt($session, CURLOPT_POST, true);
curl_setopt($session, CURLOPT_POSTFIELDS, $set['postvars']);

//X-Requested-With:XMLHttpRequest
//http_get_request_headers
//curl_setopt($session, CURLOPT_ENCODING, "");
//curl_setopt($session, CURLINFO_HEADER_OUT, true);
//curl_setopt($session, CURLOPT_TIMEOUT, 5);
// Make the call
$set['answer'] = curl_exec($session);
//$set['returnHeaders'] = curl_getinfo($session);
curl_close($session);

/**
 * Compute return
 */
list($set['answerHeaders'], $set['answerContent']) = explode("\r\n\r\n", $set['answer'], 2);

/**
 * Setting up log output
 */
$tmpLogOut .= "facility  : " . $set['facility'] . "\n";
$tmpLogOut .= "headSize  : " . strlen($set['answerHeaders']) . "\n";
$tmpLogOut .= "bodySize  : " . strlen($set['answerContent']) . "\n";
$tmpLogOut .= "------------------------------- request header -------------------------------\n";
foreach ($requestHeaders as $header) {
    $tmpLogOut .= $header . "\n";
}
$tmpLogOut .= "-------------------------------  request data  -------------------------------\n";
foreach ($_GET as $key => $value) {
    $tmpLogOut .= "GETDATA  : " . $key . " => " . $value . "\n";
}
foreach ($_POST as $key => $value) {
    $tmpLogOut .= "POSTDATA  : " . $key . " => " . $value . "\n";
}
$tmpLogOut .= "------------------------------- return  header -------------------------------\n";
$tmpLogOut .= $set['answerHeaders'] . "\n";
$tmpLogOut .= "-------------------------------  return  body  -------------------------------\n";
$tmpLogOut .= $set['answerContent'] . "\n";

/**
 * generate output
 */
function returnError($message) {
    echo "Error:Proxy: " + $message;
}

if ($set['facility'] == "test") {
    /**
     * Test module
     */
    header("Set-Cookie: PHPSESSID=" . $mySession . "; path=/ ");
    echo "PROXY SESSION ID: " . $mySession . "<br />";
    echo "Cookie File Path: " . $set['cookieFilePath'] . "<br />";
    echo "Cookie Content:<br /><pre>" . file_get_contents($set['cookieFilePath']) . "</pre><br />";
    echo $set['answerContent'];
} elseif ($set['facility'] == "logout") {
    /**
     * logout
     */
    unlink($set['cookieFilePath']);
    unset($set['cookieFilePath']);
    setcookie("PHPSESSID", "", time() - 3600);
    session_destroy();
    session_write_close();
    foreach (explode("\r\n", $set['answerHeaders']) as $hdr) {
        if (strpos($hdr, "PHPSESSID") == false) {
            header($hdr);
        }
    }
    echo $set['answerContent'];
} elseif ($set['answer']) {
    if (($_POST['action'] != "messenger") && ($_POST['action'] != "user")) {
        ImbaSharedFunctions::writeProxyLog($tmpLogOut);
    }
    if (empty($mySession)) {
        $tmpLogOut .= "ee: no session found (error)\n";
        ImbaSharedFunctions::writeProxyLog($tmpLogOut);
    }
    /**
     * normal proxy return
     */
    $contentType = "Content-Type: text/html";
    foreach (explode("\r\n", $set['answerHeaders']) as $hdr) {
        if (strpos($hdr, "PHPSESSID") == false) {
            if ($hdr == "Transfer-Encoding: chunked") {
                header("Content-Length: " . strlen($set['answerContent']));
            } elseif (strpos($hdr, "ontent-Type")) { //there has to be a missing C !
                $contentType = $hdr;
            } else {
                header($hdr);
            }
        }
    }
    header($contentType);

    if ($mySession != false) {
        header("Set-Cookie: PHPSESSID=" . $mySession . "; path=/ ");
    }
    echo $set['answerContent'];
    exit;
} else {
    /**
     * no return found
     */
    $tmpLogOut .= "ee: no return received (error)\n";
    ImbaSharedFunctions::writeProxyLog($tmpLogOut);
    returnError("No data recieved");
}
?>