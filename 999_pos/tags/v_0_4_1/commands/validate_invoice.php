<?php
/**
 * Library containing the ValidateInvoiceCommand class.
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
 * Defines functionality for validating an invoice.
 * @package Command
 * @author Roberto Oliveros
 */
class ValidateInvoiceCommand extends Command{
	/**
	 * Executes the tasks of the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$invoice = $helper->getObject((int)$request->getProperty('invoice_key'));
		
		try{
			$invoice->validate();
			
			Page::display(array(), 'success_xml.tpl');
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