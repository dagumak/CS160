<?php

require_once('scrapers/simple_html_dom.php');
require_once('scrapers/JobListing.php');
require_once('scrapers/monster/MonsterJob.php');

/**
 * Scraper for scraping job listings from Monster.com
 */
class MonsterScraper {

    public function scrape_monster($location, $description) {
	
		$numTerms = substr_count($description, ','); //This counts the number of independently inputed search terms
		$tail = str_repeat("5", $numTerms); //Monster URLS seem to append a '5' for every separated search term
		
		//Format description and location
		$description = str_replace(' ', '-', $description); //Removes spaces for URL
		$location = str_replace(' ', '-', $location); //Removes spaces for URL
		$location = str_replace(',', '__2C', $location); //Formats comma for URL ('__2C' = ',') 
		
        $URL = "http://jobsearch.monster.com/search/" . $description . "_5" . $tail . "?where=" . $location;

        $html = file_get_html($URL);

        $JOBS = array();

        foreach ($html->find('tr.odd') as $e) {
            $JOBS[] = $this->parseJobListing($e);
        }

        foreach ($html->find('tr.even') as $e) {
            $JOBS[] = $this->parseJobListing($e);
        }

        if (count($JOBS) == 0)
            return null;
        return $JOBS;
    }

    private function parseJobListing($e) {
        $monster_job = new MonsterJob();
        $location = $e->find('div.jobLocationSingleLine');
        $description = $e->find('div.jobTitleContainer');
        $company = $e->find('div.companyContainer');
        $date = $e->find('div.fnt20');

        $monster_job->setLocation($location[0]->plaintext);
        $monster_job->setDescription($description[0]->plaintext);
        $monster_job->setCompany($company[0]->plaintext);
        $monster_job->setDate($date[0]->plaintext);
        return $monster_job;
    }

}
