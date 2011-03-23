<?php

require_once 'ImbaConstants.php';
require_once 'Controller/ImbaManagerBase.php';
require_once 'Model/ImbaGame.php';
require_once 'Model/ImbaGameCategory.php';

/**
 * Description of ImbaManagerGame
 * SQL:
 * ALTER TABLE  `oom_openid_multig_games` ADD  `icon` VARCHAR( 255 ) NULL , ADD  `forumlink` VARCHAR( 255 ) NULL;
 * CREATE TABLE  `oom_openid_multig_category` (`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,`name` VARCHAR( 100 ) NOT NULL) ENGINE = MYISAM
 * CREATE TABLE IF NOT EXISTS `oom_openid_multig_int_games_cat` (`game_id` int(11) NOT NULL, `cat_id` int(11) NOT NULL, UNIQUE KEY `game_id` (`game_id`,`cat_id`)) ENGINE=MyISAM
 * CREATE TABLE  `oom_openid_multig_game_properties` (`id` INT NOT NULL AUTO_INCREMENT ,`game_id` INT NOT NULL ,`property` VARCHAR( 255 ) NOT NULL ,PRIMARY KEY (  `id` )) ENGINE = MYISAM
 */
class ImbaManagerMultigaming extends ImbaManagerBase {
    /*
     * Cache
     */

    protected $categoriesCached = null;
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
     * Selects all Gaming Categories
     */
    public function selectAllCategories() {
        if ($this->categoriesCached == null) {
            $query = "SELECT * FROM %s order by name DESC;";
            $this->database->query($query, array(
                ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_CATEGORIES
            ));

            $result = array();
            while ($row = $this->database->fetchRow()) {
                $category = new ImbaGameCategory();
                $category->setId($row["id"]);
                $category->setName($row["name"]);
                array_push($result, $category);
            }

            $this->categoriesCached = $result;
        }

        return $this->categoriesCached;
    }

    /**
     * Selecting a Categorie by Id
     */
    public function selectCategoryById($gameCategoryId) {
        if ($this->categoriesCached == null) {
            $this->selectAllCategories();
        }

        foreach ($this->categoriesCached as $category) {
            if ($category->getId() == $gameCategoryId) {
                return $category;
            }
        }

        return null;
    }

    /**
     * Inserts a Category without properites
     */
    public function insertCategory(ImbaGameCategory $gameCategory) {
        $query = "INSERT INTO %s (name) VALUES ('%s');";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_CATEGORIES,
            $gameCategory->getName()
        ));
        $this->categoriesCached = null;
    }

    /**
     * Updates a category
     */
    public function updateCategory(ImbaGameCategory $gameCategory) {
        $query = "UPDATE %s SET name = '%s' Where Id = '%s';";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_CATEGORIES,
            $gameCategory->getName(),
            $gameCategory->getId()
        ));
        $this->categoriesCached = null;
    }

    /**
     * Delets a category
     */
    public function deleteCategory(ImbaGameCategory $gameCategory) {
        $query = "DELETE FROM %s Where Id = '%s';";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_CATEGORIES,
            $gameCategory->getId()
        ));

        $this->categoriesCached = null;
    }

    /**
     * Selects all GameProperty by Game Id
     */
    public function selectAllGamePropertiesByGameId($gameId) {
        $query = "SELECT * FROM %s Where game_id = '%s' order by property DESC;";
        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_GAMES_PROPERTIES, $gameId));

        $result = array();
        while ($row = $this->database->fetchRow()) {
            $property = new ImbaGameProperty();
            $property->setGameId($gameId);
            $property->setId($row["id"]);
            $property->setProperty($row["property"]);
            array_push($result, $property);
        }

        return $result;
    }
    
    /**
     * Inserts a Gameproperty
     */
    public function insertGameProperty(ImbaGameProperty $gameProperty) {
        $query = "INSERT INTO %s (game_id, property) VALUES ('%s', '%s');";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_GAMES_PROPERTIES,
            $gameProperty->getGameId(),
            $gameProperty->getProperty()
        ));
    }
        
    /**
     * Updates a Gameproperty
     */
    public function updateGameProperty(ImbaGameProperty $gameProperty) {
        $query = "UPDATE %s SET game_id = '%s', property = '%s' WHERE id='%s';";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_GAMES_PROPERTIES,
            $gameProperty->getGameId(),
            $gameProperty->getProperty(),
            $gameProperty->getId()
        ));
    }
    
    /**
     * Deletes a Gameproperty
     */
    public function deleteGameProperty(ImbaGameProperty $gameProperty) {
        $query = "DELETE FROM %s WHERE id='%s';";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_GAMES_PROPERTIES,            
            $gameProperty->getId()
        ));
    }

    /**
     * Selects all Games
     */
    public function selectAllGames() {
        if ($this->gamesCached == null) {
            $query = "SELECT * FROM %s order by name DESC;";
            $this->database->query($query, array(
                ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_GAMES
            ));

            $result = array();
            // catch simple Gamedata
            while ($row = $this->database->fetchRow()) {
                $game = new ImbaGame();
                $game->setId($row["id"]);
                $game->setName($row["name"]);
                $game->setUrl($row["url"]);
                $game->setComment($row["comment"]);
                $game->setIcon($row["icon"]);
                $game->setForumlink($row["forumlink"]);
                array_push($result, $game);
            }

            // catch Categories
            $categories = $this->selectAllCategories();
            foreach ($result as $game) {
                $query = "SELECT * FROM %s Where game_id = '%s';";
                $this->database->query($query, array(
                    ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_INTERCEPT_GAMES_CATEGORY,
                    $game->getId())
                );

                // Reset Categories... just in case
                $game->setCategories(array());
                while ($row = $this->database->fetchRow()) {
                    array_push($game->getCategories(), $this->selectCategoryById($row["cat_id"]));
                }
                
                $game->setProperties($this->selectAllGamePropertiesByGameId($game->getId()));                
            }

            $this->gamesCached = $result;
        }
        return $this->gamesCached;
    }

    /**
     * Selecting a Game by Id
     */
    public function selectGameById($id) {
        if ($this->gamesCached == null) {
            $this->selectAllGames();
        }

        foreach ($this->gamesCached as $game) {
            if ($game->getId() == $id) {
                return $game;
            }
        }

        return null;
    }

    /**
     * Inserts a game 
     */
    public function insertGame(ImbaGame $game) {
        $query = "INSERT INTO %s (name, url, comment, icon, forumlink) VALUES ('%s', '%s', '%s', '%s', '%s');";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_GAMES,
            $game->getName(),
            $game->getUrl(),
            $game->getComment(),
            $game->getIcon(),
            $game->getForumlink()
        ));

        $query = "SELECT LAST_INSERT_ID() as id;";
        $this->database->query($query, array());
        $row = $this->database->fetchRow();
        $game->setId($row["id"]);

        foreach ($game->getCategories() as $category) {
            $this->insertCategory($category);
        }
        
        foreach ($game->getProperties() as $properties) {
            $this->insertGameProperty($properties);
        }

        $this->gamesCached = null;
    }

    /**
     * Updates a game 
     */
    public function updateGame(ImbaGame $game) {
        $query = "UPDATE %s SET name = '%s', url = '%s', comment = '%s', icon = '%s', forumlink = '%s' Where id = '%s';";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_GAMES,
            $game->getName(),
            $game->getUrl(),
            $game->getComment(),
            $game->getIcon(),
            $game->getForumlink(),
            $game->getId()
        ));

        $query = "DELETE FROM %s Where game_id = '%s';";
        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_INTERCEPT_GAMES_CATEGORY, $game->getId()));

        $query = "DELETE FROM %s Where game_id = '%s';";
        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_GAMES_PROPERTIES, $game->getId()));

        foreach ($game->getCategories() as $category) {
            $query = "INSERT INTO %s (game_id, cat_id) VALUES ('%s', '%s');";
            $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_INTERCEPT_GAMES_CATEGORY, $game->getId(), $category->getId()));
        }

        $this->gamesCached = null;
    }

    /**
     * Delets a game
     */
    public function deleteGame(ImbaGame $game) {
        $query = "DELETE FROM %s Where id = '%s';";
        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_GAMES, $game->getId()));

        $query = "DELETE FROM %s Where game_id = '%s';";
        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_INTERCEPT_GAMES_CATEGORY, $game->getId()));

        $query = "DELETE FROM %s Where game_id = '%s';";
        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_GAMES_PROPERTIES, $game->getId()));

        $this->gamesCached = null;
    }

    /**
     * Get new Category
     */
    public function getNewCategory() {
        return new ImbaGameCategory();
    }

    /**
     * Get new Gameproperty
     */
    public function getNewGameProperty() {
        return new ImbaGameProperty();
    }

    /**
     * Get new Game
     */
    public function getNewGame() {
        return new ImbaGame();
    }

}

?>
