<?php

require_once 'Controller/ImbaManagerBase.php';
require_once 'Model/ImbaUserRole.php';

/**
 *  Controller / Manager for Role
 *  - insert, update, delete Roles
 * 
 * 
 *  TODO: Remove old fields from database eqdkp and phpraider
 * 
 */
class ImbaManagerUserRole extends ImbaManagerBase {

    /**
     * ImbaManagerDatabase
     */
    protected $rolesCached = null;
    /**
     * Singleton implementation
     */
    private static $instance = NULL;

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
        if (self::$instance === NULL)
            self::$instance = new self();
        return self::$instance;
    }

    /**
     * Inserts a user into the Database
     */
    public function insert(ImbaUserRole $user) {

        $query = "INSERT INTO %s ";
        $query .= "(handle, role, name, smf, wordpress, icon) VALUES ";
        $query .= "('%s', '%s', '%s', '%s', '%s', '%s')";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_PROFILES,
            $role->getHandle(),
            $role->getRole(),
            $role->getName(),
            $role->getSmf(),
            $role->getWordpress(),
            $role->getIcon()
        ));
    }

    /**
     * Delets a Role by Id
     */
    public function delete($id) {
        $query = "DELETE FROM %s Where id = '%s';";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_PROFILES,
            $id
        ));
    }

    /**
     * Select all roles
     */
    public function selectAll() {
        if ($this->rolesCached == null) {
            $result = array();

            $query = "SELECT * FROM %s;";

            $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_PROFILES));
            while ($row = $this->database->fetchRow()) {
                $role = new ImbaUserRole();
                $role->setHandle($row["handle"]);
                $role->setRole($row["role"]);
                $role->setName($row["name"]);
                $role->setSmf($row["smf"]);
                $role->setWordpress($row["wordpress"]);
                $role->setIcon($row["icon"]);
                $role->setId($row["id"]);

                array_push($result, $role);
            }

            $this->rolesCached = $result;
        }

        return $this->rolesCached;
    }

    /**
     * Select one Role by role
     */
    public function selectByRole($roleId) {
        foreach ($this->selectAll() as $role) {
            if ($role->getRole() == $roleId)
                return $role;
        }
        return null;
    }

    /**
     * Select one Role by Id
     */
    public function selectById($id) {
        foreach ($this->selectAll() as $role) {
            if ($role->getId() == $id)
                return $role;
        }
        return null;
    }

}

?>
