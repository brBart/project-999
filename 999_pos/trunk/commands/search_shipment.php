<?php
/**
 * Library containing the SearchShipmentCommand class.
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
 * Implements functionality for searching a shipment in a given range of dates.
 * @package Command
 * @author Roberto Oliveros
 */
class SearchShipmentCommand extends SearchObjectByDateCommand{
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
		return ShipmentSearch::search($startDate, $endDate, $totalPages, $totalItems, $page);
	}
	
	/**
	 * Display failure in case an error occurs.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio', 'Movimientos', 'Envios');
		
		$start_date = $this->_mRequest->getProperty('start_date');
		$end_date = $this->_mRequest->getProperty('end_date');
		
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'main_menu_inventory_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'movements_menu_html.tpl',
				'content' => 'document_menu_html.tpl', 'document_name' => 'Envio',
				'create_link' => 'index.php?cmd=create_shipment', 'get_link' => 'index.php?cmd=get_shipment',
				'search_link' => 'index.php?cmd=search_shipment&page=1', 'notify' => '1',
				'type' => 'error', 'message' => $msg, 'start_date' => $start_date, 'end_date' => $end_date),
				'site_html.tpl');
	}
	
	/**
	 * Displays an empty list.
	 */
	protected function displayEmpty(){
		$back_trace = array('Inicio', 'Movimientos', 'Envios');
		$msg = 'No hay envios en esas fechas en la base de datos.';
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => 'index.php?cmd=show_shipment_menu', 'back_trace' => $back_trace,
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
		$back_trace = array('Inicio', 'Movimientos', 'Envios');
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => 'index.php?cmd=show_shipment_menu', 'back_trace' => $back_trace,
				'second_menu' => 'none', 'content' => 'document_list_html.tpl',
				'document_name' => 'Envio', 'list' => $list, 'start_date' => $startDate,
				'end_date' => $endDate, 'total_items' => $totalItems, 'total_pages' => $totalPages,
				'page' => $page, 'first_item' => $firstItem, 'last_item' => $lastItem,
				'previous_link' => $previousLink, 'next_link' => $nextLink,
				'item_link' => 'index.php?cmd=get_shipment&id=', 'actual_cmd' => $actualCmd),
				'site_html.tpl');
	}
}
?>