<?php
/**
 * Library with the utility database classes for the Cash package.
 * @package CashDAM
 * @author Roberto Oliveros
 */

/**
 * Defines functionality for accessing the bank's database tables.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class BankDAM{
	/**
	 * Returns a Bank if it founds an id match in the database. Otherwise returns NULL.
	 *
	 * @param integer $id
	 * @return Bank
	 */
	static public function getInstance($id){
		if($id == 123){
			$bank = new Bank(123, PersistObject::CREATED);
			$bank->setData('GyT Continental');
			return $bank;
		}
		else
			return NULL;
	}
	
	/**
	 * Insert a Bank in the database.
	 *
	 * @param Bank $obj
	 * @return void
	 */
	static public function insert(Bank $obj){
		return 123;
	}
	
	/**
	 * Updates a Bank data in the database.
	 *
	 * @param Bank $obj
	 * @return void
	 */
	static public function update(Bank $obj){
		// Code here...
	}
	
	/**
	 * Deletes a Bank from the datase. Returns true on success, otherwise it has dependencies and returns false.
	 *
	 * @param Bank $obj
	 * @return boolean
	 */
	static public function delete(Bank $obj){
		if($obj->getId() == 123)
			return true;
		else
			return false;
	}
}
?>