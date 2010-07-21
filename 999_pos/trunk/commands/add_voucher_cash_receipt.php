<?php
/**
 * Library containing the AddVoucherCashReceiptCommand class.
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
 * For obtaining the payment card objects.
 */
require_once('business/cash.php');

/**
 * Defines functionality for adding a voucher to cash receipt.
 * @package Command
 * @author Roberto Oliveros
 */
class AddVoucherCashReceiptCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		// Check if the user entered the values.
		$transaction_number = $request->getProperty('transaction_number');
		if($transaction_number == ''){
			$msg = 'Ingrese n&uacute;mero de transacci&oacute;n.';
			Page::display(array('success' => '0', 'element_id' => 'transaction_number',
					'message' => $msg), 'validate_xml.tpl');
			return;
		}
		
		$payment_card_number = $request->getProperty('payment_card_number');
		if($payment_card_number == ''){
			$msg = 'Ingrese n&uacute;mero de la tarjeta.';
			Page::display(array('success' => '0', 'element_id' => 'payment_card_number',
					'message' => $msg), 'validate_xml.tpl');
			return;
		}
		
		$payment_card_type_id = $request->getProperty('payment_card_type_id');
		if($payment_card_type_id == ''){
			$msg = 'Seleccione un tipo de tarjeta.';
			Page::display(array('success' => '0', 'element_id' => 'payment_card_type_id',
					'message' => $msg), 'validate_xml.tpl');
			return;
		}
		
		$payment_card_type = PaymentCardType::getInstance((int)$payment_card_type_id);
		if(is_null($payment_card_type)){
			$msg = 'Tipo de tarjeta no existe.';
			Page::display(array('message' => $msg), 'error_xml.tpl');
			return;
		}
		
		$payment_card_brand_id = $request->getProperty('payment_card_brand_id');
		if($payment_card_brand_id == ''){
			$msg = 'Seleccione una marca de tarjeta.';
			Page::display(array('success' => '0', 'element_id' => 'payment_card_brand_id',
					'message' => $msg), 'validate_xml.tpl');
			return;
		}
		
		$payment_card_brand = PaymentCardBrand::getInstance((int)$payment_card_brand_id);
		if(is_null($payment_card_brand)){
			$msg = 'Marca de tarjeta no existe.';
			Page::display(array('message' => $msg), 'error_xml.tpl');
			return;
		}
		
		$holder_name = $request->getProperty('holder_name');
		if($holder_name == ''){
			$msg = 'Ingrese el nombre del titular.';
			Page::display(array('success' => '0', 'element_id' => 'holder_name',
					'message' => $msg), 'validate_xml.tpl');
			return;
		}
		
		$expiration_date = $request->getProperty('expiration_date');
		if($expiration_date == '' || $expiration_date == '/'){
			$msg = 'Ingrese fecha de vencimiento.';
			Page::display(array('success' => '0', 'element_id' => 'expiration_date',
					'message' => $msg), 'validate_xml.tpl');
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
			$payment_card = new PaymentCard($payment_card_number, $payment_card_type,
					$payment_card_brand, $holder_name, $expiration_date);
					
			$invoice = $helper->getObject((int)$request->getProperty('invoice_key'));
			$receipt = $helper->getObject((int)$request->getProperty('cash_receipt_key'));

			VoucherEntryEvent::apply($transaction_number, $payment_card, $invoice,
					$receipt, $amount);
					
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