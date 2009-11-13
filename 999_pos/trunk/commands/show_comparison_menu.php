<?php
/**
 * Library containing the ShowComparisonMenuCommand class.
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
 * Command to display the comparison menu.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowComparisonMenuCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Inventariados', 'Comparaciones');
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'main_menu_operations_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'inventory_menu_html.tpl',
				'content' => 'document_menu_html.tpl', 'document_name' => 'Comparaci&oacute;n',
				'create_link' => 'index.php?cmd=show_create_comparison_form',
				'get_link' => 'index.php?cmd=get_comparison',
				'search_link' => 'index.php?cmd=search_comparison&page=1'), 'site_html.tpl');
	}
}
?>