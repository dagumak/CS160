<?php

/**
 * A job listing retrieved from Monster.com
 *
 * @author Matt Castagnolo
 */
class MonsterJob extends JobListing {

    function __construct() {
        parent::__construct();
    }
    
    public function setUrl($url) {
        parent::setMonsterUrl($url);
    }
    
    public function getUrl() {
        return parent::getMonsterUrl();
    }
    
    public function compareListing($other_listing) {
        return parent::compareListing($other_listing);
    }

}

?>
