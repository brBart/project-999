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
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'main_menu_operations_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'movements_menu_html.tpl',
				'content' => 'document_menu_html.tpl', 'document_name' => 'Recibo',
				'create_link' => 'index.php?cmd=create_receipt', 'get_link' => 'index.php?cmd=get_receipt',
				'search_link' => 'index.php?cmd=search_receipt&page=1'), 'site_html.tpl');
	}
}
?>