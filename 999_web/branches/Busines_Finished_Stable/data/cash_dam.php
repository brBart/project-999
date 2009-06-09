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
	static private $_mName = 'GyT Continental';
	
	/**
	 * Returns a Bank if it founds an id match in the database. Otherwise returns NULL.
	 *
	 * @param integer $id
	 * @return Bank
	 */
	static public function getInstance($id){
		if($id == 123){
			$bank = new Bank(123, PersistObject::CREATED);
			$bank->setData(self::$_mName);
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
		self::$_mName = $obj->getName();
	}
	
	/**
	 * Deletes a Bank from the database. Returns true on success, otherwise it has dependencies and
	 * returns false.
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
	static private $_mName = 'Diurno';
	
	/**
	 * Returns a Shift if it founds an id match in the database. Otherwise returns NULL.
	 *
	 * @param integer $id
	 * @return Shift
	 */
	static public function getInstance($id){
		switch($id){
			case 123:
				$shift = new Shift($id, PersistObject::CREATED);
				$shift->setData(self::$_mName, '8am - 6pm');
				return $shift;
				break;
				
			case 124:
				$shift = new Shift($id, PersistObject::CREATED);
				$shift->setData('Nocturno', '6pm - 11pm');
				return $shift;
				break;
				
			default:
				return NULL;
		}
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
		self::$_mName = $obj->getName();
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
	static private $_mIsOpen123 = true;
	static private $_mIsOpen124 = true;
	static private $_mIsOpen125 = true;
	static private $_mIsOpen126 = true;
	
	/**
	 * Returns the status of the cash register.
	 *
	 * @param CashRegister $obj
	 * @return boolean
	 */
	static public function isOpen(CashRegister $obj){
		switch($obj->getId()){
			case 123:
				return self::$_mIsOpen123;
				break;
				
			case 124:
				return self::$_mIsOpen124;
				break;
				
			case 125:
				return self::$_mIsOpen125;
				break;
				
			case 126:
				return self::$_mIsOpen126;
				break;
				
			default:
				return false;
		}
	}
	
	/**
	 * Close the cash register in the database.
	 *
	 * @param CashRegister $obj
	 */
	static public function close(CashRegister $obj){
		switch($obj->getId()){
			case 123:
				self::$_mIsOpen123 = false;
				break;
				
			case 124:
				self::$_mIsOpen124 = false;
				break;
				
			case 125:
				self::$_mIsOpen125 = false;
				break;
				
			case 126:
				self::$_mIsOpen126 = false;
				break;
				
			default:
		}
	}
	
	/**
	 * Returns an instance of a cash register.
	 *
	 * Returns NULL if not match was found in the database.
	 * @param integer $id
	 * @return CashRegister
	 */
	static public function getInstance($id){
		switch($id){
			case 123:
				$shift = Shift::getInstance(123);
				$cash_register = new CashRegister($shift, 123, Persist::CREATED);
				return $cash_register;
				break;
				
			case 124:
				$shift = Shift::getInstance(123);
				$cash_register = new CashRegister($shift, 124, Persist::CREATED);
				return $cash_register;
				break;
				
			case 125:
				$shift = Shift::getInstance(123);
				$cash_register = new CashRegister($shift, 125, Persist::CREATED);
				return $cash_register;
				break;
				
			case 126:
				$shift = Shift::getInstance(123);
				$cash_register = new CashRegister($shift, 126, Persist::CREATED);
				return $cash_register;
				break;
				
			default:
				return NULL;
		}
	}
}


/**
 * Defines functionality for accessing the type of payment card database tables.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class PaymentCardTypeDAM{
	static private $_mName = 'Credito';
	
	/**
	 * Returns a type of payment card if it founds an id match in the database. Otherwise returns NULL.
	 *
	 * @param integer $id
	 * @return PaymentCardType
	 */
	static public function getInstance($id){
		if($id == 123){
			$type = new PaymentCardType(123, PersistObject::CREATED);
			$type->setData(self::$_mName);
			return $type;
		}
		else
			return NULL;
	}
	
	/**
	 * Insert a type of payment card in the database.
	 *
	 * @param PaymentCardType $obj
	 */
	static public function insert(PaymentCardType $obj){
		return 123;
	}
	
	/**
	 * Updates a type of payment card data in the database.
	 *
	 * @param PaymentCardType $obj
	 */
	static public function update(PaymentCardType $obj){
		self::$_mName = $obj->getName();
	}
	
	/**
	 * Deletes a type of payment card from the database. Returns true on success, otherwise it has dependencies
	 * and returns false.
	 *
	 * @param PaymentCardType $obj
	 * @return boolean
	 */
	static public function delete(PaymentCardType $obj){
		if($obj->getId() == 123)
			return true;
		else
			return false;
	}
}


/**
 * Defines functionality for accessing the payment card brand database tables.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class PaymentCardBrandDAM{
	static private $_mName = 'Visa';
	
	/**
	 * Returns a payment card brand if it founds an id match in the database. Otherwise returns NULL.
	 *
	 * @param integer $id
	 * @return PaymentCardBrand
	 */
	static public function getInstance($id){
		if($id == 123){
			$brand = new PaymentCardBrand(123, PersistObject::CREATED);
			$brand->setData(self::$_mName);
			return $brand;
		}
		else
			return NULL;
	}
	
	/**
	 * Insert a payment card brand in the database.
	 *
	 * @param PaymentCardBrand $obj
	 */
	static public function insert(PaymentCardBrand $obj){
		return 123;
	}
	
	/**
	 * Updates a payment card brand data in the database.
	 *
	 * @param PaymentCardBrand $obj
	 */
	static public function update(PaymentCardBrand $obj){
		self::$_mName = $obj->getName();
	}
	
	/**
	 * Deletes a payment card brand from the database. Returns true on success, otherwise it has dependencies
	 * and returns false.
	 *
	 * @param PaymentCardBrand $obj
	 * @return boolean
	 */
	static public function delete(PaymentCardBrand $obj){
		if($obj->getId() == 123)
			return true;
		else
			return false;
	}
}


/**
 * Utility class for modifiying cash amount values on the receipt table in the database.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class CashDAM{
	static private $_mAmount123 = 95.55;
	static private $_mReserved123 = 10.00;
	static private $_mDeposited123 = 0.0;
	
	static private $_mAmount124 = 68.91;
	static private $_mReserved124 = 22.75;
	static private $_mDeposited124 = 24.00;
	
	static private $_mAmount125 = 128.61;
	static private $_mReserved125 = 12.75;
	static private $_mDeposited125 = 30.00;
	
	/**
	 * Returns the cash amount of the provided object from the database.
	 *
	 * @param Cash $obj
	 * @return float
	 */
	static public function getAmount(Cash $obj){
		switch($obj->getId()){
			case 123:
				return self::$_mAmount123;
				break;
				
			case 124:
				return self::$_mAmount124;
				break;
				
			case 125:
				return self::$_mAmount125;
				break;
			
			default:
				return 0;
		}
	}
	
	
	static public function getAvailable(Cash $obj){
		switch($obj->getId()){
			case 123:
				return self::$_mAmount123 - (self::$_mReserved123 + self::$_mDeposited123);
				break;
				
			case 124:
				return self::$_mAmount124 - (self::$_mReserved124 + self::$_mDeposited124);
				break;
				
			case 125:
				return self::$_mAmount125 - (self::$_mReserved125 + self::$_mDeposited125);
				break;
			
			default:
				return 0;
		}
	}
	
	/**
	 * Reserves the provided cash amount in the database.
	 *
	 * @param Cash $obj
	 * @param float $amount
	 */
	static public function reserve(Cash $obj, $amount){
		switch($obj->getId()){
			case 123:
				self::$_mReserved123 += $amount;
				return;
				
			case 124:
				self::$_mReserved124 += $amount;
				return;
				
			case 125:
				self::$_mReserved125 += $amount;
				return;
				
			default:
		}
	}
	
	/**
	 * Decreases the reserved cash amount in the database.
	 *
	 * @param Cash $obj
	 * @param float $amount
	 */
	static public function decreaseReserve(Cash $obj, $amount){
		switch($obj->getId()){
			case 123:
				self::$_mReserved123 -= $amount;
				return;
				
			case 124:
				self::$_mReserved124 -= $amount;
				return;
				
			case 125:
				self::$_mReserved125 -= $amount;
				return;
				
			default:
		}
	}
	
	/**
	 * Increases the deposited cash amount in the database.
	 *
	 * @param Cash $obj
	 * @param float $amount
	 */
	static public function deposit(Cash $obj, $amount){
		switch($obj->getId()){
			case 123:
				self::$_mDeposited123 += $amount;
				return;
				
			case 124:
				self::$_mDeposited124 += $amount;
				return;
				
			case 125:
				self::$_mDeposited125 += $amount;
				return;
				
			default:
		}
	}
	
	/**
	 * Decreases the deposited cash amount in the database.
	 *
	 * @param Cash $obj
	 * @param float $amount
	 */
	static public function decreaseDeposit(Cash $obj, $amount){
		switch($obj->getId()){
			case 123:
				self::$_mDeposited123 -= $amount;
				return;
				
			case 124:
				self::$_mDeposited124 -= $amount;
				return;
				
			case 125:
				self::$_mDeposited125 -= $amount;
				return;
				
			default:
		}
	}
	
	/**
	 * Returns an instance of a cash class.
	 *
	 * @param integer $id
	 * @return Cash
	 */
	static public function getInstance($id){
		return new Cash(0.00, $id, Persist::CREATED);
	}
}


/**
 * Utility class for accessing database tables regarding receipts.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class CashReceiptDAM{
	/**
	 * Returns an instance of a receipt.
	 *
	 * Returns NULL if there was no match for the provided invoice in the database.
	 * @param Invoice $obj
	 * @return CashReceipt
	 */
	static public function getInstance(Invoice $obj){
		switch($obj->getId()){
			case 123:
				$receipt = new CashReceipt($obj, 123, $obj->getStatus());
				$cash = new Cash(43.50, 123, Persist::CREATED);
				$receipt->setData($cash);
				return $receipt;
				break;
				
			default:
				return NULL;
		}
	}
	
	/**
	 * Inserts the receipt's data in the database.
	 *
	 * @param CashReceipt $obj
	 */
	static public function insert(CashReceipt $obj){
		// Code here...
	}
}


/**
 * Utility class for accessing deposit database tables.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class DepositDAM{
	/**
	 * Change the deposit's status to confirmed in the database.
	 *
	 * @param Deposit $obj
	 */
	static public function confirm(Deposit $obj){
		// Code here...
	}
	
	/**
	 * Inserts the deposit's data in the database.
	 *
	 * Returns the new created id from the database.
	 * @param Deposit $obj
	 * @return integer
	 */
	static public function insert(Deposit $obj){
		return 123;
	}
	
	/**
	 * Updates the deposit status to cancelled in the database.
	 *
	 * The user and date arguments are to register who and when does the cancel action took place.
	 * @param Deposit $deposit
	 * @param UserAccount $user
	 * @param string $date
	 */
	static public function cancel(Deposit $deposit, UserAccount $user, $date){
		// Code here...
	}
	
	/**
	 * Returns a deposit with the details corresponding to the requested page.
	 *
	 * The total_pages and total_items arguments are necessary to return their respective values. Returns NULL
	 * if there was no match for the provided id in the database.
	 * @param integer $id
	 * @param integer $total_pages
	 * @param integer $total_items
	 * @param integer $page
	 * @return Deposit
	 */
	static public function getInstance($id, &$total_pages, &$total_items, $page){
		switch($id){
			case 123:
				$deposit = new Deposit(CashRegister::getInstance(123), '12/03/2009',
						UserAccount::getInstance('roboli'), $id, Deposit::CREATED);
				$details[] = new DepositDetail(new Cash(1.0, 123, Persist::CREATED), 5.00);
				$deposit->setData('4215', BankAccount::getInstance('123'), 5.00, $details);
				$total_pages = 1;
				$total_items = 1;
				return $deposit;
				break;
				
			default:
				return NULL;
		}
	}
}


/**
 * Utility class for creating a sales report with database data.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class SalesReportDAM{
	/**
	 * Returns an instance of a cash register's sales report.
	 *
	 * @param CashRegister $obj
	 * @return SalesReport
	 */
	static public function getInstance(CashRegister $obj){
		switch($obj->getId()){
			case 123:
				$invoices = array(array('serial_number' => 'A021', 'number' => 123,
									'name' => 'Roberto', 'total' => 23.45),
								array('serial_number' => 'A021', 'number' => 124,
									'name' => 'Carlos', 'total' => 33.81),
								array('serial_number' => 'A021', 'number' => 125,
									'name' => 'Jose', 'total' => 135.15));
				$report = new SalesReport(0.0, 231.49, 23.14, $invoices);
				return $report;
				break;
				
			default:
		}
	}
}


/**
 * Class used for obtaining certain receipts' cash deposits.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class DepositDetailListDAM{
	/**
	 * Returns an array with all the deposits' id which contain the provided cash.
	 *
	 * @param Cash $obj
	 * @return array
	 */
	static public function getList(Cash $obj){
		switch($obj->getId()){
			case 123:
				return array(array('id' => 123), array('id' => 124), array('id' => 125));
				break;
				
			default:
		}
	}
}


/**
 * Class for accessing working day database tables.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class WorkingDayDAM{
	static private $_mIsOpenCurrent = true;
	static private $_mIsOpenPast = true;
	
	/**
	 * Returns true if the working day stills open.
	 *
	 * @param WorkingDay $obj
	 * @return boolean
	 */
	static public function isOpen(WorkingDay $obj){
		switch($obj->getDate()){
			case date('d/m/Y'):
				return self::$_mIsOpenCurrent;
				break;
				
			default:
				return self::$_mIsOpenPast;
		}
	}
	
	/**
	 * Close all the cash registers that belongs to the provided working day.
	 *
	 * @param WorkingDay $obj
	 */
	static public function closeCashRegisters(WorkingDay $obj){
		// Code here...
	}
	
	/**
	 * Close the provided working day in the database.
	 *
	 * @param WorkingDay $obj
	 */
	static public function close(WorkingDay $obj){
		switch($obj->getDate()){
			case date('d/m/Y'):
				self::$_mIsOpenCurrent = false;
				break;
				
			default:
				self::$_mIsOpenPast = false;
		}
	}
	
	/**
	 * Returns an instance of a cash register.
	 *
	 * Returns NULL if there was no match for the provided data in the database.
	 * @param WorkingDay $workingDay
	 * @param Shift $shift
	 * @return CashRegister
	 */
	static public function getCashRegister(WorkingDay $workingDay, Shift $shift){
		if($workingDay->getDate() == date('d/m/Y') && $shift->getId() == 123)
			return CashRegister::getInstance(123);
		else
			return NULL;
	}
	
	/**
	 * Inserts the provided data in the database and returns an instance of a cash register.
	 *
	 * @param Shift $shift
	 * @return CashRegister
	 */
	static public function insertCashRegister(WorkingDay $workingDay, Shift $shift){
		return new CashRegister($shift, 127, Persist::CREATED);
	}
	
	/**
	 * Returns an instance of a working day.
	 *
	 * Returns NULL if there is no match for the provided date in the database.
	 * @param string $date
	 * @return WorkingDay
	 */
	static public function getInstance($date){
		switch($date){
			case date('d/m/Y'):
				$working_day = new WorkingDay(date('d/m/Y'), Persist::CREATED);
				return $working_day;
				break;
				
			default:
				$working_day = new WorkingDay($date, Persist::CREATED);
				return $working_day;
		}
	}
	
	/**
	 * Inserts the provided data into the database.
	 *
	 * It returns an instance of the new created working day.
	 * @param string $date
	 * @return WorkingDay
	 */
	static public function insert($date){
		return new WorkingDay($date, Persist::CREATED);
	}
}


/**
 * Utility class for accessing database tables with information regarding the daily sales report.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class GeneralSalesReportDAM{
	
	static public function getInstance(WorkingDay $obj){
		$registers = array(array('register_id' => 123, 'shift' => 'Diurno', 'total' => 235.38),
							array('register_id' => 124, 'shift' => 'Nocturno', 'total' => 212.43));
		return new GeneralSalesReport(525.32, $registers);
	}
}


/**
 * Utility class for accessing deposit database tables.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class DepositListDAM{
	/**
	 * Returns an array with all the deposits belonging to the provided cash register.
	 *
	 * @param CashRegister $obj
	 * @return array
	 */
	static public function getList(CashRegister $obj){
		if($obj->getId() == 123)
			return array(123, 124, 125);
	}
}


/**
 * Utility class for accessing invoice database tables.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class InvoiceListDAM{
	/**
	 * Returns an array with all the invoices belonging to the provided cash register.
	 *
	 * @param CashRegister $obj
	 * @return array
	 */
	static public function getList(CashRegister $obj){
		if($obj->getId() == 123)
			return array(123, 124, 125);
	}
}


/**
 * Utility class for accessing receipt database tables.
 * @package CashDAM
 * @author Roberto Oliveros
 */
class AvailableCashReceiptListDAM{
	/**
	 * Returns an array with all the receipts with cash available that belongs to the provided cash register.
	 *
	 * The array contains the fields receipt_id, received_cash and available_cash.
	 * @param CashRegister $obj
	 * @return array
	 */
	static public function getList(CashRegister $obj){
		if($obj->getId() == 123)
			return array(array('receipt_id' => 123, 'received_cash' => 42.39, 'available_cash' => 20.00),
						array('receipt_id' => 124, 'received_cash' => 480.32, 'available_cash' => 100.00));
	}
}
?>