<?php
/**
 * Library containing the GetInvoiceListCommand class.
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
class GetInvoiceListCommand extends GetCashRegisterObjectListCommand{
	/**
	 * Gets the desired object's list.
	 * @param CashRegister $register
	 * @return array
	 */
	protected function getList(CashRegister $register){
		return InvoiceList::getList($register);
	}
	
	/**
	 * Display the object's list.
	 * @param array $list
	 */
	protected function displayList($list){
		Page::display(array('list' => $list), 'invoice_list_xml.tpl');
	}
}
?>