<?php
/**
 * Library containing the ShowUnitOfMeasureListCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/show_list.php');
/**
 * For obtaining the list.
 */
require_once('business/list.php');

/**
 * Implements functionality for showing the unit of measure list.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowUnitOfMeasureListCommand extends ShowListCommand{
	/**
	 * Returns a list with information.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	protected function getList(&$totalPages, &$totalItems, $page){
		return UnitOfMeasureList::getList($totalPages, $totalItems, $page);
	}
	
	/**
	 * Displays an empty list.
	 */
	protected function displayEmpty(){
		$back_trace = array('Inicio', 'Mantenimiento', 'Unidades de Medida');
		$msg = 'No hay unidades de medida en la base de datos.';
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => 'index.php?cmd=show_unit_of_measure_menu', 'back_trace' => $back_trace,
				'second_menu' => 'none', 'content' => 'none', 'notify' => '1', 'type' => 'info',
				'message' => $msg), 'site_html.tpl');
	}
	
	/**
	 * Displays the list.
	 * @param array $list
	 * @param integer $totalPages
	 * @param integer $totalItems
	 * @param integer $page
	 * @param integer $firstItem
	 * @param integer $lastItem
	 * @param string $previousLink
	 * @param string $nextLink
	 * @param string $actualCmd
	 */
	protected function displayList($list, $totalPages, $totalItems, $page, $firstItem, $lastItem, $previousLink,
			$nextLink, $actualCmd){
		$back_trace = array('Inicio', 'Mantenimiento', 'Unidades de Medida');
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => 'index.php?cmd=show_unit_of_measure_menu', 'back_trace' => $back_trace,
				'second_menu' => 'none', 'content' => 'identifier_list_html.tpl', 'list' => $list,
				'total_items' => $totalItems, 'total_pages' => $totalPages, 'page' => $page,
				'first_item' => $firstItem, 'last_item' => $lastItem, 'previous_link' => $previousLink,
				'next_link' => $nextLink, 'item_link' => 'index.php?cmd=get_unit_of_measure&id=',
				'actual_cmd' => $actualCmd), 'site_html.tpl');
	}
}
?>