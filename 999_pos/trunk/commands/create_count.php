<?php
/**
 * Library containing the CreateCountCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/create_object.php');
/**
 * Library with the count class.
 */
require_once('business/inventory.php');

/**
 * Displays the count form in edit mode.
 * @package Command
 * @author Roberto Oliveros
 */
class CreateCountCommand extends CreateObjectCommand{
	/**
	 * Tests if the user has the right to create the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'count', 'write');
	}
	
	/**
	 * Display failure in case the user doesn't have rights.
	 */
	protected function displayFailure(){
		$back_trace = array('Inicio', 'Inventariados', 'Conteos');
		$msg = 'Usuario no cuenta con los suficientes privilegios.';
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'main_menu_operations_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'inventory_menu_html.tpl',
				'content' => 'document_menu_html.tpl', 'document_name' => 'Conteo',
				'create_link' => 'index.php?cmd=create_count', 'get_link' => 'index.php?cmd=get_count',
				'search_link' => 'index.php?cmd=search_count&page=1', 'notify' => '1', 'type' => 'error',
				'message' => $msg), 'site_html.tpl');
	}
	
	/**
	 * Creates the desired object.
	 * @return variant
	 */
	protected function createObject(){
		return new Count();
	}
	
	/**
	 * Display the form for creating the object.
	 * @param string $key
	 * @param variant $obj
	 */
	protected function displayObject($key, $obj){
		$back_trace = array('Inicio', 'Inventariados', 'Conteos');
		
		$user = $obj->getUser();
		
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'blank.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'none', 'content' => 'count_form_html.tpl',
				'status' => '0', 'key' => $key, 'back_link' => 'index.php?cmd=show_count',
				'foward_link' => 'index.php?cmd=get_count', 'username' => $user->getUserName(),
				'date_time' => $obj->getDateTime()), 'site_html.tpl');
	}
}
?>