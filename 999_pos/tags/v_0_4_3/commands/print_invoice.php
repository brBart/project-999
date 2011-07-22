<?php
/**
 * Library containing the PrintInvoiceCommand base class.
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
 * For displaying the object details.
 */
require_once('business/itemized.php');
/**
 * For displaying the invoice.
 */
require_once('business/document.php');
/**
 * For displaying the company's name.
 */
require_once('business/various.php');
/**
 * For obtaining the cash receipt.
 */
require_once('business/cash.php');

/**
 * Defines functionality for displaying an invoice for printing purposes.
 * @package Command
 * @author Roberto Oliveros
 */
class PrintInvoiceCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$invoice = Invoice::getInstance((int)$request->getProperty('id'));
		
		$company = Company::getInstance();
		
		$correlative = $invoice->getCorrelative();
		$details = DetailsPrinter::showPage($invoice);
		
		$cash_receipt = CashReceipt::getInstance($invoice);
		$cash = $cash_receipt->getCash();
		
		$cash_register = $invoice->getCashRegister();
		
		$user = $invoice->getUser();
		
		Page::display(array('company_name' => $company->getName(), 'company_nit' => $company->getNit(),
				'corporate_name' => $company->getCorporateName(), 'telephone' => $company->getTelephone(),
				'address' => $company->getAddress(),
				'resolution_number' => $correlative->getResolutionNumber(),
				'resolution_date' => $correlative->getResolutionDate(), 'regime' => $correlative->getRegime(),
				'correlative_initial_number' => $correlative->getInitialNumber(),
				'correlative_final_number' => $correlative->getFinalNumber(),
				'serial_number' => $correlative->getSerialNumber(), 'number' => $invoice->getNumber(),
				'date_time' => $invoice->getDateTime(), 'customer_nit' => $invoice->getCustomerNit(),
				'customer_name' => $invoice->getCustomerName(), 'details' => $details,
				'sub_total' => $invoice->getSubTotal(),
				'discount_percentage' => $invoice->getDiscountPercentage(),
				'discount' => $invoice->getTotalDiscount(), 'total' => $invoice->getTotal(),
				'cash_amount' => $cash->getAmount() + $cash_receipt->getChange(),
				'vouchers_total' => $cash_receipt->getTotalVouchers(),
				'change_amount' => $cash_receipt->getChange(), 'cash_register_id' => $cash_register->getId(),
				'username' => $user->getUserName()), 'invoice_print_html.tpl');
	}
}
?>