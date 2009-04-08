<?php
/**
 * Library with utility classes for the cash flow.
 * @package Cash
 * @author Roberto Oliveros
 */

/**
 * Includes the Persist package.
 */
require_once('business/persist.php');
/**
 * Includes the CashDAM package.
 */
require_once('data/cash_dam.php');

/**
 * Class representing a bank.
 * @package Cash
 * @author Roberto Oliveros
 */
class Bank extends Identifier{
	/**
	 * Constructs the bank with the provided id and status.
	 * 
	 * Parameters must be set only if the method is called from the database layer.
	 * @param integer $id
	 * @param integer $status
	 */
	public function __construct($id = NULL, $status = Persist::IN_PROGRESS){
		parent::__construct($id, $status);
	}
	
	/**
	 * Returns instance of a bank.
	 * 
	 * Returns NULL if there was no match in the database.
	 * @param integer $id
	 * @return Bank
	 */
	static public function getInstance($id){
		self::validateId($id);
		return BankDAM::getInstance($id);
	}
	
	/**
	 * Deletes the bank from database.
	 * 
	 * Returns true confirming the deletion, otherwise false due dependencies.
	 * @param Bank $obj
	 * @return boolean
	 */
	static public function delete(Bank $obj){
		self::validateObjectFromDatabase($obj);		
		return BankDAM::delete($obj);
	}
	
	/**
	 * Inserts the bank's data in the database.
	 * 
	 * Returns the new created id from the database.
	 * @return integer
	 */
	protected function insert(){
		return BankDAM::insert($this);
	}
	
	/**
	 * Updates the bank's data in the database.
	 * @return void
	 */
	protected function update(){
		BankDAM::update($this);
	}
}


/**
 * Defines functionality for a Deposit.
 * @package Cash
 * @author Roberto Oliveros
 */
class Deposit{
	/**
	 * Internal identifier.
	 *
	 * @var integer
	 */
	private $_mId;
	
	/**
	 * Deposit slip number.
	 *
	 * @var string
	 */
	private $_mNumber;
	
	/**
	 * Bank where the Deposit is being made.
	 *
	 * @var BankAccount
	 */
	private $_mBankAccount;
	
	/**
	 * Cash Register from where the Deposit's money went.
	 *
	 * @var CashRegister
	 */
	private $_mCashRegister;
	
	/**
	 * Holds who made the deposit.
	 *
	 * @var UserAccount
	 */
	private $_mUser;
	
	/**
	 * Deposit object internal status, e.g. Persist::IN_PROGRESS or Persist::CREATED.
	 *
	 * @var integer
	 */
	private $_mStatus;
	
	/**
	 * Array with DepositDetail items.
	 *
	 * @var array
	 */
	private $_mDetails;
	
	/**
	 * Deposit total.
	 *
	 * @var float
	 */
	private $_mTotal;
}


/**
 * Class that represent a bank account.
 * @package Cash
 * @author Roberto Oliveros
 */
class BankAccount extends PersistObject{
	/**
	 * Holds the account's number.
	 *
	 * @var string
	 */
	private $_mNumber;
	
	/**
	 * Holds the account's holder.
	 *
	 * @var string
	 */
	private $_mName;
	
	/**
	 * Holds the account's bank.
	 *
	 * @var Bank
	 */
	private $_mBank;
	
	/**
	 * Construct the bank account with the provided number and status.
	 * 
	 * Parameters must be set only if called from the database layer.
	 * @param string $number
	 * @param integer $status
	 */
	public function __construct($number = NULL, $status = Persist::IN_PROGRESS){
		parent::__construct($status);
		
		if(!is_null($number))
			try{
				$this->validateNumber($number);
			} catch(Exception $e){
				$et = new Exception('Internal error, calling BankAccount constructor method with bad data! ' .
						$e->getMessage());
				throw $et;
			}
			
		$this->_mNumber = $number;
	}
	
	/**
	 * Returns the account's number.
	 *
	 * @return string
	 */
	public function getNumber(){
		return $this->_mNumber;
	}
	
	/**
	 * Returns the account's holder.
	 *
	 * @return string
	 */
	public function getName(){
		return $this->_mName;
	}
	
	/**
	 * Returns the account's bank.
	 *
	 * @return Bank
	 */
	public function getBank(){
		return $this->_mBank;
	}
	
	/**
	 * Sets the account's number.
	 *
	 * @param string $number
	 * @return void
	 */
	public function setNumber($number){
		if($this->_mStatus == self::CREATED)
			throw new Exception('No se puede editar n&uacute;mero de cuenta.');
		
		$this->validateNumber($number);
		
		$this->verifyNumber($number);
		
		$this->_mNumber = $number;
	}
	
	/**
	 * Sets the account's bank;
	 *
	 * @param Bank $obj
	 * @return void
	 */
	public function setBank(Bank $obj){
		self::validateObjectFromDatabase($obj);
		$this->_mBank = $obj;
	}
	
	/**
	 * Sets the account's holder.
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName($name){
		Identifier::validateName($name);
		$this->_mName = $name;
	}
	
	/**
	 * Set the object's data provided by the database.
	 * 
	 * Must be call only from the database layer corresponding class. The object's status must be set to
	 * Persist::CREATED in the constructor method too.
	 * @param string $name
	 * @param Bank $bank
	 */
	public function setData($name, Bank $bank){
		try{
			Identifier::validateName($name);
			self::validateObjectFromDatabase($bank);
		} catch(Exception $e){
			$et = new Exception('Internal error, calling BankAccount\'s setData method with bad data! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mName = $name;
		$this->_mBank = $bank;
	}
	
	/**
	 * Saves bank account's data to the database.
	 * 
	 * If the object's status set to Persist::IN_PROGRESS the method insert()
	 * is called, if it's set to Persist::CREATED the method update() is called.
	 * @return void
	 */
	public function save(){
		$this->validateMainProperties();
		
		if($this->_mStatus == self::IN_PROGRESS){
			$this->verifyNumber($this->_mNumber);
			$this->insert();
			$this->_mStatus = self::CREATED;
		}
		else
			$this->update();
	}
	
	/**
	 * Returns an instance of bank account.
	 * 
	 * Returns NULL if there was no match in the database.
	 * @param string $number
	 * @return BankAccount
	 */
	static public function getInstance($number){
		self::validateNumber($number);
		return BankAccountDAM::getInstance($number);
	}
	
	/**
	 * Deletes the banck account from the database.
	 *
	 * Returns true confirming the deletion, otherwise false due dependencies.
	 * @param BankAccount $obj
	 * @return boolean
	 */
	static public function delete(BankAccount $obj){
		self::validateObjectFromDatabase($obj);			
		return BankAccountDAM::delete($obj);
	}
	
	/**
	 * Inserts the bank account's data in the database.
	 *
	 * @return void
	 */
	protected function insert(){
		BankAccountDAM::insert($this);
	}
	
	/**
	 * Updates the bank account's data in the database.
	 * @return void
	 */
	protected function update(){
		BankAccountDAM::update($this);
	}
	
	/**
	 * Validates bank account's main properties.
	 * 
	 * Verifies that the number and name are not empty. The bank's status must not be PersisObject::IN_PROGRESS.
	 * Otherwise it throws an exception.
	 * @return void
	 */
	protected function validateMainProperties(){
		$this->validateNumber($this->_mNumber);
		Identifier::validateName($this->_mName);
		
		if(is_null($this->_mBank))
			throw new Exception('Banco inv&aacute;lido.');
		else
			self::validateObjectFromDatabase($this->_mBank);
	}
	
	/**
	 * Validates the bank account's number.
	 *
	 * Must not be number. Otherwise it throws an exception.
	 * @param string $number
	 * @return void
	 */
	private function validateNumber($number){
		if(empty($number))
			throw new Exception('N&uacute;mero de cuenta inv&aacute;lido.');
	}
	
	/**
	 * Verifies if an account's number already exists in the database.
	 * 
	 * Throws an exception if it does.
	 * @param string $number
	 */
	private function verifyNumber($number){
		if(BankAccountDAM::exists($number))
			throw new Exception('Cuenta Bancaria ya existe.');
	}
}


/**
 * Represents a working shift in the cash register.
 * @package Cash
 * @author Roberto Oliveros
 */
class Shift extends Identifier{
	/**
	 * Holds the timetable of the working shift.
	 *
	 * @var string
	 */
	private $_mTimeTable;
	
	/**
	 * Construct the shift with the provided id and status.
	 * 
	 * Parameters must be set only if the method is called from the database layer.
	 * @param integer $id
	 * @param integer $status
	 */
	public function __construct($id = NULL, $status = Persist::IN_PROGRESS){
		parent::__construct($id, $status);
	}
	
	/**
	 * Returns shift's timetable.
	 *
	 * @return string
	 */
	public function getTimeTable(){
		return $this->_mTimeTable;
	}
	
	/**
	 * Sets the shift's timetable.
	 *
	 * @param string $timeTable
	 */
	public function setTimeTable($timeTable){
		$this->validateTimeTable($timeTable);
		$this->_mTimeTable = $timeTable;
	}
	
	/**
	 * Sets the shift's properties with data from the database.
	 * 
	 * Must be called only from the database layer. The object's status must be set to
	 * Persist::CREATED in the constructor method too.
	 * @param string $name
	 * @param string $timeTable
	 */
	public function setData($name, $timeTable){
		parent::setData($name);
		
		try{
			$this->validateTimeTable($timeTable);
		} catch(Exception $e){
			$et = new Exception('Internal error, calling Shift\'s setData method with bad data! '.
					$e->getMessage());
			throw $et;
		}
		
		$this->_mTimeTable = $timeTable;
	}
	
	/**
	 * Returns an instance of a shift.
	 * 
	 * Returns NULL if there was no match in the database.
	 * @param integer $id
	 * @return Shift
	 */
	static public function getInstance($id){
		self::validateId($id);
		return ShiftDAM::getInstance($id);
	}
	
	/**
	 * Deletes the shift from the database.
	 * 
	 * Returns true on success, otherwise false due dependencies.
	 * @param Shift $obj
	 * @return boolean
	 */
	static public function delete(Shift $obj){
		self::validateObjectFromDatabase($obj);
		return ShiftDAM::delete($obj);
	}
	
	/**
	 * Inserts the shift's data in the database.
	 * 
	 * Returns the new created id from the database.
	 * @return integer
	 */
	protected function insert(){
		return ShiftDAM::insert($this);
	}
	
	/**
	 * Updates shift's data in the database.
	 *
	 * @return void
	 */
	protected function update(){
		ShiftDAM::update($this);
	}
	
	/**
	 * Validates the object's main properties.
	 * 
	 * Verifies that the name and timetable are not empty. Otherwise it throws an exception.
	 * @return void
	 */
	protected function validateMainProperties(){
		parent::validateMainProperties();
		$this->validateTimeTable($this->_mTimeTable);
	}
	
	/**
	 * Validates if the provided timetable is correct.
	 *
	 * Must not be empty. Otherwise it throws an exception.
	 * @param string $timeTable
	 * @return void
	 */
	private function validateTimeTable($timeTable){
		if(empty($timeTable))
			throw new Exception('Horario inv&aacutelido.');
	}
}

/**
 * Represent a cash register used to create sales invoices.
 * @package Cash
 * @author Roberto Oliveros
 */
class CashRegister{
	/**
	 * Holds the object's id.
	 *
	 * @var integer
	 */
	protected $_mId;
	
	/**
	 * Holds the cash register's shift.
	 *
	 * @var Shift
	 */
	private $_mShift;
	
	/**
	 * Constructs the cash register with the provided shift and id.
	 *
	 * The id parameters must be set only if the method is called from the database layer.
	 * @param Shift $shift
	 * @param integer $id
	 */
	public function __construct(Shift $shift, $id = NULL){
		PersistObject::validateObjectFromDatabase($shift);
		if(!is_null($id))
			try{
				Identifier::validateId($id);
			} catch(Exception $e){
				$et = new Exception('Internal error, calling CashRegister constructor method with bad data! ' .
						$e->getMessage());
				throw $et;
			}
		
		$this->_mShift = $shift;
		$this->_mId = $id;
	}
	
	/**
	 * Returns the object's id.
	 *
	 * @return integer
	 */
	public function getId(){
		return $this->_mId;
	}
	
	/**
	 * Returns the status of the cash register.
	 *
	 * Returns true if it's open, otherwise false if it's closed.
	 * @return boolean
	 */
	public function isOpen(){
		return CashRegisterDAM::isOpen($this);
	}
	
	/**
	 * Returns the cash register's shift.
	 *
	 * @return Shift
	 */
	public function getShift(){
		return $this->_mShift;
	}
	
	/**
	 * Close the cash register.
	 *
	 * Once closed no more invoices can be created using this cash register.
	 * @return void
	 */
	public function close(){
		CashRegisterDAM::close($this);
	}
	
	/**
	 * Returns an instance of a cash register.
	 * 
	 * Returns NULL if there was no match in the database.
	 * @param integer $id
	 * @return CashRegister
	 */
	static public function getInstance($id){
		Identifier::validateId($id);
		return CashRegisterDAM::getInstance($id);
	}
}
?>