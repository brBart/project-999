<?php
/**
 * Library containing the PrintInvoiceDiscountLogCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/print_log.php');

/**
 * Implements functionality for printing the log.
 * @package Command
 * @author Roberto Oliveros
 */
class PrintInvoiceDiscountLogCommand extends PrintLogCommand{
	/**
	 * Tests if the user has the right to cancel.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'invoice_discount_log', 'read');
	}
	
	/**
	 * Returns a list with information.
	 * @param string $startDate
	 * @param string $endDate
	 * @return array
	 */
	protected function getList($startDate, $endDate){
		return DiscountList::getList($startDate, $endDate);
	}
	
	/**
	 * Display failure in case an error occurs.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		Page::display(array('notify' => '1', 'type' => 'error', 'message' => $msg),
				'invoice_discount_log_print_html.tpl');
	}
	
	/**
	 * Displays the list.
	 * @param array $list
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer $totalPages
	 * @param integer $totalItems
	 * @param integer $page
	 * @param integer $firstItem
	 * @param integer $lastItem
	 * @param string $previousLink
	 * @param string $nextLink
	 * @param string $actualCmd
	 */
	protected function displayList($list, $startDate, $endDate, $totalItems){
		Page::display(array('log_name' => 'Descuento a Facturas', 'list' => $list, 'start_date' => $startDate,
				'end_date' => $endDate, 'total_items' => $totalItems), 'invoice_discount_log_print_html.tpl');
	}
}
?>