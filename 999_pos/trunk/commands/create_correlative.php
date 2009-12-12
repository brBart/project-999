<?php
/**
 * Library containing the CreateCorrelativeCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/create_object.php');
/**
 * Library with the correlative class.
 */
require_once('business/document.php');

/**
 * Displays the invoice correlative form in edit mode.
 * @package Command
 * @author Roberto Oliveros
 */
class CreateCorrelativeCommand extends CreateObjectCommand{
	/**
	 * Tests if the user has the right to create the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'correlative', 'write');
	}
	
	/**
	 * Display failure in case the user doesn't have rights.
	 */
	protected function displayFailure(){
		$back_trace = array('Inicio', 'Mantenimiento', 'Correlativos');
		$msg = 'Usuario no cuenta con los suficientes privilegios.';
		Page::display(array('module_title' => ADMIN_TITLE, 'main_menu' => 'main_menu_admin_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_admin_html.tpl',
				'content' => 'object_menu_html.tpl', 'notify' => '1', 'type' => 'error', 'message' => $msg,
				'create_link' => 'index.php?cmd=create_correlative',
				'show_list_link' => 'index.php?cmd=show_correlative_list&page=1'), 'site_html.tpl');
	}
	
	/**
	 * Creates the desired object.
	 * @return variant
	 */
	protected function createObject(){
		return new Correlative();
	}
	
	/**
	 * Display the form for creating the object.
	 * @param string $key
	 * @param variant $obj
	 */
	protected function displayObject($key, $obj){
		$back_trace = array('Inicio', 'Mantenimiento', 'Correlativos');
		Page::display(array('module_title' => ADMIN_TITLE, 'main_menu' => 'blank.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'correlative_form_html.tpl', 'status' => '0', 'key' => $key,
				'back_link' => 'index.php?cmd=show_correlative_menu',
				'foward_link' => 'index.php?cmd=get_correlative'), 'site_html.tpl');
	}
}
?>