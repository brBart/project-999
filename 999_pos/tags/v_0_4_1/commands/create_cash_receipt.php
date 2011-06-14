<?php
/**
 * Library containing the CreateCashReceiptCommand class.
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
 * For creating the cash receipt object.
 */
require_once('business/cash.php');

/**
 * Defines functionality for creating a cash receipt.
 * @package Command
 * @author Roberto Oliveros
 */
class CreateCashReceiptCommand extends Command{
	/**
	 * Executes the tasks of the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$invoice = $helper->getObject((int)$request->getProperty('invoice_key'));
		
		try{
			$receipt = $invoice->createCashReceipt();
			$key = KeyGenerator::generateKey();
			$helper->setObject($key, $receipt);
			
			Page::display(array('key' => $key), 'object_key_xml.tpl');
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('message' => $msg), 'error_xml.tpl');
		}
	}
}
?>