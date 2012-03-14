<?php
/**
 * Library containing the GetAvailableCashReceiptListCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_cash_register_object_list.php');

/**
 * Gets and displays the list.
 * @package Command
 * @author Roberto Oliveros
 */
class GetAvailableCashReceiptListCommand extends GetCashRegisterObjectListCommand{
	/**
	 * Gets the desired object's list.
	 * @param CashRegister $register
	 * @return array
	 */
	protected function getList(CashRegister $register){
		return AvailableCashReceiptList::getList($register);
	}
	
	/**
	 * Display the object's list.
	 * @param array $list
	 */
	protected function displayList($list){
		Page::display(array('list' => $list), 'available_cash_receipt_list_xml.tpl');
	}
}
?>