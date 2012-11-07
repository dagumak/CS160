<?php
require_once 'scrapers/monster/MonsterScraper.php';

//Array for holding keyword tokens from description input
$KEYWORDS = array();

//Variable for the company to be filtered
$companyFilter = '';

if (isset($_GET["location"]) && isset($_GET["description"])) {
    $monster_scraper = new MonsterScraper();
    $JOBS = array();
	
	//Get keyword tokens from description input
	$KEYWORDS = explode(" ", $_GET["description"]);
	
    //If there are any results returned from scraping monster, collect them.
    if ($monster_results = $monster_scraper->scrape_monster($_GET["location"], $_GET["description"])) {
        $JOBS += $monster_results;
		
    }
    	 if (isset($_GET["filter-by-company"])) {
    		$companyFilter = $_GET["filter-by-company"];
    	} 

	if (isset($_GET["sort-by"])) {
		switch($_GET["sort-by"]) {
			case 'date':
				// Run function to sort the data by date and re-set the variable 
			    // sort by date
				usort($JOBS, 
					function($a, $b) {
						return $a->getDate() - $b->getDate();
					}
				);
				break;
			case 'relevance':
				// Run function to sort the data by relevance and re-set the variable
				// sort by relevance
				usort($JOBS,
					function($a, $b) {
						GLOBAL $KEYWORDS;
						$aDescription = $a->getDescription();
						$bDescription = $b->getDescription();
						$aNum = 0;
						$bNum = 0;
						foreach($KEYWORDS as $k) {
							if(strpos($aDescription, $k) === false);
							else $aNum++;
							if(strpos($bDescription, $k) === false);
							else $bNum++;
						}
						return $aNum - $bNum;
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
			  </tr>
			</thead>
			<tbody>';

    foreach ($JOBS as $job) {
    	    //if the companyFilter is empty or if the job contains the companyFilter, then show the job
    	    if (isSubstring($job->getCompany(), $companyFilter)) {
    	    	    echo "<tr>
    	    	    	<td>" . $job->getDescription() . "</td>
    	    	    	<td>" . $job->getLocation() . "</td>
    	    	    	<td>" . $job->getCompany() . "</td>
    	    	    	<td>" . $job->getDate() . "</td>
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
