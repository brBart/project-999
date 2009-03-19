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
	static public function insert(Customer $customer){
		// Code here...
	}
	
	/**
	 * Update Customer's data in the database.
	 *
	 * @param Customer $customer
	 * @return void
	 */
	static public function update(Customer $customer){
		// Code here...
	}
}


/**
 * Class that provides access to the database.
 * @package agentDAM
 * @author Roberto Oliveros
 *
 */
class SupplierDAM{
	/**
	 * Returns a Supplier if founds an id match in the database, otherwise returns null.
	 *
	 * @param integer $id
	 * @return Supplier
	 */
	static public function getInstance($id){
		if($id == 123){
			$supplier = new Supplier(123, FROM_DATABASE);
			$supplier->setData('350682-7', 'Jose Gil', '24129999', '3a calle 7-32 z.1',
					'info@josegil.net', 'Roberto');
			return $supplier;
		}
		else
			return null;
	}
	
	/**
	 * Insert Supplier in the database.
	 *
	 * @param Supplier $supplier
	 * @return void
	 */
	static public function insert(Supplier $supplier){
		// Code here...
	}
	
	/**
	 * Update Supplier's data in the database.
	 *
	 * @param Supplier $supplier
	 * @return void
	 */
	static public function update(Supplier $supplier){
		// Code here...
	}
	
	/**
	 * Removes Supplier from database.
	 *
	 * @param Supplier $supplier
	 * @return boolean
	 */
	static public function delete(Supplier $supplier){
		if($supplier->getId() == 123)
			return true;
		else
			return false;
	}
}
?>