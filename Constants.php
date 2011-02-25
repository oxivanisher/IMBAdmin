<?php
/**
 * static class holding:
 *  - Database
 */
class ImbaConstants {
	
	/**
 	* Database
 	*/
	public static $DATABASE_HOST = 'localhost';
	public static $DATABASE_USER = 'root';
	public static $DATABASE_PASS = '';
	public static $DATABASE_DB = 'alptroeim';

	/**
 	* Database - Tables
 	*/
	private static $DB_TABLES_PREFIX = 'imba_';
	private static $DB_TABLES_PREFIX_WOW = 'wow_';
	private static $DB_TABLES_PREFIX_EVE = 'eve_';

	//public static $DB_TABLES_ARMORY_CHARCACHE = $DB_TABLES_PREFIX . $DB_TABLES_PREFIX_WOW . 'armory_charcache';
}
?>