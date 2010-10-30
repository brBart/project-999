<?php
/**
 * Library containing the ShowInvoiceDiscountLogCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/show_log.php');

/**
 * Implements functionality for showing the log.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowInvoiceDiscountLogCommand extends ShowLogCommand{
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
	 * @param string &$startDate
	 * @param string &$endDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	protected function getList(&$startDate, &$endDate, &$totalPages, &$totalItems, $page){
		$startDate = $this->_mRequest->getProperty('discount_start_date');
		$endDate = $this->_mRequest->getProperty('discount_end_date');
		
		return DiscountList::getList($startDate, $endDate, $totalPages, $totalItems, $page);
	}
	
	/**
	 * Display failure in case an error occurs.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio', 'Herramientas', 'Bitacoras');
		
		$start_date = $this->_mRequest->getProperty('discount_start_date');
		$end_date = $this->_mRequest->getProperty('discount_end_date');
		
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'main_menu_pos_admin_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'tools_menu_pos_admin_html.tpl',
				'content' => 'log_menu_pos_admin_html.tpl', 'notify' => '1', 'type' => 'error',
				'message' => $msg, 'discount_start_date' => $start_date, 'discount_end_date' => $end_date),
				'site_html.tpl');
	}
	
	/**
	 * Displays an empty list.
	 */
	protected function displayEmpty(){
		$back_trace = array('Inicio', 'Herramientas', 'Bitacoras');
		$msg = 'No hay descuentos a facturas en esas fechas en la base de datos.';
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => 'index.php?cmd=show_log_menu_pos_admin', 'back_trace' => $back_trace,
				'second_menu' => 'none', 'content' => 'none', 'notify' => '1', 'type' => 'info',
				'message' => $msg), 'site_html.tpl');
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
	protected function displayList($list, $startDate, $endDate, $totalPages, $totalItems, $page, $firstItem,
			$lastItem, $previousLink, $nextLink, $actualCmd){
		$back_trace = array('Inicio', 'Herramientas', 'Bitacoras');
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => 'index.php?cmd=show_log_menu_pos_admin', 'back_trace' => $back_trace,
				'second_menu' => 'none', 'content' => 'invoice_discount_log_html.tpl', 'log_name' => 'Descuento a Facturas',
				'list' => $list, 'start_date' => $startDate, 'end_date' => $endDate,
				'total_items' => $totalItems, 'total_pages' => $totalPages, 'page' => $page,
				'first_item' => $firstItem, 'last_item' => $lastItem, 'previous_link' => $previousLink,
				'next_link' => $nextLink, 'print_cmd' => 'print_invoice_discounts_log'), 'site_html.tpl');
	}
}
?>