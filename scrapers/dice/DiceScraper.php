<?php

require_once('scrapers/Scraper.php');
require_once('scrapers/JobListing.php');
require_once('scrapers/dice/DiceJob.php');

/**
 * Scraper for scraping job listings from dice.com
 */
class DiceScraper extends Scraper {

    public function scrape_dice($location, $description, $radius) {
    	$diceRadius = 32.18688; //diceRadius is initialized to 20 miles
    	
    	//dice.com has its own special value for the radius
    	switch ($radius) {
    		case 5:
    			$diceRadius = 8.04672;
    			break;
		case 10:
			$diceRadius = 16.09344;
			break;
		case 20:
			$diceRadius = 32.18688;
			break;
		case 30:
			$diceRadius = 48.28032;
			break;
		case 40:
			$diceRadius = 64.37376;
			break;
		case 50:
			$diceRadius = 80.4672;
			break;
		case 75: 
			$diceRadius = 120.7008;
			break;
    	}

        $numTerms = substr_count($description, ','); //This counts the number of independently inputed search terms
        //$tail = str_repeat("5", $numTerms); //Monster URLS seem to append a '5' for every separated search term
        //Format description and location
        //$description = str_replace(' ', '-', $description); //Removes spaces for URL
        //$location = str_replace(' ', '-', $location); //Removes spaces for URL
        //$location = str_replace(',', '__2C', $location); //Formats comma for URL ('__2C' = ',') 

        $description = $this->parseSymbols($description);
        $location = $this->parseSymbols($location);

        $url = "http://seeker.dice.com/jobsearch/servlet/JobSearch?op=300&N=0&Hf=0&NUM_PER_PAGE=30&Ntk=JobSearchRanking&Ntx=mode+matchall&AREA_CODES=&AC_COUNTRY=1525&QUICK=1&ZIPCODE=&RADIUS=$diceRadius&ZC_COUNTRY=0&COUNTRY=1525&STAT_PROV=0&METRO_AREA=33.78715899%2C-84.39164034&TRAVEL=0&TAXTERM=0&SORTSPEC=0&FRMT=0&DAYSBACK=30&LOCATION_OPTION=2&FREE_TEXT=$description&WHERE=$location";

        $searchResult = file_get_contents($url);

        $dom = new DOMDocument();
        @$dom->loadHTML($searchResult);
        $xPath = new DOMXPath($dom);

        $JOBS = array();

        $elements = $xPath->query("//tr[@class='STDsrRes']");

        foreach ($elements as $e) {
            $JOBS[] = $this->parseJobListing($e);
        }

        if (count($JOBS) == 0)
            return null;
        return $JOBS;
    }

    private function parseJobListing($e) {
        $dice_job = new DiceJob();
        $description = trim($e->childNodes->item(0)->childNodes->item(1)->childNodes->item(0)->nodeValue);
        $url = "http://seeker.dice.com" . $e->childNodes->item(0)->childNodes->item(1)->childNodes->item(0)->attributes->item(0)->nodeValue;
        $company = trim($e->childNodes->item(2)->childNodes->item(1)->nodeValue);
        $location = trim($e->childNodes->item(4)->nodeValue);
        $date = trim($e->childNodes->item(6)->nodeValue);

        $dice_job->setLocation($location);
        $dice_job->setDescription($description);
        $dice_job->setCompany($company);
        $dice_job->setDate($date);
        $dice_job->setURL($url);
        return $dice_job;
    }

}
