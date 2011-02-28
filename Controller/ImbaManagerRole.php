<?php

require_once 'Controller/ImbaManagerDatabase.php';
require_once 'Model/ImbaUserRole.php';

/**
 *  Controller / Manager for Role
 *  - insert, update, delete Roles
 * 
 * 
 *  TODO: Remove old fields from database eqdkp and phpraider
 * 
 */
class ImbaManagerUserRole {

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
    public function insert(ImbaUserRole $user) {

        $query = "INSERT INTO " . ImbaConstants::$DATABASE_TABLES_SYS_PROFILES . " ";
        $query .= "(handle, role, name, smf, wordpress, icon) VALUES ";
        $query .= "('%s', '%s', '%s', '%s', '%s', '%s')";
        $this->database->query($query, array($role->getHandle(), $role->getRole(), $role->getName(), $role->getSmf(), $role->getWordpress(), $role->getIcon()));
    }

    /**
     * Delets a Role by Id
     */
    public function delete($id) {
        $query = "DELETE FROM  " . ImbaConstants::$DATABASE_TABLES_SYS_PROFILES . " Where id = '%s';";
        $this->database->query($query, array($id));
    }

    /**
     * Select one Role by Id
     */
    public function selectById($id) {
        $query = "SELECT * FROM  " . ImbaConstants::$DATABASE_TABLES_SYS_PROFILES . " Where id = '%s';";

        $this->database->query($query, array($id));
        $result = $this->database->fetchRow();

        // FIXME: muss hier auch eines hin für id?
        $role = new ImbaUserRole();
        $role->setHandle($result["handle"]);
        $role->setRole($result["role"]);
        $role->setName($result["name"]);
        $role->setSmf($result["smf"]);
        $role->setWordpress($result["wordpress"]);
        $role->setIcon($result["icon"]);
        return $role;
    }

}

?>
