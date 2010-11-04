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
	 * Makes a closing on the system's data leaving only the provided days of information.
	 *
	 * It also makes a backup of the database till the actual date. It returns the name of the backup file that
	 * is stored in the BACKUP_DIR constant directory value.
	 * @param integer $days
	 * @return string
	 */
	static public function apply($days){
		Number::validatePositiveInteger($days, 'Cantidad de dias inv&aacute;lida.');
		return ClosingEventDAM::apply($days);
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
		$this->_mNit = $nit;
		String::validateNit($nit, 'Nit inv&aacute;lido.');
	}
	
	/**
	 * Sets the company's name.
	 *
	 * @param string $name
	 */
	public function setName($name){
		$this->_mName = $name;
		String::validateString($name, 'Nombre inv&aacute;lido.');
	}
	
	/**
	 * Saves the company's data in the database.
	 *
	 */
	public function save(){
		$this->validateMainProperties();
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
	
	/**
	 * Validates the object's main properties.
	 * 
	 * Verifies that the company's nit and name are set correctly. Otherwise it throws an exception.
	 */
	private function validateMainProperties(){
		String::validateNit($this->_mNit, 'Nit inv&aacute;lido.', 'nit');
		String::validateString($this->_mName, 'Nombre inv&aacute;lido.', 'name');
	}
}


/**
 * Utility class for obtaining the history of changes of prices on products.
 * @package Various
 * @author Roberto Oliveros
 */
class ChangePriceList{
	/**
	 * Retuns an array with the report information.
	 *
	 * The array's fields are date, username, bar_code, manufacturer, name, packaging, last_price and new_price.
	 * If no page argument or cero is passed all the details are returned. The total_pages and total_items
	 * arguments are necessary to return their respective values. Date format: 'dd/mm/yyyy'.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList($firstDate, $lastDate, &$total_pages = 0, &$total_items = 0, $page = 0){
		Date::validateDate($firstDate, 'Fecha inicial inv&aacute;lida.');
		Date::validateDate($lastDate, 'Fecha final inv&aacute;lida.');
		if($page !== 0)
			Number::validatePositiveInteger($page, 'Pagina inv&aacute;lida.');
			
		return ChangePriceListDAM::getList($firstDate, $lastDate, $total_pages, $total_items, $page);
	}
}


/**
 * Utility class for obtaing the discount report data.
 * @package Various
 * @author Roberto Oliveros
 */
class DiscountList{
	/**
	 * Retuns an array with the report information.
	 *
	 * The array's fields are date, username, serial_number, number, subtotal, percentage, amount and total.
	 * If no page argument or cero is passed all the details are returned. The total_pages and total_items
	 * arguments are necessary to return their respective values. Date format: 'dd/mm/yyyy'.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList($firstDate, $lastDate, &$total_pages = 0, &$total_items = 0, $page = 0){
		Date::validateDate($firstDate, 'Fecha inicial inv&aacute;lida.');
		Date::validateDate($lastDate, 'Fecha final inv&aacute;lida.');
		if($page !== 0)
			Number::validatePositiveNumber($page, 'Pagina inv&aacute;lida.');
			
		return DiscountListDAM::getList($firstDate, $lastDate, $total_pages, $total_items, $page);
	}
}


/**
 * Utility class for obtaining the history of document cancelled.
 * @package Various
 * @author Roberto Oliveros
 */
class CancelDocumentList{
	/**
	 * Retuns an array with the report information.
	 *
	 * The array's fields are date, username, document, number and total.
	 * If no page argument or cero is passed all the details are returned. The total_pages and total_items
	 * arguments are necessary to return their respective values. Date format: 'dd/mm/yyyy'.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList($firstDate, $lastDate, &$total_pages = 0, &$total_items = 0, $page = 0){
		Date::validateDate($firstDate, 'Fecha inicial inv&aacute;lida.');
		Date::validateDate($lastDate, 'Fecha final inv&aacute;lida.');
		if($page !== 0)
			Number::validatePositiveNumber($page, 'Pagina inv&aacute;lida.');
			
		return CancelDocumentListDAM::getList($firstDate, $lastDate, $total_pages, $total_items, $page);
	}
}


/**
 * Utility class for obtaining the history of cash register document cancelled.
 * @package Various
 * @author Roberto Oliveros
 */
class CancelCashDocumentList{
	/**
	 * Retuns an array with the report information.
	 *
	 * The array's fields are date, username, document, number and total.
	 * If no page argument or cero is passed all the details are returned. The total_pages and total_items
	 * arguments are necessary to return their respective values. Date format: 'dd/mm/yyyy'.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList($firstDate, $lastDate, &$total_pages = 0, &$total_items = 0, $page = 0){
		Date::validateDate($firstDate, 'Fecha inicial inv&aacute;lida.');
		Date::validateDate($lastDate, 'Fecha final inv&aacute;lida.');
		if($page !== 0)
			Number::validatePositiveNumber($page, 'Pagina inv&aacute;lida.');
			
		return CancelCashDocumentListDAM::getList($firstDate, $lastDate, $total_pages, $total_items, $page);
	}
}
?>