<?php
/**
 * Library containing the ShowMenuOperations command.
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
 * Command to display the operations main menu.
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
		Page::display(array('main_menu' => 'main_menu_operations.tpl', 'second_menu' => 'blank.tpl',
				'content' => 'blank.tpl', 'success' => '1'), 'operations_html.tpl');
	}
}
?>