<?php
/**
 * Library containing the ShowEntryAdjustmentMenuCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('presentation/command.php');
/**
 * For displaying the results.
 */
require_once('presentation/page.php');

/**
 * Command to display the entry adjustment document menu.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowEntryAdjustmentMenuCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Movimientos', 'Vales de Entrada');
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'main_menu_inventory_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'movements_menu_html.tpl',
				'content' => 'document_menu_html.tpl', 'document_name' => 'Vale de Entrada',
				'create_link' => 'index.php?cmd=create_entry_adjustment',
				'get_link' => 'index.php?cmd=get_entry_adjustment',
				'search_link' => 'index.php?cmd=search_entry_adjustment&page=1'), 'site_html.tpl');
	}
}
?>