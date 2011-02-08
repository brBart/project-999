<?php
/**
 * Library containing the ShowPurchaseReturnMenuCommand class.
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
 * Command to display the purchase return menu.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowPurchaseReturnMenuCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Movimientos', 'Devoluciones');
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'main_menu_inventory_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'movements_menu_html.tpl',
				'content' => 'document_menu_html.tpl', 'document_name' => 'Devoluci&oacute;n',
				'create_link' => 'index.php?cmd=create_purchase_return',
				'get_link' => 'index.php?cmd=get_purchase_return',
				'search_link' => 'index.php?cmd=search_purchase_return&page=1'), 'site_html.tpl');
	}
}
?>