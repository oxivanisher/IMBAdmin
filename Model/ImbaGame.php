<?php

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

    public function setCategories($categories) {
        $this->categories = $categories;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function getIcon() {
        return $this->icon;
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
