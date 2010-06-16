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
 * For user accounts validations.
 */
require_once('business/user_account.php');
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
		$user = $helper->getUser();
		$invoice = $helper->getObject((int)$request->getProperty('invoice_key'));
		
		try{
			if(AccessManager::isAllowed($user, 'cash_receipt', 'write')){
				$receipt = $invoice->createCashReceipt();
				$key = KeyGenerator::generateKey();
				$helper->setObject($key, $receipt);
				Page::display(array('key' => $key), 'object_key_xml.tpl');
			}
			else{
				$msg = 'Usuario no cuenta con los suficientes privilegios.';
				Page::display(array('message' => $msg), 'error_xml.tpl');
			}
		} catch(ValidateException $e){
			$msg = $e->getMessage();
			$element_id = $e->getProperty();
			Page::display(array('element_id' => $element_id, 'message' => $msg, 'success' => '0'),
					'validate_xml.tpl');
			return;
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('message' => $msg), 'error_xml.tpl');
		}
	}
}
?>