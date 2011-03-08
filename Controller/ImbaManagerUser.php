<?php

require_once 'Controller/ImbaManagerDatabase.php';
require_once 'Model/ImbaUser.php';

/**
 *  Controller / Manager for User
 *  - insert, update, delete Users
 */
class ImbaManagerUser {

    /**
     * ImbaManagerDatabase
     */
    protected $database = null;

    /**
     * Ctor
     */
    public function __construct(ImbaManagerDatabase $database) {
        $this->database = $database;
    }

    /**
     * Inserts a user into the Database
     */
    public function insert(ImbaUser $user) {
        $query = "INSERT INTO " . ImbaConstants::$DATABASE_TABLES_SYS_USER_PROFILES . " ";
        $query .= "(openid, nickname, email, surname, forename, dob, mob, yob, sex, icq, msn, skype, usertitle, avatar, signature, website, motto, accurate, role) VALUES ";
        $query .= "('" . $user->getOpenId() . "', '" . $user->getNickname() . "', '" . $user->getEmail() . "', '" . $user->getLastname() . "', '" . $user->getFirstname() . "', '" . $user->getBirthday() . "', '" . $user->getBirthmonth() . "', '" . $user->getBirthyear() . "', '" . $user->getSex() . "', '" . $user->getIcq() . "', '" . $user->getMsn() . "', '" . $user->getSkype() . "', '" . $user->getUsertitle() . "', '" . $user->getAvatar() . "', '" . $user->getSignature() . "', '" . $user->getWebsite() . "', '" . $user->getMotto() . "', '" . $user->getAccurate() . "', '" . $user->getRole() . "')";
        $this->database->query($query);
    }

    /**
     * Delets a user by Id
     */
    public function delete($openId) {
        $query = "DELETE FROM  " . ImbaConstants::$DATABASE_TABLES_SYS_USER_PROFILES . " Where openid = '" . $openId . "';";
        $this->database->query($query);
    }

    /**
     * Select one User by OpenId
     */
    public function selectByOpenId($openId) {
        $query = "SELECT * FROM  " . ImbaConstants::$DATABASE_TABLES_SYS_USER_PROFILES . " Where openid = '" . $openId . "';";

        $this->database->query($query);
        $result = $this->database->fetchRow();

        $user = new ImbaUser();
        $user->setOpenId($openId);
        $user->setNickname($result["nickname"]);
        $user->setEmail($result["email"]);
        $user->setFirstname($result["forename"]);
        $user->setLastname($result["surname"]);
        $user->setBirthday($result["dob"]);
        $user->setBirthmonth($result["mob"]);
        $user->setBirthyear($result["yob"]);
        $user->setSex($result["sex"]);
        $user->setIcq($result["icq"]);
        $user->setMsn($result["msn"]);
        $user->setSkype($result["skype"]);
        $user->setUsertitle($result["usertitle"]);
        $user->setAvatar($result["avatar"]);
        $user->setSignature($result["signature"]);
        $user->setWebsite($result["website"]);
        $user->setMotto($result["motto"]);
        $user->setAccurate($result["accurate"]);
        $user->setRole($result["role"]);
        return $user;
    }

    /**
     * Selects a list of Users into an array w/o yourself
     */
    public function selectAllUser($openidYourself) {
        // Only fetch Users with role <> banned
        $query = "SELECT * FROM %s Where openid <> '%s' And Role <> 0 order by nickname;";

        $result = array();
        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_USER_PROFILES, $openidYourself));

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
            $user->setRole($row["role"]);

            array_push($result, $user);
        }

        return $result;
    }

    /**
     * Selects a list of Users into an array w/o yourself, starting with
     */
    public function selectAllUserStartWith($openidYourself, $startingWith) {
        // Only fetch Users with role <> banned
        $query = "SELECT * FROM %s Where openid <> '%s' And Role <> 0 And nickname like '%s%%' order by nickname;";

        $result = array();
        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_USER_PROFILES, $openidYourself, $startingWith));

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
            $user->setRole($row["role"]);

            array_push($result, $user);
        }

        return $result;
    }

}

?>
