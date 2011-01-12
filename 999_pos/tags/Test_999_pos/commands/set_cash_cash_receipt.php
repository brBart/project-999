<?php
/**
 * Library containing the SetCashCashReceiptCommand base class.
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
 * For adding the cash amount to the cash receipt.
 */
require_once('business/cash.php');

/**
 * Defines functionality for setting the cash amount to a cash receipt.
 * @package Command
 * @author Roberto Oliveros
 */
class SetCashCashReceiptCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$amount = $request->getProperty('amount');
		$receipt = $helper->getObject((int)$request->getProperty('key'));
		
		try{
			CashEntryEvent::apply($receipt, $amount);
		} catch(ValidateException $e){
			$msg = $e->getMessage();
			$element_id = $e->getProperty();
			Page::display(array('success' => '0', 'element_id' => $element_id, 'message' => $msg),
					'validate_xml.tpl');
			return;
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('message' => $msg), 'error_xml.tpl');
			return;
		}
			
		Page::display(array('change' => $receipt->getChange()),
				'set_cash_cash_receipt_xml.tpl');
	}
}
?>