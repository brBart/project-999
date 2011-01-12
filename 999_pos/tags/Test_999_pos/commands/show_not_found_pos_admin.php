<?php
/**
 * Library containing the ShowNotFoundPosAdminCommand class.
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
 * Command to display the not found message on the POS Administration site.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowNotFoundPosAdminCommand extends ShowNotFoundCommand{
	/**
	 * Displays the failure message to the user in html format.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio');
		Page::display(array('module_title' => POS_ADMIN_TITLE,
				'main_menu' => 'main_menu_pos_admin_html.tpl', 'back_trace' => $back_trace,
				'second_menu' => 'none', 'content' => 'none', 'notify' => '1', 'type' => 'error',
				'message' => $msg), 'site_html.tpl');
	}
}
?>