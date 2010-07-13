<?php
/**
 * Library containing the GetCashReceiptVouchersCommand class.
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
 * Defines functionality for obtaining an cash receipt's vouchers.
 * @package Command
 * @author Roberto Oliveros
 */
class GetCashReceiptVouchersCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$receipt = $helper->getObject((int)$request->getProperty('key'));
		$details = $receipt->getVouchers();
		Page::display(array('details' => $details, 'total' => $receipt->getTotalVouchers()),
				'cash_receipt_vouchers_xml.tpl');
	}
}
?>