<?php
/**
 * Description of ImbaUserContext
 *
 * stores the user context
 */
require_once 'ImbaConstants.php';

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

    public static function setOpenIdUrl($openid) {
        $_SESSION["IUC_openIdUrl"] = $openid;
    }

    public static function getUserId() {
        return $_SESSION["IUC_UserId"];
    }

    public static function setUserId($id) {
        $_SESSION["IUC_UserId"] = $id;
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

    public static function getAuthReferer() {
        return $_SESSION["IUC_AuthReferer"];
    }

    public static function setAuthReferer($url) {
        $_SESSION["IUC_AuthReferer"] = $url;
    }

    public static function getImbaErrorMessage() {
        return $_SESSION["IUC_ImbaErrorMessage"];
    }

    public static function setImbaErrorMessage($imbaErrorMessage) {
        $_SESSION["IUC_ImbaErrorMessage"] = $imbaErrorMessage;
    }

    public static function getNeedToRegister() {
        return $_SESSION["IUC_NeedToRegister"];
    }

    public static function setNeedToRegister($needToRegister) {
        $_SESSION["IUC_NeedToRegister"] = $needToRegister;
    }

    public static function getWaitingForVerify() {
        return $_SESSION["IUC_WaitingForVerify"];
    }

    public static function setWaitingForVerify($waitingForVerify) {
        $_SESSION["IUC_WaitingForVerify"] = $waitingForVerify;
    }

    public static function getPortalContext() {
        //FIXME: portal magic with aliases in here plz!
        ImbaConstants::loadSettings();
        $tmpPortalID = ImbaConstants::$SETTINGS['DEFAULT_PORTAL_ID'];
        if ($_SESSION["IUC_PortalContext"]) {
            $tmpPortalID = $_SESSION["IUC_PortalContext"];
        }
        return $tmpPortalID;
    }

    public static function setPortalContext($PortalContext) {
        $_SESSION["IUC_PortalContext"] = $PortalContext;
    }
}

?>
