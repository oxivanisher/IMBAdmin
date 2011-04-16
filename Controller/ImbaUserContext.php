<?php
/**
 * Description of ImbaUserContext
 *
 * stores the user context
 */
class ImbaUserContext {

    //user session basics    
    public static function getLoggedIn() {
        return $_SESSION["IUC_loggedIn"];
    }

    public static function setLoggedIn($loggedIn) {
        $_SESSION["IUC_loggedIn"] = $loggedIn;
    }

    public static function getRedirectUrl() {
        return $_SESSION["IUC_redirectUrl"];
    }

    public static function setRedirectUrl($redirectUrl) {
        $_SESSION["IUC_redirectUrl"] = $redirectUrl;
    }

    public static function getStatusOfRegistration() {
        return $_SESSION["IUC_statusOfRegistration"];
    }

    public static function setStatusOfRegistration($statusOfRegistration) {
        $_SESSION["IUC_statusOfRegistration"] = $statusOfRegistration;
    }

    public static function getOpenIdUrl() {
        return $_SESSION["IUC_openIdUrl"];
    }

    public static function setOpenIdUrl($UserRole) {
        $_SESSION["IUC_openIdUrl"] = $UserRole;
    }

    public static function getUserRole() {
        return $_SESSION["IUC_UserRole"];
    }

    public static function setUserRole($UserRole) {
        $_SESSION["IUC_UserRole"] = $UserRole;
    }

    public static function getUserLastOnline() {
        return $_SESSION["IUC_LastOnline"];
    }

    public static function setUserLastOnline() {
        $_SESSION["IUC_LastOnline"] = time();
    }

    public static function getNeedToRegister() {
        return $_SESSION["IUC_NeedToRegister"];
    }

    public static function setNeedToRegister($needToRegister) {
        $_SESSION["IUC_NeedToRegister"] = $needToRegister;
    }

    public static function getGameContext() {
        return $_SESSION["IUC_GameContext"];
    }

    public static function setGameContext($GameContext) {
        $_SESSION["IUC_GameContext"] = $GameContext;
    }
}

?>
