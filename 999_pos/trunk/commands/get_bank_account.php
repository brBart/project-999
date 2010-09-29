<?php
/**
 * Library containing the GetBankAccountCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_object.php');
/**
 * Library with the bank account class.
 */
require_once('business/cash.php');
/**
 * For creating the select options.
 */
require_once('business/list.php');

/**
 * Displays the bank account form in idle mode.
 * @package Command
 * @author Roberto Oliveros
 */
class GetBankAccountCommand extends GetObjectCommand{
	/**
	 * Gets the desired object.
	 * @return variant
	 */
	protected function getObject(){
		$bank_account = BankAccount::getInstance($this->_mRequest->getProperty('id'));
		if(!is_null($bank_account))
			return $bank_account;
		else
			throw new Exception('Cuenta bancaria no existe.');
	}
	
	/**
	 * Display failure in case the object does not exists or an error occurs.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio', 'Mantenimiento', 'Cuentas Bancarias');
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'main_menu_pos_admin_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_pos_admin_html.tpl',
				'content' => 'object_menu_html.tpl', 'notify' => '1', 'type' => 'error',
				'message' => $msg, 'create_link' => 'index.php?cmd=create_bank_account',
				'show_list_link' => 'index.php?cmd=show_bank_account_list&page=1'), 'site_html.tpl');
	}
	
	/**
	 * Display the form for the object.
	 * @param string $key
	 * @param variant $obj
	 * @param array $backQuery
	 */
	protected function displayObject($key, $obj, $backQuery){
		$back_trace = array('Inicio', 'Mantenimiento', 'Cuentas Bancarias');
		$number = $obj->getNumber();
		$bank = $obj->getBank();
		
		// Build the back link.
		$back_link = (is_null($backQuery)) ? 'index.php?cmd=show_bank_account_menu' :
				'index.php?cmd=' . $backQuery['cmd'] . '&page=' . $backQuery['page'];
		// Build the foward link.
		$foward_link = 'index.php?cmd=get_bank_account';
		$foward_link .= (is_null($backQuery)) ? '' : '&last_cmd=' . $backQuery['cmd'] . '&page=' .
				$backQuery['page'];
				
		// Get the lists for the select options.
		$empty_item = array(array());
		$bank_list = array_merge($empty_item, BankList::getList($pages, $items, 0));
		
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => $back_link, 'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'bank_account_form_html.tpl', 'status' => '1', 'key' => $key, 'id' => $number,
				'number' => $number, 'holder_name' => $obj->getHolderName(), 'bank_id' => $bank->getId(),
				'bank_list' => $bank_list, 'foward_link' => $foward_link, 'edit_cmd' => 'edit_bank_account',
				'delete_cmd' => 'delete_bank_account'), 'site_html.tpl');
	}
}
?>