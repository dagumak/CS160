<?php

require_once('scrapers/simple_html_dom.php');
require_once('scrapers/JobListing.php');
require_once('scrapers/monster/MonsterJob.php');

/**
 * Scraper for scraping job listings from Monster.com
 */
class MonsterScraper {

    public function scrape_monster($location, $description) {
        $URL = "http://jobsearch.monster.com/search/" . str_replace(' ', '-', $description) . "_5?where=" . $location;

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
