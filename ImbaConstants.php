<?php

/**
 * Load the config
 */
require_once 'ImbaConfig.php';
require_once 'Controller/ImbaManagerDatabase.php';

/**
 * static class holding:
 *  - Database
 */
class ImbaConstants extends ImbaConfig {

//    $WEB_PATH = dirname($_SERVER["PHP_SELF"]);
    /**
     * Site context settings
     */
    public static $CONTEXT_LOCALE = array(LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge');
    public static $CONTEXT_NEW_USER_ROLE = 1;
    /**
     * Files and Folders
     */
    public static $WEB_AUTH_PROXY_PATH = "ImbaProxy.php?facility=auth";
    //public static $WEB_AUTH_PROXY_PATH = "ImbaAuth.php";
    public static $WEB_AUTH_MAIN_PATH = "ImbaAuth.php";
    public static $WEB_AJAX_PROXY_PATH = "ImbaProxy.php?facility=ajax";
    public static $WEB_AJAX_MAIN_PATH = "ImbaAjax.php";
    
    /**
     *  0   => Auto, let the system decide
     * +1   => always use proxy
     * -1   => never use proxy
     */
    public static $WEB_FORCE_PROXY = false;
    public static $WEB_AUTH_SSL_CHECK = false;


    public static $WEB_ENTRY_INDEX_FILE = "index.html";
    public static $WEB_DEFAULT_LOGGED_IN_MODULE = "Welcome";
    public static $WEB_DEFAULT_LOGGED_OUT_MODULE = "Register";
    public static $WEB_DEFAULT_GAME = "Index";
    public static $WEB_IMBADMIN_BUTTON_NAME = "Menu";
    public static $WEB_IMBADMIN_BUTTON_COMMENT = "IMBAdmin &ouml;ffnen";
    public static $WEB_IMBAGAME_BUTTON_NAME = "Games";
    public static $WEB_IMBAGAME_BUTTON_COMMENT = "IMBA Games &ouml;ffnen";
    /**
     * Error Codes
     */
// TODO: $ERROR_OPENID_Auth_OpenID_REDIRECT_FAILED -> $ERROR_Auth_OpenID_REDIRECT_FAILED
    public static $ERROR_OPENID_Auth_OpenID_INVALID_URI = "Auth_OpenID_INVALID_URI";
    public static $ERROR_OPENID_Auth_OpenID_REQUEST_FAILED = "Auth_OpenID_REQUEST_FAILED";
    public static $ERROR_OPENID_Auth_OpenID_REDIRECT_FAILED = "Auth_OpenID_REDIRECT_FAILED";
    public static $ERROR_OPENID_Auth_OpenID_FORM_FAILED = "Auth_OpenID_FORM_FAILED";
    public static $ERROR_OPENID_Auth_OpenID_CANCEL = "Auth_OpenID_CANCEL";
    public static $ERROR_OPENID_Auth_OpenID_FAILURE = "Auth_OpenID_FAILURE";
    /**
     * Database - Tables
     */
    public static $DATABASE_TABLES_SYS_PORTALS = "oom_openid_portals";
    public static $DATABASE_TABLES_SYS_PORTALS_ALIAS = "oom_openid_portals_alias";
    public static $DATABASE_TABLES_SYS_PORTALS_NAVIGATION_ITEMS = "oom_openid_portals_navigation_items";
    public static $DATABASE_TABLES_SYS_SETTINGS = "oom_openid_settings";
    public static $DATABASE_TABLES_SYS_PROFILES = "oom_openid_profiles";
    public static $DATABASE_TABLES_SYS_AUTH_REQUEST = "oom_openid_auth_request";
    public static $DATABASE_TABLES_SYS_USER_PROFILES = "oom_openid_user_profiles";
    public static $DATABASE_TABLES_SYS_SYSTEMMESSAGES = "oom_openid_systemmessages";
    public static $DATABASE_TABLES_SYS_MULTIGAMING_GAMES = "oom_openid_multig_games";
    public static $DATABASE_TABLES_SYS_MULTIGAMING_GAMES_PROPERTIES = "oom_openid_multig_game_properties";
    public static $DATABASE_TABLES_SYS_MULTIGAMING_CATEGORIES = "oom_openid_multig_category";
    public static $DATABASE_TABLES_SYS_MULTIGAMING_INTERCEPT_GAMES_CATEGORY = "oom_openid_multig_int_games_cat";
    public static $DATABASE_TABLES_USR_MULTIGAMING_INTERCEPT_GAMES_PROPERTY = "oom_openid_multig_int_user_gameproperties";
    public static $DATABASE_TABLES_USR_MULTIGAMING_NAMES = "oom_openid_multig_names";
    public static $DATABASE_TABLES_USR_MESSAGES = "oom_openid_messages";
    public static $DATABASE_TABLES_CHAT_CHATCHANNELS = "oom_openid_chatchannels";
    public static $DATABASE_TABLES_CHAT_CHATMESSAGES = "oom_openid_chatmessages";
    public static $DATABASE_TABLES_CHAT_INTERCEPT_CHATCHANNELS_USER = "oom_openid_chat_int_chatchannels_user";
    public static $DATABASE_TABLES_GAME_EVE_CHARS = "oom_game_eve_chars";
    /**
     * currently unused tables
      public static $DATABASE_TABLES_USR_XMPP = "oom_openid_xmpp";
      public static $DATABASE_TABLES_SYS_SESSION = "oom_openid_session";
      public static $DATABASE_TABLES_SYS_ADMINUSERS = "oom_openid_usermanager";
     */
    /**
     * Support for loading settings from database
     */
    public static $SETTINGS_CACHED = null;
    public static $SETTINGS = array();
    public function loadSettings() {
        if (ImbaConstants::$SETTINGS_CACHED == null) {
            $database = ImbaManagerDatabase::getInstance();
            $database->query("SELECT name, value FROM %s WHERE 1;", array(ImbaConstants::$DATABASE_TABLES_SYS_SETTINGS));
            while ($row = $database->fetchRow()) {
                ImbaConstants::$SETTINGS[$row['name']] = $row['value'];
            }
            ImbaConstants::$SETTINGS_CACHED == true;
        }
    }

}

?>