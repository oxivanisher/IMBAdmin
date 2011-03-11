<?php
require_once 'Controller/ImbaManagerDatabase.php';

/**
 * Description of ImbaUserContext
 *
 * stores the user context
 */
class ImbaUserContext {

    //user session basics
    
    public function __construct(ImbaManagerDatabase $database) {
        $this->database = $database;
    }
    
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

    public static function setMeOnline() {
        if (ImbaUserContext::getLoggedIn() && (!empty(ImbaUserContext::getOpenIdUrl()))) {
            $query = "UPDATE %s SET timestamp='%s' WHERE openid='%s';";
            $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_LASTONLINE, time(), ImbaUserContext::getOpenIdUrl()));
        }
    }

}

?>
