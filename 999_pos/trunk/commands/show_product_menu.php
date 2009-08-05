<?php
/**
 * Library containing the ShowProductMenu command.
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
 * For obtaining the suppliers list.
 */
require_once('business/list.php');

/**
 * Command to display the products menu.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowProductMenuCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Mantenimiento', 'Productos');
		
		// For displaying the first blank item.
		$list = array(array());
		$list = array_merge($list, SupplierList::getList($pages, $items, 0));
		
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'main_menu_operations_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_operations_html.tpl',
				'content' => 'product_menu_html.tpl', 'supplier_list' => $list), 'site_html.tpl');
	}
}
?>