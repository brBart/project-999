<?php
/**
 * Library with utility classes to accesing database tables.
 * @package ListDAM
 * @author Roberto Oliveros
 */

/**
 * Class for accesing database data for creating lists.
 * @package ListDAM
 * @author Roberto Oliveros
 */
class BankListDAM{
	/**
	 * Returns an array with the banks' ids and names from the database.
	 *
	 * The total_pages and total_items parameters are necessary to return their respective values.
	 * @param integer $page
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @return array
	 */
	static public function getList($page, &$total_pages, &$total_items){
		$total_pages = 1;
		$total_items = 2;
		return array(array('id' => 123, 'name' => 'Gyt'), array('id' => 124, 'name' => 'Bi'));
	}
}
?>