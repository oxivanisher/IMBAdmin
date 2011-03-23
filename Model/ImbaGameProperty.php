<?php

require_once 'Model/ImbaBase.php';

/**
 * Property for all Games managed by the IMBAdmin
 */
class ImbaGameProperty extends ImbaBase {

    /**
     * Fields
     */
    protected $gameId = null;
    protected $property = null;

    /**
     * Properties
     */
    public function getGameId() {
        return $this->gameId;
    }

    public function setGameId($gameId) {
        $this->gameId = $gameId;
    }

    public function getProperty() {
        return $this->property;
    }

    public function setProperty($property) {
        $this->property = $property;
    }

}

?>
