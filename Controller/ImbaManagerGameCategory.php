<?php

require_once 'Controller/ImbaManagerBase.php';
require_once 'Model/ImbaGameCategory.php';

/**
 *  Controller / Manager for Categories
 *  - insert, update, delete Categories
 */
class ImbaManagerGameCategory extends ImbaManagerBase {

    /**
     * Property
     */
    protected $categoryCategoriesCached = null;
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
     * Inserts a category into the Database
     */
    public function insert(ImbaGameCategory $category) {
        $query = "INSERT INTO %s ";
        $query .= "(name) VALUES ";
        $query .= "('%s')";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_CATEGORIES,
            $category->getName(),
        ));
    }

    /**
     * Updates a category into the Database
     */
    public function update(ImbaGameCategory $category) {
        $query = "UPDATE %s SET ";
        $query .= "name = '%s' ";
        $query .= "WHERE id = '%s';";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_CATEGORIES,
            $category->getName(),
            $category->getId()
        ));
    }

    /**
     * Delets a category by Id
     */
    public function delete($id) {
        $query = "DELETE FROM %s Where id = '%s';";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_CATEGORIES,
            $id
        ));
    }

    /**
     * Select all categories
     */
    public function selectAll() {
        if ($this->gameCategoriesCached == null) {
            $result = array();

            $query = "SELECT * FROM %s WHERE 1 ORDER BY name ASC;";

            $this->database->query($query, array(ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_CATEGORIES));
            while ($row = $this->database->fetchRow()) {
                $category = new ImbaGameCategory();
                $category->setId($row["id"]);
                $category->setName($row["name"]);

                array_push($result, $category);
            }

            $this->gameCategoriesCached = $result;
        }

        return $this->gameCategoriesCached;
    }

    /**
     * Get a new Game category
     */
    public function getNew() {
        $category = new ImbaGameCategory();
        return $category;
    }

    /**
     * Select one Game by Id
     */
    public function selectById($id) {
        foreach ($this->selectAll() as $category) {
            if ($category->getId() == $id)
                return $category;
        }
        return null;
    }

}

?>
