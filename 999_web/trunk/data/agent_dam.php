<?php
/**
 * @package agentDAM
 */

/**
 * Class that provides access to the database.
 * @package agentDAM
 * @author Roberto Oliveros
 */
class CustomerDAM{
	/**
	 * Returns Customer instance object if a match for the nit providad was found in database.
	 *
	 * @param string $nit
	 * @return Customer
	 */
	static public function getInstance($nit){
		return new Customer($nit);
	}
	
	/**
	 * Returns true or false depending if a Customer already has the provided nit in the database.
	 *
	 * @param string $nit
	 * @return boolean
	 */
	static public function exist($nit){
		return true;
	}
	
	/**
	 * Update Customer's data in the database.
	 *
	 * @param Customer $customer
	 */
	static public function update($customer){
		//Code here...
	}
}
?>