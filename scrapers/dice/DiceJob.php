<?php

/**
 * A job listing retrieved from dice.com
 *
 * @author Matt Castagnolo
 */
class DiceJob extends JobListing {

    function __construct() {
        parent::__construct();
    }

    public function setUrl($url) {
        parent::setDiceUrl($url);
    }

    public function getUrl() {
        return parent::getDiceUrl();
    }

    public function compareListing($other_listing) {
        return parent::compareListing($other_listing);
    }

}

?>
