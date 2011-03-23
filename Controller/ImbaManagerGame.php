<?php

require_once 'Controller/ImbaManagerBase.php';
require_once 'Model/ImbaGame.php';

/**
 *  Controller / Manager for Game
 *  - insert, update, delete Games
  */
class ImbaManagerGame extends ImbaManagerBase {

    /**
     * ImbaManagerDatabase
     */
    protected $gamesCached = null;
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
     * Inserts a game into the Database
     */
    public function insert(ImbaGame $game) {

        $query = "INSERT INTO %s ";
        $query .= "(handle, role, name, smf, wordpress, icon) VALUES ";
        $query .= "('%s', '%s', '%s', '%s', '%s', '%s')";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_PROFILES,
            $game->getHandle(),
            $game->getRole(),
            $game->getName(),
            $game->getSmf(),
            $game->getWordpress(),
            $game->getIcon()
        ));
    }

    /**
     * Updates a game into the Database
     */
    public function update(ImbaGame $game) {
        $query = "UPDATE %s SET ";
        $query .= "name = '%s', icon= '%s', url = '%s', forumlink = '%s' ";
        $query .= "WHERE id = '%s'";

        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_GAMES,
            $game->getName(),
            $game->getIcon(),
            $game->getUrl(),
            $game->getForumlink(),
            $game->getId()
        ));
    }

    /**
     * Delets a Game by Id
     */
    public function delete($id) {

        $query = "DELETE FROM %s Where id = '%s';";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_GAMES,
            $id
        ));
    }

    /**
     * Select all roles
     */
    public function selectAll() {
        if ($this->gamesCached == null) {
            $result = array();

            $query = "SELECT * FROM %s WHERE 1 ORDER BY name ASC;";

            $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_GAMES));
            while ($row = $this->database->fetchRow()) {
                $game = new ImbaGame();
                $game->setId($row["id"]);
                $game->setName($row["name"]);
                $game->setIcon($row["icon"]);
                $game->setUrl($row["url"]);
                $game->setForumlink($row["forumlink"]);

                array_push($result, $game);
            }

            $this->gamesCached = $result;
        }

        return $this->gamesCached;
    }

    /**
     * Get a new Game
     */
    public function getNew() {
        $game = new ImbaGame();
        return $game;
    }

    /**
     * Select one Game by Id
     */
    public function selectById($id) {
        foreach ($this->selectAll() as $game) {
            if ($game->getId() == $id)
                return $game;
        }
        return null;
    }

}

?>
