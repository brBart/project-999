<?php
/**
 * Library for the DocumentSearch derived classes.
 * @package DocumentSearch
 * @author Roberto Oliveros
 */

require_once('data/document_search_dam.php');

/**
 * Defines common functionality for DocumentSearch derived classes.
 * @package DocumentSearch
 * @author Roberto Oliveros
 *
 */
abstract class DocumentSearch{
	/**
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id. The first 2 paramters must be in the
	 * the format dd/mm/YYYY. The $page parameter is necesary because of the use of pagination. The last 2 
	 * parameters are passed by reference so the respective values can be return.
	 *
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer $page
	 * @param integer $totalPages
	 * @param integer $totalItems
	 * @return array
	 */
	abstract static public function search($startDate, $endDate, $page = 1,
			&$totalPages = 0, &$totalItems = 0);
	
	/**
	 * Validates if the provided date is correct.
	 *
	 * @param string $date
	 * @return void
	 */
	protected function validateDate($date){
		$date_array = explode('/', $date);
		
		$day = (int)$date_array[0];
		$month = (int)$date_array[1];
		$year = (int)$date_array[2];
		
		if(!checkdate($month, $day, $year))
			throw new Exception('Fecha inv&aacute;lida.');
	}
	
	/**
	 * Verifies if the provided $page is correct.
	 *
	 * @param integer $page
	 * @return void
	 */
	protected function validatePage($page){
		if(!is_int($page) || $page < 1)
			throw new Exception('Pagina inv&aacute;lida.');
	}
}



/**
 * Implements specific functionality for Deposit Documents search.
 * @package DocumentSearch
 * @author Roberto Oliveros
 */
class DepositSearch extends DocumentSearch{
	/**
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id. The first 2 paramters must be in the
	 * the format dd/mm/YYYY. The $page parameter is necesary because of the use of pagination. The last 2 
	 * parameters are passed by reference so the respective values can be return.
	 *
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer $page
	 * @param integer $totalPages
	 * @param integer $totalItems
	 * @return array
	 */
	static public function search($startDate, $endDate, $page = 1, &$totalPages = 0, &$totalItems = 0){
		self::validateDate($startDate);
		self::validateDate($endDate);
		self::validatePage($page);
		return DepositSearchDAM::search($startDate, $endDate, $page, $totalPages, $totalItems);
	}
}


/**
 * Implements specific functionality for Comparison Documents search.
 * @package DocumentSearch
 * @author Roberto Oliveros
 */
class ComparisonSearch extends DocumentSearch{
	/**
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id. The first 2 paramters must be in the
	 * the format dd/mm/YYYY. The $page parameter is necesary because of the use of pagination. The last 2 
	 * parameters are passed by reference so the respective values can be return.
	 *
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer $page
	 * @param integer $totalPages
	 * @param integer $totalItems
	 * @return array
	 */
	static public function search($startDate, $endDate, $page = 1, &$totalPages = 0, &$totalItems = 0){
		self::validateDate($startDate);
		self::validateDate($endDate);
		self::validatePage($page);
		return ComparisonSearchDAM::search($startDate, $endDate, $page, $totalPages, $totalItems);
	}
}


/**
 * Implements specific functionality for Count Documents search.
 * @package DocumentSearch
 * @author Roberto Oliveros
 */
class CountSearch extends DocumentSearch{
	/**
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id. The first 2 paramters must be in the
	 * the format dd/mm/YYYY. The $page parameter is necesary because of the use of pagination. The last 2 
	 * parameters are passed by reference so the respective values can be return.
	 *
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer $page
	 * @param integer $totalPages
	 * @param integer $totalItems
	 * @return array
	 */
	static public function search($startDate, $endDate, $page = 1, &$totalPages = 0, &$totalItems = 0){
		self::validateDate($startDate);
		self::validateDate($endDate);
		self::validatePage($page);
		return CountSearchDAM::search($startDate, $endDate, $page, $totalPages, $totalItems);
	}
}


/**
 * Implements specific functionality for Purchase Return Documents search.
 * @package DocumentSearch
 * @author Roberto Oliveros
 */
class PurchaseReturnSearch extends DocumentSearch{
	/**
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id. The first 2 paramters must be in the
	 * the format dd/mm/YYYY. The $page parameter is necesary because of the use of pagination. The last 2 
	 * parameters are passed by reference so the respective values can be return.
	 *
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer $page
	 * @param integer $totalPages
	 * @param integer $totalItems
	 * @return array
	 */
	static public function search($startDate, $endDate, $page = 1, &$totalPages = 0, &$totalItems = 0){
		self::validateDate($startDate);
		self::validateDate($endDate);
		self::validatePage($page);
		return PurchaseReturnSearchDAM::search($startDate, $endDate, $page, $totalPages, $totalItems);
	}
}


/**
 * Implements specific functionality for Shipment Documents search.
 * @package DocumentSearch
 * @author Roberto Oliveros
 */
class ShipmentSearch extends DocumentSearch{
	/**
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id. The first 2 paramters must be in the
	 * the format dd/mm/YYYY. The $page parameter is necesary because of the use of pagination. The last 2 
	 * parameters are passed by reference so the respective values can be return.
	 *
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer $page
	 * @param integer $totalPages
	 * @param integer $totalItems
	 * @return array
	 */
	static public function search($startDate, $endDate, $page = 1, &$totalPages = 0, &$totalItems = 0){
		self::validateDate($startDate);
		self::validateDate($endDate);
		self::validatePage($page);
		return ShipmentSearchDAM::search($startDate, $endDate, $page, $totalPages, $totalItems);
	}
}


/**
 * Implements specific functionality for Invoice Documents search.
 * @package DocumentSearch
 * @author Roberto Oliveros
 */
class InvoiceSearch extends DocumentSearch{
	/**
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id. The first 2 paramters must be in the
	 * the format dd/mm/YYYY. The $page parameter is necesary because of the use of pagination. The last 2 
	 * parameters are passed by reference so the respective values can be return.
	 *
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer $page
	 * @param integer $totalPages
	 * @param integer $totalItems
	 * @return array
	 */
	static public function search($startDate, $endDate, $page = 1, &$totalPages = 0, &$totalItems = 0){
		self::validateDate($startDate);
		self::validateDate($endDate);
		self::validatePage($page);
		return InvoiceSearchDAM::search($startDate, $endDate, $page, $totalPages, $totalItems);
	}
}


/**
 * Implements specific functionality for Receipt Documents search.
 * @package DocumentSearch
 * @author Roberto Oliveros
 */
class ReceiptSearch extends DocumentSearch{
	/**
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id. The first 2 paramters must be in the
	 * the format dd/mm/YYYY. The $page parameter is necesary because of the use of pagination. The last 2 
	 * parameters are passed by reference so the respective values can be return.
	 *
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer $page
	 * @param integer $totalPages
	 * @param integer $totalItems
	 * @return array
	 */
	static public function search($startDate, $endDate, $page = 1, &$totalPages = 0, &$totalItems = 0){
		self::validateDate($startDate);
		self::validateDate($endDate);
		self::validatePage($page);
		return ReceiptSearchDAM::search($startDate, $endDate, $page, $totalPages, $totalItems);
	}
}


/**
 * Implements specific functionality for Entry Inventory Adjustment Documents search.
 * @package DocumentSearch
 * @author Roberto Oliveros
 */
class EntryIASearch extends DocumentSearch{
	/**
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id. The first 2 paramters must be in the
	 * the format dd/mm/YYYY. The $page parameter is necesary because of the use of pagination. The last 2 
	 * parameters are passed by reference so the respective values can be return.
	 *
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer $page
	 * @param integer $totalPages
	 * @param integer $totalItems
	 * @return array
	 */
	static public function search($startDate, $endDate, $page = 1, &$totalPages = 0, &$totalItems = 0){
		self::validateDate($startDate);
		self::validateDate($endDate);
		self::validatePage($page);
		return EntryIASearchDAM::search($startDate, $endDate, $page, $totalPages, $totalItems);
	}
}


/**
 * Implements specific functionality for Withdraw Inventory Adjustment Documents search.
 * @package DocumentSearch
 * @author Roberto Oliveros
 */
class WithdrawIASearch extends DocumentSearch{
	/**
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id. The first 2 paramters must be in the
	 * the format dd/mm/YYYY. The $page parameter is necesary because of the use of pagination. The last 2 
	 * parameters are passed by reference so the respective values can be return.
	 *
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer $page
	 * @param integer $totalPages
	 * @param integer $totalItems
	 * @return array
	 */
	static public function search($startDate, $endDate, $page = 1, &$totalPages = 0, &$totalItems = 0){
		self::validateDate($startDate);
		self::validateDate($endDate);
		self::validatePage($page);
		return WithdrawIASearchDAM::search($startDate, $endDate, $page, $totalPages, $totalItems);
	}
}
?>