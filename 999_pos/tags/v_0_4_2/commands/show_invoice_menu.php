<?php
/**
 * Library containing the ShowInvoiceMenuCommand class.
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
 * Command to display the invoice menu.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowInvoiceMenuCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Movimientos', 'Facturas');
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'main_menu_inventory_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'movements_menu_html.tpl',
				'content' => 'invoice_menu_html.tpl'), 'site_html.tpl');
	}
}
?>