<?php
/**
 * Library containing the CreateBankAccountCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/create_object.php');
/**
 * Library with the bank account class.
 */
require_once('business/cash.php');
/**
 * For creating the select options.
 */
require_once('business/list.php');

/**
 * Displays the bank account form in edit mode.
 * @package Command
 * @author Roberto Oliveros
 */
class CreateBankAccountCommand extends CreateObjectCommand{
	/**
	 * Tests if the user has the right to create the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'bank_account', 'write');
	}
	
	/**
	 * Display failure in case the user doesn't have rights.
	 */
	protected function displayFailure(){
		$back_trace = array('Inicio', 'Mantenimiento', 'Cuentas Bancarias');
		$msg = 'Usuario no cuenta con los suficientes privilegios.';
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'main_menu_pos_admin_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_pos_admin_html.tpl',
				'content' => 'object_menu_html.tpl', 'notify' => '1', 'type' => 'error', 'message' => $msg,
				'create_link' => 'index.php?cmd=create_bank_account',
				'show_list_link' => 'index.php?cmd=show_bank_account_list&page=1'), 'site_html.tpl');
	}
	
	/**
	 * Creates the desired object.
	 * @return variant
	 */
	protected function createObject(){
		return new BankAccount();
	}
	
	/**
	 * Display the form for creating the object.
	 * @param string $key
	 * @param variant $obj
	 */
	protected function displayObject($key, $obj){
		$back_trace = array('Inicio', 'Mantenimiento', 'Cuentas Bancarias');
		
		// Get the lists for the select options.
		$empty_item = array(array());
		$bank_list = array_merge($empty_item, BankList::getList($pages, $items, 0));
		
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'blank.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'bank_account_form_html.tpl', 'status' => '0', 'key' => $key,
				'back_link' => 'index.php?cmd=show_bank_account_menu',
				'forward_link' => 'index.php?cmd=get_bank_account', 'bank_list' => $bank_list),
				'site_html.tpl');
	}
}
?>