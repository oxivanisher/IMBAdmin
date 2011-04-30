<?php

require_once 'Controller/ImbaManagerBase.php';
require_once 'Controller/ImbaManagerPortalEntry.php';
require_once 'Model/ImbaPortal.php';
require_once 'Model/ImbaPortalEntry.php';

/**
 *  Controller / Manager for Portal Sites
 *  - insert, update, delete Portal
 */

/**
 * MySql Setup
  CREATE TABLE IF NOT EXISTS `oom_openid_portals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `aliases` text NOT NULL,
  `navitems` text NOT NULL,
  `icon` varchar(200) NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
 * 
 */
class ImbaManagerPortal extends ImbaManagerBase {

    /**
     * Property
     */
    protected $portalsCached = null;
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
     * Inserts a portal into the Database
     */
    public function insert(ImbaPortal $portal) {
        $query = "INSERT INTO %s (icon, name, comment) VALUES ('%s', '%s', '%s');";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_PORTALS,
            $portal->getIcon(),
            $portal->getName(),
            $portal->getComment()
        ));

        $query = "SELECT LAST_INSERT_ID() as LastId;";
        $this->database->query($query, array());
        $row = $this->database->fetchRow();

        $this->portalsCached = null;

        return $row["LastId"];
    }

    /**
     * Updates a portal into the Database
     */
    public function update(ImbaPortal $portal) {
        if ($portal->getId() == null)
            throw new Exception("No Portal Id given");

        // update the portal itself
        $query = "UPDATE %s SET ";
        $query .= "name = '%s', icon = '%s', comment = '%s' ";
        $query .= "WHERE id = '%s';";

        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_PORTALS,
            $portal->getName(),
            $portal->getIcon(),
            $portal->getComment(),
            $portal->getId()
        ));

        // add the aliases
        $query = "DELETE FROM %s WHERE portal_id = '%s';";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_PORTALS_ALIAS,
            $portal->getId()
        ));

        foreach ($portal->getAliases() as $alias) {
            $query = "INSERT INTO %s (name, portal_id) VALUES('%s', '%s');";
            $this->database->query($query, array(
                ImbaConstants::$DATABASE_TABLES_SYS_PORTALS_ALIAS,
                $alias,
                $portal->getId()
            ));
        }


        $this->portalsCached = null;
    }

    /**
     * Delets a portal by Id
     */
    public function delete($id) {
        $query = "DELETE FROM %s Where id = '%s';";
        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_PORTALS, $id));

        $this->portalsCached = null;
    }

    /**
     * Select all Portals
     */
    public function selectAll() {
        if ($this->portalsCached == null) {
            $managerPortalEntries = ImbaManagerPortalEntry::getInstance();
            $result = array();

            /**
             * Get the aliases of the portals
             */
            $query = "SELECT * FROM %s WHERE 1;";
            $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_PORTALS_ALIAS));
            $aliases = array();
            while ($row = $this->database->fetchRow()) {
                array_push($aliases, array("portal_id" => $row['portal_id'], "name" => $row['name']));
            }

            /**
             * Get the portal entries of the portals
             */
            $query = "SELECT * FROM %s WHERE 1;";
            $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_PORTALS_PORTALENTRIES));
            $portalentries = array();
            while ($row = $this->database->fetchRow()) {
                $portalEntry = $managerPortalEntries->getNew();
                $portalEntry->setId($row['id']);
                $portalEntry->setHandle($row['handle']);
                $portalEntry->setName($row['name']);
                $portalEntry->setTarget($row['target']);
                $portalEntry->setUrl($row['url']);
                $portalEntry->setComment($row['comment']);
                $portalEntry->setLoggedin($row['loggedin']);
                $portalEntry->setRole($row['role']);
                array_push($portalentries, $portalEntry);
            }

            /**
             * Get the portal <-> entries intersect data
             */
            $query = "SELECT * FROM %s WHERE 1;";
            $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_PORTALS_INTERCEPT_PORTALS_PORTALENTRIES));
            $portalentries_intersect = array();
            while ($row = $this->database->fetchRow()) {
                array_push($portalentries_intersect, array(
                    "portal_id" => $row['portal_id'],
                    "portalentry_id" => $row['portalentry_id']
                ));
            }

            /**
             * Get the portals data and put it all together
             */
            $query = "SELECT * FROM %s WHERE 1 ORDER BY name ASC;";
            $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_PORTALS));
            while ($row = $this->database->fetchRow()) {
                $portal = new ImbaPortal();
                $portal->setId($row["id"]);
                $portal->setName($row["name"]);
                $portal->setComment($row["comment"]);
                $portal->setIcon($row["icon"]);

                /**
                 * Fill the aliases
                 */
                $tmpAliases = array();
                foreach ($aliases as $alias) {
                    if ($alias['portal_id'] == $portal->getId()) {
                        array_push($tmpAliases, $alias['name']);
                    }
                }
                $portal->setAliases($tmpAliases);

                /**
                 * Fill the portal entries
                 */
                $tmpEntries = array();
                foreach ($portalentries_intersect as $intersect) {
                    if ($intersect['portal_id'] == $portal->getId()) {
                        foreach ($portalentries as $portalEntry) {
                            if ($intersect['portalentry_id'] == $portalEntry->getId())
                                array_push($tmpEntries, $portalEntry);
                        }
                    }
                }
                $portal->setPortalEntries($tmpEntries);

                array_push($result, $portal);
            }

            $this->portalsCached = $result;
        }
        return $this->portalsCached;
    }

    /**
     * Get a new Portal
     */
    public function getNew() {
        $portal = new ImbaManagerPortal();
        return $portal;
    }

    /**
     * Select one Portal by Id
     */
    public function selectById($id) {
        foreach ($this->selectAll() as $portal) {
            if ($portal->getId() == $id)
                return $portal;
        }
        return null;
    }

}

?>
