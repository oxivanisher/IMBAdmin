<?php

/**
 * Base class for all Models
 */
class ImbaBase {

    /**
     * Field for Id
     */
    protected $Id;

    /**
     * Property for Id
     */
    public function getId() {
        return $this->Id;
    }

    public function setId($id) {
        $this->Id = $id;
    }

    public function toString() {
        foreach ($this as $key => $value) {
//            $json->$key = htmlentities ($value);
            $json->$key = $value;
        }
        return json_encode($json);
    }

    // TODO: Testen ob json_decode auch geht
    public function fromString($json_str) {
        $json = json_decode($json_str, 1);
        foreach ($json as $key => $value) {
            $this->$key = $value;
        }
    }
}

?>