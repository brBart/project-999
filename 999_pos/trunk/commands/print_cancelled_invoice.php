<?php
/**
 * Library containing the PrintCancelledInvoiceCommand class.
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
 * Defines functionality for obtaining an invoice's cash receipt's vouchers.
 * @package Command
 * @author Roberto Oliveros
 */
class PrintCancelledInvoiceCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$invoice = $helper->getObject((int)$request->getProperty('key'));
		$correlative = $invoice->getCorrelative();
		
		Page::display(array('serial_number' => $correlative->getSerialNumber(), 'number' => $invoice->getNumber(),
				'total' => $invoice->getTotal(), 'date_time' => date('d/m/Y H:i:s')),
				'invoice_cancelled_print_html.tpl');
	}
}
?>