<?php
/**
 * Library containing the ShowPendingDepositListCommand class.
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
 * Implements functionality for showing the pending deposit list.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowPendingDepositListCommand extends ShowListCommand{
	/**
	 * Returns a list with information.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	protected function getList(&$totalPages, &$totalItems, $page){
		return PendingDepositList::getList($totalPages, $totalItems, $page);
	}
	
	/**
	 * Displays an empty list.
	 */
	protected function displayEmpty(){
		$back_trace = array('Inicio', 'Caja', 'Depositos');
		$msg = 'No hay depositos pendientes de confirmaci&oacute;n.';
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => 'index.php?cmd=show_deposit_menu', 'back_trace' => $back_trace,
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
		$back_trace = array('Inicio', 'Caja', 'Depositos');
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => 'index.php?cmd=show_deposit_menu', 'back_trace' => $back_trace,
				'second_menu' => 'none', 'content' => 'pending_deposit_list_html.tpl', 'list' => $list,
				'total_items' => $totalItems, 'total_pages' => $totalPages, 'page' => $page,
				'first_item' => $firstItem, 'last_item' => $lastItem, 'previous_link' => $previousLink,
				'next_link' => $nextLink), 'site_html.tpl');
	}
}
?>