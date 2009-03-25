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
	 * Internal identifier.
	 *
	 * @var integer
	 */
	protected $_mId;
	
	/**
	 * Name of the Bank.
	 * @var string
	 */
	private $_mName;
	
	/**
	 * Bank constructor method. Parameters must be set only if the method is called from the database layer.
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
				$et = new Exception('Internal error, calling Bank constructor with bad data! ' .
						$e->getMessage());
				throw $et;
			}
			
		$this->_mId = $id;
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
		self::validateObjectForDelete($obj);		
		return BankDAM::delete($obj);
	}
	
	/**
	 * Inserts the Bank's data in the database.
	 * @return integer
	 */
	protected function insert(){
		return BankDAM::insert($this);
	}
	
	/**
	 * Updates Bank's data in the database.
	 * @return void
	 */
	protected function update(){
		BankDAM::update($this);
	}
	
	/**
	 * Validates Bank's main properties.
	 * @return void
	 */
	protected function validateMainProperties(){
		$this->validateName($this->_mName);
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
	 * Verifies that all the main properties are set.
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
?>