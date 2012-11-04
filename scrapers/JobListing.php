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
    private $url;
    
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

    public function setDomain($domain) {
        $this->domain = $domain;
    }

    public function getDomain() {
        return $this->domain;
    }

    public function setURL($url) {
        $this->url = $url;
    }

    public function getURL() {
        return $this->url;
    }

}

?>
