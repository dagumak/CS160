<?php

/**
 * Parent class of job listings retrieved from Monster and Dice. Common details between
 * child class implementations should be brought up to this class
 *
 * @author Matt Castagnolo
 */
class JobListing {

    private $location;
    private $description;
    private $company;
    private $date;
    private $domain;
    private $monster_url = null;
    private $dice_url = null;
    
    function __construct() {
        
    }

    public function setLocation($location) {
        $this->location = $location;
    }

    public function getLocation() {
        return $this->location;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setCompany($company) {
        $this->company = $company;
    }

    public function getCompany() {
        return $this->company;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function getDate() {
        return $this->date;
    }

    public function setMonsterUrl($monster_url) {
        $this->monster_url = $monster_url;
    }
    
    public function getMonsterUrl() {
        return $this->monster_url;
    }

    public function setDiceUrl($dice_url) {
        $this->dice_url = $dice_url;
    }
    
    public function getDiceUrl() {
        return $this->dice_url;
    }
    
    public function getUrl() {
        if($this->dice_url) {
            return $this->dice_url;
        }
        else if($this->monster_url) {
            return $this->monster_url;
        }
        else return null;
    }
    
    public function compareListing($other_listing) {
        if( ($this->getCompany() == $other_listing->getCompany()) && ($this->getDescription() == $other_listing->getDescription())  && ($this->getLocation() == $other_listing->getLocation())){
            return true;
        }
        return false;
    }

}

?>
