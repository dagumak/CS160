<?php
/*
DON'T PUT JAVASCRIPT IN HERE
DON'T PUT JAVASCRIPT IN HERE
DON'T PUT JAVASCRIPT IN HERE
DON'T PUT JAVASCRIPT IN HERE
DON'T PUT JAVASCRIPT IN HERE
DON'T PUT JAVASCRIPT IN HERE
DON'T PUT JAVASCRIPT IN HERE
DON'T PUT JAVASCRIPT IN HERE
DON'T PUT JAVASCRIPT IN HERE
*/
require_once 'scrapers/monster/MonsterScraper.php';
require_once 'database/db_util.php';
require_once 'trending.php';
require_once 'views.php';

//Array for holding keyword tokens from description input
$KEYWORDS = array();

//Variable for the company to be filtered
$companyFilter = '';

if (isset($_GET["location"]) && isset($_GET["description"])) {
    $monster_scraper = new MonsterScraper();
    // $db_conn = get_job_lube_db_conn();
    $JOBS = array();

    //Format search terms for relevance-search keywords
    $input = str_replace(',', ' ', $_GET["description"]);

    //Get keyword tokens from description input
    $KEYWORDS = explode(" ", $input);
    //Log each term
    foreach($KEYWORDS as $keyword) {
        log_search_term($keyword);
    }

    //If there are any results returned from scraping monster, collect them.
    if (!is_null($monster_results = $monster_scraper->scrape_monster($_GET["location"], $_GET["description"]))) {
        $JOBS += $monster_results;
    }
    	 if (isset($_GET["filter-by-company"])) {
    		$companyFilter = $_GET["filter-by-company"];
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
                <th>Views</th>
                <th>Location</th>
                <th>Company</th>
                <th>Date</th>
                <th>Twitter</th>
              </tr>
            </thead>
            <tbody>';
    foreach ($JOBS as $job) {
    	 //if the companyFilter is empty or if the job contains the companyFilter, then show the job
    	    if (isSubstring($job->getCompany(), $companyFilter) !== false) {
    	    	    //reason for comparing the above statement with !==false is b/c 
    	    	    // in the strpos(mainString, subString), if subString starts with the first character, strpos will return 0 (which will return false)
    	    	echo "<tr>
                	<td><a class='post_link' href='" . $job->getURL() . "' target ='_blank'>" . $job->getDescription() . "</a></td>
			 <td>" . get_views($job->getURL()) . "</td>
			 <td>" . $job->getLocation() . "</td>
			 <td>" . $job->getCompany() . "</td>
			 <td>" . $job->getDate() . "</td>
			 <td><a href=\"https://twitter.com/share\" class=\"twitter-share-button\" data-dnt=\"false\" data-count=\"none\" data-related=\"qi:Social Media Expert\" data-hashtags=\"JobLube\" data-text=\"I found this job: ". $job->getDescription() . " " . $job->getURL() ."\">Tweet</a></td>
              </tr>";
      }

    }
    echo '</tbody> 
        </table> 
        </div>';
}

/* function to see if the second string is part of the first string 
	same as strpos except if the second string is empty then this
	function returns true */
function isSubstring($mainString, $subString) {
	if ($subString == '' or $subString == null) {
		return true;
	}
	else {
		//convert both strings to lower case
		$mainString = strtolower($mainString);
		$subString = strtolower($subString);
		return strpos($mainString, $subString);
	}
}

?>
