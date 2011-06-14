<?php
/**
 * Library containing the ShowToolsMenuInventoryCommand class.
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
 * Command to display the tools menu on the Inventory site.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowToolsMenuInventoryCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Herramientas');
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'main_menu_inventory_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'tools_menu_inventory_html.tpl',
				'content' => 'none', 'notify' => '0'), 'site_html.tpl');
	}
}
?>