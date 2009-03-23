<?php
/**
 * Library with utility classes for the cash flow.
 * @package Cash
 * @author Roberto Oliveros
 */

require_once('include/config.php');
require_once('data/cash_dam.php');

/**
 * Class representing a bank.
 * @package Cash
 * @author Roberto Oliveros
 */
class Bank{
	/**
	 * Internal identifier.
	 *
	 * @var integer
	 */
	private $_mId;
	
	/**
	 * Name of the Bank.
	 * @var string
	 */
	private $_mName;
	
	/**
	 * Internal status of the object, e.g. JUST_CREATED or FROM DATABASE.
	 *
	 * @var integer
	 */
	private $_mStatus;
	
	
	public function __construct($id = NULL, $status = JUST_CREATED){
		
		if(!is_null($id))
			$this->validateId($id);
			
		$this->_mId = $id;
		$this->_mStatus = $status;
	}
	
	/**
	 * Returns the name of the Bank.
	 *
	 * @return string
	 */
	public function getName(){
		return $this->_mName;
	}
	
	/**
	 * Returns the Bank's id.
	 *
	 * @return integer
	 */
	public function getId(){
		return $this->_mId;
	}
	
	/**
	 * Returns the object's status.
	 *
	 * @return integer
	 */
	public function getStatus(){
		return $this->_mStatus;
	}
	
	/**
	 * Sets the Bank's name.
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName($name){
		$this->validateName($name);
		$this->_mName = $name;
	}
	
	/**
	 * Sets the Bank's data. Must be called only from the data layer corresponding class. Lack of experience,
	 * sorry.
	 *
	 * @param string $name
	 * @return void
	 */
	public function setData($name){
		$this->validateName($name);
		$this->_mName = $name;
	}
	
	/**
	 * Saves Bank's data in the database.
	 * @return void
	 */
	public function save(){
		$this->validateMainProperties();
		
		if($this->_mStatus == JUST_CREATED){
			$this->_mId = BankDAM::insert($this);
			$this->_mStatus = FROM_DATABASE;
		}
		else
			BankDAM::update($this);
	}
	
	/**
	 * Returns instance of a Bank if a match was found in the database for the provided id. Otherwise returns
	 * NULL.
	 *
	 * @param integer $id
	 * @return Bank
	 */
	static public function getInstance($id){
		self::validateId($id);
		return BankDAM::getInstance($id);
	}
	
	/**
	 * Deletes Bank from database. Returns true confirming the deletion, false otherwise because it has
	 * dependencies.
	 *
	 * @param Bank $obj
	 * @return boolean
	 */
	static public function delete(Bank $obj){
		if ($obj->_mStatus == JUST_CREATED)
			throw new Exception('Cannot delete a just created organization from database.');
			
		return BankDAM::delete($obj);
	}
	
	/**
	 * Validates the Bank's name.
	 *
	 * @param string $name
	 * @return void
	 */
	private function validateName($name){
		if(empty($name))
			throw new Exception('Nombre inv&aacute;lido.');
	}
	
	/**
	 * Validates the Bank's id.
	 *
	 * @param integer $id
	 * @return void
	 */
	private function validateId($id){
		if(!is_int($id))
			throw new Exception('Id inv&aacute;lido.');
	}
	
	/**
	 * Validates Bank's main properties.
	 * @return void
	 */
	private function validateMainProperties(){
		$this->validateName($this->_mName);
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
	 * Deposit object internal status, e.g. JUST_CREATED or FROM_DATABASE.
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
class BankAccount{
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
	 * Internal status of instance object, e.g. JUST_CREATED or FROM_DATABASE
	 *
	 * @var integer
	 */
	private $_mStatus;
	
	
	public function __construct($number = NULL, $status = JUST_CREATED){
		if(!is_null($number))
			$this->validateNumber($number);
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
		if($this->_mStatus == FROM_DATABASE)
			throw new Exception('No se puede editar n&uacute;mero de cuenta.');
		
		$this->validateNumber($number);
		
		if(BankAccountDAM::exists($number))
			throw new Exception('Cuenta Bancaria ya existe.');
		
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
	 * Returns an instance of BankAccount if found in the database. Otherwise returns NULL.
	 *
	 * @param string $number
	 * @return BankAccount
	 */
	static public function getInstance($number){
		$this->validateNumber($number);
		return BankAccountDAM::getInstance($number);
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
		if($obj->getStatus() != FROM_DATABASE)
			throw new Exception('JUST_CREATED Bank provided.');
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
}
?>