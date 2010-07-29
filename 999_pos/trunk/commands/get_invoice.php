<?php
/**
 * Library containing the GetInvoiceCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_object.php');
/**
 * Library with the invoice class.
 */
require_once('business/document.php');

/**
 * Displays the invoice form in idle mode.
 * @package Command
 * @author Roberto Oliveros
 */
class GetInvoiceCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Facturaci&oacute;n');
		
		// Sorry, bad practice necessary.
		$working_day = $helper->getWorkingDay();
		$cash_register = $helper->getObject((int)$request->getProperty('register_key'));
		$shift = $cash_register->getShift();
		
		$invoice = Invoice::getInstance((int)$request->getProperty('id'));
		if(!is_null($invoice)){
			$key = KeyGenerator::generateKey();
			$helper->setObject($key, $invoice);
			
			$correlative = $invoice->getCorrelative();
			$user = $invoice->getUser();
			$cash_receipt = CashReceipt::getInstance($invoice);
			$cash = $cash_receipt->getCash();
			
			Page::display(array('module_title' => POS_TITLE, 'back_trace' => $back_trace,
					'content' => 'invoice_form_html.tpl', 'key' => $key,
					'cash_register_id' => $cash_register->getId(), 'date' => $working_day->getDate(),
					'status' => '1', 'shift' => $shift->getName() . ', ' . $shift->getTimeTable(),
					'cash_register_status' => (int)$cash_register->isOpen(),
					'serial_number' => $correlative->getSerialNumber(), 'number' => $invoice->getNumber(),
					'date_time' => $invoice->getDateTime(), 'username' => $user->getUserName(),
					'nit' => $invoice->getCustomerNit(), 'customer' => $invoice->getCustomerName(),
					'cash_amount' => $cash->getAmount() + $cash_receipt->getChange(),
					'vouchers_total' => $cash_receipt->getTotalVouchers(),
					'change_amount' => $cash_receipt->getChange()), 'site_pos_html.tpl');
		}
		else{
			$msg = 'Factura no existe.';
			Page::display(array('module_title' => POS_TITLE, 'back_trace' => $back_trace,
					'content' => 'invoice_form_html.tpl', 'cash_register_id' => $cash_register->getId(),
					'date' => $working_day->getDate(), 'status' => '1',
					'shift' => $shift->getName() . ', ' . $shift->getTimeTable(),
					'cash_register_status' => (int)$cash_register->isOpen(), 'notify' => '1',
					'type' => 'error', 'message' => $msg), 'site_pos_html.tpl');
		}
	}
}
?>