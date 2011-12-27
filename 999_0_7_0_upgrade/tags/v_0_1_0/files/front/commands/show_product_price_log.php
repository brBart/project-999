<?php
/**
 * Library containing the ShowProductPriceLogCommand class.
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
class ShowProductPriceLogCommand extends ShowLogCommand{
	/**
	 * Show the empty form.
	 */
	protected function showForm(){
		$back_trace = array('Inicio', 'Herramientas', 'Bitacoras');
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'blank.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'none',
				'reference_type' => LOG_TYPE, 'reference_name' => 'Cambio de Precios a Productos',
				'reference_cmd' => 'index.php?cmd=show_product_price_log&page=1',
				'back_link' => 'index.php?cmd=show_log_menu_inventory',
				'content' => 'reference_form_html.tpl'), 'site_html.tpl');
	}
	
	/**
	 * Tests if the user has the right to cancel.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'product_price_log', 'read');
	}
	
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
		return ChangePriceList::getList($startDate, $endDate, $totalPages, $totalItems, $page);
	}
	
	/**
	 * Display failure in case an error occurs.
	 * @param string $msg
	 * @param string $startDate
	 * @param string $endDate
	 */
	protected function displayFailure($msg, $startDate, $endDate){
		$back_trace = array('Inicio', 'Herramientas', 'Bitacoras');
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'blank.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'none',
				'reference_type' => LOG_TYPE, 'reference_name' => 'Cambio de Precios a Productos',
				'reference_cmd' => 'index.php?cmd=show_product_price_log&page=1',
				'back_link' => 'index.php?cmd=show_log_menu_inventory',
				'content' => 'reference_form_html.tpl', 'notify' => '1', 'type' => 'error',
				'message' => $msg, 'start_date' => $startDate, 'end_date' => $endDate),
				'site_html.tpl');
	}
	
	/**
	 * Displays an empty list.
	 */
	protected function displayEmpty(){
		$back_trace = array('Inicio', 'Herramientas', 'Bitacoras');
		$msg = 'No hay cambio de precios a productos en esas fechas en la base de datos.';
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => 'index.php?cmd=show_log_menu_inventory', 'back_trace' => $back_trace,
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
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => 'index.php?cmd=show_log_menu_inventory', 'back_trace' => $back_trace,
				'second_menu' => 'none', 'content' => 'product_price_log_html.tpl',
				'log_name' => 'Cambio de Precios a Productos',
				'list' => $list, 'start_date' => $startDate, 'end_date' => $endDate,
				'total_items' => $totalItems, 'total_pages' => $totalPages, 'page' => $page,
				'first_item' => $firstItem, 'last_item' => $lastItem, 'previous_link' => $previousLink,
				'next_link' => $nextLink, 'print_cmd' => 'print_product_price_log'), 'site_html.tpl');
	}
}
?>