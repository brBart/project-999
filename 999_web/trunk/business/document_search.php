<?php
/**
 * Library for the DocumentSearch derived classes.
 * @package DocumentSearch
 * @author Roberto Oliveros
 */

/**
 * Includes date class for validating.
 */
require_once('business/validator.php');
/**
 * Includes the DocumentSearchDAM package for accessing the database.
 */
require_once('data/document_search_dam.php');

/**
 * Defines the interface for DocumentSearch derived classes.
 * @package DocumentSearch
 * @author Roberto Oliveros
 *
 */
abstract class DocumentSearch{
	/**
	 * Defines the method signature.
	 *
	 * Date format: 'dd/mm/yyyy'.
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	abstract static public function search($startDate, $endDate, &$totalPages = 0, &$totalItems = 0, $page = 1);
}



/**
 * Implements specific functionality for a deposit document search.
 * @package DocumentSearch
 * @author Roberto Oliveros
 */
class DepositSearch extends DocumentSearch{
	/**
	 * Realizes a deposit search in the database.
	 * 
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id. The first 2 paramters must be in the
	 * the format dd/mm/YYYY. The $page parameter is necesary because of the use of pagination. The other 2 
	 * arguments are passed by reference so the respective values can be return.
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages = 0, &$totalItems = 0, $page = 1){
		Date::validateDate($startDate, 'Fecha inicial inv&aacute;lida.');
		Date::validateDate($endDate, 'Fecha final inv&aacute;lida.');
		Number::validatePositiveInteger($page, 'Pagina inv&aacute;lida.');
		return DepositSearchDAM::search($startDate, $endDate, $totalPages, $totalItems, $page);
	}
}


/**
 * Implements specific functionality for a comparison document search.
 * @package DocumentSearch
 * @author Roberto Oliveros
 */
class ComparisonSearch extends DocumentSearch{
	/**
	 * Realizes a comparison search in the database.
	 * 
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id. The first 2 paramters must be in the
	 * the format dd/mm/YYYY. The $page parameter is necesary because of the use of pagination. The other 2 
	 * arguments are passed by reference so the respective values can be return.
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages = 0, &$totalItems = 0, $page = 1){
		Date::validateDate($startDate, 'Fecha inicial inv&aacute;lida.');
		Date::validateDate($endDate, 'Fecha final inv&aacute;lida.');
		Number::validatePositiveInteger($page, 'Pagina inv&aacute;lida.');
		return ComparisonSearchDAM::search($startDate, $endDate, $totalPages, $totalItems, $page);
	}
}


/**
 * Implements specific functionality for a count document search.
 * @package DocumentSearch
 * @author Roberto Oliveros
 */
class CountSearch extends DocumentSearch{
	/**
	 * Realizes a count search in the database.
	 * 
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id. The first 2 paramters must be in the
	 * the format dd/mm/YYYY. The $page parameter is necesary because of the use of pagination. The other 2 
	 * arguments are passed by reference so the respective values can be return.
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages = 0, &$totalItems = 0, $page = 1){
		Date::validateDate($startDate, 'Fecha inicial inv&aacute;lida.');
		Date::validateDate($endDate, 'Fecha final inv&aacute;lida.');
		Number::validatePositiveInteger($page, 'Pagina inv&aacute;lida.');
		return CountSearchDAM::search($startDate, $endDate, $totalPages, $totalItems, $page);
	}
}


/**
 * Implements specific functionality for a purchase return document search.
 * @package DocumentSearch
 * @author Roberto Oliveros
 */
class PurchaseReturnSearch extends DocumentSearch{
	/**
	 * Realizes a purchase return search in the database.
	 * 
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id. The first 2 paramters must be in the
	 * the format dd/mm/YYYY. The $page parameter is necesary because of the use of pagination. The other 2 
	 * arguments are passed by reference so the respective values can be return.
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages = 0, &$totalItems = 0, $page = 1){
		Date::validateDate($startDate, 'Fecha inicial inv&aacute;lida.');
		Date::validateDate($endDate, 'Fecha final inv&aacute;lida.');
		Number::validatePositiveInteger($page, 'Pagina inv&aacute;lida.');
		return PurchaseReturnSearchDAM::search($startDate, $endDate, $totalPages, $totalItems, $page);
	}
}


/**
 * Implements specific functionality for a shipment document search.
 * @package DocumentSearch
 * @author Roberto Oliveros
 */
class ShipmentSearch extends DocumentSearch{
	/**
	 * Realizes a shipment search in the database.
	 * 
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id. The first 2 paramters must be in the
	 * the format dd/mm/YYYY. The $page parameter is necesary because of the use of pagination. The other 2 
	 * arguments are passed by reference so the respective values can be return.
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages = 0, &$totalItems = 0, $page = 1){
		Date::validateDate($startDate, 'Fecha inicial inv&aacute;lida.');
		Date::validateDate($endDate, 'Fecha final inv&aacute;lida.');
		Number::validatePositiveInteger($page, 'Pagina inv&aacute;lida.');
		return ShipmentSearchDAM::search($startDate, $endDate, $totalPages, $totalItems, $page);
	}
}


/**
 * Implements specific functionality for a invoice document search.
 * @package DocumentSearch
 * @author Roberto Oliveros
 */
class InvoiceSearch extends DocumentSearch{
	/**
	 * Realizes an invoice search in the database.
	 * 
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id. The first 2 paramters must be in the
	 * the format dd/mm/YYYY. The $page parameter is necesary because of the use of pagination. The other 2 
	 * arguments are passed by reference so the respective values can be return.
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages = 0, &$totalItems = 0, $page = 1){
		Date::validateDate($startDate, 'Fecha inicial inv&aacute;lida.');
		Date::validateDate($endDate, 'Fecha final inv&aacute;lida.');
		Number::validatePositiveInteger($page, 'Pagina inv&aacute;lida.');
		return InvoiceSearchDAM::search($startDate, $endDate, $totalPages, $totalItems, $page);
	}
}


/**
 * Implements specific functionality for a receipt document search.
 * @package DocumentSearch
 * @author Roberto Oliveros
 */
class ReceiptSearch extends DocumentSearch{
	/**
	 * Realizes a receipt search in the database.
	 * 
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id. The first 2 paramters must be in the
	 * the format dd/mm/YYYY. The $page parameter is necesary because of the use of pagination. The last 2 
	 * arguments are passed by reference so the respective values can be return.
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages = 0, &$totalItems = 0, $page = 1){
		Date::validateDate($startDate, 'Fecha inicial inv&aacute;lida.');
		Date::validateDate($endDate, 'Fecha final inv&aacute;lida.');
		Number::validatePositiveInteger($page, 'Pagina inv&aacute;lida.');
		return ReceiptSearchDAM::search($startDate, $endDate, $totalPages, $totalItems, $page);
	}
}


/**
 * Implements specific functionality for an entry inventory adjustment document search.
 * @package DocumentSearch
 * @author Roberto Oliveros
 */
class EntryIASearch extends DocumentSearch{
	/**
	 * Realizes a search in the database.
	 * 
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id. The first 2 paramters must be in the
	 * the format dd/mm/YYYY. The $page parameter is necesary because of the use of pagination. The other 2 
	 * arguments are passed by reference so the respective values can be return.
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages = 0, &$totalItems = 0, $page = 1){
		Date::validateDate($startDate, 'Fecha inicial inv&aacute;lida.');
		Date::validateDate($endDate, 'Fecha final inv&aacute;lida.');
		Number::validatePositiveInteger($page, 'Pagina inv&aacute;lida.');
		return EntryIASearchDAM::search($startDate, $endDate, $totalPages, $totalItems, $page);
	}
}


/**
 * Implements specific functionality for a withdraw inventory adjustment document search.
 * @package DocumentSearch
 * @author Roberto Oliveros
 */
class WithdrawIASearch extends DocumentSearch{
	/**
	 * Realizes a search in the database.
	 * 
	 * Returns an array with the found data in the database. The array consists of the 2 fields(Date & Id) which
	 * is the date when the document was created and its respective id. The first 2 paramters must be in the
	 * the format dd/mm/YYYY. The $page parameter is necesary because of the use of pagination. The other 2 
	 * arguments are passed by reference so the respective values can be return.
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function search($startDate, $endDate, &$totalPages = 0, &$totalItems = 0, $page = 1){
		Date::validateDate($startDate, 'Fecha inicial inv&aacute;lida.');
		Date::validateDate($endDate, 'Fecha final inv&aacute;lida.');
		Number::validatePositiveInteger($page, 'Pagina inv&aacute;lida.');
		return WithdrawIASearchDAM::search($startDate, $endDate, $totalPages, $totalItems, $page);
	}
}
?>