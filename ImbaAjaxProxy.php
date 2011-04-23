<?php
//require_once 'Libs/ajax-proxy/src/proxy.php';
require_once 'ImbaConstants.php';

//FIXME: we possibly need a routing php script here! http://stackoverflow.com/questions/2106090/cross-domain-ajax-and-php-sessions
// for accessing ourself. we can find out when to direct with $_POST['imbaSsoOpenIdLoginReferer'] is = $_SERVER['SERVER_NAME']
// and then use curl to redirect our request
//ImbaSharedFunctions::getDomain();

//FIXME: load allowed hosts from portal aliases
//$allowedHosts = array('b.oom.ch', 'alptroeim.ch', 'localhost');

//$proxy = new AjaxProxy(ImbaConstants::$WEB_AJAX_MAIN_FILE, $allowedHosts, FALSE);
//$proxy->execute();

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, dirname($_SERVER['PHP_SELF']) . "/". ImbaConstants::$WEB_AJAX_MAIN_FILE); 
curl_setopt($ch, CURLOPT_HEADER, 1); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
//curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1); 
$data = curl_exec($ch); 
curl_close($ch);
echo $data;
?>
