<?php
/**
 * Library containing the GetInvoiceDetailsCommand class.
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
 * Defines functionality for obtaining an invoice's details.
 * @package Command
 * @author Roberto Oliveros
 */
class GetInvoiceDetailsCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$invoice = $helper->getObject((int)$request->getProperty('key'));
		$details = DetailsPrinter::showPage($invoice);
		Page::display(array('details' => $details, 'sub_total' => $invoice->getSubTotal(),
				'discount_percentage' => $invoice->getDiscountPercentage(),
				'discount' => $invoice->getTotalDiscount(), 'total' => $invoice->getTotal(),
				'total_items' => count($details)), 'invoice_details_xml.tpl');
	}
}
?>