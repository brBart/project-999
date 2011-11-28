<?php
/**
 * Library containing the SetDiscountInvoiceCommand base class.
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
 * Defines functionality for setting a discount object on an invoice.
 * @package Command
 * @author Roberto Oliveros
 */
class SetDiscountInvoiceCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$invoice = $helper->getObject((int)$request->getProperty('key'));
		$discount = $helper->getObject((int)$request->getProperty('discount_key'));
		
		if($discount->getPercentage() != 0){
			$invoice->setDiscount($discount);
		}
		else{
			// If percentage is cero, then set it as NULL and remove it from memory.
			$invoice->setDiscount(NULL);
			$helper->removeObject((int)$request->getProperty('discount_key'));
		}
		
		Page::display(array(), 'success_xml.tpl');
	}
}
?>