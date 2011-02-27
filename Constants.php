<?php

/**
 * Load the config
 */
require_once 'Config.php';

/**
 * static class holding:
 *  - Database
 */
class ImbaConstants extends Config {

        /**
         * Open ID
         */
        //public static $OPENID_RETURN_TO_URL = ""
        
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
	public static $DATABASE_TABLES_SYS_PROFILES = "oom_openid_profiles";
	public static $DATABASE_TABLES_SYS_REQUEST_LOG = "oom_openid_request_log";
	public static $DATABASE_TABLES_SYS_SESSION = "oom_openid_session";
	public static $DATABASE_TABLES_SYS_SETTINGS = "oom_openid_settings";
	public static $DATABASE_TABLES_SYS_SYSTEMMESSAGES = "oom_openid_systemmessages";
	public static $DATABASE_TABLES_SYS_ADMINUSERS = "oom_openid_usermanager";
	public static $DATABASE_TABLES_SYS_FRONTEND_SAFE = "oom_openid_frontend_safe";
	public static $DATABASE_TABLES_SYS_MULTIGAMING_GAMES = "oom_openid_multig_games";
	public static $DATABASE_TABLES_SYS_USER_PROFILES = "oom_openid_user_profiles";

	public static $DATABASE_TABLES_USR_MESSAGES = "oom_openid_messages";
	public static $DATABASE_TABLES_USR_MULTIGAMING_NAMES = "oom_openid_multig_names";
	public static $DATABASE_TABLES_USR_APPLICATIONS = "oom_openid_user_applications";
	public static $DATABASE_TABLES_USR_XMPP = "oom_openid_xmpp";
	public static $DATABASE_TABLES_USR_LASTONLINE = "oom_openid_lastonline";

	public static $DATABASE_TABLES_WOW_ARMORY_ITEMCACHE = "oom_openid_armory_itemcache";
	public static $DATABASE_TABLES_WOW_ARMORY_NAMES = "oom_openid_armory_names";
	public static $DATABASE_TABLES_WOW_CHARACTERS = "oom_openid_characters";
	public static $DATABASE_TABLES_WOW_ARMORY_CHARCACHE = "oom_openid_armory_charcache";

	public static $DATABASE_TABLES_CHAT_CHATCHANNELS = "oom_openid_chatchannels";
	public static $DATABASE_TABLES_CHAT_CHATMESSAGES = "oom_openid_chatmessages";


}

?>