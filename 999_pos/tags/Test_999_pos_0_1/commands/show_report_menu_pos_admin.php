<?php
/**
 * Library containing the ShowReportMenuPosAdminCommand class.
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
 * Command to display the report menu on the POS Administration site.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowReportMenuPosAdminCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Herramientas', 'Reportes');
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'main_menu_pos_admin_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'tools_menu_pos_admin_html.tpl',
				'content' => 'report_menu_pos_admin_html.tpl', 'notify' => '0'), 'site_html.tpl');
	}
}
?>