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
        $company = $e->childNodes->item(2)->childNodes->item(1);
		//Some company name fields on dice.com are not wrapped with href attribute tags
		//This check prevents php from reporting an error, such companies are listed by
		//joblube.com as unknown.
		if($company != null) $company = trim($company->nodeValue);
		else($company = "Unknown");
        $location = trim($e->childNodes->item(4)->nodeValue);
        $date = trim($e->childNodes->item(6)->nodeValue);

        $modifiedDate = $this->normalizeDate($date);
        $dice_job->setLocation($location);
        $dice_job->setDescription($description);
        $dice_job->setCompany($company);
        $dice_job->setDate($modifiedDate);
        $dice_job->setUrl($url);
        return $dice_job;
    }
    
    /* This method is to change the date format returned by Dice. 
     The format will be similar to that of Monster's. For example, if the job 
     listing date was Nov-20-2012 and today is Dec-10-2012, this method will 
     return the string '19 days ago' */
    private function normalizeDate($date) {
    	    $newDate;
    	    $month = substr($date, 0, 3); //extract the month out of the date
    	    $day = substr($date, 4, 2); //extract the day out of the date
    	    settype($day, "integer"); //set day to integer
    	    $daysago;
    	    $intMonth;
    	    $additionalDays; //if month is not the same then add additional days
    	   
    	    /* This is how the addition is done. Based on what the previous 
    	    month was, subtract the day from the total number in the month
    	    $additionalDays = total number of days in the month - day [of the listing]
    	    $daysago = additionalDays + today's day */
    	    
    	    switch ($month) {
    	    	case "Jan":
    	    		$additionalDays = 31 - $day;
    	    		$intMonth = 1;
    	    		break;
    	    	case "Feb":
    	    		$additionalDays = 29 - $day;
    	    		$intMonth = 2;
    	    		break;
    	    	case "Mar":
    	    		$additionalDays = 31 - $day;
    	    		$intMonth = 3;
    	    		break;
    	    	case "Apr":
    	    		$additionalDays = 30 - $day;
    	    		$intMonth = 4;
    	    		break;
    	    	case "May":
    	    		$additionalDays = 31 - $day;
    	    		$intMonth = 5;
    	    		break;
    	    	case "Jun":
    	    		$additionalDays = 30 - $day;
    	    		$intMonth = 6;
    	    		break;
    	    	case "Jul":
    	    		$additionalDays = 31 - $day;
    	    		$intMonth = 7;
    	    		break;
    	    	case "Aug": 
    	    		$additionalDays = 31 - $day;
    	    		$intMonth = 8;
    	    		break;
    	    	case "Sep":
    	    		$additionalDays = 30 - $day;
    	    		$intMonth = 9;
    	    		break;
    	    	case "Oct":
    	    		$additionalDays = 31 - $day;
    	    		break;
    	    	case "Nov":
    	    		$additionalDays = 30 - $day;
    	    		$intMonth = 11;
    	    		break;
    	    	case "Dec":
    	    		$additionalDays = 31 - $day;
    	    		$intMonth = 12;
    	    		break;
    	    }
    	    
    	    $todaysDay = date('d');
    	    $currentMonth = date('m');
    	    settype($todaysDay, "integer");
    	    settype($currentMonth, "integer");
    	    settype($additionalDays, "integer");
    	    settype($intMonth, "integer");
    	    settype($daysago, "integer");
    	    
    	    //if the job listing month is same as the current month
    	    if ($intMonth == $currentMonth) {
    	    	    if ($day == $todaysDay) {//if the date is same as today's date 
    	    	    	return "Today";
    	    	    }
    	    	    //subtract today's day from the job listing day
    	    	    $daysago = $todaysDay - $day; 
    	    }
    	    else {
    	    	    //add this months days to the last month's days from the job listing day
    	    	    $daysago = ($todaysDay + $additionalDays - 1); 
    	    }
    	    
    	    if ($daysago == 1) {
    	    	    $newDate = $daysago . " day ago";
    	    }
    	    else {
    	    	    $newDate = $daysago . " days ago";
    	    }
    	    
    	    return $newDate;
    }
	public function parseSymbols($string)
	{
		$string = parent::parseSymbols($string);
		$string = str_replace("\x20", "+", $string);	//Space replacement
		$string = str_replace("\t", "+", $string);		//Tab replacement
		return $string;
	}
}
