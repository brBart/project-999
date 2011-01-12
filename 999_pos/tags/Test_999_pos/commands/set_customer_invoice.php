<?php
/**
 * Library containing the SetCustomerInvoiceCommand base class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('presentation/command.php');
/**
 * For displaying the results.
 */
require_once('presentation/page.php');

/**
 * Defines functionality for setting a customer object on an invoice.
 * @package Command
 * @author Roberto Oliveros
 */
class SetCustomerInvoiceCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$invoice = $helper->getObject((int)$request->getProperty('key'));
		$customer = $helper->getObject((int)$request->getProperty('customer_key'));
		
		try{
			$invoice->setCustomer($customer);
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('message' => $msg), 'error_xml.tpl');
			return;
		}
			
		Page::display(array('nit' => $invoice->getCustomerNit(), 'name' => $invoice->getCustomerName()),
				'set_customer_invoice_xml.tpl');
	}
}
?>