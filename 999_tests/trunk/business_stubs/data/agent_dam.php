<?php
/**
 * Library with the utility classes for the Agent derived classes, which access the database.
 * @package AgentDAM
 * @author Roberto Oliveros
 */

/**
 * Class that provides access to the database.
 * @package AgentDAM
 * @author Roberto Oliveros
 */
class CustomerDAM{
	/**
	 * Returns Customer instance object if a match for the nit providad was found in database. NULL if not.
	 *
	 * @param string $nit
	 * @return Customer
	 */
	static public function getInstance($nit){
		if($nit == '1725045-5'){
			$customer = new Customer('1725045-5', PersistObject::CREATED);
			$customer->setData('Infodes');
			return $customer;
		}
		else
			return NULL;
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
 * @package AgentDAM
 * @author Roberto Oliveros
 *
 */
class SupplierDAM{
	static private $_mName = 'Jose Gil';
	
	/**
	 * Returns a Supplier if founds an id match in the database, otherwise returns NULL.
	 *
	 * @param integer $id
	 * @return Supplier
	 */
	static public function getInstance($id){
		if($id == 123){
			$supplier = new Supplier(123, PersistObject::CREATED);
			$supplier->setData('350682-7', self::$_mName, '24129999', '3a calle 7-32 z.1',
					'info@josegil.net', 'Roberto');
			return $supplier;
		}
		else
			return NULL;
	}
	
	/**
	 * Insert Supplier in the database.
	 *
	 * @param Supplier $supplier
	 * @return void
	 */
	static public function insert(Supplier $supplier){
		return 123;
	}
	
	/**
	 * Update Supplier's data in the database.
	 *
	 * @param Supplier $supplier
	 * @return void
	 */
	static public function update(Supplier $supplier){
		self::$_mName = $supplier->getName();
	}
	
	/**
	 * Deletes Supplier from database.
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


/**
 * Class that provides access to the branch's database tables.
 * @package AgentDAM
 * @author Roberto Oliveros
 */
class BranchDAM{
	static private $_mName = 'JG Jutiapa';
	
	/**
	 * Returns a Branch if it founds an id match in the database. Otherwise returns NULL.
	 *
	 * @param integer $id
	 * @return Branch
	 */
	static public function getInstance($id){
		if($id == 123){
			$branch = new Branch(123, PersistObject::CREATED);
			$branch->setData('350682-7', self::$_mName, '78123456', 'Mercado central.',
					'jutiapa@josegil.net', 'Idania');
			return $branch;
		}
		else
			return NULL;
	}
	
	/**
	 * Inserts Branch into the database.
	 *
	 * @param Branch $branch
	 * @return void
	 */
	static public function insert(Branch $branch){
		return 123;
	}
	
	/**
	 * Updates Branch's data in the database.
	 *
	 * @param Branch $branch
	 * @return void
	 */
	static public function update(Branch $branch){
		self::$_mName = $branch->getName();
	}
	
	/**
	 * Deletes Branch from the database. Returns true on success, otherwise it has dependencies and returns
	 * false.
	 *
	 * @param Branch $branch
	 * @return boolean
	 */
	static public function delete(Branch $branch){
		if($branch->getId() == 123)
			return true;
		else
			return false;
	}
}
?>