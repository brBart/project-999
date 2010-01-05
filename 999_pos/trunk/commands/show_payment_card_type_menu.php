<?php
/**
 * Library containing the ShowPaymentCardTypeMenuCommand class.
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
 * Command to display the payment card type menu.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowPaymentCardTypeMenuCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Mantenimiento', 'Tipos de Tarjeta');
		Page::display(array('module_title' => ADMIN_TITLE, 'main_menu' => 'main_menu_admin_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_admin_html.tpl',
				'content' => 'object_menu_html.tpl', 'create_link' => 'index.php?cmd=create_payment_card_type',
				'show_list_link' => 'index.php?cmd=show_payment_card_type_list&page=1'), 'site_html.tpl');
	}
}
?>