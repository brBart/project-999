<?php
/**
 * Library containing the ShowCashReceiptFormCommand class.
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
 * Command to display the cash receipt form.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowCashReceiptFormCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$receipt = $helper->getObject((int)$request->getProperty('key'));
		$invoice = $receipt->getInvoice();
		$cash = $receipt->getCash();
		$change = $receipt->getChange();
		
		Page::display(array('cash' => ($cash->getAmount() + $change),
				'total_vouchers' => $receipt->getTotalVouchers(),
				'invoice_total' => $invoice->getTotal(), 'change' => $change),
				'cash_receipt_form_html.tpl');
	}
}
?>