<?php
/**
 * Library containing the CreateUserAccountCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/create_object.php');
/**
 * Library with the user account class.
 */
require_once('business/user_account.php');
/**
 * For creating the select options.
 */
require_once('business/list.php');

/**
 * Displays the user account form in edit mode.
 * @package Command
 * @author Roberto Oliveros
 */
class CreateUserAccountCommand extends CreateObjectCommand{
	/**
	 * Tests if the user has the right to create the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'user_account', 'write');
	}
	
	/**
	 * Display failure in case the user doesn't have rights.
	 */
	protected function displayFailure(){
		$back_trace = array('Inicio', 'Mantenimiento', 'Cuentas de Usuario');
		$msg = 'Usuario no cuenta con los suficientes privilegios.';
		Page::display(array('module_title' => ADMIN_TITLE, 'main_menu' => 'main_menu_admin_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_admin_html.tpl',
				'content' => 'object_menu_html.tpl', 'notify' => '1', 'type' => 'error', 'message' => $msg,
				'create_link' => 'index.php?cmd=create_user_account',
				'show_list_link' => 'index.php?cmd=show_user_account_list&page=1'), 'site_html.tpl');
	}
	
	/**
	 * Creates the desired object.
	 * @return variant
	 */
	protected function createObject(){
		return new UserAccount();
	}
	
	/**
	 * Display the form for creating the object.
	 * @param string $key
	 * @param variant $obj
	 */
	protected function displayObject($key, $obj){
		$back_trace = array('Inicio', 'Mantenimiento', 'Cuentas de Usuario');
		
		// Get the lists for the select options.
		$empty_item = array(array());
		$role_list = array_merge($empty_item, RoleList::getList($pages, $items, 0));
		
		Page::display(array('module_title' => ADMIN_TITLE, 'main_menu' => 'blank.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'user_account_form_html.tpl', 'status' => '0', 'key' => $key,
				'back_link' => 'index.php?cmd=show_user_account_menu',
				'foward_link' => 'index.php?cmd=get_user_account', 'role_list' => $role_list),
				'site_html.tpl');
	}
}
?>