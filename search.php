<?php
require_once 'scrapers/monster/MonsterScraper.php';

if (isset($_GET["location"]) && isset($_GET["description"])) {
    $monster_scraper = new MonsterScraper();
    $JOBS = array();
    //If there are any results returned from scraping monster, collect them.
    if ($monster_results = $monster_scraper->scrape_monster($_GET["location"], $_GET["description"])) {
        $JOBS += $monster_results;
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

        echo "<tr>
				<td>" . $job->getDescription() . "</td>
				<td>" . $job->getLocation() . "</td>
				<td>" . $job->getCompany() . "</td>
				<td>" . $job->getDate() . "</td>
		  	  </tr>";
    }

    echo '</tbody> 
        </table> 
        </div>';
}
?>
