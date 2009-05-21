<?php
/**
 * Library with utility class with validation or other routines.
 * @package Validator
 * @author Roberto Oliveros
 */

/**
 * Class with needed routines regarding dates.
 * @package Validator
 * @author Roberto Oliveros
 */
class Date{
	/**
	 * Validates the provided date.
	 *
	 * Verifies if it is a valid date. Otherwise it throws an exception.
	 * @param string $date
	 * @param string $msg
	 * @return void
	 * @throws Exception
	 */
	static public function validateDate($date, $msg){
		$date_array = explode('/', $date);
		
		$day = (int)$date_array[0];
		$month = (int)$date_array[1];
		$year = (int)$date_array[2];
		
		if(!checkdate($month, $day, $year))
			throw new Exception($msg . ' No existe o debe ser en formato \'dd\\mm\\yyyy\'.');
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
	
	/**
	 * Change the dates format 'dd/mm/yyyy' to an english format 'mm/dd/yyyy'.
	 *
	 * @param string $date
	 * @return string
	 */
	static private function englishFormat($date){
		$date_array = explode('/', $date);
		
		$eng_date_array = array($date_array[1], $date_array[0], $date_array[2]);
		
		return implode('/', $eng_date_array);
	}
}


/**
 * Class for validating values.
 * @package Validator
 * @author Roberto Oliveros
 */
class Number{
	/**
	 * Validates the provided number.
	 *
	 * Must be an integer. Otherwise it throws an exception. The msg parameter is for displaying the desire
	 * message.
	 * @param integer $quantity
	 * @throws Exception
	 */
	static public function validateInteger($number, $msg){
		if(!is_int($number))
			throw new Exception($msg . ' Valor debe ser numerico.');
	}
	
	/**
	 * Validates the provided number.
	 *
	 * Must be greater than cero. Otherwise it throws an exception. The msg parameter is for displaying
	 * the desire message.
	 * @param integer $number
	 * @param string $msg
	 * @throws Exception
	 */
	static public function validatePositiveInteger($number, $msg){
		if(!is_int($number) || $number < 1)
			throw new Exception($msg . ' Valor debe ser mayor que cero.');
	}
	
	/**
	 * Validates the provided number.
	 *
	 * Must be greater or equal to cero. Otherwise it throws an exception. The msg parameter is for
	 * displaying the desire message.
	 * @param integer $number
	 * @param string $msg
	 * @throws Exception
	 */
	static public function validateUnsignedInteger($number, $msg){
		if(!is_int($number) || $number < 0)
			throw new Exception($msg . ' Valor no debe ser menor que cero.');
	}
	
	/**
	 * Validates the provided number.
	 *
	 * Must be a float number. The msg parameter is for displaying the desire
	 * message.
	 * @param float $number
	 * @param string $msg
	 * @throws Exception
	 */
	static public function validateFloat($number, $msg){
		if(!is_float($number))
			throw new Exception($msg . ' Valor debe ser numerico.');
	}
	
	/**
	 * Validates the provided number.
	 *
	 * Must be greater than cero. Otherwise it throws an exception. The msg parameter is for displaying
	 * the desire message.
	 * @param float $number
	 * @param string $msg
	 * @throws Exception
	 */
	static public function validatePositiveFloat($number, $msg){
		if(!is_float($number) || $number < 1)
			throw new Exception($msg . ' Valor no debe ser menor que cero.');
	}
	
	/**
	 * Validates the provided number.
	 *
	 * Must be greater or equal to cero. Otherwise it throws an exception. The msg parameter is for
	 * displaying the desire message.
	 * @param float $number
	 * @param string $msg
	 * @throws Exception
	 */
	static public function validateUnsignedFloat($number, $msg){
		if(!is_float($number) || $number < 0)
			throw new Exception($msg . ' Valor no debe ser menor que cero.');
	}
}


/**
 * Class for validating string values.
 * @package Validator
 * @author Roberto Oliveros
 */
class String{
	/**
	 * Validates a normal string.
	 *
	 * Must not be empty. The msg parameter is for displaying the desire
	 * message.
	 * @param string $string
	 * @param string $msg
	 * @throws Exception
	 */
	static public function validateString($string, $msg){
		if(empty($string))
			throw new Exception($msg . ' Valor no puede ser vacio.');
	}
	
	/**
	 * Validates the provided nit.
	 * 
	 * Verifies that the nit is set correctly, e.g. 1725045-5. Otherwise it throws an exception. The msg
	 * parameter is for displaying the desire message.
	 * @param string $nit
	 * @return void
	 * @throws Exception
	 */
	static public function validateNit($nit, $msg){
		if(!preg_match('/^[0-9]+[-][0-9]$/', $nit))
			throw new Exception($msg . ' Formato debe ser ######-#');
	}
}
?>