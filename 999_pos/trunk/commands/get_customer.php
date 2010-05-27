<?php
/**
 * Library containing the GetCustomerCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_object.php');
/**
 * Library with the branch class.
 */
require_once('business/agent.php');

/**
 * Returns the customer object key and name.
 * @package Command
 * @author Roberto Oliveros
 */
class GetCustomerCommand extends GetObjectCommand{
	/**
	 * Gets the desired object.
	 * @return variant
	 */
	protected function getObject(){
		return Customer::getInstance($this->_mRequest->getProperty('nit'));
	}
	
	/**
	 * Display failure in case the object does not exists or an error occurs.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		Page::display(array('message' => $msg), 'error_xml.tpl');
	}
	
	/**
	 * Display the information of the object.
	 * @param string $key
	 * @param variant $obj
	 * @param array $backQuery
	 */
	protected function displayObject($key, $obj, $backQuery){
		Page::display(array('key' => $key, 'name' => $obj->getName()), 'customer_xml.tpl');
	}
}
?>