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
    protected $rolesCachedTimestamp = null;

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
     * Select one Role by Id
     */
    public function selectById($id) {
        if ($this->usersCached == null) {
            // Only fetch Users with role <> banned
            $result = array();

            $query = "SELECT * FROM %s;";

            $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_PROFILES));
            while ($row = $this->database->fetchRow()) {
                // FIXME: muss hier auch eines hin für id?
                $role = new ImbaUserRole();
                $role->setHandle($row["handle"]);
                $role->setRole($row["role"]);
                $role->setName($row["name"]);
                $role->setSmf($row["smf"]);
                $role->setWordpress($row["wordpress"]);
                $role->setIcon($row["icon"]);

                array_push($result, $role);
            }

            $this->rolesCachedTimestamp = time();
            $this->rolesCached = $result;
        }
        foreach ($this->rolesCached as $role) {
            if ($role->getRole() == $id)
                return $role;
        } return null;
    }

}

?>
