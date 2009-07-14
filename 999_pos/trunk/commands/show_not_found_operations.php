<?php
/**
 * Library containing the ShowNotFoundOperations command.
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
 * Command to display the not found message on the operations site.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowNotFoundOperationsCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio');
		$msg = 'Comando no existe.';
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'main_menu_operations_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'blank.tpl', 'content' => 'blank.tpl',
				'notify' => '1', 'message' => $msg), 'site_html.tpl');
	}
}
?>