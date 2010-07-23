<?php
/**
 * Library containing the GetCustomerCommand class.
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
 * Defines functionality for obtaining a customer object.
 * @package Command
 * @author Roberto Oliveros
 */
class GetCustomerCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		try{
			$customer = Customer::getInstance($request->getProperty('nit'));
			
			$key = KeyGenerator::generateKey();
			$helper->setObject($key, $customer);
			
			Page::display(array('key' => $key, 'name' => $customer->getName()),
					'customer_xml.tpl');
		} catch(ValidateException $e){
			$msg = $e->getMessage();
			$element_id = $e->getProperty();
			Page::display(array('success' => '0', 'element_id' => $element_id, 'message' => $msg),
					'validate_xml.tpl');
			return;
		} catch(Exception $e){
			Page::display(array('message' => $msg), 'error_xml.tpl');
		}
	}
}
?>