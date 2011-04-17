<?php

require_once 'Controller/ImbaManagerBase.php';
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
    public function insert($name) {
        $query = "INSERT INTO %s (name) VALUES ('%s');";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_PORTALS,
            $name
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
        /*        if ($game->getId() == null)
          throw new Exception("No Game Id given");

          $query = "UPDATE %s SET ";
          $query .= "name = '%s', icon= '%s', url = '%s', comment = '%s',  forumlink = '%s' ";
          $query .= "WHERE id = '%s'";

          $this->database->query($query, array(
          ImbaConstants::$DATABASE_TABLES_SYS_PORTALS,
          $game->getName(),
          $game->getIcon(),
          $game->getUrl(),
          $game->getComment(),
          $game->getForumlink(),
          $game->getId()
          ));

          foreach ($game->getCategories() as $category) {
          if ($category->getId() == null)
          throw new Exception("No Category Id given");

          $query = "DELETE FROM %s WHERE game_id = '%s';";
          $this->database->query($query, array(
          ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_INTERCEPT_GAMES_CATEGORY,
          $game->getId()
          ));

          foreach ($game->getCategories() as $category) {
          $query = "INSERT INTO %s (game_id, cat_id) VALUES ('%s', '%s');";
          $this->database->query($query, array(
          ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_INTERCEPT_GAMES_CATEGORY,
          $game->getId(),
          $category->getId()
          ));
          }
          }

          $this->portalsCached = null;
          }
         */
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

            $query = "SELECT * FROM %s WHERE 1 ORDER BY name ASC;";

            $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_PORTALS));
            while ($row = $this->database->fetchRow()) {
                //load the data trough ImbaManagerPortalEntry

                $portal = new ImbaPortal();
                $portal->setId($row["id"]);
                $portal->setName($row["name"]);
                $portal->setComment($row["comment"]);

                $portal->setAliases(json_decode($row["aliases"]));

                $tmpEntries = array();
                foreach (json_decode($row["navitems"]) as $navItemId) {
                    array_push($tmpEntries, $managerPortalEntries->selectById($navItemId));
                }
                $portal->setNavitems($tmpEntries);
                
                array_push($result, $portal);
            }

            $this->portalsCached = $result;
        }

        return $this->portalsCached;
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
