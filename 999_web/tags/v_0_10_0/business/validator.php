<?php
/**
 * Library with utility class with validation or other routines.
 * @package Validator
 * @author Roberto Oliveros
 */

/**
 * Class that represents an exception when a validation has failed.
 * @package Validator
 * @author Roberto Oliveros
 */
class ValidateException extends Exception{
	/**
	 * Holds the name of the property.
	 * @var string
	 */
	private $_mProperty;
	
	/**
	 * Constructs the exception object with the property name.
	 * @param string $property
	 * @param string $msg
	 */
	public function __construct($msg, $property = NULL){
		parent::__construct($msg);
		
		$this->_mProperty = $property;
	}
	
	/**
	 * Returns the property name.
	 * @return string
	 */
	public function getProperty(){
		return $this->_mProperty;
	}
}

/**
 * Class with needed routines regarding dates.
 * @package Validator
 * @author Roberto Oliveros
 */
class Date{
	/**
	 * Validates the provided date.
	 *
	 * Verifies if it is a valid date. Otherwise it throws a validate exception.
	 * @param string $date
	 * @param string $msg
	 * @param string $property
	 * @throws ValidateException
	 */
	static public function validateDate($date, $msg, $property = NULL){
		$date_array = explode('/', $date);
		
		if((ctype_digit($date_array[0]) && ctype_digit($date_array[1])
				&& ctype_digit($date_array[2]))) {
			$day = (int)$date_array[0];
			$month = (int)$date_array[1];
			$year = (int)$date_array[2];
			
			if(checkdate($month, $day, $year))
				return;
		}
			
		throw new ValidateException($msg . ' No existe o debe ser en formato \'dd/mm/aaaa\'.', $property);
	}
	
	/**
	 * Validates the provided datetime value.
	 *
	 * Verifies if it is a valid date and time. Otherwise it throws a validate exception.
	 * @param string $dateTime
	 * @param string $msg
	 * @param string $property
	 * @throws ValidateException
	 */
	static public function validateDateTime($dateTime, $msg, $property = NULL){
		$date_array = explode(' ', $dateTime);
		self::validateDate($date_array[0], $msg);
		
		if(!preg_match('/([0-1]\d|2[0-3]):([0-5]\d):([0-5]\d)/', $date_array[1]))
			throw new ValidateException($msg . ' Formato de hora debe ser HH::MM:SS', $property);
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
	 * Change the dates format 'dd/mm/yyyy' to a database format 'yyyy/mm/dd'.
	 *
	 * @param string $date
	 * @return string
	 */
	static public function dbFormat($date){
		$date_array = explode('/', $date);
		
		$db_date_array = array(
						str_pad($date_array[2], 4, "0", STR_PAD_LEFT),
						str_pad($date_array[1], 2, "0", STR_PAD_LEFT),
						str_pad($date_array[0], 2, "0", STR_PAD_LEFT)
						);
		
		return implode('/', $db_date_array);
	}
	
	/**
	 * Change the dates format 'dd/mm/yyyy' to a database format 'yyyy/mm/dd'.
	 *
	 * @param string $dateTime
	 * @return string
	 */
	static public function dbDateTimeFormat($dateTime){
		$date_array = explode(' ', $dateTime);		
		return self::dbFormat($date_array[0]) . ' ' . $date_array[1];
	}
	
	/**
	 * Adds a cero to the left side in case the day or month values contains only 1 digit.
	 *
	 * Eg: 1/1/2009 becomes 01/01/2009.
	 * @param string $date
	 * @return string
	 */
	static public function paddingDate($date){
		$date_array = explode('/', $date);
		
		$db_date_array = array(sprintf('%02s', $date_array[0]), sprintf('%02s', $date_array[1]),
				$date_array[2]);
		
		return implode('/', $db_date_array);
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
	 * Must be an integer. Otherwise it throws a validate exception. The msg parameter is for displaying the
	 * desire message.
	 * @param integer $quantity
	 * @param string $property
	 * @throws ValidateException
	 */
	static public function validateInteger($number, $msg, $property = NULL){
		if(!is_int($number))
			throw new ValidateException($msg . ' Valor debe ser numerico.', $property);
	}
	
	/**
	 * Validates the provided number.
	 *
	 * Must be greater than cero. Otherwise it throws a validate exception. The msg parameter is for displaying
	 * the desire message.
	 * @param integer $number
	 * @param string $msg
	 * @param string $property
	 * @throws ValidateException
	 */
	static public function validatePositiveInteger($number, $msg, $property = NULL){
		if(!is_int($number) || $number < 1)
			throw new ValidateException($msg . ' Valor debe ser mayor que cero.', $property);
	}
	
	/**
	 * Validates the provided number.
	 *
	 * Must be greater or equal to cero. Otherwise it throws a validate exception. The msg parameter is for
	 * displaying the desire message.
	 * @param integer $number
	 * @param string $msg
	 * @param string $property
	 * @throws Exception
	 */
	static public function validateUnsignedInteger($number, $msg, $property = NULL){
		if(!is_int($number) || $number < 0)
			throw new ValidateException($msg . ' Valor no debe ser menor que cero.', $property);
	}
	
	/**
	 * Validates the provided number.
	 *
	 * Must be a float number. The msg parameter is for displaying the desire
	 * message.
	 * @param float $number
	 * @param string $msg
	 * @param string $property
	 * @throws ValidateException
	 */
	static public function validateFloat($number, $msg, $property = NULL){
		if(!is_float($number))
			throw new ValidateException($msg . ' Valor debe ser numerico.', $property);
	}
	
	/**
	 * Validates the provided number.
	 *
	 * Must be greater than cero. Otherwise it throws a validate exception. The msg parameter is for displaying
	 * the desire message.
	 * @param float $number
	 * @param string $msg
	 * @param string $property
	 * @throws ValidateException
	 */
	static public function validatePositiveFloat($number, $msg, $property = NULL){
		if(!is_float($number) || $number < 1)
			throw new ValidateException($msg . ' Valor no debe ser menor que cero.', $property);
	}
	
	/**
	 * Validates the provided number.
	 *
	 * Must be greater or equal to cero. Otherwise it throws a validate exception. The msg parameter is for
	 * displaying the desire message.
	 * @param float $number
	 * @param string $msg
	 * @param string $property
	 * @throws ValidateException
	 */
	static public function validateUnsignedFloat($number, $msg, $property = NULL){
		if(!is_float($number) || $number < 0)
			throw new ValidateException($msg . ' Valor no debe ser menor que cero.', $property);
	}
	
	/**
	 * Validates the provided number.
	 *
	 * Must be numeric. Otherwise it throws a validate exception. The msg parameter is for displaying the
	 * desire message.
	 * @param integer $quantity
	 * @param string $property
	 * @throws ValidateException
	 */
	static public function validateNumber($number, $msg, $property = NULL){
		if(!is_numeric($number))
			throw new ValidateException($msg . ' Valor debe ser numerico.', $property);
	}
	
	/**
	 * Validates the provided number.
	 *
	 * Must be greater than cero. Otherwise it throws a validate exception. The msg parameter is for displaying
	 * the desire message.
	 * @param string $number
	 * @param string $msg
	 * @param string $property
	 * @throws ValidateException
	 */
	static public function validatePositiveNumber($number, $msg, $property = NULL){
		if(!is_numeric($number) || !($number > 0))
			throw new ValidateException($msg . ' Valor debe ser mayor que cero.', $property);
	}
	
	/**
	 * Validates the provided number.
	 *
	 * Must be greater or equal to cero. Otherwise it throws a validate exception. The msg parameter is for
	 * displaying the desire message.
	 * @param string $number
	 * @param string $msg
	 * @param string $property
	 * @throws ValidateException
	 */
	static public function validateUnsignedNumber($number, $msg, $property = NULL){
		if(!is_numeric($number) || $number < 0)
			throw new ValidateException($msg . ' Valor no debe ser menor que cero.', $property);
	}
	
	/**
	 * Validates the provided number if it is between 0 to 99.
	 *
	 * Must be greater or equal to cero and less or equal to 99. Otherwise it throws a validate exception.
	 * The msg parameter is for displaying the desire message.
	 * @param string $number
	 * @param string $msg
	 * @param string $property
	 * @throws ValidateException
	 */
	static public function validateBetweenCeroToNinetyNineNumber($number, $msg, $property = NULL){
		if(!is_numeric($number) || ($number < 0 || $number > 99))
			throw new ValidateException($msg . ' Valor no debe ser menor que cero ni mayor que noventa y nueve.',
					$property);
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
	 * @param string $property
	 * @throws ValidateException
	 */
	static public function validateString($string, $msg, $property = NULL){
		if($string == '')
			throw new ValidateException($msg . ' Valor no puede ser vacio.', $property);
	}
	
	/**
	 * Validates if string is composed by only alphanumeric characters.
	 *
	 * The msg parameter is for displaying the desire message.
	 * @param string $string
	 * @param string $msg
	 * @param string $property
	 * @throws ValidateException
	 */
	static public function validateAlphanum($string, $msg, $property = NULL){
		if(!ctype_alnum($string))
			throw new ValidateException($msg . ' Valor solo puede ser alfanum&eacute;rico.', $property);
	}
	
	/**
	 * Validates the provided nit.
	 * 
	 * Verifies that the nit is set correctly, e.g. 1725045-5. Otherwise it throws a validate exception. The msg
	 * parameter is for displaying the desire message.
	 * @param string $nit
	 * @param string $property
	 * @throws ValidateException
	 */
	static public function validateNit($nit, $msg, $property = NULL){
		if(!preg_match('/^[0-9]+\-\w$/', $nit))
			throw new ValidateException($msg . ' Formato debe ser ######-#', $property);
	}
}
?>