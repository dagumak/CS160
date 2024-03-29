<?php

/**
 * Parent class of the scrapers for Monster and Dice.
 * 
 * @author Phil Gebhardt
 */

 class Scraper {
 
	/* WARNING: This function converts special and reserved
	 * characters, such as "#" into their URL safe equivalents.
	 * This function assumes that all websites use the same
	 * symbol conversion rules. If this assumption is false, this
	 * function must be defined by the child class.
	 * Found at: http://www.blooberry.com/indexdot/html/topics/urlencoding.htm
	 */
	public function parseSymbols($string) {
		$string = str_replace(", ", "%2C", $string);	//Comma with a space
		$string = str_replace('+', '%2B', $string);
		$string = str_replace(',', '%2C', $string);
		$string = str_replace('#', '%23', $string);
		return $string;
	}
 }
?>