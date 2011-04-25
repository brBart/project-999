<?php
/**
 * Library containing the GetInvoiceCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_cash_register_object.php');
/**
 * Library with the invoice class.
 */
require_once('business/document.php');
/**
 * For obtaining the cash receipt.
 */
require_once('business/cash.php');

/**
 * Displays the invoice form in idle mode.
 * @package Command
 * @author Roberto Oliveros
 */
class GetInvoiceCommand extends GetCashRegisterObjectCommand{
	/**
	 * Returns an instance of the object to display.
	 * 
	 * @param string $id
	 * @return variant
	 */
	protected function getObject($id){
		return Invoice::getInstance((int)$id);
	}
	
	/**
	 * Displays the object's form.
	 * @param WorkingDay $workingDay
	 * @param CashRegister $cashRegister
	 * @param Shift $shift
	 * @param string $key
	 * @param variant $obj
	 */
	protected function displayObject(WorkingDay $workingDay, CashRegister $cashRegister,
			Shift $shift, $key, $obj){
				
		$correlative = $obj->getCorrelative();
		$user = $obj->getUser();
		$cash_receipt = CashReceipt::getInstance($obj);
		$cash = $cash_receipt->getCash();
		
		Page::display(array('module_title' => POS_TITLE, 'back_trace' => $this->getBackTrace(),
				'content' => $this->getTemplate(), 'key' => $key,
				'cash_register_id' => $cashRegister->getId(), 'date' => $workingDay->getDate(),
				'status' => $obj->getStatus(), 'shift' => $shift->getName() . ', ' . $shift->getTimeTable(),
				'cash_register_status' => (int)$cashRegister->isOpen(),
				'serial_number' => $correlative->getSerialNumber(), 'number' => $obj->getNumber(),
				'date_time' => $obj->getDateTime(), 'username' => $user->getUserName(),
				'nit' => $obj->getCustomerNit(), 'customer' => $obj->getCustomerName(),
				'cash_amount' => $cash->getAmount() + $cash_receipt->getChange(),
				'vouchers_total' => $cash_receipt->getTotalVouchers(),
				'change_amount' => $cash_receipt->getChange()), 'site_pos_html.tpl');
	}
	
	/**
	 * Returns the back trace array.
	 * 
	 * @return array
	 */
	protected function getBackTrace(){
		return array('Inicio', 'Facturaci&oacute;n');
	}
	
	/**
	 * Returns the template's name.
	 * 
	 * @return string
	 */
	protected function getTemplate(){
		return 'invoice_form_html.tpl';
	}
	
	/**
	 * Returns the "does not exist" message to display.
	 * 
	 * @return string
	 */
	protected function getMessage(){
		return 'Factura no existe.';
	}
}
?>