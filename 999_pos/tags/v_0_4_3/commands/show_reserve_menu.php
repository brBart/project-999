<?php
/**
 * Library containing the ShowReserveMenuCommand class.
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
 * Command to display the product reserves menu.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowReserveMenuCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Mantenimiento', 'Reservados');
		Page::display(array('module_title' => ADMIN_TITLE, 'main_menu' => 'main_menu_admin_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_admin_html.tpl',
				'content' => 'reserve_menu_html.tpl', 'create_link' => 'index.php?cmd=create_bank',
				'show_list_link' => 'index.php?cmd=show_bank_list&page=1'), 'site_html.tpl');
	}
}
?>