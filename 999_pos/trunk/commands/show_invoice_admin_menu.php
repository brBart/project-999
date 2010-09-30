<?php
/**
 * Library containing the ShowInvoiceAdminMenuCommand class.
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
 * Command to display the invoice menu on the POS Admin module.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowInvoiceAdminMenuCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Movimientos', 'Facturas');
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'main_menu_pos_admin_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'cash_register_menu_html.tpl',
				'content' => 'invoice_admin_menu_html.tpl'), 'site_html.tpl');
	}
}
?>