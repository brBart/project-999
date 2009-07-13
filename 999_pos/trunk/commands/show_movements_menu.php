<?php
/**
 * Library containing the ShowMovementsMenu command.
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
 * Command to display the movements menu.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowMovementsMenuCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Movimientos');
		Page::display(array('main_menu' => 'main_menu_operations.tpl', 'back_trace' => $back_trace,
				'second_menu' => 'movements_menu.tpl', 'content' => 'blank.tpl', 'success' => '1'),
				'operations_html.tpl');
	}
}
?>