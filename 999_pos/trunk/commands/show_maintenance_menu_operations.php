<?php
/**
 * Library containing the ShowMaintenanceMenuOperations command.
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
 * Command to display the maintenance menu on the operations site.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowMaintenanceMenuOperationsCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		// Removed previos session object if necessary.
		$key = $request->getProperty('key');
		if(!is_null($key))
			$helper->removeObject((int)$key);
		
		$back_trace = array('Inicio', 'Mantenimiento');
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'main_menu_operations_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_operations_html.tpl',
				'content' => 'blank.tpl', 'notify' => '0'), 'site_html.tpl');
	}
}
?>