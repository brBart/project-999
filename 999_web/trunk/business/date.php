<?php
/**
 * Library with utility class with validation or other routines regarding dates.
 * @package Date
 * @author Roberto Oliveros
 */

/**
 * Class with needed routines regarding dates.
 * @package Date
 * @author Roberto Oliveros
 */
class Date{
	/**
	 * Validates the provided date.
	 *
	 * Verifies if it is a valid date. Otherwise it throws an exception.
	 * @param string $date
	 * @return void
	 */
	static public function validateDate($date){
		$date_array = explode('/', $date);
		
		$day = (int)$date_array[0];
		$month = (int)$date_array[1];
		$year = (int)$date_array[2];
		
		if(!checkdate($month, $day, $year))
			throw new Exception('Fecha inv&aacute;lida.');
	}
	
	/**
	 * Compares if the last date is greater than the first date.
	 *
	 * Returns true if it is the case.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @return boolean
	 */
	static public function compareDates($firstDate, $lastDate){
		$first_date = Date::englishFormat($firstDate);
		$last_date = Date::englishFormat($lastDate);
		
		$nix_first_date = strtotime($first_date);
		$nix_last_date = strtotime($last_date);
		
		if($nix_first_date < $nix_last_date)
			return true;
		else
			return false;
	}
}
?>