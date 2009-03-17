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
	 * Returns Customer instance object if a match for the nit providad was found in database.
	 *
	 * @param string $nit
	 * @return Customer
	 */
	static public function getInstance($nit){
		if($nit == '1725045-5'){
			$customer = new Customer('1725045-5', 1);
			$customer->setData('Infodes');
			return $customer;
		}
		else
			throw new Exception();
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