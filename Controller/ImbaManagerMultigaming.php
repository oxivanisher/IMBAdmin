<?php

/**
 * Description of ImbaManagerGame
 * SQL:
 * ALTER TABLE  `oom_openid_multig_games` ADD  `icon` VARCHAR( 255 ) NULL , ADD  `forumlink` VARCHAR( 255 ) NULL;
 * CREATE TABLE  `oom_openid_multig_category` (`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,`name` VARCHAR( 100 ) NOT NULL) ENGINE = MYISAM
 */
class ImbaManagerMultigaming {

    /**
     * ImbaManagerDatabase
     */
    protected $database = null;

    /**
     * Ctor
     */
    public function __construct(ImbaManagerDatabase $database) {
        $this->database = $database;
    }

    public function selectAllCategories() {
        $query = "SELECT * FROM %s order by name DESC;";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_CATEGORIES
        ));

        $result = array();
        while ($row = $this->database->fetchRow()) {
            $category = new ImbaGameCategory();
            $category->setId($row["ID"]);
            $category->setName($row["name"]);
            array_push($result, $category);
        }

        return $result;
    }

    public function selectCategoryById($gameCategoryId) {
        $query = "SELECT * FROM %s Where ID='%s';";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_CATEGORIES,
            $gameCategoryId
        ));

        $row = $this->database->fetchRow();
        $category = new ImbaGameCategory();
        $category->setId($row["ID"]);
        $category->setName($row["name"]);

        return $category;
    }

    public function insertCategory($gameCategory) {
        $query = "INSERT INTO %s (name) VALUES ('%s');";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_CATEGORIES,
            $gameCategory->getName()
        ));
    }

    public function updateCategory($gameCategory) {
        $query = "UPDATE %s SET name = '%s' Where Id = '%s';";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_CATEGORIES,
            $gameCategory->getName(),
            $gameCategory->getId()
        ));
    }

    public function deleteCategory($gameCategory) {
        $query = "DELETE FROM %s Where Id = '%s';";
        $this->database->query($query, array(
            ImbaConstants::$DATABASE_TABLES_SYS_MULTIGAMING_CATEGORIES,
            $gameCategory->getId()
        ));
    }

}

?>
