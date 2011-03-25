<?php

require_once 'Controller/ImbaManagerBase.php';
require_once 'Model/ImbaGame.php';

/**
 *  Controller / Manager for Game
 *  - insert, update, delete Games
 */
class ImbaManagerGame extends ImbaManagerBase {

    /**
     * Property
     */
    protected $gamesCached = null;
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
     * Inserts a game into the Database
     */
    public function insert(ImbaGame $game) {
        $query = "INSERT INTO %s (name, url, comment, icon, forumlink) VALUES ('%s', '%s', '%s', '%s', '%s');";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_GAMES,
            $game->getName(),
            $game->getUrl(),
            $game->getComment(),
            $game->getIcon(),
            $game->getForumlink()
        ));

        $query = "SELECT LAST_INSERT_ID() as LastId;";
        $this->database->query($query, array());
        $row = $this->database->fetchRow();
        $game->setId($row["LastId"]);

        foreach ($game->getCategories() as $category) {
            if ($category->getId() == null || $category->getId() == 0)
                throw new Exception("No Category Id given");

            $query = "INSERT INTO %s (game_id, cat_id) VALUES ('%s', '%s');";
            $this->database->query($query, array(
                ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_INTERCEPT_GAMES_CATEGORY,
                $game->getId(),
                $category->getId()
            ));
        }

        $this->gamesCached = null;

        return $game;
    }

    /**
     * Updates a game into the Database
     */
    public function update(ImbaGame $game) {
        if ($game->getId() == null)
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

        $this->gamesCached = null;
    }

    /**
     * Delets a Game by Id
     */
    public function delete($id) {
        $query = "DELETE FROM %s Where id = '%s';";
        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_GAMES, $id));

        $query = "DELETE FROM %s Where game_id = '%s';";
        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_GAMES_PROPERTIES, $id));

        $query = "DELETE FROM %s Where game_id = '%s';";
        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_INTERCEPT_GAMES_CATEGORY, $id));

        $this->gamesCached = null;
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
                $game->setComment($row["comment"]);

                array_push($result, $game);
            }

            // catch categories and properties
            ImbaManagerGameCategory::getInstance()->selectAll();
            foreach ($result as $game) {
                $query = "SELECT * FROM %s Where game_id = '%s';";
                $this->database->query($query, array(
                    ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_INTERCEPT_GAMES_CATEGORY,
                    $game->getId())
                );

                // Reset Categories... just in case
                $categories = array();
                while ($row = $this->database->fetchRow()) {
                    array_push($categories, ImbaManagerGameCategory::getInstance()->selectById($row["cat_id"]));
                }
                $game->setCategories($categories);

                // Properties
                $game->setProperties(ImbaManagerGameProperty::getInstance()->selectAllByGameId($game->getId()));
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
