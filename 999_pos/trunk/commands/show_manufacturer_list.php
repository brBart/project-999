<?php
/**
 * Library containing the show manufacturer list class command.
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
 * Implements functionality for showing the manufacturer list.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowManufacturerListCommand extends ShowListCommand{
	/**
	 * Returns a list with information.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	protected function getList(&$totalPages, &$totalItems, $page){
		return ManufacturerList::getList($totalPages, $totalItems, $page);
	}
	
	/**
	 * Displays an empty list.
	 */
	protected function displayFailure(){
		$back_trace = array('Inicio', 'Mantenimiento', 'Casas');
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => 'index.php?cmd=show_manufacturer_menu', 'back_trace' => $back_trace,
				'second_menu' => 'blank.tpl', 'content' => 'manufacturer_list_html.tpl',
				'total_items' => '0'), 'site_html.tpl');
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
	 */
	protected function displayList($list, $totalPages, $totalItems, $page, $firstItem, $lastItem, $previousLink,
			$nextLink, $actualCmd){
		$back_trace = array('Inicio', 'Mantenimiento', 'Casas');
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => 'index.php?cmd=show_manufacturer_menu', 'back_trace' => $back_trace,
				'second_menu' => 'blank.tpl', 'content' => 'manufacturer_list_html.tpl', 'list' => $list,
				'total_items' => $totalItems, 'total_pages' => $totalPages, 'page' => $page,
				'first_item' => $firstItem, 'last_item' => $lastItem, 'previous_link' => $previousLink,
				'next_link' => $nextLink, 'item_link' => 'index.php?cmd=get_manufacturer&id=',
				'actual_cmd' => $actualCmd), 'site_html.tpl');
	}
}
?>