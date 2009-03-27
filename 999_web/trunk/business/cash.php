<?php
/**
 * Library with utility classes for the cash flow.
 * @package Cash
 * @author Roberto Oliveros
 */

require_once('business/persist.php');
require_once('data/cash_dam.php');

/**
 * Class representing a bank.
 * @package Cash
 * @author Roberto Oliveros
 */
class Bank extends PersistObject{
	/**
	 * Holds the bank's id.
	 *
	 * @var integer
	 */
	protected $_mId;
	
	/**
	 * Name the bank's name.
	 * @var string
	 */
	private $_mName;
	
	/**
	 * Constructs the bank with the provided id and status.
	 * 
	 * Parameters must be set only if the method is called from the database layer.
	 * @param integer $id
	 * @param integer $status
	 */
	public function __construct($id = NULL, $status = PersistObject::IN_PROGRESS){
		parent::__construct($status);
		
		if(!is_null($id))
			try{
				$this->validateId($id);
			} catch(Exception $e){
				$et = new Exception('Internal error, calling Bank constructor with bad data! ' .
						$e->getMessage());
				throw $et;
			}
			
		$this->_mId = $id;
	}
	
	/**
	 * Returns the name of the bank.
	 *
	 * @return string
	 */
	public function getName(){
		return $this->_mName;
	}
	
	/**
	 * Returns the bank's id.
	 *
	 * @return integer
	 */
	public function getId(){
		return $this->_mId;
	}
	
	/**
	 * Sets the bank's name.
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName($name){
		$this->validateName($name);
		$this->_mName = $name;
	}
	
	/**
	 * Set the bank's data provided by the database.
	 * 
	 * Must be call only from the database layer corresponding class. The object's status must be set to
	 * PersistObject::CREATED in the constructor method too.
	 * @param string $name
	 * @return void
	 */
	public function setData($name){
		try{
			$this->validateName($name);
		} catch(Exception $e){
			$et = new Exception('Internal error, calling Bank setData method with bad data! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mName = $name;
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
		self::validateObjectForDelete($obj);		
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
	
	/**
	 * Validates the bank's main properties.
	 * 
	 * Verifies that the bank's name is not empty. Otherwise it throws an exception.
	 * @return void
	 */
	protected function validateMainProperties(){
		$this->validateName($this->_mName);
	}
	
	/**
	 * Validates the bank's name.
	 *
	 * Must not be empty. Otherwise it throws an exception.
	 * @param string $name
	 * @return void
	 */
	private function validateName($name){
		if(empty($name))
			throw new Exception('Nombre inv&aacute;lido.');
	}
	
	/**
	 * Validates the bank's id.
	 *
	 * Verifies that the id is greater than cero. Otherwise it throws an exception.
	 * @param integer $id
	 * @return void
	 */
	private function validateId($id){
		if(!is_int($id) || $id <= 0)
			throw new Exception('Id inv&aacute;lido.');
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
	 * Deposit object internal status, e.g. PersistObject::IN_PROGRESS or PersistObject::CREATED.
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
 * Class that representd a bank account.
 * @package Cash
 * @author Roberto Oliveros
 */
class BankAccount extends PersistObject{
	/**
	 * The bank account number.
	 *
	 * @var string
	 */
	private $_mNumber;
	
	/**
	 * The account's holder.
	 *
	 * @var string
	 */
	private $_mName;
	
	/**
	 * Bank of the account.
	 *
	 * @var Bank
	 */
	private $_mBank;
	
	/**
	 * BankAccount's constructor method. Parameters must be set only if called from the database layer.
	 *
	 * @param string $number
	 * @param integer $status
	 */
	public function __construct($number = NULL, $status = PersistObject::IN_PROGRESS){
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
	 * Returns the BankAccount's number.
	 *
	 * @return string
	 */
	public function getNumber(){
		return $this->_mNumber;
	}
	
	/**
	 * Returns the BankAccount's holder.
	 *
	 * @return string
	 */
	public function getName(){
		return $this->_mName;
	}
	
	/**
	 * Returns the BankAccount's Bank.
	 *
	 * @return Bank
	 */
	public function getBank(){
		return $this->_mBank;
	}
	
	/**
	 * Sets the BankAccount's number.
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
	 * Sets then BankAccount's bank;
	 *
	 * @param Bank $obj
	 * @return void
	 */
	public function setBank(Bank $obj){
		$this->validateBank($obj);
		$this->_mBank = $obj;
	}
	
	/**
	 * Sets the BankAccount's holder.
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName($name){
		$this->validateName($name);
		$this->_mName = $name;
	}
	
	/**
	 * Sets the properties of the BankAccount. Must be called only from the data layer.
	 *
	 * @param string $name
	 * @param Bank $bank
	 */
	public function setData($name, Bank $bank){
		try{
			$this->validateName($name);
			$this->validateBank($bank);
		} catch(Exception $e){
			$et = new Exception('Internal error, calling BankAccount\'s setData method with bad data! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mName = $name;
		$this->_mBank = $bank;
	}
	
	/**
	 * Saves BankAccount's data in the database.
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
	 * Returns an instance of BankAccount if found in the database. Otherwise returns NULL.
	 *
	 * @param string $number
	 * @return BankAccount
	 */
	static public function getInstance($number){
		self::validateNumber($number);
		return BankAccountDAM::getInstance($number);
	}
	
	/**
	 * Deletes BanckAccount from the database.
	 *
	 * @param BankAccount $obj
	 * @return boolean
	 */
	static public function delete(BankAccount $obj){
		self::validateObjectForDelete($obj);			
		return BankAccountDAM::delete($obj);
	}
	
	/**
	 * Inserts BankAccount's data in the database.
	 *
	 * @return void
	 */
	protected function insert(){
		BankAccountDAM::insert($this);
	}
	
	/**
	 * Updates BankAccount's data in the database.
	 * @return void
	 */
	protected function update(){
		BankAccountDAM::update($this);
	}
	
	/**
	 * Validates BankAccount::_mNumber, BankAccount::_mName and BankAccount::_mBank are set correctly. Otherwise
	 * it throws an exception.
	 * @return void
	 */
	protected function validateMainProperties(){
		$this->validateNumber($this->_mNumber);
		$this->validateName($this->_mName);
		
		if(is_null($this->_mBank))
			throw new Exception('Banco inv&aacute;lido.');
		else
			$this->validateBank($this->_mBank);
	}
	
	/**
	 * Validates the BankAccount's number.
	 *
	 * @param string $number
	 * @return void
	 */
	private function validateNumber($number){
		if(empty($number))
			throw new Exception('N&uacute;mero de cuenta inv&aacute;lido.');
	}
	
	/**
	 * Validates that the Bank is in the database.
	 *
	 * @param Bank $obj
	 * @return void
	 */
	private function validateBank(Bank $obj){
		if($obj->getStatus() != self::CREATED)
			throw new Exception('PersistObject::IN_PROGRESS Bank provided.');
	}
	
	/**
	 * Validates the Bank's holder.
	 *
	 * @param string $name
	 * @return void
	 */
	private function validateName($name){
		if(empty($name))
			throw new Exception('Nombre inv&aacute;lido.');
	}
	
	/**
	 * Verifies if an BankAccount already exists with that number.
	 *
	 * @param unknown_type $number
	 */
	private function verifyNumber($number){
		if(BankAccountDAM::exists($number))
			throw new Exception('Cuenta Bancaria ya existe.');
	}
}

/**
 * Represents a CashRegister working shift.
 * @package Cash
 * @author Roberto Oliveros
 */
class Shift extends PersistObject{
	/**
	 * Internal identifier.
	 *
	 * @var integer
	 */
	protected $_mId;
	
	/**
	 * Name of the shift.
	 *
	 * @var string
	 */
	private $_mName;
	
	/**
	 * Timetable of the Shift.
	 *
	 * @var string
	 */
	private $_mTimeTable;
	
	/**
	 * Shift's constructor method. Parameters must be set only if the method is called from the database layer.
	 *
	 * @param integer $id
	 * @param integer $status
	 */
	public function __construct($id = NULL, $status = PersistObject::IN_PROGRESS){
		parent::__construct($status);
		
		if(!is_null($id))
			try{
				$this->validateId($id);
			} catch(Exception $e){
				$et = new Exception('Internal error, calling Shift\'s construct method with bad data!' .
						$e->getMessage());
				throw $et;
			}
			
		$this->_mId = $id;
	}
	
	/**
	 * Returns the Shift's id.
	 *
	 * @return integer
	 */
	public function getId(){
		return $this->_mId;
	}
	
	/**
	 * Returns name.
	 *
	 * @return string
	 */
	public function getName(){
		return $this->_mName;
	}
	
	/**
	 * Returns Shift's Timetable.
	 *
	 * @return string
	 */
	public function getTimeTable(){
		return $this->_mTimeTable;
	}
	
	/**
	 * Sets the Shift's name.
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName($name){
		$this->validateName($name);
		$this->_mName = $name;
	}
	
	/**
	 * Sets the Shift's Timetable.
	 *
	 * @param string $timeTable
	 */
	public function setTimeTable($timeTable){
		$this->validateTimeTable($timeTable);
		$this->_mTimeTable = $timeTable;
	}
	
	/**
	 * Sets Shift's properties. Must be called only from the database layer.
	 *
	 * @param string $name
	 * @param string $timeTable
	 */
	public function setData($name, $timeTable){
		try{
			$this->validateName($name);
			$this->validateTimeTable($timeTable);
		} catch(Exception $e){
			$et = new Exception('Internal error, calling Shift\'s setData method with bad data! '.
					$e->getMessage());
			throw $et;
		}
		
		$this->_mName = $name;
		$this->_mTimeTable = $timeTable;
	}
	
	/**
	 * Returns an instance of Shift class if it founds a match in the database. Otherwise returns NULL.
	 *
	 * @param integer $id
	 * @return Shift
	 */
	static public function getInstance($id){
		self::validateId($id);
		return ShiftDAM::getInstance($id);
	}
	
	/**
	 * Deletes Shift from the database. Returns true on success, otherwise false due dependencies.
	 *
	 * @param Shift $obj
	 * @return boolean
	 */
	static public function delete(Shift $obj){
		self::validateObjectForDelete($obj);
		return ShiftDAM::delete($obj);
	}
	
	/**
	 * Inserts Shift's data in the database. Returns the new created id.
	 *
	 * @return integer
	 */
	protected function insert(){
		return ShiftDAM::insert($this);
	}
	
	/**
	 * Updates Shift's data in the database.
	 *
	 * @return void
	 */
	protected function update(){
		ShiftDAM::update($this);
	}
	
	/**
	 * Validates Shift::_mName and Shift::_mTimeTable are set correctly. Otherwise it throws an exception.
	 * @return void
	 */
	protected function validateMainProperties(){
		$this->validateName($this->_mName);
		$this->validateTimeTable($this->_mTimeTable);
	}
	
	/**
	 * Validates if the provided name is correct.
	 *
	 * @param string $name
	 * @return void
	 */
	private function validateName($name){
		if(empty($name))
			throw new Exception('Nombre inv&aacute;lido.');
	}
	
	/**
	 * Validates if the provided timetable is correct.
	 *
	 * @param string $timeTable
	 * @return void
	 */
	private function validateTimeTable($timeTable){
		if(empty($timeTable))
			throw new Exception('Horario inv&aacutelido.');
	}
	
	/**
	 * Validates if the provided id is correct.
	 *
	 * @param integer $id
	 * @return void
	 */
	private function validateId($id){
		if(!is_int($id))
			throw new Exception('Id inv&aacute;lido.');
	}
}
?>