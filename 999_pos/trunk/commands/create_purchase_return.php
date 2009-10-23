<?php
/**
 * Library containing the CreatePurchaseReturnCommand class.
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
 * Displays the purchase return form in edit mode.
 * @package Command
 * @author Roberto Oliveros
 */
class CreatePurchaseReturnCommand extends CreateObjectCommand{
	/**
	 * Tests if the user has the right to create the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'purchase_return', 'write');
	}
	
	/**
	 * Display failure in case the user doesn't have rights.
	 */
	protected function displayFailure(){
		$back_trace = array('Inicio', 'Movimientos', 'Devoluciones');
		$msg = 'Usuario no cuenta con los suficientes privilegios.';
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'main_menu_operations_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'movements_menu_html.tpl',
				'content' => 'document_menu_html.tpl', 'document_name' => 'Devoluci&oacute;n',
				'create_link' => 'index.php?cmd=create_purchase_return',
				'get_link' => 'index.php?cmd=get_purchase_return',
				'search_link' => 'index.php?cmd=search_purchase_return&page=1',
				'notify' => '1', 'type' => 'error', 'message' => $msg), 'site_html.tpl');
	}
	
	/**
	 * Creates the desired object.
	 * @return variant
	 */
	protected function createObject(){
		return new PurchaseReturn();
	}
	
	/**
	 * Display the form for creating the object.
	 * @param string $key
	 * @param variant $obj
	 */
	protected function displayObject($key, $obj){
		$back_trace = array('Inicio', 'Movimientos', 'Devoluciones');
		
		$user = $obj->getUser();
		
		// Get the lists for the select options.
		$empty_item = array(array());
		$supplier_list = array_merge($empty_item, SupplierList::getList($pages, $items, 0));
		
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'blank.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'purchase_return_form_html.tpl', 'status' => '0', 'key' => $key,
				'back_link' => 'index.php?cmd=show_purchase_return_menu',
				'foward_link' => 'index.php?cmd=get_purchase_return', 'username' => $user->getUserName(),
				'date_time' => $obj->getDateTime(), 'supplier_list' => $supplier_list,
				'reason' => $obj->getReason()), 'site_html.tpl');
	}
}
?>