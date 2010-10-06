<?php
/**
 * Library containing the ShowInvoiceCashReceiptVouchersCommand class.
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
 * For cash receipt object.
 */
require_once('business/cash.php');

/**
 * Defines functionality for obtaining an invoice's cash receipt's vouchers.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowInvoiceCashReceiptVouchersCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$invoice = $helper->getObject((int)$request->getProperty('key'));
		$receipt = CashReceipt::getInstance($invoice);
		$vouchers_obj = $receipt->getVouchers();
		$vouchers = array();
		
		foreach($vouchers_obj as $voucher)
			$vouchers[] = $voucher->show();
		
		Page::display(array('vouchers' => $vouchers, 'vouchers_count' => count($vouchers),
				'vouchers_total' => $receipt->getTotalVouchers()), 'invoice_cash_receipt_vouchers_html.tpl');
	}
}
?>