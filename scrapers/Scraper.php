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
		$string = str_replace("\x20", "-", $string);	//Space replacement
		$string = str_replace("\t", "-", $string);		//Tab replacement
		$string = str_replace('+', '__2B', $string);
		$string = str_replace(',', '__2C', $string);
		$string = str_replace('#', '__23', $string);
		return $string;
	}
 }
?>