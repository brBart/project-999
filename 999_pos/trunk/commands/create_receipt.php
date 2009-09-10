<?php
/**
 * Library containing the CreateReceipt command.
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
 * Displays the receipt form in edit mode.
 * @package Command
 * @author Roberto Oliveros
 */
class CreateReceiptCommand extends CreateObjectCommand{
	/**
	 * Tests if the user has the right to create the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'receipt', 'write');
	}
	
	/**
	 * Display failure in case the user doesn't have rights.
	 */
	protected function displayFailure(){
		$back_trace = array('Inicio', 'Movimientos', 'Recibos');
		$msg = 'Usuario no cuenta con los suficientes privilegios.';
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'main_menu_operations_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'movements_menu_html.tpl',
				'content' => 'receipt_menu_html.tpl', 'notify' => '1', 'type' => 'error', 'message' => $msg),
				'site_html.tpl');
	}
	
	/**
	 * Creates the desired object.
	 * @return variant
	 */
	protected function createObject(){
		return new Receipt();
	}
	
	/**
	 * Display the form for creating the object.
	 * @param string $key
	 * @param variant $obj
	 */
	protected function displayObject($key, $obj){
		$back_trace = array('Inicio', 'Movimientos', 'Recibos');
		
		$user = $obj->getUser();
		$date = $obj->getDate();
		
		// Get the lists for the select options.
		$empty_item = array(array());
		$supplier_list = array_merge($empty_item, SupplierList::getList($pages, $items, 0));
		
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'blank.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'none', 'content' => 'receipt_form_html.tpl',
				'key' => $key, 'back_link' => 'index.php?cmd=show_receipt_menu',
				'foward_link' => 'index.php?cmd=get_receipt', 'username' => $user->getUserName(),
				'date' => $date, 'supplier_list' => $supplier_list),
				'site_html.tpl');
	}
}
?>