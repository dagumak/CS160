<?php

/**
 * A job listing retrieved from Monster.com
 *
 * @author Matt Castagnolo
 */
class MonsterJob extends JobListing {

    function __construct() {
        parent::__construct();
        parent::setDomain("monster");
    }

}

?>
