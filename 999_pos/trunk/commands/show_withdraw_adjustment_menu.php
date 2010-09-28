<?php
/**
 * Library containing the ShowWithdrawAdjustmentMenuCommand class.
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
 * Command to display the withdraw adjustment document menu.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowWithdrawAdjustmentMenuCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Movimientos', 'Vales de Salida');
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'main_menu_inventory_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'movements_menu_html.tpl',
				'content' => 'document_menu_html.tpl', 'document_name' => 'Vale de Salida',
				'create_link' => 'index.php?cmd=create_withdraw_adjustment',
				'get_link' => 'index.php?cmd=get_withdraw_adjustment',
				'search_link' => 'index.php?cmd=search_withdraw_adjustment&page=1'), 'site_html.tpl');
	}
}
?>