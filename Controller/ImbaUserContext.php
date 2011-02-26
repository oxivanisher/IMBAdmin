<?php

/**
 * Description of ImbaUserContext
 *
 * stores the user context
 */
class ImbaUserContext {

    //user session basics
    public static function getLoggedIn() {
        return $SESSION["IUC_loggedIn"];
    }

    public static function setLoggedIn($loggedIn) {
        $SESSION["IUC_loggedIn"] = $loggedIn;
    }

    public static function getRedirectUrl() {
        return $SESSION["IUC_redirectUrl"];
    }

    public static function setRedirectUrl($redirectUrl) {
        $SESSION["IUC_redirectUrl"] = $redirectUrl;
    }

    public static function getStatusOfRegistration() {
        return $SESSION["IUC_statusOfRegistration"];
    }

    public static function setStatusOfRegistration($statusOfRegistration) {
        $SESSION["IUC_statusOfRegistration"] = $statusOfRegistration;
    }

    public static function getOpenIdUrl() {
        return $SESSION["IUC_openIdUrl"];
    }

    public static function setStatusOfRegistration($openIdUrl) {
        $SESSION["IUC_openIdUrl"] = $openIdUrl;
    }

}

?>
