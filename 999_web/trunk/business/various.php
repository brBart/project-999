<?php
/**
 * Library with utility classes for multiple purposes.
 * @package Various
 * @author Roberto Oliveros
 */

/**
 * For validation purposes.
 */
require_once('business/validator.php');
/**
 * For accessing the database.
 */
require_once('data/various_dam.php');

/**
 * Class to perform a closing event in the system's data.
 * @package Various
 * @author Roberto Oliveros
 */
class ClosingEvent{
	/**
	 * Makes a closing on the system's data leaving only the provided months of information.
	 *
	 * @param integer $months
	 */
	static public function apply($months){
		Number::validatePositiveInteger($months, 'Cantidad de meses inv&aacute;lida.');
		ClosingEventDAM::apply($months);
	}
}


/**
 * Represents the company that owns the system.
 * @package Various
 * @author Roberto Oliveros
 */
class Company{
	/**
	 * Holds the company's nit (Numero de Identificacion Tributaria).
	 * 
	 * Note that must match the "^[0-9]+[-][0-9]$" pattern to be valid, e.g. 1725045-5.
	 * @var string
	 */
	private $_mNit;
	
	/**
	 * Holds the company's name.
	 *
	 * @var string
	 */
	private $_mName;
	
	/**
	 * Constructs the company with the provided data.
	 *
	 * @param string $nit
	 * @param string $name
	 */
	public function __construct($nit, $name){
		try{
			String::validateNit($nit, 'Nit inv&aacute;lido.');
			String::validateString($name, 'Nombre inv&aacute;lido.');
		} catch(Exception $e){
			$et = new Exception('Interno: Llamando al metodo construct en Company con datos erroneos! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mNit = $nit;
		$this->_mName = $name;
	}
	
	/**
	 * Returns the company's nit.
	 *
	 * @return string
	 */
	public function getNit(){
		return $this->_mNit;
	}
	
	/**
	 * Returns the company's name.
	 *
	 * @return string
	 */
	public function getName(){
		return $this->_mName;
	}
	
	/**
	 * Sets the company's nit.
	 *
	 * @param string $nit
	 */
	public function setNit($nit){
		String::validateNit($nit, 'Nit inv&aacute;lido.');
		$this->_mNit = $nit;
	}
	
	/**
	 * Sets the company's name.
	 *
	 * @param string $name
	 */
	public function setName($name){
		String::validateString($name, 'Nombre inv&aacute;lido.');
		$this->_mName = $name;
	}
	
	/**
	 * Saves the company's data in the database.
	 *
	 */
	public function save(){
		CompanyDAM::update($this);
	}
	
	/**
	 * Returns an instance of the company.
	 *
	 * @return Company
	 */
	static public function getInstance(){
		return CompanyDAM::getInstance();
	}
}


/**
 * Utility class for obtaining the history of changes of prices on products.
 * @package Various
 * @author Roberto Oliveros
 */
class ChangePriceReport{
	/**
	 * Retuns an array with the report information.
	 *
	 * The array's fields are date, username, bar_code, manufacturer, name, packaging, last_price and new_price.
	 * If no page argument or cero is passed all the details are returned. The total_pages and total_items
	 * arguments are necessary to return their respective values. Date format: 'dd/mm/yyyy'.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer $page
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @return array
	 */
	static public function getData($firstDate, $lastDate, $page = 0, &$total_pages = 0, &$total_items = 0){
		Date::validateDate($firstDate, 'Fecha inicial inv&aacute;lida.');
		Date::validateDate($lastDate, 'Fecha final inv&aacute;lida.');
		if($page !== 0)
			Number::validatePositiveInteger($page, 'Pagina inv&aacute;lida.');
			
		return ChangePriceReportDAM::getData($firstDate, $lastDate, $page, $total_pages, $total_items);
	}
}


/**
 * Utility class for obtaing the discount report data.
 * @package Various
 * @author Roberto Oliveros
 */
class DiscountReport{
	/**
	 * Retuns an array with the report information.
	 *
	 * The array's fields are date, username, serial_number, number, subtotal, percentage, amount and total.
	 * If no page argument or cero is passed all the details are returned. The total_pages and total_items
	 * arguments are necessary to return their respective values. Date format: 'dd/mm/yyyy'.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer $page
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @return array
	 */
	static public function getData($firstDate, $lastDate, $page = 0, &$total_pages = 0, &$total_items = 0){
		Date::validateDate($firstDate, 'Fecha inicial inv&aacute;lida.');
		Date::validateDate($lastDate, 'Fecha final inv&aacute;lida.');
		if($page !== 0)
			Number::validatePositiveInteger($page, 'Pagina inv&aacute;lida.');
			
		return DiscountReportDAM::getData($firstDate, $lastDate, $page, $total_pages, $total_items);
	}
}
?>