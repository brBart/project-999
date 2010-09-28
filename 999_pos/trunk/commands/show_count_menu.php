<?php
/**
 * Library containing the ShowCountMenuCommand class.
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
 * Command to display the inventory counts menu.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowCountMenuCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Inventariados', 'Conteos');
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'main_menu_inventory_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'inventory_menu_html.tpl',
				'content' => 'document_menu_html.tpl', 'document_name' => 'Conteo',
				'create_link' => 'index.php?cmd=create_count', 'get_link' => 'index.php?cmd=get_count',
				'search_link' => 'index.php?cmd=search_count&page=1'), 'site_html.tpl');
	}
}
?>