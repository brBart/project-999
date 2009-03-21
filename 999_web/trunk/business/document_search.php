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
	 * is the date when the document was created and its respective id. The $page parameter is necesary
	 * because of the use of pagination. The last 2 parameters are passed by reference so the respective values
	 * can be return.
	 *
	 * @param date $startDate
	 * @param date $endDate
	 * @param integer $page
	 * @param integer $totalPages
	 * @param integer $totalItems
	 */
	abstract public function search($startDate, $endDate, $page, &$totalPages, &$totalItems);
}


class DepositSearch extends DocumentSearch{
	
	public function search($startDate, $endDate, $page, &$totalPages, &$totalItems){
		return;
	}
}
?>