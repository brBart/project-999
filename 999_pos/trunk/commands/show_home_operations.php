<?php
/**
 * Library containing the ShowHomeOperations command.
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
 * Command to display the operations site.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowHomeOperationsCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio');
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'main_menu_operations.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'blank.tpl', 'content' => 'blank.tpl',
				'success' => '1'), 'site_html.tpl');
	}
}
?>