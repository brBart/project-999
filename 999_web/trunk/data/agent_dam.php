<?php
/**
 * Library for the classes which access the database.
 * @package agentDAM
 */

/**
 * Class that provides access to the database.
 * @package agentDAM
 * @author Roberto Oliveros
 */
class CustomerDAM{
	/**
	 * Returns Customer instance object if a match for the nit providad was found in database. Null if not.
	 *
	 * @param string $nit
	 * @return Customer
	 */
	static public function getInstance($nit){
		if($nit == '1725045-5'){
			$customer = new Customer('1725045-5', FROM_DATABASE);
			$customer->setData('Infodes');
			return $customer;
		}
		else
			return null;
	}
	
	/**
	 * Returns true or false depending if a Customer already has the provided nit in the database.
	 *
	 * @param string $nit
	 * @return boolean
	 */
	static public function exist($nit){
		if($nit == '1725045-5')
			return true;
		else
			return false;
	}
	
	/**
	 * Insert Customer's data to the database.
	 *
	 * @param Customer $customer
	 * @return void
	 */
	static public function insert($customer){
		// Code here...
	}
	
	/**
	 * Update Customer's data in the database.
	 *
	 * @param Customer $customer
	 * @return void
	 */
	static public function update($customer){
		// Code here...
	}
}
?>