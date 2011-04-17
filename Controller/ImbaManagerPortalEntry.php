<?php

require_once 'Controller/ImbaManagerBase.php';
require_once 'Model/ImbaPortal.php';

/**
 *  Controller / Manager for Portal Entries
 *  - insert, update, delete Portal Entry
 */

/**
 * Mysql Setup
 * 
  CREATE TABLE IF NOT EXISTS `oom_openid_navigation_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `handle` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `target` varchar(20) NOT NULL,
  `url` varchar(250) NOT NULL,
  `comment` text NOT NULL,
  `loggedin` int(1) NOT NULL,
  `role` int(2) NOT NULL,
  PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
 *  
 */
class ImbaManagerPortalEntry extends ImbaManagerBase {

    /**
     * Property
     */
    protected $portalEntriesCached = null;
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
     * Inserts a PortalEntry into the Database
     */
    public function insert(ImbaManagerPortalEntry $portalEntry) {
        $query = "INSERT INTO %s (handle, name, target, url, comment, loggedin, role) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s');";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_GAMES,
            $portalEntry->getHandle(),
            $portalEntry->getName(),
            $portalEntry->getTarget(),
            $portalEntry->getUrl(),
            $portalEntry->getComment(),
            $portalEntry->getLoggedin(),
            $portalEntry->getRole()
        ));

        $query = "SELECT LAST_INSERT_ID() as LastId;";
        $this->database->query($query, array());
        $row = $this->database->fetchRow();

        $this->portalEntriesCached = null;

        return $row["LastId"];
    }

    /**
     * Updates a game into the Database
     */
    public function update(ImbaGame $game) {
        /*        if ($game->getId() == null)
          throw new Exception("No Game Id given");

          $query = "UPDATE %s SET ";
          $query .= "name = '%s', icon= '%s', url = '%s', comment = '%s',  forumlink = '%s' ";
          $query .= "WHERE id = '%s'";

          $this->database->query($query, array(
          ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_GAMES,
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

          $this->portalEntriesCached = null;
          }
         */
    }

    /**
     * Delets a PortalEntry by Id
     */
    public function delete($id) {
        $query = "DELETE FROM %s Where id = '%s';";
        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_GAMES, $id));

        //FIXME: we need to delet this portalentry also in the portal
        $this->portalEntriesCached = null;
    }

    /**
     * Select all PortalEntries
     */
    public function selectAll() {
        if ($this->portalEntriesCached == null) {
            $result = array();

            $query = "SELECT * FROM %s WHERE 1 ORDER BY name ASC;";

            $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_GAMES));
            while ($row = $this->database->fetchRow()) {
                $entry = new ImbaPortalEntry();
                $entry->setId($id);
                $entry->setHandle($handle);
                $entry->setComment($comment);
                $entry->setLoggedin($loggedin);
                $entry->setName($name);
                $entry->setRole($role);
                $entry->setTarget($target);
                $entry->setUrl($url);

                array_push($result, $game);
            }
            $this->portalEntriesCached = $result;
        }
        return $this->portalEntriesCached;
    }

    /**
     * Get a new PortalEntry
     */
    public function getNew() {
        $portalEntry = new ImbaManagerPortalEntry();
        return $portalEntry;
    }

    /**
     * Select one Portal Entry by Id
     */
    public function selectById($id) {
        foreach ($this->selectAll() as $portalEntry) {
            if ($portalEntry->getId() == $id)
                return $portalEntry;
        }
        return null;
    }
}

?>
