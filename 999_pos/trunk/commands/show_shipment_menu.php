<?php
/**
 * Library containing the ShowShipmentMenuCommand class.
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
 * Command to display the shipments menu.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowShipmentMenuCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Movimientos', 'Envios');
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'main_menu_operations_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'movements_menu_html.tpl',
				'content' => 'document_menu_html.tpl', 'document_name' => 'Envio',
				'create_link' => 'index.php?cmd=create_shipment', 'get_link' => 'index.php?cmd=get_shipment',
				'search_link' => 'index.php?cmd=search_shipment&page=1'), 'site_html.tpl');
	}
}
?>