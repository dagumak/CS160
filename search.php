<?php

require_once 'scrapers/monster/MonsterScraper.php';

//Array for holding keyword tokens from description input
$KEYWORDS = array();

if (isset($_GET["location"]) && isset($_GET["description"])) {
    $monster_scraper = new MonsterScraper();
    $JOBS = array();

    //Format search terms for relevance-search keywords
    $input = str_replace(',', ' ', $_GET["description"]);

    //Get keyword tokens from description input
    $KEYWORDS = explode(" ", $input);

    //If there are any results returned from scraping monster, collect them.
    if ($monster_results = $monster_scraper->scrape_monster($_GET["location"], $_GET["description"])) {
        $JOBS += $monster_results;
    }


    if (isset($_GET["sort-by"])) {
        switch ($_GET["sort-by"]) {
            case 'date':
                // Run function to sort the data by date and re-set the variable 
                // sort by date
                usort($JOBS, function($a, $b) {
                            return $a->getDate() - $b->getDate();
                        }
                );
                break;
            case 'relevance':
                // Run function to sort the data by relevance and re-set the variable
                // sort by relevance
                usort($JOBS, function($a, $b) {
                            GLOBAL $KEYWORDS;
                            $aDescription = $a->getDescription();
                            $bDescription = $b->getDescription();
                            $aNum = 0;
                            $bNum = 0;
                            foreach ($KEYWORDS as $k) {
                                if ($k == "")
                                    continue;
                                if (strpos($aDescription, $k) === false)
                                    ;
                                else
                                    $aNum++;
                                if (strpos($bDescription, $k) === false)
                                    ;
                                else
                                    $bNum++;
                            }
                            return ($aNum < $bNum) ? -1 : 1;
                        }
                );
                break;
        }
    }

    echo '<table class="table table-striped">
            <thead>
              <tr>
                <th>Description</th>
                <th>Location</th>
                <th>Company</th>
                <th>Date</th>
                <th>Twitter</th>
              </tr>
            </thead>
            <tbody>';
    foreach ($JOBS as $job) {

        echo "<tr>
                <td><a href='" . $job->getURL() . "'>" . $job->getDescription() . "</a></td>
                <td>" . $job->getLocation() . "</td>
                <td>" . $job->getCompany() . "</td>
                <td>" . $job->getDate() . "</td>
                <td><a href=\"https://twitter.com/share\" class=\"twitter-share-button\" data-dnt=\"false\" data-count=\"none\" data-related=\"qi:Social Media Expert\" data-hashtags=\"JobLube\" data-text=\"I found this job: ". $job->getDescription() . $job->getURL() ."\">Tweet</a></td>
              </tr>";
    }

    echo '</tbody> 
        </table> 
        </div>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
}
?>
