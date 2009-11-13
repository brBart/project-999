<?php
/**
 * Library containing the ShowCreateComparisonFormCommand base class.
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
 * Defines functionality for displaying the form for creating a comparison.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowCreateComparisonFormCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$user = $helper->getUser();
		
		if(!AccessManager::isAllowed($user, 'comparison', 'write')){
			$back_trace = array('Inicio', 'Inventariados', 'Comparaciones');
			$msg = 'Usuario no cuenta con los suficientes privilegios.';
			Page::display(array('module_title' => OPERATIONS_TITLE,
					'main_menu' => 'main_menu_operations_html.tpl', 'back_trace' => $back_trace,
					'second_menu' => 'inventory_menu_html.tpl', 'content' => 'document_menu_html.tpl',
					'notify' => '1', 'type' => 'error', 'message' => $msg,
					'document_name' => 'Comparaci&oacute;n',
					'create_link' => 'index.php?cmd=create_comparison',
					'get_link' => 'index.php?cmd=get_comparison',
					'search_link' => 'index.php?cmd=search_comparison&page=1'), 'site_html.tpl');
		}
		else{
			$back_trace = array('Inicio', 'Inventariados', 'Comparaciones');
			Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'blank.tpl',
					'back_trace' => $back_trace, 'second_menu' => 'none',
					'content' => 'comparison_form_html.tpl', 'status' => '0', 'key' => $key,
					'back_link' => 'index.php?cmd=show_comparison_menu',
					'foward_link' => 'index.php?cmd=get_comparison', 'username' => $user->getUserName()),
					'site_html.tpl');
		}
	}
}
?>