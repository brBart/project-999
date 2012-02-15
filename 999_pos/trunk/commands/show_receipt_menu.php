<?php
/**
 * Library containing the ShowReceiptMenuCommand class.
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
 * For creating the select options.
 */
require_once('business/list.php');

/**
 * Command to display the receipts menu.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowReceiptMenuCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Movimientos', 'Recibos');
		
		// Get the lists for the select options.
		$empty_item = array(array());
		$supplier_list = array_merge($empty_item, SupplierList::getList($pages, $items, 0));
		
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'main_menu_inventory_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'movements_menu_html.tpl',
				'content' => 'receipt_menu_html.tpl', 'supplier_list' => $supplier_list), 'site_html.tpl');
	}
}
?>