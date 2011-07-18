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
			$customer = new Customer($nit, Persist::CREATED);
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
		return (boolean)DatabaseHandler::getOne($sql, $params);
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
			$supplier = new Supplier($id, Persist::CREATED);
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
		return (int)DatabaseHandler::getOne($sql);
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
	 * Returns true on success, otherwise it has dependencies and returns false.
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
	/**
	 * Returns a branch if it founds an id match in the database. Otherwise returns NULL.
	 *
	 * @param integer $id
	 * @return Branch
	 */
	static public function getInstance($id){
		$sql = 'CALL branch_get(:branch_id)';
		$params = array(':branch_id' => $id);
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			$branch = new Branch($id, Persist::CREATED);
			$branch->setData($result['nit'], $result['name'], $result['telephone'], $result['address'],
					$result['email'], $result['contact']);
			return $branch;
		}
		else
			return NULL;
	}
	
	/**
	 * Inserts branch into the database.
	 *
	 * Returns the last created id from the database.
	 * @param Branch $branch
	 * @return integer
	 */
	static public function insert(Branch $branch){
		$sql = 'CALL branch_insert(:name, :nit, :telephone, :address, :email, :contact)';
		$params = array(':name' => $branch->getName(), ':nit' => $branch->getNit(),
				':telephone' => $branch->getTelephone(), ':address' => $branch->getAddress(),
				':email' => $branch->getEmail(), ':contact' => $branch->getContact());
		DatabaseHandler::execute($sql, $params);
		
		$sql = 'CALL get_last_insert_id()';
		return (int)DatabaseHandler::getOne($sql);
	}
	
	/**
	 * Updates branch's data in the database.
	 *
	 * @param Branch $branch
	 */
	static public function update(Branch $branch){
		$sql = 'CALL branch_update(:branch_id, :name, :nit, :telephone, :address, :email, :contact)';
		$params = array(':branch_id' => $branch->getId(), ':name' => $branch->getName(),
				':nit' => $branch->getNit(), ':telephone' => $branch->getTelephone(),
				':address' => $branch->getAddress(), ':email' => $branch->getEmail(),
				':contact' => $branch->getContact());
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Deletes branch from the database.
	 *
	 * Returns true on success, otherwise it has dependencies and returns false.
	 * @param Branch $branch
	 * @return boolean
	 */
	static public function delete(Branch $branch){
		$sql = 'CALL branch_dependencies(:branch_id)';
		$params = array(':branch_id' => $branch->getId());
		$result = DatabaseHandler::getOne($sql, $params);
		
		// If there are dependencies in the shipment table.
		if($result) return false;
		
		$sql = 'CALL branch_delete(:branch_id)';
		DatabaseHandler::execute($sql, $params);
		return true;
	}
}
?>