<?php

require_once('scrapers/simple_html_dom.php');
require_once('scrapers/JobListing.php');
require_once('scrapers/monster/MonsterJob.php');

/**
 * Scraper for scraping job listings from Monster.com
 */
class MonsterScraper {

    public function scrape_monster($location, $description) {

        // Create DOM from URL or file
        $ckfile = "cookies.txt";
        $useragent = "Mozilla/5.0 (iPhone; U; CPU iPhone OS 3_0 like Mac OS X; en-us) AppleWebKit/528.18 (KHTML, like Gecko) Version/4.0 Mobile/7A341 Safari/528.16"; // Sets user agent to iphone
        $url = "http://jobsearch.monster.com/search/" . str_replace(' ', '-', $description) . "_5?where=" . $location;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent); // set user agent
        curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $ckfile);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $searchResult = curl_exec($ch);

        $dom = new DOMDocument();
        @$dom->loadHTML($searchResult);
        $xPath = new DOMXPath($dom);

        $JOBS = array();

        $elements = $xPath->query("//tr[@class='odd']");

        foreach ($elements as $e) {
            $JOBS[] = $this->parseJobListing($e);
        }
        $elements = $xPath->query("//tr[@class='even']");
        foreach ($elements as $e) {
            $JOBS[] = $this->parseJobListing($e);
        }

        if (count($JOBS) == 0)
            return null;
        return $JOBS;
    }

    private function parseJobListing($e) {
        $monster_job = new MonsterJob();

        $jobTitleAttrs = $e->childNodes->item(2)->childNodes->item(1)->childNodes->item(1)->childNodes->item(1)->attributes;
        $description = $jobTitleAttrs->item(2)->nodeValue;
        $url = $jobTitleAttrs->item(5)->nodeValue;
        $location = $e->childNodes->item(4)->childNodes->item(1)->childNodes->item(1)->childNodes->item(1)->attributes->item(1)->nodeValue;
        $company = $e->childNodes->item(2)->childNodes->item(1)->childNodes->item(3)->childNodes->item(1)->childNodes->item(1)->nodeValue;
        $date = trim($e->childNodes->item(2)->childNodes->item(1)->childNodes->item(7)->nodeValue);

        $monster_job->setLocation($location);
        $monster_job->setDescription($description);
        $monster_job->setCompany($company);
        $monster_job->setDate($date);
        $monster_job->setURL($url);
        return $monster_job;
    }

}
