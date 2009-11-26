<?php
/**
 * Library containing the CreateSupplierCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/create_object.php');
/**
 * Library with the supplier class.
 */
require_once('business/agent.php');

/**
 * Displays the supplier form in edit mode.
 * @package Command
 * @author Roberto Oliveros
 */
class CreateSupplierCommand extends CreateObjectCommand{
	/**
	 * Tests if the user has the right to create the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'supplier', 'write');
	}
	
	/**
	 * Display failure in case the user doesn't have rights.
	 */
	protected function displayFailure(){
		$back_trace = array('Inicio', 'Mantenimiento', 'Proveedores');
		$msg = 'Usuario no cuenta con los suficientes privilegios.';
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'main_menu_operations_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_operations_html.tpl',
				'content' => 'object_menu_html.tpl', 'notify' => '1', 'type' => 'error', 'message' => $msg,
				'create_link' => 'index.php?cmd=create_supplier',
				'show_list_link' => 'index.php?cmd=show_supplier_list&page=1'), 'site_html.tpl');
	}
	
	/**
	 * Creates the desired object.
	 * @return variant
	 */
	protected function createObject(){
		return new Supplier();
	}
	
	/**
	 * Display the form for creating the object.
	 * @param string $key
	 * @param variant $obj
	 */
	protected function displayObject($key, $obj){
		$back_trace = array('Inicio', 'Mantenimiento', 'Proveedores');
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'blank.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'supplier_form_html.tpl', 'status' => '0', 'key' => $key,
				'back_link' => 'index.php?cmd=show_supplier_menu',
				'foward_link' => 'index.php?cmd=get_supplier'), 'site_html.tpl');
	}
}
?>