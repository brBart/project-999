<?php
/**
 * Library containing the SearchInvoiceByWorkingDayCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/search_object_by_date.php');
/**
 * For making the search.
 */
require_once('business/document_search.php');

/**
 * Implements functionality for searching an invoice in a given range of working days.
 * @package Command
 * @author Roberto Oliveros
 */
class SearchInvoiceByWorkingDayCommand extends SearchObjectByDateCommand{
	/**
	 * Returns a list with information.
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	protected function getList($startDate, $endDate, &$totalPages, &$totalItems, $page){
		return InvoiceByWorkingDaySearch::search($startDate, $endDate, $totalPages, $totalItems, $page);
	}
	
	/**
	 * Display failure in case an error occurs.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio', 'Caja', 'Facturas');
		
		$start_date = $this->_mRequest->getProperty('start_date');
		$end_date = $this->_mRequest->getProperty('end_date');
		
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'main_menu_pos_admin_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'cash_register_menu_html.tpl',
				'content' => 'invoice_admin_menu_html.tpl', 'notify' => '1', 'type' => 'error',
				'message' => $msg, 'start_date' => $start_date, 'end_date' => $end_date),
				'site_html.tpl');
	}
	
	/**
	 * Displays an empty list.
	 */
	protected function displayEmpty(){
		$back_trace = array('Inicio', 'Caja', 'Facturas');
		$msg = 'No hay facturas en esas jornadas en la base de datos.';
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => 'index.php?cmd=show_invoice_admin_menu', 'back_trace' => $back_trace,
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
		$back_trace = array('Inicio', 'Caja', 'Facturas');
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => 'index.php?cmd=show_invoice_admin_menu', 'back_trace' => $back_trace,
				'second_menu' => 'none', 'content' => 'invoice_admin_list_html.tpl',
				'list' => $list, 'start_date' => $startDate, 'end_date' => $endDate,
				'total_items' => $totalItems, 'total_pages' => $totalPages,
				'page' => $page, 'first_item' => $firstItem, 'last_item' => $lastItem,
				'previous_link' => $previousLink, 'next_link' => $nextLink, 'actual_cmd' => $actualCmd),
				'site_html.tpl');
	}
}
?>