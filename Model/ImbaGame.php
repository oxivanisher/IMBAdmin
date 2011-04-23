<?php

require_once 'Model/ImbaBase.php';
require_once 'ImbaConstants.php';
require_once 'Controller/ImbaSharedFunctions.php';

/**
 * Class for all Games managed by the IMBAdmin
 */
class ImbaGame extends ImbaBase {

    /**
     * Fields
     */
    protected $name = null;
    protected $comment = null;
    protected $categories = array();
    protected $properties = array();
    protected $url = null;
    protected $icon = null;
    protected $forumlink = null;

    /**
     * Properties
     */
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getComment() {
        return $this->comment;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

    public function getCategories() {
        return $this->categories;
    }

    public function addCategory($category) {
        array_push($this->categories, $category);
    }

    public function setCategories($categories) {
        $this->categories = $categories;
    }

    public function getProperties() {
        return $this->properties;
    }

    public function addProperty($property) {
        array_push($this->properties, $property);
    }

    public function setProperties($properties) {
        $this->properties = $properties;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function getIcon() {
        if ($this->icon == null || $this->icon == "") {
            return ImbaSharedFunctions::fixWebPath("Images/noicon.png");
        } else {
            return ImbaSharedFunctions::fixWebPath($this->icon);
        }
    }

    public function setIcon($icon) {
        $this->icon = $icon;
    }

    public function getForumlink() {
        return $this->forumlink;
    }

    public function setForumlink($forumlink) {
        $this->forumlink = $forumlink;
    }

}

?>
