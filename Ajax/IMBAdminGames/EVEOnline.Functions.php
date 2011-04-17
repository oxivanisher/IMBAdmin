<?php

/*
  Collection of Functions for EVE Online ingame
 */

require_once 'Model/ImbaUser.php';
require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerUser.php';
require_once 'Controller/ImbaManagerUserRole.php';
require_once 'Controller/ImbaUserContext.php';
require_once 'Controller/ImbaSharedFunctions.php';

function IGBAcces() {
    
    //$host_url = "http://".$_SERVER[SERVER_NAME]."/*";


    if ($_SERVER['HTTP_EVE_TRUSTED'] == "No") {
        echo '<h3>hi stranger!</h3>';
        echo 'To run this tool, please trust this page.';
        echo "<button type=\"button\" onclick=\"CCPEVE.requestTrust('" . ImbaSharedFunctions::getTrustRoot() . "')\">Trust Me!</button>";

        return 'untrusted';
    } else {
        echo 'GO FOR IT!!';
        return 'trusted';
    }
}

?>