<?php
/**
 * Library containing the AddCashDepositCommand class.
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
 * For making the transaction.
 */
require_once('business/cash.php');

/**
 * Defines functionality for adding cash to a deposit.
 * @package Command
 * @author Roberto Oliveros
 */
class AddCashDepositCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$receipt_id = $request->getProperty("cash_receipt_id");
		if ($receipt_id == ''){
			$msg = 'Seleccione un recibo.';
			Page::display(array('success' => '0', 'element_id' => 'cash_receipt_id',
					'message' => $msg), 'validate_xml.tpl');
			return;
		}
		
		$cash = Cash::getInstance((int)$receipt_id);
		if(is_null($cash)){
			$msg = 'Recibo no existe.';
			Page::display(array('message' => $msg), 'error_xml.tpl');
			return;
		}
		
		$amount = $request->getProperty('amount');
		if($amount == ''){
			$msg = 'Ingrese el monto.';
			Page::display(array('success' => '0', 'element_id' => 'amount',
					'message' => $msg), 'validate_xml.tpl');
			return;
		}
		
		// Now do the work.
		try{
			$deposit = $helper->getObject((int)$request->getProperty("deposit_key"));
			
			DepositEvent::apply($cash, $deposit, $amount);
					
			Page::display(array(), 'success_xml.tpl');
		} catch(ValidateException $e){
			$msg = $e->getMessage();
			$element_id = $e->getProperty();
			Page::display(array('success' => '0', 'element_id' => $element_id,
					'message' => $msg), 'validate_xml.tpl');
			return;
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('message' => $msg), 'error_xml.tpl');
			return;
		}
	}
}
?>