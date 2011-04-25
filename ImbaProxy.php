<?php

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
 * Curl Magic
 */
//$headers = ($_POST['headers']);
//$mimeType =($_POST['mimeType']) ? $_POST['mimeType'] : $_GET['mimeType'];
//Start the Curl session
$session = curl_init($set['requestUrl']);

curl_setopt($session, CURLOPT_POST, true);
curl_setopt($session, CURLOPT_POSTFIELDS, $set['postvars']);
if (!empty($set['cookieFilePath'])) {
    curl_setopt($session, CURLOPT_COOKIEJAR, $set['cookieFilePath']);
    curl_setopt($session, CURLOPT_COOKIEFILE, $set['cookieFilePath']);
}
curl_setopt($session, CURLOPT_HEADER, true);
curl_setopt($session, CURLOPT_FOLLOWLOCATION, true);
//curl_setopt($session, CURLOPT_TIMEOUT, 5);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

// Make the call
$set['answer'] = curl_exec($session);
//$set['returnHeaders'] = curl_getinfo($session);
curl_close($session);

/**
 * Compute return
 */
list($set['answerHeaders'], $set['answerContent']) = explode("\r\n\r\n", $set['answer'], 2);

/**
 * generate output
 */
function displayDebug($set) {
    echo "<h2>Debug Info:</h2>";
    echo "requestUrl: " . $set['requestUrl'] . "<br />";
    echo "facility: " . $set['facility'] . "<br />";
    echo "cookieFile: " . $set['cookieFilePath'] . "<br />";
    echo "postvars: " . $set['postvars'] . "<br />";
    echo "response:" . $set['answer'] . "<br />";
    echo "<h3>returnHeaders:</h3><pre>";
    print_r($set['returnHeaders']);
    echo "PROXY SESSION ID: " . $mySession . "<br />";
    echo "Client session cookieTmpString: " . $set['cookieTmpString'] . "<br />";
    echo "Cookie File Path: " . $set['cookieFilePath'] . "<br />";
    echo "Cookie Content:<br /><pre>" . file_get_contents($set['cookieFilePath']) . "</pre><br />";
    echo "</pre><br />";
    echo "<h3>POST:</h3><pre>";
    print_r($_POST);
    echo "</pre>";
}

function returnError($message) {
    echo "Error:Proxy: " + $message;
}

/**
 * Setting up log output
 */
$tmpLogOut = "-------------------------------------------------------\n";
$tmpLogOut .= "facility: " . $set['facility'] . "\n";
$tmpLogOut .= "module: " . $_POST['action'] . "\n";
$tmpLogOut .= "request: " . $_POST['request'] . "\n";
$tmpLogOut .= "cookie: " . $mySession . "\n";

if ($set['facility'] == "test") {
    header("Set-Cookie: PHPSESSID=" . $mySession . "; path=/ ");
    echo "PROXY SESSION ID: " . $mySession . "<br />";
    echo "Cookie File Path: " . $set['cookieFilePath'] . "<br />";
    echo "Cookie Content:<br /><pre>" . file_get_contents($set['cookieFilePath']) . "</pre><br />";
    echo $set['answerContent'];
} elseif ($set['facility'] == "logout") {
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
    foreach (explode("\r\n", $set['answerHeaders']) as $hdr) {
        if (strpos($hdr, "PHPSESSID") == false) {
            header($hdr);
        }
    }
    if ($mySession != false) {
        header("Set-Cookie: PHPSESSID=" . $mySession . "; path=/ ");
    }
    if ($mySession != false) {
        //write requests without session to log
        foreach (explode("\n", $tmpLogOut) as $line) {
            ImbaSharedFunctions::writeToLog($line);
        }
    }
    echo $set['answerContent'];
} else {
    $tmpLogOut .= "ee: no return given (error)\n";
    ImbaSharedFunctions::writeToLog($tmpLogOut);
    if ($set['debug']) {
        displayDebug($set);
    } else {
        returnError("No data recieved");
    }
}
?>