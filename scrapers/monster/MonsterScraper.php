<?php
require_once('scrapers/Scraper.php');
require_once('scrapers/JobListing.php');
require_once('scrapers/monster/MonsterJob.php');

/**
 * Scraper for scraping job listings from Monster.com
 */
class MonsterScraper extends Scraper {

    public function scrape_monster($location, $description) {

        $numTerms = substr_count($description, ','); //This counts the number of independently inputed search terms
        $tail = str_repeat("5", $numTerms); //Monster URLS seem to append a '5' for every separated search term
        //Format description and location
        //$description = str_replace(' ', '-', $description); //Removes spaces for URL
        //$location = str_replace(' ', '-', $location); //Removes spaces for URL
        //$location = str_replace(',', '__2C', $location); //Formats comma for URL ('__2C' = ',') 

		$description = $this->parseSymbols($description);
		$location = $this->parseSymbols($location);
		
        $url = "http://jobsearch.monster.com/search/" . $description . "_5" . $tail . "?where=" . $location;
		
        /*// Create DOM from URL or file
        $ckfile = "cookies.txt";
        //This should probably be changed to something dynamic.
        $useragent = "Mozilla/5.0 (iPhone; U; CPU iPhone OS 3_0 like Mac OS X; en-us) AppleWebKit/528.18 (KHTML, like Gecko) Version/4.0 Mobile/7A341 Safari/528.16"; // Sets user agent to iphone

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent); // set user agent
        curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $ckfile);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $searchResult = curl_exec($ch);*/
        //The above CURl doesn't work with legacy PHP
        $searchResult = file_get_contents($url);

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
        $description = trim($jobTitleAttrs->item(2)->nodeValue);
        $url = $jobTitleAttrs->item(5)->nodeValue;
        $location = trim($e->childNodes->item(4)->nodeValue);
        $company = trim($e->childNodes->item(2)->childNodes->item(1)->childNodes->item(3)->childNodes->item(1)->childNodes->item(1)->nodeValue);
        $date = trim($e->childNodes->item(2)->childNodes->item(1)->childNodes->item(7)->nodeValue);

        $monster_job->setLocation($location);
        $monster_job->setDescription($description);
        $monster_job->setCompany($company);
        $monster_job->setDate($date);
        $monster_job->setURL($url);
        return $monster_job;
    }

}
