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
	 * @param integer $days
	 * @return string
	 */
	static public function apply($days){
		Number::validatePositiveNumber($days, 'Cantidad de dias inv&aacute;lida.');
		return ClosingEventDAM::apply($days);
	}
}


/**
 * Class to perform a backup of the system's data.
 * @package Various
 * @author Roberto Oliveros
 */
class BackupEvent{
	/**
	 * It makes a backup of the database till the actual date. It returns the name of the backup file that
	 * is stored in the BACKUP_DIR constant directory value.
	 * @return string
	 */
	static public function apply(){
		return BackupEventDAM::apply();
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
	 * Holds the company's corporate name.
	 *
	 * @var string
	 */
	private $_mCorporateName;
	
	/**
	 * Holds the company's telephone number.
	 *
	 * @var string
	 */
	private $_mTelephone;
	
	/**
	 * Holds the company's address.
	 *
	 * @var string
	 */
	private $_mAddress;
	
	/**
	 * Constructs the company with the provided data.
	 *
	 * @param string $nit
	 * @param string $name
	 */
	public function __construct($nit, $name, $corporateName, $telephone, $address){
		try{
			String::validateNit($nit, 'Nit inv&aacute;lido.');
			String::validateString($name, 'Nombre inv&aacute;lido.');
			String::validateString($corporateName, 'Raz&oacute;n social inv&aacute;lida.');
			String::validateString($telephone, 'Telefono inv&aacute;lido.');
			String::validateString($address, 'Direcci&oacute;n inv&aacute;lida.');
		} catch(Exception $e){
			$et = new Exception('Interno: Llamando al metodo construct en Company con datos erroneos! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mNit = $nit;
		$this->_mName = $name;
		$this->_mCorporateName = $corporateName;
		$this->_mTelephone = $telephone;
		$this->_mAddress = $address;
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
	 * Returns the company's corporate name.
	 *
	 * @return string
	 */
	public function getCorporateName(){
		return $this->_mCorporateName;
	}
	
	/**
	 * Returns the company's telephone number.
	 *
	 * @return string
	 */
	public function getTelephone(){
		return $this->_mTelephone;
	}
	
	/**
	 * Returns the company's address.
	 *
	 * @return string
	 */
	public function getAddress(){
		return $this->_mAddress;
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
	 * Sets the company's corporate name.
	 *
	 * @param string $name
	 */
	public function setCorporateName($name){
		$this->_mCorporateName = $name;
		String::validateString($name, 'Raz&oacute;n social inv&aacute;lida.');
	}
	
	/**
	 * Sets the company's telephone number.
	 *
	 * @param string $telephone
	 */
	public function setTelephone($telephone){
		$this->_mTelephone = $telephone;
		String::validateString($telephone, 'Telefono inv&aacute;lido.');
	}
	
	/**
	 * Sets the company's address.
	 *
	 * @param string $address
	 */
	public function setAddress($address){
		$this->_mAddress = $address;
		String::validateString($address, 'Direcci&oacute;n inv&aacute;lida.');
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
	 * Verifies that the company's values are set correctly. Otherwise it throws an exception.
	 */
	private function validateMainProperties(){
		String::validateNit($this->_mNit, 'Nit inv&aacute;lido.', 'nit');
		String::validateString($this->_mName, 'Nombre inv&aacute;lido.', 'name');
		String::validateString($this->_mCorporateName, 'Raz&oacute;n social inv&aacute;lida.', 'corporate_name');
		String::validateString($this->_mTelephone, 'Telefono inv&aacute;lido.', 'telephone');
		String::validateString($this->_mAddress, 'Direcci&oacute;n inv&aacute;lida.', 'address');
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
	 * The array's fields are date, username, bar_code, manufacturer, name, last_price and new_price.
	 * If no page argument or cero is passed all the details are returned. The total_pages and total_items
	 * arguments are necessary to return their respective values. Date format: 'dd/mm/yyyy'.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList($firstDate, $lastDate, &$totalPages = 0, &$totalItems = 0, $page = 0){
		Date::validateDate($firstDate, 'Fecha inicial inv&aacute;lida.');
		Date::validateDate($lastDate, 'Fecha final inv&aacute;lida.');
		if($page !== 0)
			Number::validatePositiveInteger($page, 'Pagina inv&aacute;lida.');
			
		return ChangePriceListDAM::getList($firstDate, $lastDate, $totalPages, $totalItems, $page);
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
	static public function getList($firstDate, $lastDate, &$totalPages = 0, &$totalItems = 0, $page = 0){
		Date::validateDate($firstDate, 'Fecha inicial inv&aacute;lida.');
		Date::validateDate($lastDate, 'Fecha final inv&aacute;lida.');
		if($page !== 0)
			Number::validatePositiveNumber($page, 'Pagina inv&aacute;lida.');
			
		return DiscountListDAM::getList($firstDate, $lastDate, $totalPages, $totalItems, $page);
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
	static public function getList($firstDate, $lastDate, &$totalPages = 0, &$totalItems = 0, $page = 0){
		Date::validateDate($firstDate, 'Fecha inicial inv&aacute;lida.');
		Date::validateDate($lastDate, 'Fecha final inv&aacute;lida.');
		if($page !== 0)
			Number::validatePositiveNumber($page, 'Pagina inv&aacute;lida.');
			
		return CancelDocumentListDAM::getList($firstDate, $lastDate, $totalPages, $totalItems, $page);
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
	static public function getList($firstDate, $lastDate, &$totalPages = 0, &$totalItems = 0, $page = 0){
		Date::validateDate($firstDate, 'Fecha inicial inv&aacute;lida.');
		Date::validateDate($lastDate, 'Fecha final inv&aacute;lida.');
		if($page !== 0)
			Number::validatePositiveNumber($page, 'Pagina inv&aacute;lida.');
			
		return CancelCashDocumentListDAM::getList($firstDate, $lastDate, $totalPages, $totalItems, $page);
	}
}


/**
 * Utility class for obtaining the ranking list of sales by product.
 * @package Various
 * @author Roberto Oliveros
 */
class SalesRankingList{
	/**
	 * Retuns an array with the report information.
	 *
	 * The array's fields are rank, bar_code, manufacturer, name and quantity.
	 * If no page argument or cero is passed all the details are returned. The total_pages and total_items
	 * arguments are necessary to return their respective values. Date format: 'dd/mm/yyyy'.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList($firstDate, $lastDate, &$totalPages = 0, &$totalItems = 0, $page = 0){
		Date::validateDate($firstDate, 'Fecha inicial inv&aacute;lida.');
		Date::validateDate($lastDate, 'Fecha final inv&aacute;lida.');
		if($page !== 0)
			Number::validatePositiveNumber($page, 'Pagina inv&aacute;lida.');
			
		return SalesRankingListDAM::getList($firstDate, $lastDate, $totalPages, $totalItems, $page);
	}
}


/**
 * Utility class for obtaining the sales and receipts stadistics from certain products.
 * @package Various
 * @author Roberto Oliveros
 */
class SalesAndPurchasesStadisticsList{
	/**
	 * Returns the stadistics for the range of products between first and last for the past number of months.
	 * Date format in dd/mm/yyyy.
	 * 
	 * @param string $first
	 * @param string $last
	 * @param string $date
	 * @param integer $numMonths
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getListByProduct($first, $last, $date, $numMonths, &$totalPages = 0, &$totalItems = 0, $page = 0){
		String::validateString($first, 'Seleccione el primer producto.');
		String::validateString($last, 'Seleccione el segundo producto.');
		
		$labels = SalesAndPurchasesStadisticsListDAM::getLabelsByProduct($first, $last, $totalPages, $totalItems, $page);
		
		$sales_data = array();
		$purchases_data = array();
		
		$date = Date::dbFormat($date);
		
		for($i = $numMonths; $i > 0 ; $i--){
			self::getRangeDates($date, $i, $start_date, $end_date);
			$sales_data[] = SalesAndPurchasesStadisticsListDAM::getSalesListByProduct($first, $last, $start_date, $end_date, $totalItems, $page);
			$purchases_data[] = SalesAndPurchasesStadisticsListDAM::getPurchasesListByProduct($first, $last, $start_date, $end_date, $totalItems, $page);
		}
		
		return self::prepareList($labels, $sales_data, $purchases_data);
	}
	
	/**
	 * Returns the stadistics for the range of manufacturers between first and last for the past number of months.
	 * Date format in dd/mm/yyyy.
	 * 
	 * @param string $first
	 * @param string $last
	 * @param string $date
	 * @param integer $numMonths
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getListByManufacturer($first, $last, $date, $numMonths, &$totalPages = 0, &$totalItems = 0, $page = 0){
		String::validateString($first, 'Seleccione la primera casa.');
		String::validateString($last, 'Seleccione la segunda casa.');
		
		$labels = SalesAndPurchasesStadisticsListDAM::getLabelsByManufacturer($first, $last, $totalPages, $totalItems, $page);
		
		$sales_data = array();
		$purchases_data = array();
		
		$date = Date::dbFormat($date);
		
		for($i = $numMonths; $i > 0 ; $i--){
			self::getRangeDates($date, $i, $start_date, $end_date);
			$sales_data[] = SalesAndPurchasesStadisticsListDAM::getSalesListByManufacturer($first, $last, $start_date, $end_date, $totalItems, $page);
			$purchases_data[] = SalesAndPurchasesStadisticsListDAM::getPurchasesListByManufacturer($first, $last, $start_date, $end_date, $totalItems, $page);
		}
		
		return self::prepareList($labels, $sales_data, $purchases_data);
	}
	
	/**
	 * Returns an array with the names of the months.
	 * 
	 * The date is from where the preceding months will be returned. And months are the number
	 * of months. Date format dd/mm/yyyy.
	 * @param string $date
	 * @param int $months
	 * @return array
	 */
	static public function buildMonthsNames($date, $months){
		$year_months = array('1' => 'Ene',
							'2' => 'Feb',
							'3' => 'Mar',
							'4' => 'Abr',
							'5' => 'May',
							'6' => 'Jun',
							'7' => 'Jul',
							'8' => 'Ago',
							'9' => 'Sep',
							'10' => 'Oct',
							'11' => 'Nov',
							'12' => 'Dic');
		
		$dateObj = new DateTime(Date::dbFormat($date));
		$dateObj->modify('- ' . $months  . ' month');
		
		$names = array();
		$i = 0;
		
		do{
			$names[] = $year_months[$dateObj->format('n')] . ' ' . $dateObj->format('y');
			$dateObj->modify('+1 month');
			$i++;
		}while($i < $months);
		
		return $names;
	}
	
	/**
	 * Returns the dates in the format dd/mm/yyyy.
	 * $date format in yyyy/mm/dd.
	 * 
	 * @param string $date
	 * @param integer $numMonths
	 * @param string &$startDate
	 * @param string &$endDate
	 * @return array
	 */
	static private function getRangeDates($date, $numMonths, &$startDate, &$endDate){
		$dateObj = new DateTime($date);
		
		$dateObj->modify('-' . $numMonths . ' month');
		$startDate = '01/' . $dateObj->format('m/Y');
		
		$dateObj->modify('+1 month');
		$endDate = '01/' . $dateObj->format('m/Y');
	}
	
	/**
	 * Changes the list on the way is useful for constructing the report.
	 * 
	 * @param array labels
	 * @param array $salesData
	 * @param array $purchasesData
	 * @return array
	 */
	static private function prepareList($labels, $salesData, $purchasesData){
		$list = array();
		
		$rows = count($labels);
		$months = count($salesData);
		
		for($y = 0; $y < $rows; $y++){
			$row = $labels[$y];
			
			for($x = 0; $x < $months; $x++){
				$sales[] = $salesData[$x][$y];
				$purchases[] = $purchasesData[$x][$y];
				
				$sales_avg += $salesData[$x][$y]['sales'];
				$purchases_avg += $purchasesData[$x][$y]['purchases'];
			}
			
			$row['sales'] = $sales;
			$row['purchases'] = $purchases;
			
			// Calculate the average.
			$row['sales_average'] = $sales_avg / $months;
			$row['purchases_average'] = $purchases_avg / $months;
			
			$list[] = $row;
			
			$sales = array();
			$purchases = array();
			
			$sales_avg = 0;
			$purchases_avg = 0;
		}
		
		return $list;
	}
}


/**
 * Class to generate a sales ledger file.
 * @package Various
 * @author Roberto Oliveros
 */
class SalesLedger{
	/**
	 * Generates a file with the sales ledger data. It returns the url of the file
	 * created. Date params in dd/mm/yyyy format.
	 * 
	 * @param string $firstDate
	 * @param string $lastDate
	 * @return string
	 */
	static public function generate($firstDate, $lastDate){
		Date::validateDate($firstDate, 'Fecha inicial inv&aacute;lida.');
		Date::validateDate($lastDate, 'Fecha final inv&aacute;lida.');
		return SalesLedgerDAM::generate($firstDate, $lastDate);
	}
}


/**
 * Utility class for obtaining the history of invoice transactions.
 * @package Various
 * @author Roberto Oliveros
 */
class InvoiceTransactionList{
	/**
	 * Retuns an array with the report information.
	 *
	 * The array's fields are serail_number, number, date, total and state.
	 * If no page argument or cero is passed all the details are returned. The total_pages and total_items
	 * arguments are necessary to return their respective values. Date format: 'dd/mm/yyyy'.
	 * @param string $firstDate
	 * @param string $lastDate
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList($firstDate, $lastDate, &$totalPages = 0, &$totalItems = 0, $page = 0){
		Date::validateDate($firstDate, 'Fecha inicial inv&aacute;lida.');
		Date::validateDate($lastDate, 'Fecha final inv&aacute;lida.');
		if($page !== 0)
			Number::validatePositiveInteger($page, 'Pagina inv&aacute;lida.');
			
		return InvoiceTransactionListDAM::getList($firstDate, $lastDate, $totalPages, $totalItems, $page);
	}
}
?>