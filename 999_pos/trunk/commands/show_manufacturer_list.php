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
	 * Returns the name of the template.
	 * @return string
	 */
	protected function getTemplate(){
		return 'manufacturer_list_html.tpl';
	}
}
?>