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
class Deposit extends PersistDocument{
	/**
	 * Statys type.
	 * 
	 * Indicates that the document has been confirmed.
	 */
	const CONFIRMED = 3;
	
	/**
	 * Deposit slip number.
	 *
	 * @var string
	 */
	private $_mNumber;
	
	/**
	 * Holds the date in which the deposit was creates.
	 *
	 * Date format: 'dd/mm/yyyy'.
	 * @var string
	 */
	private $_mDate;
	
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
	 * Deposit total.
	 *
	 * @var float
	 */
	private $_mTotal = 0.00;
	
	/**
	 * Holds who made the deposit.
	 *
	 * @var UserAccount
	 */
	private $_mUser;
	
	/**
	 * Array with DepositDetail items.
	 *
	 * @var array<DepositDetail>
	 */
	private $_mDetails = array();
	
	/**
	 * Constructs the deposit with the provided data.
	 *
	 * Arguments must be passed only when called from the database layer correponding class. If a new deposit
	 * its been created (Deposit::IN_PROGRESS) the cash register must be open, otherwise it doesn't
	 * matter because it is an already created (Deposit::CREATED) deposit.
	 * @param CashRegister $cashRegister
	 * @param string $date
	 * @param UserAccount $user
	 * @param integer $id
	 * @param integer $status
	 */
	public function __construct(CashRegister $cashRegister, $date = NULL, UserAccount $user = NULL,
			$id = NULL, $status = Deposit::IN_PROGRESS){
		parent::__construct($id, $status);
		
		if($this->_mStatus == Deposit::IN_PROGRESS && !$cashRegister->isOpen())
			throw new Exception('Caja ya esta cerrada.');
			
		if(!is_null($date)){
			try{
				Date::validateDate($date, 'Fecha inv&aacute;lida.');
			} catch(Exception $e){
				$et = new Exception('Interno: Llamando al metodo construct en Document con datos erroneos! ' .
						$e->getMessage());
				throw $et;
			}
			$this->_mDate = $date;
		}
		else
			$this->_mDate = date('d/m/Y');
		
		if(!is_null($user)){
			try{
				Persist::validateObjectFromDatabase($user);
			} catch(Exception $e){
				$et = new Exception('Internal error, calling Document constructor method with bad data! ' .
						$e->getMessage());
				throw $et;
			}
			$this->_mUser = $user;
		}
		else
			$this->_mUser = SessionHelper::getUser();
				
		$this->_mCashRegister = $cashRegister;
	}
	
	/**
	 * Returns the deposit slip number.
	 *
	 * @return string
	 */
	public function getNumber(){
		return $this->_mNumber;
	}
	
	/**
	 * Returns the deposit's creation date.
	 *
	 * @return string
	 */
	public function getDate(){
		return $this->_mDate;
	}
	
	/**
	 * Returns the deposit's bank account.
	 *
	 * @return BankAccount
	 */
	public function getBankAccount(){
		return $this->_mBankAccount;
	}
	
	/**
	 * Returns the deposit's cash register.
	 *
	 * @return CashRegister
	 */
	public function getCashRegister(){
		return $this->_mCashRegister;
	}
	
	/**
	 * Returns the deposit's total amount.
	 *
	 * @return float
	 */
	public function getTotal(){
		return $this->_mTotal;
	}
	
	/**
	 * Returns the user who created the deposit.
	 *
	 * @return UserAccount
	 */
	public function getUser(){
		return $this->_mUser;
	}
	
	/**
	 * Returns the detail which id match the provided id.
	 *
	 * Returns NULL in case there was no match.
	 * @param integer $id
	 * @return DepositDetail
	 */
	public function getDetail($id){
		Number::validatePositiveInteger($id, 'Id inv&aacute;lido');
		
		foreach($this->_mDetails as &$detail)
			if($detail->getCash()->getId() == $id)
				return $detail;
				
		return NULL;
	}
	
	/**
	 * Returns an array with all the deposit's details.
	 *
	 * @return array<DepositDetail>
	 */
	public function getDetails(){
		return $this->_mDetails;
	}
	
	/**
	 * Sets the deposit slip number.
	 *
	 * @param string $number
	 */
	public function setNumber($number){
		String::validateString($number, 'N&uacute;mero de deposito inv&aacute;lido.');
		$this->_mNumber = $number;
	}
	
	/**
	 * Sets the deposit's bank account.
	 *
	 * @param BankAccount $obj
	 */
	public function setBankAccount(BankAccount $obj){
		self::validateObjectFromDatabase($obj);
		$this->_mBankAccount = $obj;
	}
	
	/**
	 * Sets the deposit properties.
	 *
	 * Must be called only from the database layer. The object's status must be set to
	 * Persist::CREATED in the constructor method too.
	 * @param string $number
	 * @param BankAccount $bankAccount
	 * @param float $total
	 * @param array<DepositDetail> $details
	 */
	public function setData($number, BankAccount $bankAccount, $total, $details){
		try{
			String::validateString($number, 'N&uacute;mero de deposito inv&aacute;lido.');
			self::validateObjectFromDatabase($bankAccount);
			Number::validatePositiveFloat($total, 'Total inv&aacute;lido.');
			if(empty($details))
				throw new Exception('No hay ning detalle.');
		} catch(Exception $e){
			$et = new Exception('Interno: Llamando al metodo setData en Deposit con datos erroneos! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mNumber = $number;
		$this->_mBankAccount = $bankAccount;
		$this->_mTotal = $total;
		$this->_mDetails = $details;
	}
	
	/**
	 * Adds a deposit detail to the document.
	 *
	 * @param DepositDetail $newDetail
	 */
	public function addDetail(DepositDetail $newDetail){
		$this->_mTotal += $newDetail->getAmount();
		
		// For moving the modified the detail to the last place.
		$temp_details = array();
		foreach($this->_mDetails as &$detail)
			if($detail->getCash()->getId() != $newDetail->getCash()->getId())
				$temp_details[] = $detail;
			else{
				$detail->increase($newDetail->getAmount());
				$newDetail = $detail;
			}
			
		$temp_details[] = $newDetail;
		$this->_mDetails = $temp_details;
	}
	
	/**
	 * Deletes the detail from the deposit.
	 *
	 * @param DepositDetail $purgeDetail
	 */
	public function deleteDetail(DepositDetail $purgeDetail){
		$temp_details = array();
		
		foreach($this->_mDetails as &$detail)
			if($detail->getCash()->getId() != $purgeDetail->getCash()->getId())
				$temp_details[] = $detail;
			else
				$this->_mTotal -= $detail->getAmount();
				
		$this->_mDetails = $temp_details;
	}
	
	/**
	 * Saves the deposit's data in the database.
	 *
	 * Only applies if the document's status property has the Deposit::IN_PROGRESS value. Returns
	 * the new created id from the database on success.
	 * @return integer
	 */
	public function save(){
		if($this->_mStatus == self::IN_PROGRESS){
			$this->validateMainProperties();
			$this->insert();
			return $this->_mId;
		}
	}
	
	/**
	 * Change the deposit status propety to Deposit::CONFIRMED.
	 *
	 */
	public function confirm(){
		if($this->_mStatus == self::CREATED){
			DepositDAM::confirm($this);
			$this->_mStatus = self::CONFIRMED;
		}
	}
	
	/**
	 * Does not save the deposit's data and reverts its effects.
	 *
	 * Only applies if the status property is set to Deposit::IN_PROGRESS.
	 */
	public function discard(){
		if($this->_mStatus == Deposit::IN_PROGRESS)
			foreach($this->_mDetails as &$detail)
				DepositEvent::cancel($this, $detail);
	}
	
	/**
	 * Cancels the document and reverts its effects.
	 *
	 * The user argument registers who authorized the action.
	 * @param UserAccount $user
	 */
	public function cancel(UserAccount $user){
		if($this->_mStatus == self::CREATED){
			self::validateObjectFromDatabase($user);
			
			if(!$this->_mCashRegister->isOpen())
				throw new Exception('Caja ya esta cerrada, no se puede anular.');
				
			foreach($this->_mDetails as &$detail)
				$detail->cancel();
			DepositDAM::cancel($this, $user, date('d/m/Y'));
			$this->_mStatus = self::CANCELLED;
		}
	}
	
	/**
	 * Returns a deposit with the details corresponding to the requested page.
	 *
	 * The total_pages and total_items arguments are necessary to return their respective values. Returns NULL
	 * if there was no match for the provided id in the database. 
	 * @param integer $id
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return Invoice
	 */
	static public function getInstance($id, &$total_pages, &$total_items, $page = 0){
		Number::validatePositiveInteger($id, 'Id inv&aacute;lido.');
		if($page !== 0)
			Number::validatePositiveInteger($page, 'N&uacute;mero de pagina inv&aacute;lido.');
			
		return DepositDAM::getInstance($id, $total_pages, $total_items, $page);
	}
	
	/**
	 * Inserts the deposit's data in the database.
	 *
	 */
	protected function insert(){
		foreach($this->_mDetails as &$detail)
				$detail->apply();
		$this->_mId = DepositDAM::insert($this);
		$this->_mStatus = self::CREATED;
	}
	
	/**
	 * Validates the deposit's main properties.
	 *
	 * Numer property must not be empty and bank account must not be NULL.
	 */
	private function validateMainProperties(){
		String::validateString($this->_mNumber, 'N&uacute;mero de deposito inv&aacute;lido.');
		
		if(is_null($this->_mBankAccount))
			throw new Exception('Cuenta Bancaria inv&aacute;lida.');
			
		if(empty($this->_mDetails))
			throw new Exception('No hay ningun detalle.');
	}
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
	 * Holds the cash received from the customer.
	 *
	 * @var Cash
	 */
	private $_mCash;
	
	/**
	 * Holds how much cash change was given to the customer.
	 *
	 * @var float
	 */
	private $_mChange = 0.00;
	
	/**
	 * Holds the sum of all the vouchers on the receipt.
	 *
	 * @var float
	 */
	private $_mTotalVouchersAmount = 0.00;
	
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
	private $_mVouchers = array();
	
	/**
	 * Constructs the receipt with the provided data.
	 *
	 * Note that if you pass the status argument as PersistDocument::IN_PROGRESS, the invoice argument must
	 * have any details and its status property must be set to PersistDocument::IN_PROGRESS too. Otherwise
	 * call this method only from the database layer.
	 * @param Invoice $invoice
	 * @param integer $id
	 * @param integer $status
	 */
	public function __construct(Invoice $invoice, $id = NULL, $status = PersistDocument::IN_PROGRESS){
		parent::__construct($id, $status);
		
		if($this->_mStatus == PersistDocument::IN_PROGRESS){
			self::validateNewObject($invoice);
			if($invoice->getTotal() <= 0)
				throw new Exception('Factura no contiene detalles.');
		}
		else
			try{
				self::validateObjectFromDatabase($invoice);
			} catch(Exception $e){
				$et = new Exception('Interno; Llamando al metodo construct en Receipt con datos erroneos! ' .
						$e->getMessage());
				throw $et;
			}
			
		$invoice->hasReceipt(true);
		$this->_mInvoice = $invoice;
	}
	
	/**
	 * Returns the receipt's cash.
	 *
	 * @return Cash
	 */
	public function getCash(){
		return $this->_mCash;
	}
	
	/**
	 * Returns the amount of change that was given to the customer.
	 *
	 * @return float
	 */
	public function getChange(){
		return $this->_mChange;
	}
	
	/**
	 * Returns the sum of all the vouchers of the receipt.
	 *
	 * @return float
	 */
	public function getTotalVouchersAmount(){
		return $this->_mTotalVouchersAmount;
	}
	
	/**
	 * Returns the receipt's invoice.
	 *
	 * @return Invoice
	 */
	public function getInvoice(){
		return $this->_mInvoice;
	}
	
	/**
	 * Returns the receipt's total amount.
	 *
	 * @return float
	 */
	public function getTotal(){
		$cash_amount = (!is_null($this->_mCash)) ? $this->_mCash->getAmount() : 0.0;
		return $cash_amount + $this->_mTotalVouchersAmount;
	}
	
	/**
	 * Returns the voucher which transaction number matchs the one provided.
	 *
	 * @param string $transactionNumber
	 * @return Voucher
	 */
	public function getVoucher($transactionNumber){
		String::validateString($transactionNumber, 'N&uacute;mero de transacci&oacute;n inv&aacute;lido.');
		
		foreach($this->_mVouchers as &$voucher)
			if($voucher->getTransactionNumber() == $transactionNumber)
				return $voucher;
				
		return NULL;
	}
	
	/**
	 * Returns an array with all the receipt's vouchers.
	 *
	 * @return array<Voucher>
	 */
	public function getVouchers(){
		return $this->_mVouchers;
	}
	
	/**
	 * Sets the receipt's cash.
	 *
	 * @param Cash $obj
	 */
	public function setCash(Cash $obj){
		self::validateNewObject($obj);
		$this->_mCash = $obj;
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
	 * Sets the receipt's properties with data provided.
	 * 
	 * Must be called only from the database layer. The object's status must be set to
	 * Persist::CREATED in the constructor method too.
	 * @param float $change
	 * @param Cash $cash
	 * @param float $totalVouchersAmount
	 * @param array<Voucher> $vouchers
	 */
	public function setData(Cash $cash = NULL, $totalVouchersAmount = 0.0, $change = 0.0, $vouchers = NULL){
		try{
			if(!is_null($cash))
				self::validateObjectFromDatabase($cash);
				
			if($totalVouchersAmount !== 0.0){
				Number::validatePositiveFloat($totalVouchersAmount, 'Total inv&aacute;lido.');
				if(empty($vouchers))
					throw new Exception('No hay ningun voucher.');
			}
			
			if($change !== 0.0)
				Number::validatePositiveFloat($change, 'Monto de cambio inv&aacute;lido.');
		} catch(Exception $e){
			$et = new Exception('Interno: Llamando al metodo setData en Receipt con datos erroneos! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mCash = $cash;
		$this->_mTotalVouchersAmount = $totalVouchersAmount;
		$this->_mChange = $change;
		if(!is_null($vouchers))
			$this->_mVouchers = $vouchers;
	}
	
	/**
	 * Adds a voucher to the receipt.
	 *
	 * @param Voucher $newVoucher
	 */
	public function addVoucher(Voucher $newVoucher){
		$this->_mTotalVouchersAmount += $newVoucher->getAmount();
		
		// For moving the modified voucher to the last place.
		$temp_vouchers = array();
		foreach($this->_mVouchers as &$voucher)
			if($voucher->getTransactionNumber() != $newVoucher->getTransactionNumber())
				$temp_vouchers[] = $voucher;
			else {
				$voucher->increase($newVoucher->getAmount());
				$newVoucher = $voucher;
			}
			
		$temp_vouchers[] = $newVoucher;
		$this->_mVouchers = $temp_vouchers;
	}
	
	/**
	 * Deletes the provided voucher from the receipt.
	 *
	 * @param Voucher $purgeVoucher
	 */
	public function deleteVoucher(Voucher $purgeVoucher){
		$temp_vouchers = array();
		
		foreach($this->_mVouchers as &$voucher)
			if($voucher->getTransactionNumber() != $purgeVoucher->getTransactionNumber())
				$temp_vouchers[] = $voucher;
			else
				$this->_mTotalVouchersAmount -= $voucher->getAmount();
				
		$this->_mVouchers = $temp_vouchers;
	}
	
	/**
	 * Saves the receipt's data in the database.
	 *
	 * Only applies if the receipt's status property is set to PersistDocument::IN_PROGRESS.
	 */
	public function save(){
		if($this->_mStatus == PersistDocument::IN_PROGRESS){
			$this->validateMainProperties();
			$this->insert();
		}
	}
	
	/**
	 * Does nothing, just to fulfill the abstraction.
	 *
	 */
	public function discard(){
		// Do nothing
	}
	
	/**
	 * Cancels the document and reverts its effects.
	 *
	 * The user argument registers who authorized the action. Note that you must cancel the receipt's invoice
	 * first in order to cancel the receipt. Call the invoice cancel method instead.
	 * @param UserAccount $user
	 */
	public function cancel(UserAccount $user){
		if($this->_mStatus == PersistDocument::CREATED){
			if($this->_mInvoice->getStatus() == PersistDocument::CANCELLED){
				$deposit_ids = DepositDetailsList::getList($this->_mCash);
				if(!empty($deposit_details))
					foreach($deposit_ids as $id){
						$deposit = Deposit::getInstance($id);
						$deposit->cancel($user);
					}
					
				ReceiptDAM::cancel($this);
				$this->_mStatus = PersistDocument::CANCELLED;
			}
		}
	}
	
	/**
	 * Returns an instance of a receipt.
	 *
	 * Returns NULL in case there was no match for the provided invoice in the database.
	 * @param Invoice $obj
	 * @return Receipt
	 */
	static public function getInstance(Invoice $obj){
		self::validateObjectFromDatabase($obj);
		return ReceiptDAM::getInstance($obj);
	}
	
	/**
	 * Inserts the receipt's data in the database.
	 *
	 * It also calls the save method of the receipt's invoice.
	 */
	protected function insert(){
		$this->_mInvoice->save();
		ReceiptDAM::insert($this);
		$this->_mId = $this->_mInvoice->getId();
		$this->_mStatus = PersistDocument::CREATED;
	}
	
	/**
	 * Validates the receipt's main properties.
	 *
	 * At least the cash property must not be NULL or the vouchers property must not be empty.
	 */
	private function validateMainProperties(){
		if(is_null($this->_mCash) && empty($this->_mVouchers))
			throw new Exception('Favor ingresar efectivo o algun voucher.');
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
 * 
 * Be careful, as the Lot class, this class represents two type of classes or stages of the matter. One is when
 * the status property is set to Persist::IN_PROGRESS and hasn't been saved to the database. At this stage
 * the values are obtained from the object itself. The other is when the status property is set to
 * Persist::CREATED, the values of the object are obtained from the database directly. Sorry.
 * @package Cash
 * @author Roberto Oliveros
 */
class Cash extends Persist{
	/**
	 * Holds the cash id, practically the receipt's id.
	 *
	 * @var integer
	 */
	private $_mId;
	
	/**
	 * Holds the amount value on cash.
	 *
	 * @var float
	 */
	private $_mAmount;
	
	/**
	 * Constructs the cash with the provided data.
	 *
	 * @param float $amount
	 */
	public function __construct($amount, $id = NULL, $status = Persist::IN_PROGRESS){
		parent::__construct($status);
		
		if($this->_mStatus == Persist::IN_PROGRESS)
			Number::validatePositiveFloat($amount, 'Monto de efectivo inv&aacute;lido.');
		else
			Number::validateUnsignedFloat($amount, 'Monto de efectivo inv&aacute;lido.');
		
		if(!is_null($id))
			Number::validatePositiveInteger($id, 'Id inv&aacute;lido.');
		
		$this->_mAmount = $amount;
		$this->_mId = $id;
	}
	
	/**
	 * Returns the cash id.
	 *
	 * @return integer
	 */
	public function getId(){
		return $this->_mId;
	}
	
	/**
	 * Returns the cash amount.
	 *
	 * If the status property is set to Persist::CREATED the database value is returned.
	 * @return float
	 */
	public function getAmount(){
		if($this->_mStatus == Persist::CREATED)
			return CashDAM::getAmount($this);
		else
			return $this->_mAmount;
	}
	
	/**
	 * Returns how much of cash is available.
	 *
	 * Only applies if the status property is set to Persist::CREATED.
	 * @return float
	 */
	public function getAvailable(){
		if($this->_mStatus == Persist::CREATED)
			return CashDAM::getAvailable($this);
		else
			return 0;
	}
	
	/**
	 * Reserve the cash amount in the database.
	 *
	 * Only applies if the status property is set to Persist::CREATED.
	 * @param float $amount
	 */
	public function reserve($amount){
		if($this->_mStatus == Persist::CREATED){
			Number::validatePositiveFloat($amount, 'Monto inv&aacute;lido.');
			CashDAM::reserve($this, $amount);
		}
	}
	
	/**
	 * Decreases the reserved cash amount in the database.
	 *
	 * Only applies if the status property is set to Persist::CREATED.
	 * @param float $amount
	 */
	public function decreaseReserve($amount){
		if($this->_mStatus == Persist::CREATED){
			Number::validatePositiveFloat($amount, 'Monto inv&aacute;lido.');
			CashDAM::decreaseReserve($this, $amount);
		}
	}
	
	/**
	 * Increases the deposited cash amount in the database.
	 *
	 * Only applies if the status property is set to Persist::CREATED.
	 * @param float $amount
	 */
	public function deposit($amount){
		if($this->_mStatus == Persist::CREATED){
			Number::validatePositiveFloat($amount, 'Monto inv&aacute;lido.');
			CashDAM::deposit($this, $amount);
		}
	}
	
	/**
	 * Decreases the deposited cash amount in the database.
	 *
	 * Only applies if the status property is set to Persist::CREATED.
	 * @param float $amount
	 */
	public function decreaseDeposit($amount){
		if($this->_mStatus == Persist::CREATED){
			Number::validatePositiveFloat($amount, 'Monto inv&aacute;lido.');
			CashDAM::decreaseDeposit($this, $amount);
		}
	}
}


/**
 * Class used for obtaining certain receipts' deposits.
 * @package Cash
 * @author Roberto Oliveros
 */
class DepositDetailsList{
	/**
	 * Returns an array with all the deposits' id which contain the provided cash.
	 *
	 * @param Cash $obj
	 * @return array
	 */
	static public function getList(Cash $obj){
		// Code here...
	}
}


/**
 * A detail on a deposit document which contains the cash of a receipt document.
 * @package Cash
 * @author Roberto Oliveros
 */
class DepositDetail{
	/**
	 * Holds a receipt's cash used in the deposit.
	 *
	 * @var Cash
	 */
	private $_mCash;
	
	/**
	 * Holds the deposit detail's cash amount.
	 *
	 * @var float
	 */
	private $_mAmount;
	
	/**
	 * Constructs the detail with the provided data.
	 *
	 * @param Cash $cash
	 * @param integer $amount
	 */
	public function __construct(Cash $cash, $amount){
		Persist::validateObjectFromDatabase($cash);
		Number::validatePositiveFloat($amount, 'Monto inv&aacute;lido.');
		
		$this->_mCash = $cash;
		$this->_mAmount = $amount;
	}
	
	/**
	 * Returns the detail's cash.
	 *
	 * @return Cash
	 */
	public function getCash(){
		return $this->_mCash;
	}
	
	/**
	 * Returns the detail's cash amount.
	 *
	 * @return float
	 */
	public function getAmount(){
		return $this->_mAmount;
	}
	
	/**
	 * Returns an array with the detail's data.
	 *
	 * The fields in the array are receipt_id, received and deposited.
	 * @return array
	 */
	public function show(){
		return array('receipt_id' => $this->_mCash->getId(), 'received' => $this->_mCash->getAmount(),
				'deposited' => $this->_mAmount);
	}
	
	/**
	 * Increases the detail's amount.
	 *
	 * @param float $amount
	 */
	public function increase($amount){
		Number::validatePositiveFloat($amount, 'Monto inv&aacute;lido.');
		$this->_mAmount += $amount;
	}
	
	/**
	 * Apply the deposit effects on the detail's cash.
	 *
	 */
	public function apply(){
		$this->_mCash->decreaseReserve($this->_mAmount);
		$this->_mCash->deposit($this->_mAmount);
	}
	
	/**
	 * Reverts the deposit effects.
	 *
	 */
	public function cancel(){
		$this->_mCash->decreaseDeposit($this->_mAmount);
	}
}
?>