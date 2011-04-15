<?php

require_once 'Model/ImbaUser.php';
require_once 'Controller/ImbaManagerBase.php';
require_once 'Controller/ImbaManagerUserRole.php';
require_once 'Controller/ImbaUserContext.php';
require_once 'Controller/ImbaManagerGame.php';
require_once 'Controller/ImbaManagerGameProperty.php';
require_once 'Controller/ImbaSharedFunctions.php';

/**
 *  Controller / Manager for User
 *  - insert, update, delete Users
 * CREATE TABLE IF NOT EXISTS `oom_openid_multig_int_user_gameproperties` (
  `openid` varchar(200) NOT NULL,
  `property_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL
  );
 */
class ImbaManagerUser extends ImbaManagerBase {

    /**
     * ImbaManagerDatabase
     */
    protected $usersCached = null;
    protected $usersCachedTimestamp = null;
    /**
     * Singleton implementation
     */
    private static $instance = null;

    /**
     * Ctor
     */
    protected function __construct() {
        //parent::__construct();
        $this->database = ImbaManagerDatabase::getInstance();
    }

    /*
     * Singleton init
     */

    public static function getInstance() {
        if (self::$instance === null)
            self::$instance = new self();
        return self::$instance;
    }

    /**
     * Ich bin potthaesslich und muesste myOpenid aus dem array entfernen
     */
    public function selectAllUserButme($myOpenid) {
        $result = array();

        foreach ($this->selectAllUser()as $user) {
            if ($user->getOpenId() != $myOpenid)
                array_push($result, $user);
        }
        return $result;
    }

    /**
     * Selects a list of Users into an array w/o yourself
     */
    public function selectAllUser() {
        if ($this->usersCached == null) {
            // Only fetch Users with role <> banned
            $result = array();

            if (ImbaUserContext::getUserRole() != "" && ImbaUserContext::getUserRole() != null && ImbaUserContext::getUserRole() == 3) {
                $query = "SELECT p.* , l.timestamp FROM %s p LEFT JOIN %s l ON p.openid = l.openid order by p.nickname;";
            } else {
                $query = "SELECT p.* , l.timestamp FROM %s p LEFT JOIN %s l ON p.openid = l.openid Where p.role <> 0 order by p.nickname;";
            }

            $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_USER_PROFILES, ImbaConstants::$DATABASE_TABLES_SYS_LASTONLINE));

            while ($row = $this->database->fetchRow()) {
                $user = new ImbaUser();
                $user->setOpenId($row["openid"]);
                $user->setNickname($row["nickname"]);
                $user->setEmail($row["email"]);
                $user->setFirstname($row["forename"]);
                $user->setLastname($row["surname"]);
                $user->setBirthday($row["dob"]);
                $user->setBirthmonth($row["mob"]);
                $user->setBirthyear($row["yob"]);
                $user->setSex($row["sex"]);
                $user->setIcq($row["icq"]);
                $user->setMsn($row["msn"]);
                $user->setSkype($row["skype"]);
                $user->setUsertitle($row["usertitle"]);
                $user->setAvatar($row["avatar"]);
                $user->setSignature($row["signature"]);
                $user->setWebsite($row["website"]);
                $user->setMotto($row["motto"]);
                $user->setAccurate($row["accurate"]);
                $user->setLastonline($row["timestamp"]);
                $user->setRole($row["role"]);
                array_push($result, $user);
            }

            // cache all games
            ImbaManagerGame::getInstance()->selectAll();
            ImbaManagerGameProperty::getInstance()->selectAll();

            foreach ($result as $user) {
                // fetch games for user
                $query = "SELECT * FROM %s WHERE openid = '%s';";
                $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_USR_MULTIGAMING_NAMES, $user->getOpenId()));

                while ($row = $this->database->fetchRow()) {
                    $game = ImbaManagerGame::getInstance()->selectById($row["gameid"]);
                    $user->addGame($game);
                }

                // fetch games properties for user if it is me
                if ($user->getOpenId() == ImbaUserContext::getOpenIdUrl() && ImbaUserContext::getLoggedIn()) {
                    $query = "SELECT * FROM %s WHERE openid = '%s';";
                    $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_USR_MULTIGAMING_INTERCEPT_GAMES_PROPERTY, $user->getOpenId()));
                    
                    while ($row = $this->database->fetchRow()) {
                        $value = new ImbaGamePropertyValue();
                        $value->setId($row["id"]);
                        $value->setUser($user);
                        $value->setValue($row["value"]);
                        $value->setProperty(ImbaManagerGameProperty::getInstance()->selectById($row["property_id"]));
                        $user->addGamesPropertyValues($value);
                    }
                }
            }

            $this->usersCachedTimestamp = time();
            $this->usersCached = $result;
        }

        return $this->usersCached;
    }

    /**
     * Inserts a user into the Database
     */
    public function insert(ImbaUser $user) {
        $query = "INSERT INTO %s ";
        $query .= "(openid, nickname, email, surname, forename, dob, mob, yob, sex, icq, msn, skype, usertitle, avatar, signature, website, motto, accurate, role) VALUES ";
        $query .= "('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_USER_PROFILES,
            $user->getOpenId(),
            $user->getNickname(),
            $user->getEmail(),
            $user->getLastname(),
            $user->getFirstname(),
            $user->getBirthday(),
            $user->getBirthmonth(),
            $user->getBirthyear(),
            $user->getSex(),
            $user->getIcq(),
            $user->getMsn(),
            $user->getSkype(),
            $user->getUsertitle(),
            $user->getAvatar(),
            $user->getSignature(),
            $user->getWebsite(),
            $user->getMotto(),
            $user->getAccurate(),
            $user->getRole()
        ));

        $this->usersCached = null;
        $this->usersCachedTimestamp = null;
    }

    /**
     * Updates a user in the Database
     */
    public function update(ImbaUser $user) {
        $query = "UPDATE %s SET ";
        $query .= "nickname = '%s', email = '%s', surname = '%s', forename = '%s', dob = '%s', mob = '%s', yob = '%s', sex = '%s', icq = '%s', msn = '%s', skype = '%s', usertitle = '%s', avatar = '%s', signature = '%s', website = '%s', motto = '%s', accurate = '%s', role = '%s' ";
        $query .= "WHERE openid = '%s'";

        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_USER_PROFILES,
            $user->getNickname(),
            $user->getEmail(),
            $user->getLastname(),
            $user->getFirstname(),
            $user->getBirthday(),
            $user->getBirthmonth(),
            $user->getBirthyear(),
            $user->getSex(),
            $user->getIcq(),
            $user->getMsn(),
            $user->getSkype(),
            $user->getUsertitle(),
            $user->getAvatar(),
            $user->getSignature(),
            $user->getWebsite(),
            $user->getMotto(),
            $user->getAccurate(),
            $user->getRole(),
            $user->getOpenId()
        ));

        // Games updaten
        $query = "DELETE FROM %s WHERE openid = '%s'";
        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_USR_MULTIGAMING_NAMES, $user->getOpenId()));

        foreach ($user->getGames() as $game) {
            $query = "INSERT INTO %s (openid, gameid) VALUES ('%s', '%s')";
            $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_USR_MULTIGAMING_NAMES, $user->getOpenId(), $game->getId()));
        }

        // Game PropertyValues updaten
        $query = "DELETE FROM %s WHERE openid = '%s'";
        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_USR_MULTIGAMING_INTERCEPT_GAMES_PROPERTY, $user->getOpenId()));

        foreach ($user->getGamesPropertyValues() as $gamePropertyValue) {
            $query = "INSERT INTO %s (openid, property_id, value) VALUES ('%s', '%s', '%s')";
            $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_USR_MULTIGAMING_INTERCEPT_GAMES_PROPERTY, $user->getOpenId(), $gamePropertyValue->getProperty()->getId(), $gamePropertyValue->getValue()));
        }

        $this->usersCached = null;
        $this->usersCachedTimestamp = null;
    }

    /**
     * Delets a user by Id
     */
    public function delete($openId) {
        $query = "DELETE FROM  " . ImbaConstants::$DATABASE_TABLES_SYS_USER_PROFILES . " Where openid = '" . $openId . "';";
        $this->database->query($query);

        $this->usersCached = null;
        $this->usersCachedTimestamp = null;
    }

    /**
     * Select one User by OpenId
     */
    public function selectByOpenId($openId) {
        $result = null;
        foreach ($this->selectAllUser()as $user) {
            if ($openId == $user->getOpenId())
                $result = $user;
        }
        return $result;
    }

    /**
     * Select the actual user
     */
    public function selectMyself() {
        foreach ($this->selectAllUser()as $user) {
            if ($user->getOpenId() == ImbaUserContext::getOpenIdUrl())
                return $user;
        } return null;
    }

    /**
     * Selects a list of Users into an array w/o yourself, starting with
     */
    public function selectAllUserStartWith($openidYourself, $startingWith) {
        // Only fetch Users with role <> banned
        $query = "SELECT openid, nickname FROM %s Where openid <> '%s' And Role <> 0 And nickname like '%s%%' order by nickname;";

        $result = array();
        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_USER_PROFILES, $openidYourself, $startingWith));

        $managerRole = ImbaManagerUserRole::getInstance();
        while ($row = $this->database->fetchRow()) {
            $user = new ImbaUser();
            $user->setOpenId($row["openid"]);
            $user->setNickname($row["nickname"]);
            array_push($result, $user);
        }

        return $result;
    }

    /**
     * Setting the timestamp for Current User 
     */
    public function setMeOnline() {
        if (ImbaUserContext::getLoggedIn() &&
                ImbaUserContext::getOpenIdUrl() &&
                ImbaUserContext::getUserLastOnline() < (time() - 10)) {
            ImbaUserContext::setUserLastOnline();
            $query = "UPDATE %s SET timestamp='%s' WHERE openid='%s';";
            $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_LASTONLINE, time(), ImbaUserContext::getOpenIdUrl()));
        }
    }

    /**
     * Get a new user
     */
    public function getNew() {
        return new ImbaUser();
    }

}

?>
