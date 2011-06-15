<?php
/**
 * Library containing the ShowNotFoundPosCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/show_not_found.php');
/**
 * For displaying the results.
 */
require_once('presentation/page.php');

/**
 * Command to display the not found message on the POS site.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowNotFoundPosCommand extends ShowNotFoundCommand{
	/**
	 * Displays the failure message to the user in html format.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio');
		
		// Sorry, bad practice necessary.
		$working_day = $this->_mHelper->getWorkingDay();
		
		Page::display(array('module_title' => POS_TITLE, 'back_trace' => $back_trace,
				'content' => 'main_menu_pos_html.tpl', 'date' => $working_day->getDate(),
				'status' => (int)$working_day->isOpen(), 'notify' => '1', 'type' => 'error',
				'message' => $msg), 'site_pos_html.tpl');
	}
}
?>