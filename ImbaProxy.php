<?php
header('Access-Control-Allow-Origin: *');
session_start();

ImbaSharedFunctions::writeToLog(var_dump($_SESSION));

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
    require_once 'ImbaConstants.php';
    require_once 'Controller/ImbaSharedFunctions.php';
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
 * Link sessions between browsers together like magic
 * - one cookie store file for $_COOKIE['ImbaProxySessionId']
 */
/*
  if (!empty($_COOKIE['ImbaProxySessionId'])) {
  $_SESSION['cookieTmpString'] = $_COOKIE['ImbaProxySessionId'];
  } else if (empty($_COOKIE['ImbaProxySessionId'])) {
  $_SESSION['cookieTmpString'] = md5($_COOKIE['PHPSESSID'] . time() . rand(1, 9999999999));
  } else if ($_COOKIE['ImbaProxySessionId'] != $_SESSION['cookieTmpString']) {
  $_SESSION['cookieTmpString'] = $_COOKIE['ImbaProxySessionId'];
  }

  /**
 * Set Cookie File Path with one session magic
 */
ImbaSharedFunctions::writeToLog("my: " . session_id());
$_SESSION['cookieFilePath'] = ImbaSharedFunctions::getTmpPath() . "/ImbaSession-" . session_id();
// setcookie("ImbaProxySessionId", "", (time() - 3600));

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
curl_setopt($session, CURLOPT_COOKIEJAR, $_SESSION['cookieFilePath']);
curl_setopt($session, CURLOPT_COOKIEFILE, $_SESSION['cookieFilePath']);
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
    echo "cookieFile: " . $_SESSION['cookieFilePath'] . "<br />";
    echo "postvars: " . $set['postvars'] . "<br />";
    echo "response:" . $set['answer'] . "<br />";
    echo "<h3>returnHeaders:</h3><pre>";
    print_r($set['returnHeaders']);
    echo "PROXY SESSION ID: " . session_id() . "<br />";
    echo "Client cookie ImbaProxySessionId: " . $_COOKIE['ImbaProxySessionId'] . "<br />";
    echo "Client session cookieTmpString: " . $_SESSION['cookieTmpString'] . "<br />";
    echo "Cookie File Path: " . $_SESSION['cookieFilePath'] . "<br />";
    echo "Cookie Content:<br /><pre>" . file_get_contents($_SESSION['cookieFilePath']) . "</pre><br />";
    echo "</pre><br />";
    echo "<h3>POST:</h3><pre>";
    print_r($_POST);
    echo "</pre>";
}

function returnError($message) {
    echo "Error:Proxy: " + $message;
}

if ($set['facility'] == "test") {
    setcookie("ImbaProxySessionId", $_SESSION['cookieTmpString'], (time() + (60 * 60 * 24 * 30)));
    echo "PROXY SESSION ID: " . session_id() . "<br />";
    echo "Client cookie ImbaProxySessionId: " . $_COOKIE['ImbaProxySessionId'] . "<br />";
    echo "Client session cookieTmpString: " . $_SESSION['cookieTmpString'] . "<br />";
    echo "Cookie File Path: " . $_SESSION['cookieFilePath'] . "<br />";
    echo "Cookie Content:<br /><pre>" . file_get_contents($_SESSION['cookieFilePath']) . "</pre><br />";
    echo $set['answerContent'];
} elseif ($set['facility'] == "logout") {
    setcookie("ImbaProxySessionId", "", (time() - 3600));
    unlink($_SESSION['cookieFilePath']);
    unset($_SESSION['cookieTmpString']);
    setcookie(session_id(), "", time() - 3600);
    session_destroy();
    session_write_close();
} elseif ($set['answer']) {
    ImbaSharedFunctions::writeToLog("ou: " . session_id());
    foreach (explode("\r\n", $set['answerHeaders']) as $hdr) {
        if (strpos($hdr, "PHPSESSID") == false) {
            header($hdr);
        } else {
            header("Set-Cookie: PHPSESSID=".session_id()."; path=/ ");
            ImbaSharedFunctions::writeToLog("hd: " . session_id());
        }
    }
    setcookie("ImbaProxySessionId", session_id(), (time() + (60 * 60 * 24 * 30)));
    echo $set['answerContent'];
} else {
    if ($set['debug']) {
        displayDebug($set);
    } else {
        returnError("No data recieved");
    }
}
?>