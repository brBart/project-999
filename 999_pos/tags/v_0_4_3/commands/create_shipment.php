<?php
/**
 * Library containing the CreateShipmentCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/create_object.php');
/**
 * Library with the document classes.
 */
require_once('business/document.php');
/**
 * For creating the select options.
 */
require_once('business/list.php');

/**
 * Displays the shipment form in edit mode.
 * @package Command
 * @author Roberto Oliveros
 */
class CreateShipmentCommand extends CreateObjectCommand{
	/**
	 * Tests if the user has the right to create the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'shipment', 'write');
	}
	
	/**
	 * Display failure in case the user doesn't have rights.
	 */
	protected function displayFailure(){
		$back_trace = array('Inicio', 'Movimientos', 'Envios');
		$msg = 'Usuario no cuenta con los suficientes privilegios.';
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'main_menu_inventory_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'movements_menu_html.tpl',
				'content' => 'document_menu_html.tpl', 'document_name' => 'Envio',
				'create_link' => 'index.php?cmd=create_shipment', 'get_link' => 'index.php?cmd=get_shipment',
				'search_link' => 'index.php?cmd=search_shipment&page=1', 'notify' => '1', 'type' => 'error',
				'message' => $msg), 'site_html.tpl');
	}
	
	/**
	 * Creates the desired object.
	 * @return variant
	 */
	protected function createObject(){
		return new Shipment();
	}
	
	/**
	 * Display the form for creating the object.
	 * @param string $key
	 * @param variant $obj
	 */
	protected function displayObject($key, $obj){
		$back_trace = array('Inicio', 'Movimientos', 'Envios');
		
		$user = $obj->getUser();
		
		// Get the lists for the select options.
		$empty_item = array(array());
		$branch_list = array_merge($empty_item, BranchList::getList($pages, $items, 0));
		
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'blank.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'none', 'content' => 'shipment_form_html.tpl',
				'status' => '0', 'key' => $key, 'back_link' => 'index.php?cmd=show_shipment_menu',
				'forward_link' => 'index.php?cmd=get_shipment', 'username' => $user->getUserName(),
				'branch_list' => $branch_list), 'site_html.tpl');
	}
}
?>