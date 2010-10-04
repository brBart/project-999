<?php
/**
 * Library containing the GetInvoiceCashReceiptVouchersCommand class.
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
class GetInvoiceCashReceiptVouchersCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$invoice = $helper->getObject((int)$request->getProperty('key'));
		$receipt = CashReceipt::getInstance($invoice);
		$vouchers = $receipt->getVouchers();
		$details = array();
		
		foreach($vouchers as $voucher)
			$details[] = $voucher->show();
		
		$page_items = count($details);
		$page = ($page_items > 0) ? 1 : 0;
		
		Page::display(array('details' => $details, 'page_items' => $page_items, 'page' => $page,
				'total' => $receipt->getTotalVouchers()), 'cash_receipt_vouchers_xml.tpl');
	}
}
?>