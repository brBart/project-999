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
		Number::validatePositiveInteger($id, 'Id inv&aacute;lido.');
		return BankDAM::getInstance($id);
	}
	
	/**
	 * Deletes the bank from database.
	 * 
	 * Throws an exception due dependencies.
	 * @param Bank $obj
	 * @throws Exception
	 */
	static public function delete(Bank $obj){
		self::validateObjectFromDatabase($obj);		
		if(!BankDAM::delete($obj))
			throw new Exception('Banco tiene dependencias y no se puede eliminar.');
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
	 * Holds the account holder's name.
	 *
	 * @var string
	 */
	private $_mHolderName;
	
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
	 * @throws Exception
	 */
	public function __construct($number = NULL, $status = Persist::IN_PROGRESS){
		parent::__construct($status);
		
		if(!is_null($number))
			try{
				String::validateString($number, 'N&uacute;mero de cuenta inv&aacute;lido.');
			} catch(Exception $e){
				$et = new Exception('Interno: Llamando al metodo construct en BankAccount con datos erroneos! ' .
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
	 * Returns the account holder's name.
	 *
	 * @return string
	 */
	public function getHolderName(){
		return $this->_mHolderName;
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
	 * Method can only be called if the object's status property is set to Persist::IN_PROGRESS.
	 * @param string $number
	 * @return void
	 * @throws Exception
	 */
	public function setNumber($number){
		if($this->_mStatus == self::CREATED)
			throw new Exception('No se puede editar n&uacute;mero de cuenta.');
		
		String::validateString($number, 'N&uacute;mero de cuenta inv&aacute;lido.');
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
	 * @param string $holderName
	 * @return void
	 */
	public function setHolderName($holderName){
		String::validateString($holderName, 'Nombre inv&aacute;lido.');
		$this->_mHolderName = $holderName;
	}
	
	/**
	 * Set the object's data provided by the database.
	 * 
	 * Must be call only from the database layer corresponding class. The object's status must be set to
	 * Persist::CREATED in the constructor method too.
	 * @param string $holderName
	 * @param Bank $bank
	 * @throws Exception
	 */
	public function setData($holderName, Bank $bank){
		try{
			String::validateString($holderName, 'Nombre inv&aacute;lido.');
			self::validateObjectFromDatabase($bank);
		} catch(Exception $e){
			$et = new Exception('Interno: Llamando al metodo setData en BankAccount con datos erroneos! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mHolderName = $holderName;
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
		String::validateString($number, 'N&uacute;mero de cuenta inv&aacute;lido.');
		return BankAccountDAM::getInstance($number);
	}
	
	/**
	 * Deletes the banck account from the database.
	 *
	 * Throws an exception due dependencies.
	 * @param BankAccount $obj
	 * @throws Exception
	 */
	static public function delete(BankAccount $obj){
		self::validateObjectFromDatabase($obj);			
		if(!BankAccountDAM::delete($obj))
			throw new Exception('Cuenta Bancaria tiene dependencias y no se puede eliminar.');
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
	 * Verifies that the number and holder name are not empty. The bank's status must not be
	 * PersisObject::IN_PROGRESS. Otherwise it throws an exception.
	 * @return void
	 * @throws Exception
	 */
	protected function validateMainProperties(){
		String::validateString($this->_mNumber, 'N&uacute;mero de cuenta inv&aacute;lido.');
		String::validateString($this->_mHolderName, 'Nombre inv&aacute;lido.');
		
		if(is_null($this->_mBank))
			throw new Exception('Banco inv&aacute;lido.');
		else
			self::validateObjectFromDatabase($this->_mBank);
	}
	
	/**
	 * Verifies if an account's number already exists in the database.
	 * 
	 * Throws an exception if it does.
	 * @param string $number
	 * @throws Exception
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
		String::validateString($timeTable, 'Horario inv&aacute;lido.');
		$this->_mTimeTable = $timeTable;
	}
	
	/**
	 * Sets the shift's properties with data from the database.
	 * 
	 * Must be called only from the database layer. The object's status must be set to
	 * Persist::CREATED in the constructor method too.
	 * @param string $name
	 * @param string $timeTable
	 * @throws Exception
	 */
	public function setData($name, $timeTable){
		parent::setData($name);
		
		try{
			String::validateString($timeTable, 'Horario inv&aacute;lido.');
		} catch(Exception $e){
			$et = new Exception('Interno: Llamando al metodo setData en Shift con datos erroneos! '.
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
		Number::validatePositiveInteger($id, 'Id inv&aacute;lido.');
		return ShiftDAM::getInstance($id);
	}
	
	/**
	 * Deletes the shift from the database.
	 * 
	 * Throws an exception due dependencies.
	 * @param Shift $obj
	 * @throws Exception
	 */
	static public function delete(Shift $obj){
		self::validateObjectFromDatabase($obj);
		if(!ShiftDAM::delete($obj))
			throw new Exception('Turno tiene dependencias y no se puede eliminar.');
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
		String::validateString($this->_mTimeTable, 'Horario inv&aacute;lido.');
	}
}

/**
 * Represent a cash register used to create sales invoices.
 * 
 * Please note that you must only obtain an instance of this object through 2 methods. One is getInstance
 * and the other is through getCashRegister method in the WorkingDay class. Sorry.
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
	 * @throws Exception
	 */
	public function __construct(Shift $shift, $id = NULL){
		PersistObject::validateObjectFromDatabase($shift);
		if(!is_null($id))
			try{
				Number::validatePositiveInteger($id, 'Id inv&aacute;lido.');
			} catch(Exception $e){
				$et = new Exception('Interno: Llamando al metodo construct en CashRegister con datos ' . 
						'erroneos! ' . $e->getMessage());
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
		Number::validatePositiveInteger($id, 'Id inv&aacute;lido.');
		return CashRegisterDAM::getInstance($id);
	}
}


/**
 * Represents an invoice's cash receipt.
 * @package Cash
 * @author Roberto Oliveros
 */
class Receipt extends PersistDocument{
	/**
	 * Holds how much cash change was given to the customer.
	 *
	 * @var float
	 */
	private $_mChange;
	
	/**
	 * Holds the cash received from the customer.
	 *
	 * @var Cash
	 */
	private $_mCash;
	
	/**
	 * Holds the sum of all the payment cards on the receipt.
	 *
	 * @var float
	 */
	private $_TotalPaymentCardsAmount;
	
	/**
	 * Holds the receipt's invoice.
	 *
	 * @var Invoice
	 */
	private $_mInvoice;
	
	/**
	 * Holds an array with the receipt's vouchers.
	 *
	 * @var array<Voucher>
	 */
	private $_mVouchers;
	
	/**
	 * Returns the amount of change that was given to the customer.
	 *
	 * @return float
	 */
	public function getChange(){
		return $this->_mChange;
	}
	
	/**
	 * Returns the sum of all the payment cards of the receipt.
	 *
	 * @return float
	 */
	public function getTotalPaymentCardsAmount(){
		return $this->_TotalPaymentCardsAmount;
	}
	
	/**
	 * Returns the receipt's total amount.
	 *
	 * @return float
	 */
	public function getTotal(){
		return $this->_mCash->getAmount() + $this->_mTotalPaymentCardsAmount;
	}
	
	/**
	 * Sets the receipt given change.
	 *
	 * @param float $amount
	 */
	public function setChange($amount){
		Number::validatePositiveFloat($amount, 'Cantidad de cambio inv&aacute;lido.');
		$this->_mChange = $amount;
	}
	
	/**
	 * Sets the receipt's cash.
	 *
	 * @param Cash $obj
	 */
	public function setCash(Cash $obj){
		$this->_mCash = $obj;
	}
	
	
	public function setData($change, Cash $cash, $totalPaymentCardsAmount, $vouchers){
		
	}
	
	
	public function discard(){
		// Code here...
	}
	
	public function cancel(UserAccount $user){
		// Code here...
	}
	
	protected function insert(){
		// Code here...
	}
}


/**
 * Represents a type of electronic payment card.
 * 
 * Examples: credit or debit.
 * @package Cash
 * @author Roberto Oliveros
 */
class PaymentCardType extends Identifier{
	/**
	 * Constructs the type of payment card with the provided id and status.
	 * 
	 * Parameters must be set only if the method is called from the database layer.
	 * @param integer $id
	 * @param integer $status
	 */
	public function __construct($id = NULL, $status = Persist::IN_PROGRESS){
		parent::__construct($id, $status);
	}
	
	/**
	 * Returns instance of a type of payment card.
	 * 
	 * Returns NULL if there was no match in the database.
	 * @param integer $id
	 * @return PaymentCardType
	 */
	static public function getInstance($id){
		Number::validatePositiveInteger($id, 'Id inv&aacute;lido.');
		return PaymentCardTypeDAM::getInstance($id);
	}
	
	/**
	 * Deletes the type of payment card from database.
	 * 
	 * Throws an exception due dependencies.
	 * @param PaymentCardType $obj
	 * @throws Exception
	 */
	static public function delete(PaymentCardType $obj){
		self::validateObjectFromDatabase($obj);		
		if(!PaymentCardTypeDAM::delete($obj))
			throw new Exception('Tipo de Tarjeta tiene dependencias y no se puede eliminar.');
	}
	
	/**
	 * Inserts the type of payment card data in the database.
	 * 
	 * Returns the new created id from the database.
	 * @return integer
	 */
	protected function insert(){
		return PaymentCardTypeDAM::insert($this);
	}
	
	/**
	 * Updates the type of payment card data in the database.
	 */
	protected function update(){
		PaymentCardTypeDAM::update($this);
	}
}


/**
 * Represents a electronic payment card brand.
 * 
 * Examples: visa or mastercard.
 * @package Cash
 * @author Roberto Oliveros
 */
class PaymentCardBrand extends Identifier{
	/**
	 * Constructs the payment card brand with the provided id and status.
	 * 
	 * Parameters must be set only if the method is called from the database layer.
	 * @param integer $id
	 * @param integer $status
	 */
	public function __construct($id = NULL, $status = Persist::IN_PROGRESS){
		parent::__construct($id, $status);
	}
	
	/**
	 * Returns instance of a payment card brand.
	 * 
	 * Returns NULL if there was no match in the database.
	 * @param integer $id
	 * @return PaymentCardBrand
	 */
	static public function getInstance($id){
		Number::validatePositiveInteger($id, 'Id inv&aacute;lido.');
		return PaymentCardBrandDAM::getInstance($id);
	}
	
	/**
	 * Deletes the payment card brand from the database.
	 * 
	 * Throws an exception due dependencies.
	 * @param PaymentCardBrand $obj
	 * @throws Exception
	 */
	static public function delete(PaymentCardBrand $obj){
		self::validateObjectFromDatabase($obj);		
		if(!PaymentCardBrandDAM::delete($obj))
			throw new Exception('Marca de Tarjeta tiene dependencias y no se puede eliminar.');
	}
	
	/**
	 * Inserts the payment card brand data in the database.
	 * 
	 * Returns the new created id from the database.
	 * @return integer
	 */
	protected function insert(){
		return PaymentCardBrandDAM::insert($this);
	}
	
	/**
	 * Updates the payment card brand data in the database.
	 */
	protected function update(){
		PaymentCardBrandDAM::update($this);
	}
}


/**
 * Represents the customer's payment plastic card.
 * @package Cash
 * @author Roberto Oliveros
 */
class PaymentCard{
	/**
	 * Holds the payment card's last four digit numbers.
	 *
	 * @var integer
	 */
	private $_mNumber;
	
	/**
	 * Holds the payment card's type.
	 *
	 * @var PaymentCardType
	 */
	private $_mType;
	
	/**
	 * Holds the payment card's brand.
	 *
	 * @var PaymentCardBrand
	 */
	private $_mBrand;
	
	/**
	 * Holds the payment card holder's name.
	 *
	 * @var string
	 */
	private $_mHolderName;
	
	/**
	 * Holds the payment card's expiration date.
	 *
	 * Date format: 'dd/mm/yyyy'.
	 * @var string
	 */
	private $_mExpirationDate;
	
	/**
	 * Constructs the payment card with the provided data.
	 *
	 * Use these method only from the database layer please. Use the create method instead. Lack of experience
	 * sorry.
	 * @param integer $number
	 * @param PaymentCardType $type
	 * @param PaymentCardBrand $brand
	 * @param string $holderName
	 * @param string $date
	 */
	public function __construct($number, PaymentCardType $type, PaymentCardBrand $brand, $holderName, $date){
		Number::validatePositiveInteger($number, 'N&uacute;mero de tarjeta inv&aacute;lido.');
		Persist::validateObjectFromDatabase($type);
		Persist::validateObjectFromDatabase($brand);
		String::validateString($holderName, 'Nombre del titular inv&aacute;lido.');
		Date::validateDate($date, 'Fecha de la tarjeta inv&aacute;lida.');
		
		$this->_mNumber = $number;
		$this->_mType = $type;
		$this->_mBrand = $brand;
		$this->_mHolderName = $holderName;
		$this->_mExpirationDate = $date;
	}
	
	/**
	 * Returns the payment card's last four digit numbers.
	 *
	 * @return integer
	 */
	public function getNumber(){
		return $this->_mNumber;
	}
	
	/**
	 * Returns the payment card's type.
	 *
	 * @return PaymentCardType
	 */
	public function getType(){
		return $this->_mType;
	}
	
	/**
	 * Returns the payment card's brand.
	 *
	 * @return PaymentCardBrand
	 */
	public function getBrand(){
		return $this->_mBrand;
	}
	
	/**
	 * Returns the payment card holder's name.
	 *
	 * @return string
	 */
	public function getHolderName(){
		return $this->_mHolderName;
	}
	
	/**
	 * Returns the payment card's expiration date.
	 *
	 * @return string
	 */
	public function getExpirationDate(){
		return $this->_mExpirationDate;
	}
	
	/**
	 * Creates a new payment card validating if the provided date has not expired.
	 *
	 * @param integer $number
	 * @param PaymentCardType $type
	 * @param PaymentCardBrand $brand
	 * @param string $holderName
	 * @param string $date
	 * @return PaymentCard
	 */
	static public function create($number, PaymentCardType $type, PaymentCardBrand $brand, $holderName, $date){
		Date::validateDate($date, 'Fecha de la tarjeta inv&aacute;lida.');
		if(!Date::compareDates(date('d/m/Y'), $date))
			throw new Exception('Fecha de la tarjeta ya caduco.');
		else
			return new PaymentCard($number, $type, $brand, $holderName, $date);
	}
}


/**
 * Represents a payment card voucher on an invoice's receipt.
 * @package Cash
 * @author Roberto Oliveros
 */
class Voucher{
	/**
	 * Holds the voucher transaction's number emitted by the POS machine.
	 *
	 * @var string
	 */
	private $_mTransactionNumber;
	
	/**
	 * Holds the voucher's payment card used by the customer.
	 *
	 * @var PaymentCard
	 */
	private $_mPaymentCard;
	
	/**
	 * Holds the voucher's monetary amount.
	 *
	 * @var float
	 */
	private $_mAmount;
	
	/**
	 * Constructs the voucher with the provided data.
	 *
	 * @param string $transactionNumber
	 * @param PaymentCard $card
	 * @param float $amount
	 */
	public function __construct($transactionNumber, PaymentCard $card, $amount){
		String::validateString($transactionNumber, 'N&uacute;mero de transacci&oacute;n inv&aacute;lido.');
		Number::validatePositiveFloat($amount, 'Monto inv&aacute;lido.');
		
		$this->_mTransactionNumber = $transactionNumber;
		$this->_mPaymentCard = $card;
		$this->_mAmount = $amount;
	}
	
	/**
	 * Returns the voucher's transaction number.
	 *
	 * @return string
	 */
	public function getTransactionNumber(){
		return $this->_mTransactionNumber;
	}
	
	/**
	 * Returns the voucher's payment card.
	 *
	 * @return PaymentCard
	 */
	public function getPaymentCard(){
		return $this->_mPaymentCard;
	}
	
	/**
	 * Returns the voucher's amount.
	 *
	 * @return float
	 */
	public function getAmount(){
		return $this->_mAmount;
	}
	
	/**
	 * Returns an array with the voucher's data.
	 *
	 * The array contains the fields type, brand, number, name, amount and expiration_date.
	 * @return array
	 */
	public function show(){
		$type = $this->_mPaymentCard->getType();
		$brand = $this->_mPaymentCard->getBrand();
		
		return array('type' => $type->getName(), 'brand' => $brand->getName(),
				'number' => $this->_mPaymentCard->getNumber(), 'name' => $this->_mPaymentCard->getHolderName(),
				'amount' => $this->_mAmount, 'expiration_date' => $this->_mPaymentCard->getExpirationDate());
	}
	
	/**
	 * Increases the voucher's amount value.
	 *
	 * @param float $amount
	 */
	public function increase($amount){
		Number::validatePositiveFloat($amount, 'Monto inv&aacute;lido.');
		$this->_mAmount += $amount;
	}
}


/**
 * Represents the cash received from the customer on a receipt.
 * @package Cash
 * @author Roberto Oliveros
 */
class Cash{
	
}
?>