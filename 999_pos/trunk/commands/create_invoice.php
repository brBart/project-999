<?php
/**
 * Library containing the CreateInvoiceCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/create_cash_register_object.php');
/**
 * Library with the document classes.
 */
require_once('business/document.php');

/**
 * Creates an invoice an returns its information.
 * @package Command
 * @author Roberto Oliveros
 */
class CreateInvoiceCommand extends CreateCashRegisterObjectCommand{
	/**
	 * Tests if the user has the right to create the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'invoice', 'write');
	}
	
	/**
	 * Creates the desired object.
	 * @return variant
	 */
	protected function createObject(CashRegister $cashRegister){
		return new Invoice($cashRegister);
	}
	
	/**
	 * Display the form for creating the object.
	 * @param string $key
	 * @param variant $obj
	 */
	protected function displayObject($key, $obj){
		$user = $obj->getUser();
		Page::display(array('key' => $key, 'username' => $user->getUserName(),
				'date_time' => $obj->getDateTime()), 'invoice_xml.tpl');
	}
}
?>