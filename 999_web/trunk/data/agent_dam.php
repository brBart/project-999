<?php
/**
 * Library with the utility classes for the Agent derived classes, which access the database.
 * @package AgentDAM
 * @author Roberto Oliveros
 */

/**
 * For accessing the database.
 */
require_once('data/database_handler.php');

/**
 * Class that provides access to the database.
 * @package AgentDAM
 * @author Roberto Oliveros
 */
class CustomerDAM{
	/**
	 * Returns customer instance object if a match for the nit providad was found in database. NULL if not.
	 *
	 * @param string $nit
	 * @return Customer
	 */
	static public function getInstance($nit){
		$sql = 'CALL customer_get(:nit)';
		$params = array(':nit' => $nit);
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			$customer = new Customer($result['nit'], 1);
			$customer->setData($result['name']);
			return $customer;
		}
		else
			return NULL;
	}
	
	/**
	 * Returns true or false depending if a customer already has the provided nit in the database.
	 *
	 * @param string $nit
	 * @return boolean
	 */
	static public function exist($nit){
		$sql = 'CALL customer_exists(:nit)';
		$params = array(':nit' => $nit);
		$result = DatabaseHandler::getOne($sql, $params);
		
		if($result > 0)
			return true;
		else
			return false;
	}
	
	/**
	 * Insert customer's data to the database.
	 *
	 * @param Customer $customer
	 */
	static public function insert(Customer $customer){
		$sql = 'CALL customer_insert(:nit, :name)';
		$params = array(':nit' => $customer->getNit(), ':name' => $customer->getName());
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Update customer's data in the database.
	 *
	 * @param Customer $customer
	 */
	static public function update(Customer $customer){
		$sql = 'CALL customer_update(:nit, :name)';
		$params = array(':nit' => $customer->getNit(), ':name' => $customer->getName());
		DatabaseHandler::execute($sql, $params);
	}
}


/**
 * Class that provides access to the database.
 * @package AgentDAM
 * @author Roberto Oliveros
 *
 */
class SupplierDAM{
	/**
	 * Returns a supplier if founds an id match in the database, otherwise returns NULL.
	 *
	 * @param integer $id
	 * @return Supplier
	 */
	static public function getInstance($id){
		$sql = 'CALL supplier_get(:supplier_id)';
		$params = array(':supplier_id' => $id);
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			$supplier = new Supplier((int)$result['supplier_id'], 1);
			$supplier->setData($result['nit'], $result['name'], $result['telephone'], $result['address'],
					$result['email'], $result['contact']);
			return $supplier;
		}
		else
			return NULL;
	}
	
	/**
	 * Insert supplier in the database.
	 *
	 * Returns the last created id from the database.
	 * @param Supplier $supplier
	 * @return integer
	 */
	static public function insert(Supplier $supplier){
		$sql = 'CALL supplier_insert(:name, :nit, :telephone, :address, :email, :contact)';
		$params = array(':name' => $supplier->getName(), ':nit' => $supplier->getNit(),
				':telephone' => $supplier->getTelephone(), ':address' => $supplier->getAddress(),
				':email' => $supplier->getEmail(), ':contact' => $supplier->getContact());
		DatabaseHandler::execute($sql, $params);
		
		$sql = 'CALL get_last_insert_id()';
		return DatabaseHandler::getOne($sql);
	}
	
	/**
	 * Update supplier's data in the database.
	 *
	 * @param Supplier $supplier
	 */
	static public function update(Supplier $supplier){
		$sql = 'CALL supplier_update(:supplier_id, :name, :nit, :telephone, :address, :email, :contact)';
		$params = array(':supplier_id' => $supplier->getId(), ':name' => $supplier->getName(),
				':nit' => $supplier->getNit(), ':telephone' => $supplier->getTelephone(),
				':address' => $supplier->getAddress(), ':email' => $supplier->getEmail(),
				':contact' => $supplier->getContact());
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Deletes supplier from database.
	 *
	 * @param Supplier $supplier
	 * @return boolean
	 */
	static public function delete(Supplier $supplier){
		$sql = 'CALL supplier_dependencies(:supplier_id)';
		$params = array(':supplier_id' => $supplier->getId());
		$result = DatabaseHandler::getOne($sql, $params);
		
		// If there are dependencies in the tables product_supplier, receipt and purchase_return.
		if($result) return false;
		
		$sql = 'CALL supplier_delete(:supplier_id)';
		DatabaseHandler::execute($sql, $params);
		return true;
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