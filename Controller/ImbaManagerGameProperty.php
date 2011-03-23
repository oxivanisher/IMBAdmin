<?php

require_once 'Controller/ImbaManagerBase.php';
require_once 'Model/ImbaGameProperty.php';

/**
 *  Controller / Manager for Properties
 *  - insert, update, delete Properties
 */
class ImbaManagerGameProperty extends ImbaManagerBase {

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
     * Inserts a property into the Database
     */
    public function insert(ImbaGameProperty $property) {
        $query = "INSERT INTO %s (game_id, property) VALUES ('%s', '%s');";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_GAMES_PROPERTIES,
            $property->getGameId(),
            $property->getProperty()
        ));
    }

    /**
     * Updates a property into the Database
     */
    public function update(ImbaGameCategory $category) {
        $query = "UPDATE %s SET game_id = '%s', property = '%s' WHERE id='%s';";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_GAMES_PROPERTIES,
            $gameProperty->getGameId(),
            $gameProperty->getProperty(),
            $gameProperty->getId()
        ));
    }

    /**
     * Delets a property by Id
     */
    public function delete($id) {
        $query = "DELETE FROM %s Where id = '%s';";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_GAMES_PROPERTIES,
            $id
        ));
    }

    /**
     * Select all properties of a game
     */
    public function selectAllByGameId($gameId) {
        $result = array();

        $query = "SELECT * FROM %s WHERE game_id = '%s' ORDER BY property ASC;";

        $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_GAMES_PROPERTIES, $gameId));
        while ($row = $this->database->fetchRow()) {
            $property = new ImbaGameProperty();
            $property->setId($row["id"]);
            $property->setGameId($gameId);
            $property->setProperty($row["property"]);

            array_push($result, $property);
        }
        
        return $result;
    }

    /**
     * Get a new Game Property
     */
    public function getNew() {
        $property = new ImbaGameProperty();
        return $property;
    }

    /**
     * Select one Game Property by Id
     */
    public function selectById($id) {
        foreach ($this->selectAll() as $property) {
            if ($property->getId() == $id)
                return $property;
        }
        return null;
    }

}

?>
