<?php
/**
 * Library with utility classes for obtaining data lists of any of the system's subjects.
 * @package List
 * @author Roberto Oliveros
 */

/**
 * For validation purposes.
 */
require_once('business/validator.php');
/**
 * For accessing the database.
 */
require_once('data/list_dam.php');

/**
 * Defines the interface for the rest of the classes that extend it.
 * @package List
 * @author Roberto Oliveros
 */
abstract class DataList{
	/**
	 * Defines the method signature.
	 *
	 * @param integer $page
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @return array
	 */
	abstract static public function getList($page = 0, &$total_pages = 0, &$total_items = 0);
}


/**
 * Utility class for obtaining a list of banks from the database.
 * @package List
 * @author Roberto Oliveros
 */
class BankList extends DataList{
	/**
	 * Returns an array with the banks' ids and names from the database.
	 *
	 * The total_pages and total_items parameters are necessary to return their respective values. If no page
	 * argument is passed or a cero is passed, all the details are returned.
	 * @param integer $page
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @return array
	 */
	static public function getList($page = 0, &$total_pages = 0, &$total_items = 0){
		if($page !== 0)
			Number::validatePositiveInteger($page, 'Pagina inv&aacute;lida.');
			
		return BankListDAM::getList($page, $total_pages, $total_items);
	}
}
?>