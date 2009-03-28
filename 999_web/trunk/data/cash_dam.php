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


/**
 * Defines functionality for accessing the database.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class BankAccountDAM{
	/**
	 * Verifies if the provided number already exists in the database.
	 *
	 * @param string $number
	 * @return boolean
	 */
	static public function exists($number){
		if($number == '123')
			return true;
		else
			return false;
	}
	
 	/** Returns a BankAccount if it founds a match in the database. Otherwise returns NULL.
	 *
	 * @param string $number
	 * @return BankAccount
	 */
	static public function getInstance($number){
		if($number == '123'){
			$bank_account = new BankAccount('123', PersistObject::CREATED);
			$bank = Bank::getInstance(123);
			$bank_account->setData('Roberto Oliveros', $bank);
			return $bank_account;
		}
		else
			return NULL;
	}
	
	/**
	 * Insert a BankAccount in the database.
	 *
	 * @param BankAccount $obj
	 * @return void
	 */
	static public function insert(BankAccount $obj){
		// Code here...
	}
	
	/**
	 * Updates a BankAccount data in the database.
	 *
	 * @param BankAccount $obj
	 * @return void
	 */
	static public function update(BankAccount $obj){
		// Code here...
	}
	
	/**
	 * Deletes a BankAccount from the datase. Returns true on success, otherwise it has dependencies and 
	 * returns false.
	 *
	 * @param BankAccount $obj
	 * @return boolean
	 */
	static public function delete(BankAccount $obj){
		if($obj->getNumber() == '123')
			return true;
		else
			return false;
	}
}


/**
 * Defines functionality for accessing the shift's database tables.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class ShiftDAM{
	/**
	 * Returns a Shift if it founds an id match in the database. Otherwise returns NULL.
	 *
	 * @param integer $id
	 * @return Shift
	 */
	static public function getInstance($id){
		if($id == 123){
			$shift = new Shift(123, PersistObject::CREATED);
			$shift->setData('Diurno', '8am - 6pm');
			return $shift;
		}
		else
			return NULL;
	}
	
	/**
	 * Insert a Shift in the database.
	 *
	 * @param Shift $obj
	 * @return void
	 */
	static public function insert(Shift $obj){
		return 123;
	}
	
	/**
	 * Updates a Shift data in the database.
	 *
	 * @param Shift $obj
	 * @return void
	 */
	static public function update(Shift $obj){
		// Code here...
	}
	
	/**
	 * Deletes a Shift from the datase. Returns true on success, otherwise it has dependencies and returns false.
	 *
	 * @param Bank $obj
	 * @return boolean
	 */
	static public function delete(Shift $obj){
		if($obj->getId() == 123)
			return true;
		else
			return false;
	}
}


/**
 * Defines functionality for accessing the cash register tables.
 *	@package CashDAM
 *  @author Roberto Oliveros
 */
class CashRegisterDAM{
	/**
	 * Returns the status of the cash register.
	 *
	 * @param CashRegister $obj
	 * @return boolean
	 */
	static public function isOpen(CashRegister $obj){
		return false;
	}
	
	/**
	 * Close the cash register in the database.
	 *
	 * @param CashRegister $obj
	 */
	static public function close(CashRegister $obj){
		// Code here...
	}
	
	/**
	 * Returns an instance of a cash register.
	 *
	 * Returns NULL if not match was found in the database.
	 * @param integer $id
	 * @return CashRegister
	 */
	static public function getInstance($id){
		if($id == 123){
			$shift = Shift::getInstance(123);
			$cash_register = new CashRegister($shift, 123);
			return $cash_register;
		}
		else
			return NULL;
	}
}
?>