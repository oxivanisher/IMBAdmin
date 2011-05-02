<?php

header('Access-Control-Allow-Origin: *');

require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerAuthRequest.php';
require_once 'Controller/ImbaSharedFunctions.php';

session_start();
$mySession = false;

/**
 * Default content type
 */
$contentType = "Content-Type: text/html";

/**
 * Merging the _GET into _POST and get rid of _GET
 */
$_POST = array_merge($_POST, $_GET);
unset($_GET);

/**
 * discover my PHP Session id at Trust Root Host
 */
if (!empty($_POST['imbaHash'])) {

    /**
     * check for imbaHash and load this session when no secSession or PHPSESSID is present
     */
    $managerAuthRequest = ImbaManagerAuthRequest::getInstance();
    $authRequest = $managerAuthRequest->select($_POST['imbaHash']);
    $mySession = $authRequest->getPhpsession();
} elseif (!empty($_COOKIE['secSession'])) {
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
 * Determine which is our facility (ajax/auth)
 */
$set['facility'] = $_POST['facility'];
unset($_POST['facility']);

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
if (!($_POST["logout"] == false)) {
    $set['facility'] = "logout";
}

$set['requestUrl'] = "";
if (empty($set['facility'])) {
    echo "Error:No facility recieved";
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
array_unique($_POST);
$set['postvars'] = '';
while ($element = current($_POST)) {
    $set['postvars'] .= key($_POST) . '=' . $element . '&';
    next($_POST);
}

/**
 * Prepare for possible ajax/jquery X-Requested-With:XMLHttpRequest
 * -> temp code. no impact
 */
$requestHeaders = array();
if (!empty($_POST['addToHeader'])) {
    array_push($requestHeaders, $_POST['addToHeader']);
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
curl_close($session);

/**
 * Compute return
 */
list($set['answerHeaders'], $set['answerContent']) = explode("\r\n\r\n", $set['answer'], 2);

/**
 * normal proxy return headers (prepare return headers)
 */
$lockedContentType = false;
$set['answerHeadersArray'] = array();
foreach (explode("\r\n", $set['answerHeaders']) as $hdr) {
    if (strpos($hdr, "PHPSESSID") == false) {
        /**
         * if it is NOT a phpsessid cookie, do:
         */
        if ($hdr == "Transfer-Encoding: chunked") {
            /**
             * the server return was chunked. curl fixed that for us,
             * so we have to set the
             */
            array_push($set['answerHeadersArray'], "Content-Length: " . strlen($set['answerContent']));
            $contentType = "Content-Type: text/html";
            $lockedContentType = true;
        } elseif (strpos($hdr, "ontent-Type")) { //there has to be a missing C !
            /**
             * Server side set content type, check if we are allowed to overwrite
             * our default and set it.
             */
            if ($lockedContentType != true) {
                $contentType = $hdr;
            }
        } else {
            /**
             * non-special header information. just pass it trough
             */
            array_push($set['answerHeadersArray'], $hdr);
        }
    }
}

/**
 * Set the content type of the request
 */
array_push($set['answerHeadersArray'], $contentType);

/**
 * If available, set our phpsession on the client
 */
if ($mySession != false) {
    array_push($set['answerHeadersArray'], "Set-Cookie: PHPSESSID=" . $mySession . "; path=/ ");
}

/**
 * Setting up log output
 */
$tmpLogOut .= "facility  : " . $set['facility'] . "\n";
$tmpLogOut .= "return headSize  : " . strlen($set['answerHeaders']) . "\n";
$tmpLogOut .= "return bodySize  : " . strlen($set['answerContent']) . "\n";
$tmpLogOut .= "---------------------------- in  request data  -------------------------------\n";
foreach ($_POST as $key => $value)
    $tmpLogOut .= $key . " => " . $value . "\n";
$tmpLogOut .= "--------------------------- out request header -------------------------------\n";
foreach ($requestHeaders as $header)
    $tmpLogOut .= $header . "\n";
$tmpLogOut .= "--------------------------- out  request data  -------------------------------\n";
$tmpLogOut .= str_replace("&", "\n", $set['postvars']);
$tmpLogOut .= "------------------------------- return  header -------------------------------\n";
foreach ($set['answerHeadersArray'] as $header) 
    $tmpLogOut .= $header . "\n";
//$tmpLogOut .= "-------------------------------  return  body  -------------------------------\n";
//$tmpLogOut .= $set['answerContent'] . "\n";
$tmpLogOut .= "\n";

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
    if (($_POST['action'] != "messenger") && ($_POST['action'] != "user") && ($set['facility'] == "auth")) {
        ImbaSharedFunctions::writeProxyLog($tmpLogOut);
    }
    /*
     * if (empty($mySession)) {
     *
      $tmpLogOut .= "ee: no session found (error)\n";
      ImbaSharedFunctions::writeProxyLog($tmpLogOut);
      }
     * 
     */
    /**
     * Display the headers
     */
    foreach ($set['answerHeadersArray'] as $header) {
        header($header);
    }

    /**
     * display the body (html /json content)
     */
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



/**
 * TMP STUFF. DELETE AS SOON AS PROXY WORKS
 * 

  /**
 * Helper function for headers if we are not on a apache server
 * (who does such a thing??)
 *
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
 */
?>