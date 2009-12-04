<?php
/**
 * Library containing the GetUserAccountCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_object.php');
/**
 * Library with the user account class.
 */
require_once('business/user_account.php');
/**
 * For creating the select options.
 */
require_once('business/list.php');

/**
 * Displays the user account form in idle mode.
 * @package Command
 * @author Roberto Oliveros
 */
class GetUserAccountCommand extends GetObjectCommand{
	/**
	 * Gets the desired object.
	 * @return variant
	 */
	protected function getObject(){
		$user = UserAccount::getInstance($this->_mRequest->getProperty('id'));
		if(!is_null($user))
			return $user;
		else
			throw new Exception('Cuenta no existe.');
	}
	
	/**
	 * Display failure in case the object does not exists or an error occurs.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio', 'Mantenimiento', 'Cuentas de Usuario');
		Page::display(array('module_title' => ADMIN_TITLE, 'main_menu' => 'main_menu_admin_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_admin_html.tpl',
				'content' => 'object_menu_html.tpl', 'notify' => '1', 'type' => 'error',
				'message' => $msg, 'create_link' => 'index.php?cmd=create_user_account',
				'show_list_link' => 'index.php?cmd=show_user_account_list&page=1'), 'site_html.tpl');
	}
	
	/**
	 * Display the form for the object.
	 * @param string $key
	 * @param variant $obj
	 * @param array $backQuery
	 */
	protected function displayObject($key, $obj, $backQuery){
		$back_trace = array('Inicio', 'Mantenimiento', 'Cuentas de Usuario');
		$username = $obj->getUserName();
		$role = $obj->getRole();
		
		// Build the back link.
		$back_link = (is_null($backQuery)) ? 'index.php?cmd=show_user_account_menu' :
				'index.php?cmd=' . $backQuery['cmd'] . '&page=' . $backQuery['page'];
		// Build the foward link.
		$foward_link = 'index.php?cmd=get_user_account';
		$foward_link .= (is_null($backQuery)) ? '' : '&last_cmd=' . $backQuery['cmd'] . '&page=' .
				$backQuery['page'];
				
		// Get the lists for the select options.
		$empty_item = array(array());
		$role_list = array_merge($empty_item, RoleList::getList($pages, $items, 0));
		
		Page::display(array('module_title' => ADMIN_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => $back_link, 'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'user_account_form_html.tpl', 'status' => '1', 'key' => $key, 'id' => $username,
				'username' => $username, 'role_id' => $role->getId(), 'first_name' => $obj->getFirstName(),
				'last_name' => $obj->getLastName(), 'deactivated' => (int)$obj->isDeactivated(),
				'role_list' => $role_list, 'foward_link' => $foward_link, 'edit_cmd' => 'edit_user_account',
				'delete_cmd' => 'delete_user_account'), 'site_html.tpl');
	}
}
?>