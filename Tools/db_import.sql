-- deleting unused tables:
drop table oom_openid_armory_charcache;
drop table oom_openid_armory_itemcache;
drop table oom_openid_armory_names;
drop table oom_openid_characters;
drop table oom_openid_request_log;
drop table oom_openid_user_applications;
drop table oom_openid_lastonline;
drop table oom_openid_frontend_safe;

-- clearing temporary tables:
delete from oom_openid_systemmessages where 1;
delete from oom_openid_session where 1;

-- clearing up messages database:
delete from oom_openid_messages where subject = 'SYSTEM ALERT';
delete from oom_openid_messages where subject = 'SYSTEM MESSAGE';
delete from oom_openid_messages where subject = 'MASS/XMPP/turak-win';
delete from oom_openid_messages where subject = 'MASS/HTML GUI';
delete from oom_openid_messages where subject = 'SYSTEM INFORMATION';
delete from oom_openid_messages where message = 'ERROR: You are not allowed to use this module!';
delete from oom_openid_messages where message = ' ';
delete from oom_openid_messages where message = 'test';
update oom_openid_messages set subject = 'AJAX GUI' where subject = 'Was soll hier rein?';

-- doing db updates on users:
ALTER TABLE `oom_openid_user_profiles` DROP PRIMARY KEY;
ALTER TABLE `oom_openid_user_profiles` ADD  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;
ALTER TABLE  `oom_openid_user_profiles` ADD  `lastonline` INT( 18 ) NOT NULL;

-- doing db updates for multigaming:
ALTER TABLE `oom_openid_multig_games` ADD  `icon` VARCHAR( 255 ) NULL , ADD  `forumlink` VARCHAR( 255 ) NULL;
CREATE TABLE `oom_openid_multig_category` (`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,`name` VARCHAR( 100 ) NOT NULL) ENGINE = MYISAM;
CREATE TABLE `oom_openid_multig_int_games_cat` (`game_id` int(11) NOT NULL, `cat_id` int(11) NOT NULL, UNIQUE KEY `game_id` (`game_id`,`cat_id`)) ENGINE=MyISAM;
CREATE TABLE `oom_openid_multig_game_properties` (`id` INT NOT NULL AUTO_INCREMENT ,`game_id` INT NOT NULL ,`property` VARCHAR( 255 ) NOT NULL ,PRIMARY KEY (  `id` )) ENGINE = MYISAM;

-- doing db updates for portal:
CREATE TABLE `oom_openid_portals` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `name` varchar(50) NOT NULL,  `aliases` text NOT NULL,  `navitems` text NOT NULL,  `icon` varchar(200) NOT NULL,  `comment` text NOT NULL,  PRIMARY KEY (`id`)) ENGINE = MYISAM;
CREATE TABLE `oom_openid_navigation_items` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `handle` varchar(20) NOT NULL,  `name` varchar(100) NOT NULL,  `target` varchar(20) NOT NULL,  `url` varchar(250) NOT NULL,  `comment` text NOT NULL,  `loggedin` int(1) NOT NULL,  `role` int(2) NOT NULL,  PRIMARY KEY (`id`)  ) ENGINE = MYISAM;

-- doing db updates for game properties:
CREATE TABLE `oom_openid_multig_int_user_gameproperties` (  `openid` varchar(200) NOT NULL,  `property_id` int(11) NOT NULL,  `value` varchar(255) NOT NULL);

-- finally, optimize remaining tables:
OPTIMIZE TABLE  `oom_openid_chatchannels` ,  `oom_openid_chatmessages` ,  `oom_openid_frontend_safe` ,  `oom_openid_messages` ,  `oom_openid_multig_category` ,  `oom_openid_multig_games` ,  `oom_openid_multig_game_properties` ,  `oom_openid_multig_int_games_cat` ,  `oom_openid_multig_int_user_gameproperties` ,  `oom_openid_multig_names` , `oom_openid_navigation_items` ,  `oom_openid_portals` ,  `oom_openid_profiles` ,  `oom_openid_session` ,  `oom_openid_settings` ,  `oom_openid_systemmessages` ,  `oom_openid_usermanager` ,  `oom_openid_user_profiles` ,  `oom_openid_xmpp`