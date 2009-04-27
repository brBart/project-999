<?php
/**
 * Library defining all the necessary classes regarding documents.
 * @package Document
 * @author Roberto Oliveros
 */

/**
 * For persistence needs.
 */
require_once('business/persist.php');
/**
 * For validating purposes.
 */
require_once('business/validator.php');
/**
 * For accessing the database.
 */
require_once('data/document_dam.php');

/**
 * Defines common functionality for all the document derived classes.
 * @package Document
 * @author Roberto Oliveros
 */
abstract class Document extends PersistDocument{
	/**
	 * Holds the date in which the document was created.
	 *
	 * Date format: 'dd/mm/yyyy'.
	 * @var string
	 */
	private $_mDate;
	
	/**
	 * Holds the documents grand total.
	 *
	 * @var float
	 */
	private $_mTotal = 0.00;
	
	/**
	 * Holds the user which created the document.
	 *
	 * @var UserAccount
	 */
	private $_mUser;
	
	/**
	 * Array containing all the document details.
	 *
	 * @var array<DocumentDetail>
	 */
	private $_mDetails = array();
	
	/**
	 * Constructs the document with the provided data.
	 *
	 * Arguments must be passed only when called from the database layer correponding class.
	 * @param string $date
	 * @param UserAccount $user
	 * @param integer $id
	 * @param integer $status
	 */
	public function __construct($date = NULL, UserAccount $user = NULL, $id = NULL,
			$status = PersistDocument::IN_PROGRESS){
		parent::__construct($id, $status);
		
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
	}
	
	/**
	 * Returns the document's creation date.
	 *
	 * @return string
	 */
	public function getDate(){
		return $this->_mDate;
	}
	
	/**
	 * Returns the document's grand total.
	 *
	 * @return float
	 */
	public function getTotal(){
		return $this->_mTotal;
	}
	
	/**
	 * Returns the document's user.
	 *
	 * @return UserAccount
	 */
	public function getUser(){
		return $this->_mUser;
	}
	
	/**
	 * Returns a document's detail which id match with the provided id.
	 *
	 * If there is no match NULL is returned.
	 * @param string $id
	 * @return DocumentDetail
	 */
	public function getDetail($id){
		String::validateString($id, 'Id del detalle inv&aacute;lido.');
		
		foreach($this->_mDetails as &$detail)
			if($detail->getId() == $id)
				return $detail;
				
		return NULL;
	}
	
	/**
	 * Returns all the document's details.
	 *
	 * @return array<DocumentDetail>
	 */
	public function getDetails(){
		return $this->_mDetails;
	}
	
	/**
	 * Sets the document's properties.
	 *
	 * Must be called only from the database layer corresponding class. The object's status must be set to
	 * PersistDocument::CREATED in the constructor method too.
	 * @param string $date
	 * @param float $total
	 * @param array<DocumentDetail> $details
	 * @throws Exception
	 */
	public function setData($total, $details){
		try{
			Number::validateUnsignedFloat($total, 'Total inv&aacute;lido.');
			if(empty($details))
				throw new Exception('No hay ningun detalle.');
		} catch(Exception $e){
			$et = new Exception('Interno: Llamando al metodo setData en Document con datos erroneos! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mTotal = $total;
		$this->_mDetails = $details;
	}
	
	/**
	 * Adds a detail to the document.
	 *
	 * If a similar detail is already in the document, its quantity property will be increase. NOTE: If a
	 * DocBonusDetail is duplicated (or more) the document's total will be affected while the detail's total
	 * will not, be careful! Use the Sale class for adding bonus to a document. Sorry.
	 * @param DocumentDetail $newDetail
	 */
	public function addDetail(DocumentDetail $newDetail){
		$this->_mTotal += $newDetail->getTotal();
		
		foreach($this->_mDetails as &$detail){
			if($detail->getId() == $newDetail->getId()){
				$detail->increase($newDetail->getQuantity());
				return;
			}
		}
		
		$this->_mDetails[] = $newDetail;
	}
	
	/**
	 * Removes the detail from the document.
	 *
	 * @param DocumentDetail $purgeDetail
	 */
	public function deleteDetail(DocumentDetail $purgeDetail){
		$temp_details = array();
		
		foreach($this->_mDetails as &$detail)
			if($detail != $purgeDetail)
				$temp_details[] = $detail;
			else{
				$this->_mTotal -= $purgeDetail->getTotal();
				if($purgeDetail instanceof DocProductDetail){
					$lot = $purgeDetail->getLot();
					if($lot instanceof NegativeLot){
						$negative = $lot->getNegativeQuantity();
						$lot->decrease(-1 * $negative);
						$lot->setNegativeQuantity(0);
					}
				}
			}
				
		$this->_mDetails = $temp_details;
	}
	
	/**
	 * Saves the document's data in the database.
	 *
	 * Only applies if the document's status property has the PersistDocument::IN_PROGRESS value.
	 */
	public function save(){
		if($this->_mStatus == PersistDocument::IN_PROGRESS){
			$this->validateMainProperties();
			$this->insert();
			$this->_mStatus = PersistDocument::CREATED;
		}
	}
	
	/**
	 * Cancels the document and reverts its effects.
	 *
	 * If the document's status property value equals to PersistDocument::IN_PROGRESS the discard() method
	 * is called. If it equals to PersistDocument::CREATED, all the details are cancelled and subsequent
	 * actions are taken.
	 */
	public function cancel(){
		if($this->_mStatus == PersistDocument::IN_PROGRESS)
			$this->discard();
		elseif($this->_mStatus == PersistDocument::CREATED){
			foreach($this->_mDetails as $detail)
				if(!$detail->isCancellable())
					throw new Exception('Lotes en este documento ya fueron alterados, no se puede anular.');
					
			foreach($this->_mDetails as &$detail)
				$detail->cancel();
				
			$this->_mStatus = PersistDocument::CANCELLED;
		}
	}
	
	/**
	 * Returns a document with the details belonging to the requested page.
	 *
	 * The total_pages and total_items parameters are necessary to return their respective values.
	 * @param integer $id
	 * @param integer $page
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 */
	abstract static public function getInstance($id, $page, &$total_pages, &$total_items);
	
	/**
	 * Validates the document's main properties.
	 *
	 * The details property must not be empty.
	 * @throws Exception
	 */
	protected function validateMainProperties(){
		if(empty($this->_mDetails))
			throw new Exception('No hay ningun detalle.');
	}
}


/**
 * Represents a detail in a document.
 * @package Document
 * @author Roberto Oliveros
 */
abstract class DocumentDetail{
	/**
	 * Holds the detail's quantity.
	 *
	 * @var integer
	 */
	protected $_mQuantity;
	
	/**
	 * Holds the detail's item price.
	 *
	 * @var float
	 */
	protected $_mPrice;
	
	/**
	 * Constructs the deatail with the provided quantity and price.
	 *
	 * @param integer $quantity
	 * @param float $price
	 */
	public function __construct($quantity, $price){
		Number::validatePositiveInteger($quantity, 'Cantidad inv&aacute;lida.');
		Number::validateFloat($price, 'Precio inv&aacute;lido.');
		
		$this->_mQuantity = $quantity;
		$this->_mPrice = $price;
	}
	
	/**
	 * Returns the detail's quantity.
	 *
	 * @return integer
	 */
	public function getQuantity(){
		return $this->_mQuantity;
	}
	
	/**
	 * Returns the detail's item price.
	 *
	 * @return float
	 */
	public function getPrice(){
		return $this->_mPrice;
	}
	
	/**
	 * Returns the detail's grand total.
	 *
	 * Returns the detail's quantity * price.
	 * @return float
	 */
	public function getTotal(){
		return $this->_mQuantity * $this->_mPrice;
	}
	
	/**
	 * Saves the detail's data in the database.
	 *
	 * The document is where this detail belongs to. The number parameter is necessary to keep the order in
	 * which all the details where created.
	 * @param Document $doc
	 * @param integer $number
	 */
	public function save(Document $doc, $number){
		Persist::validateObjectFromDatabase($doc);
		Number::validatePositiveInteger($number, 'N&uacute;mero de pagina inv&aacute;lido.');
		$this->insert($doc, $number);
	}
	
	/**
	 * Returns the detail's id.
	 * @return string
	 */
	abstract public function getId();
	
	/**
	 * Shows the detail's data for displaying.
	 *
	 * Returns an array with the detail's data.
	 * @return array
	 */
	abstract public function show();
	
	/**
	 * Increase the detail's quantity.
	 *
	 * @param integer $quantity
	 */
	abstract public function increase($quantity);
	
	/**
	 * Undoes every action previously taken.
	 *
	 */
	abstract public function cancel();
	
	/**
	 * Returns true if the detail can be cancelled.
	 * @return boolean
	 */
	abstract public function isCancellable();
	
	/**
	 * Inserts the detail's data in the database.
	 *
	 * @param Document $doc
	 * @param integer $number
	 */
	abstract protected function insert(Document $doc, $number);
}


/**
 * Represents a document detail with a bonus.
 * @package Document
 * @author Roberto Oliveros
 */
class DocBonusDetail extends DocumentDetail{
	/**
	 * Holds the detail's bonus.
	 *
	 * @var Bonus
	 */
	private $_mBonus;
	
	/**
	 * Constructs the detail with the provided data.
	 *
	 * @param Bonus $bonus
	 * @param float $price
	 */
	public function __construct(Bonus $bonus, $price){
		parent::__construct(1, $price);
		
		Persist::validateObjectFromDatabase($bonus);
		$this->_mBonus = $bonus;
	}
	
	/**
	 * Returns the id of the detail.
	 *
	 * @return string
	 */
	public function getId(){
		return 'bon' . $this->_mBonus->getProduct()->getId();
	}
	
	/**
	 * Returns the detail's bonus.
	 *
	 * @return Bonus
	 */
	public function getBonus(){
		return $this->_mBonus;
	}
	
	/**
	 * Returns an array with the detail's data.
	 *
	 * The array contains the fields id, product, packaging, quantity, price, total. All the others fields
	 * are blank.
	 * @return array
	 */
	public function show(){
		$product = $this->_mBonus->getProduct();
		
		return array('id' => $this->getId(), 'bar_code' => '', 'manufacturer' => '',
				'product' => $product->getName(), 'packaging' => $product->getPackaging(),
				'um' => '', 'quantity' => $this->_mQuantity, 'price' => $this->_mPrice,
				'total' => $this->getTotal(), 'expiration_date' => '');
	}
	
	/**
	 * Does nothing, just to fulfill the abstraction.
	 *
	 * @param integer $quantity
	 */
	public function increase($quantity){
		// Do nothing.
	}
	
	/**
	 * Does nothing, just to fulfill the abstraction.
	 *
	 */
	public function cancel(){
		// Do nothing.
	}
	
	/**
	 * Returns true. Always can be cancellable.
	 *
	 * @return boolean
	 */
	public function isCancellable(){
		return true;
	}
	
	/**
	 * Inserts the detail's data in the database.
	 *
	 * The document is where this detail belongs to. The number parameter is necessary to keep the order in
	 * which all the details where created.
	 * @param Document $doc
	 * @param integer $number
	 */
	protected function insert(Document $doc, $number){
		DocBonusDetailDAM::insert($this, $doc, $number);
	}
}


/**
 * Represents a detail containing a product in document.
 * @package Document
 * @author Roberto Oliveros
 */
class DocProductDetail extends DocumentDetail{
	/**
	 * Holds the detail's lot.
	 *
	 * @var Lot
	 */
	private $_mLot;
	
	/**
	 * Holds the detail's transaction.
	 *
	 * @var Transaction
	 */
	private $_mTransaction;
	
	/**
	 * The reserve of the detail's product.
	 *
	 * @var Reserve
	 */
	private $_mReserve;
	
	/**
	 * Constructs the detail with the provided data.
	 *
	 * Warning! if the method is not called from the database layer take note of the following instructions
	 * please: Note that if the transaction is an instance of Entry class the detail must only receive a
	 * Persist::IN_PROGRESS Lot or NegativeLot. If it is an instance of Withdraw class, it needs a Reserve
	 * to work. Sorry.
	 * @param Lot $lot
	 * @param Transaction $transaction
	 * @param Reserve $reserve
	 * @param integer $quantity
	 * @param float $price
	 */
	public function __construct(Lot $lot, Transaction $transaction, $quantity, $price, Reserve $reserve = NULL){
		parent::__construct($quantity, $price);
		
		if(!is_null($reserve))
			Persist::validateObjectFromDatabase($reserve);
			
		$this->_mLot = $lot;
		$this->_mTransaction = $transaction;
		$this->_mReserve = $reserve;
	}
	
	/**
	 * Returns the detail's id.
	 *
	 * @return string
	 */
	public function getId(){
		return $this->_mLot->getProduct()->getId() . $this->_mLot->getId();
	}
	
	/**
	 * Returns the detail's lot.
	 *
	 * @return Lot
	 */
	public function getLot(){
		return $this->_mLot;
	}
	
	/**
	 * Returns the detail product's reserve.
	 *
	 * @return Reserve
	 */
	public function getReserve(){
		return $this->_mReserve;
	}
	
	/**
	 * Returns an array with the detail's data.
	 *
	 * The array contains the fields id, bar_code, manufacturer, product, packaging, unit of measure,
	 * quantity, price, total, expiration_date.
	 * @return array
	 */
	public function show(){
		$product = $this->_mLot->getProduct();
		$manufacturer = $product->getManufacturer();
		$um = $product->getUnitOfMeasure();
		$expiration_date =
				(is_null($this->_mLot->getExpirationDate())) ? 'N/A' : $this->_mLot->getExpirationDate();

		return array('id' => $this->getId(), 'bar_code' => $product->getBarCode(),
				'manufacturer' => $manufacturer->getName(), 'product' => $product->getName(),
				'packaging' => $product->getPackaging(), 'um' => $um->getName(),
				'quantity' => $this->_mQuantity, 'price' => $this->_mPrice, 'total' => $this->getTotal(),
				'expiration_date' => $expiration_date);
	}
	
	/**
	 * Increases the detail's quantity property.
	 *
	 * @param integer $quantity
	 */
	public function increase($quantity){
		Number::validatePositiveInteger($quantity, 'Cantidad inv&aacute;lida.');
		$this->_mQuantity += $quantity;
		if($this->_mTransaction instanceof Entry)
			$this->_mLot->increase($quantity);
	}
	
	/**
	 * Cancels the details action.
	 *
	 * Cancels the detail's transaction previous taken action.
	 */
	public function cancel(){
		$this->_mTransaction->cancel($this);
	}
	
	/**
	 * Returns true if the details previous action can be cancel.
	 *
	 * @return boolean
	 */
	public function isCancellable(){
		return $this->_mTransaction->isCancellable($this);
	}
	
	/**
	 * Inserts the detail's data into the database.
	 *
	 * The document is where this detail belongs to. The number parameter is necessary to keep the order in
	 * which all the details where created.
	 * @param Document $doc
	 * @param integer $number
	 */
	protected function insert(Document $doc, $number){
		// Perform the inventory transaction.
		$this->_mTransaction->apply($this);
		DocProductDetailDAM::insert($this, $doc, $number);
	}
}


/**
 * Represents a product reserve in the inventory.
 * @package Document
 * @author Roberto Oliveros
 */
class Reserve extends Persist{
	/**
	 * Holds the internal id.
	 *
	 * @var integer
	 */
	private $_mId;
	
	/**
	 * Holds the reserve's lot.
	 *
	 * @var Lot
	 */
	private $_mLot;
	
	/**
	 * Holds the reserved quantity.
	 *
	 * @var integer
	 */
	private $_mQuantity;
	
	/**
	 * Holds the user who created the reserve.
	 *
	 * @var UserAccount
	 */
	private $_mUser;
	
	/**
	 * Holds the date in which the reserve was created.
	 *
	 * Date format: 'dd/mm/yyyy'.
	 * @var string
	 */
	private $_mDate;
	
	/**
	 * Constructs the reserve with the provided data.
	 *
	 * Must be called only from the database layer corresponding class. Use createReserve method instead if
	 * a new reserve is needed. Lack of experience, sorry.
	 * @param integer $id
	 * @param Lot $lot
	 * @param integer $quantity
	 * @param UserAccount $user
	 * @param string $date
	 * @throws Exception
	 */
	public function __construct($id, Lot $lot, $quantity, UserAccount $user, $date,
			$status = Persist::IN_PROGRESS){
		parent::__construct($status);
				
		try{
			Number::validatePositiveInteger($id, 'Id inv&aacute;lido.');
			Persist::validateObjectFromDatabase($lot);
			Number::validatePositiveInteger($quantity, 'Cantidad inv&aacute;lida.');
			Persist::validateObjectFromDatabase($user);
			Date::validateDate($date, 'Fecha inv&aacute;lida.');
		} catch(Exception $e){
			$et = new Exception('Interno: Llamando al metodo construct en Reserve con datos erroneos! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mId = $id;
		$this->_mLot = $lot;
		$this->_mQuantity = $quantity;
		$this->_mUser = $user;
		$this->_mDate = $date;
	}
	
	/**
	 * Returns the reserve's id.
	 *
	 * @return integer
	 */
	public function getId(){
		return $this->_mId;
	}
	
	/**
	 * Returns the reserve's lot.
	 *
	 * @return Lot
	 */
	public function getLot(){
		return $this->_mLot;
	}
	
	/**
	 * Returns the reserve's quantity.
	 *
	 * @return integer
	 */
	public function getQuantity(){
		return $this->_mQuantity;
	}
	
	/**
	 * Creates a reserve in the database.
	 *
	 * Creates a reserve in the database and returns the instance of it.
	 * @param Lot $lot
	 * @param integer $quantity
	 * @return Reserve
	 */
	public function createReserve(Lot $lot, $quantity){
		Persist::validateObjectFromDatabase($lot);
		Number::validatePositiveInteger($quantity, 'Cantidad inv&aacute;lida.');
		
		$helper = SessionHelper::getInstance();
		return ReserveDAM::insert($lot, $quantity, $helper->getUser(), date('d/m/Y'));
	}
	
	/**
	 * Returns an instance of a reserve with database data.
	 *
	 * Returns NULL if there was no match of the provided id in the database.
	 * @param integer $id
	 * @return Reserve
	 */
	static public function getInstance($id){
		Number::validatePositiveInteger($id, 'Id inv&aacute;lido.');
		return ReserveDAM::getInstance($id);
	}
	
	/**
	 * Deletes the reserve from the database.
	 *
	 * Returns true on success. Otherwise false due dependencies.
	 * @param Reserve $obj
	 * @return boolean
	 */
	static public function delete(Reserve $obj){
		self::validateObjectFromDatabase($obj);
		return ReserveDAM::delete($obj);
	}
}


/**
 * Represents the correlative numbers invoices use to operate.
 * @package Document
 * @author Roberto Oliveros
 */
class Correlative extends Persist{
	/**
	 * Holds the serial number of the correlative.
	 *
	 * @var string
	 */
	private $_mSerialNumber;
	
	/**
	 * Flag that indicates if the correlative is the default one.
	 *
	 * @var boolean
	 */
	private $_mDefault;
	
	/**
	 * Holds the correlative's resolution number.
	 *
	 * @var string
	 */
	private $_mResolutionNumber;
	
	/**
	 * Holds the date of the correlative's resolution.
	 *
	 * Date format: 'dd/mm/yyyy'.
	 * @var string
	 */
	private $_mResolutionDate;
	
	/**
	 * Holds the first of the correlative's range of numbers.
	 *
	 * @var integer
	 */
	private $_mInitialNumber = 0;
	
	/**
	 * Holds the last of the correlative's range of numbers.
	 *
	 * @var integer
	 */
	private $_mFinalNumber = 0;
	
	/**
	 * Holds the current of the correlative's range of numbers.
	 *
	 * @var integer
	 */
	private $_mCurrentNumber;
	
	/**
	 * Construct the correlative with the provided data.
	 * 
	 * Parameters must be set only if called from the database layer.
	 * @param string $serialNumber
	 * @param boolean $default
	 * @param integer $currentNumber
	 * @param integer $status
	 * @throws Exception
	 */
	public function __construct($serialNumber = NULL, $default = false, $currentNumber = 0,
			$status = Persist::IN_PROGRESS){
		parent::__construct($status);
		
		if(!is_null($serialNumber))
			try{
				String::validateString($serialNumber, 'N&uacute;mero de serie inv&aacute;lido.');
			} catch(Exception $e){
				$et = new Exception('Interno: Llamando al metodo construct en Correlative con datos ' .
						'erroneos! ' . $e->getMessage());
				throw $et;
			}
			
		if($currentNumber !== 0)
			try{
				Number::validatePositiveInteger($currentNumber, 'N&uacute;mero actual inv&aacute;lido.');
			} catch(Exception $e){
				$et = new Exception('Interno: Llamando al metodo construct en Correlative con datos ' .
						'erroneos! ' . $e->getMessage());
				throw $et;
			}
			
		$this->_mSerialNumber = $serialNumber;
		$this->_mDefault = (boolean)$default;
		$this->_mCurrentNumber = $currentNumber;
	}
	
	/**
	 * Returns the correlative's serial number.
	 *
	 * @return string
	 */
	public function getSerialNumber(){
		return $this->_mSerialNumber;
	}
	
	/**
	 * Returns the default flag value.
	 *
	 * Returns true if this is the default correlative.
	 * @return boolean
	 */
	public function isDefault(){
		return $this->_mDefault;
	}
	
	/**
	 * Returns the correlative's resolution number.
	 *
	 * @return string
	 */
	public function getResolutionNumber(){
		return $this->_mResolutionNumber;
	}
	
	/**
	 * Returns the correlative's resolution date.
	 *
	 * @return string
	 */
	public function getResolutionDate(){
		return $this->_mResolutionDate;
	}
	
	/**
	 * Returns the first of the correlative's range of numbers.
	 *
	 * @return integer
	 */
	public function getInitialNumber(){
		return $this->_mInitialNumber;
	}
	
	/**
	 * Returns the last of the correlative's range of numbers.
	 *
	 * @return integer
	 */
	public function getFinalNumber(){
		return $this->_mFinalNumber;
	}
	
	/**
	 * Returns the current of the correlative's range of numbers.
	 *
	 * @return integer
	 */
	public function getCurrentNumber(){
		return $this->_mCurrentNumber;
	}
	
	/**
	 * Returns the next to be used in the correlative's range of numbers.
	 *
	 * Only applies when the object's status property is set to Persist::CREATED.
	 * @return integer
	 */
	public function getNextNumber(){
		if($this->_mStatus == Persist::CREATED)
			return CorrelativeDAM::getNextNumber($this);
		else
			return 0;
	}
	
	/**
	 * Sets the correlative's serial number.
	 *
	 * Only applies if the object's status property is set to Persist::IN_PROGRESS.
	 * @param string $serialNumber
	 */
	public function setSerialNumber($serialNumber){
		if($this->_mStatus == Persist::IN_PROGRESS){
			String::validateString($serialNumber, 'N&uacute;mero de serie inv&aacute;lido.');
			$this->verifySerialNumber($serialNumber);
			$this->_mSerialNumber = $serialNumber;
		}
	}
	
	/**
	 * Sets the correlative's resolution number
	 *
	 * @param string $number
	 */
	public function setResolutionNumber($number){
		String::validateString($number, 'N&uacute;mero de resoluci&oacute;n inv&aacute;lido.');
		$this->_mResolutionNumber = $number;
	}
	
	/**
	 * Sets the correlative's resolution date.
	 *
	 * @param string $date
	 */
	public function setResolutionDate($date){
		Date::validateDate($date, 'Fecha de resoluci&oacute;n inv&aacute;lida.');
		$this->_mResolutionDate = $date;
	}
	
	/**
	 * Sets the first of the correlative's range of numbers.
	 *
	 * @param integer $number
	 */
	public function setInitialNumber($number){
		Number::validatePositiveInteger($number, 'N&uacute;mero inv&aacute;lido.');
		$this->_mInitialNumber = $number;
	}
	
	/**
	 * Sets the last of the correlative's range of numbers.
	 *
	 * @param integer $number
	 */
	public function setFinalNumber($number){
		Number::validatePositiveInteger($number, 'N&uacute;mero inv&aacute;lido.');
		$this->_mFinalNumber = $number;
	}
	
	/**
	 * Set the object's properties.
	 * 
	 * Must be call only from the database layer corresponding class. The object's status must be set to
	 * Persist::CREATED in the constructor method too.
	 * @param string $resolutionNumber
	 * @param string $resolutionDate
	 * @param integer $initialNumber
	 * @param integer $finalNumber
	 * @throws Exception
	 */
	public function setData($resolutionNumber, $resolutionDate, $initialNumber, $finalNumber){
		try{
			String::validateString($resolutionNumber, 'N&uacute;mero de resoluci&oacute;n inv&aacute;lido.');
			Date::validateDate($resolutionDate, 'Fecha de resoluci&oacute;n inv&aacute;lida.');
			Number::validatePositiveInteger($initialNumber, 'N&uacute;mero inicial inv&aacute;lido.');
			Number::validatePositiveInteger($finalNumber, 'N&uacute;mero final inv&aacute;lido.');
		} catch(Exception $e){
			$et = new Exception('Interno: Llamando al metodo setData en Correlative con datos erroneos! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mResolutionNumber = $resolutionNumber;
		$this->_mResolutionDate = $resolutionDate;
		$this->_mInitialNumber = $initialNumber;
		$this->_mFinalNumber = $finalNumber;
	}
	
	/**
	 * Saves the correlative's data in the database.
	 *
	 * Only applies if the object's status property is set to Persist::IN_PROGRESS.
	 */
	public function save(){
		if($this->_mStatus == Persist::IN_PROGRESS){
			$this->validateMainProperties();
			$this->validateRangeNumbers($this->_mInitialNumber, $this->_mFinalNumber);
			
			// Verify if there are records in the database.
			$no_records = CorrelativeDAM::isEmpty();
			if(!$no_records)
				$this->verifySerialNumber($this->_mSerialNumber);
				
			CorrelativeDAM::insert($this);
			$this->_mStatus = Persist::CREATED;
			
			// If there were no records, make this one the default.
			if($no_records)
				self::makeDefault($this);
		}
	}
	
	/**
	 * Returns an instance of a correlative with database data.
	 *
	 * Returns NULL if there was no match for the provided serial number in the database.
	 * @param string $serialNumber
	 * @return Correlative
	 */
	static public function getInstance($serialNumber){
		String::validateString($serialNumber, 'N&uacute;mero de serie inv&aacute;lido.');
		return CorrelativeDAM::getInstance($serialNumber);
	}
	
	/**
	 * Returns the default correlative.
	 *
	 * @return Correlative
	 */
	static public function getDefaultInstance(){
		return CorrelativeDAM::getDefaultInstance();
	}
	
	/**
	 * Makes default the provided correlative.
	 *
	 * @param Correlative $obj
	 */
	static public function makeDefault(Correlative $obj){
		Persist::validateObjectFromDatabase($obj);
		CorrelativeDAM::makeDefault($obj);
	}
	
	/**
	 * Deletes the correlative from the database.
	 *
	 * Returns true on success. Otherwise false due dependencies. Can't be deleted if it is the default
	 * correlative.
	 * @param Correlative $obj
	 * @return boolean
	 * @throws Exception
	 */
	static public function delete(Correlative $obj){
		self::validateObjectFromDatabase($obj);
		if($obj->isDefault())
			throw new Exception('Correlativo predeterminado, no se puede eliminar.');
		
		return CorrelativeDAM::delete($obj);
	}
	
	/**
	 * Validates the correlative main properties.
	 *
	 * Serial and resolution numbers must not be empty. Resolution date must be a valid date. And initial
	 * and final numbers must be greater than cero.
	 */
	private function validateMainProperties(){
		String::validateString($this->_mSerialNumber, 'N&uacute;mero de serie inv&aacute;lido.');
		String::validateString($this->_mResolutionNumber,
				'N&uacute;mero de resoluci&oacute;n inv&aacute;lido.');
		Date::validateDate($this->_mResolutionDate, 'Fecha de resoluci&oacute;n inv&aacute;lida.');
		Number::validatePositiveInteger($this->_mInitialNumber, 'N&uacute;mero inicial inv&aacute;lido.');
		Number::validatePositiveInteger($this->_mFinalNumber, 'N&uacute;mero final inv&aacute;lido.');
	}
	
	/**
	 * Validates if the final number is greater than the initial.
	 *
	 * @param integer $initial
	 * @param integer $final
	 * @throws Exception
	 */
	private function validateRangeNumbers($initial, $final){
		if($initial >= $final)
			throw new Exception('N&uacute;mero inicial debe ser menor al n&uacute;mero final.');
	}
	
	/**
	 * Verifies if a correlative with the serial number already exists in the database.
	 *
	 * Throws an exception if it does.
	 * @param string $serialNumber
	 * @throws Exception
	 */
	private function verifySerialNumber($serialNumber){
		if(CorrelativeDAM::exists($serialNumber))
			throw new Exception('N&uacute;mero de serie ya existe.');
	}
}


/**
 * V.A.T. Value Added Tax. (I.V.A.)
 * @package Document
 * @author Roberto Oliveros
 */
class Vat{
	/**
	 * Holds the percentage value of the tax.
	 *
	 * @var float
	 */
	private $_mPercentage;
	
	/**
	 * Construct the vat with the provided percentage.
	 *
	 * Use getInstance method if an instance is required. Called this method only from the database layer
	 * corresponding class.
	 * @param float $percentage
	 */
	public function __construct($percentage){
		Number::validatePositiveFloat($percentage,
				'Interno: Llamando al metodo construct en Vat con datos erroneos! Porcentaje inv&aacute;lido.');
		
		$this->_mPercentage = $percentage;
	}
	
	/**
	 * Returns the percentage value of the tax.
	 *
	 * @return float
	 */
	public function getPercentage(){
		return $this->_mPercentage;
	}
	
	/**
	 * Sets the percentage value of the tax.
	 *
	 * @param float $value
	 */
	public function setPercentage($value){
		Number::validatePositiveFloat($value, 'Porcentaje inv&aacute;lido.');
		$this->_mPercentage = $value;
	}
	
	/**
	 * Updates the vat values in the database.
	 *
	 */
	public function save(){
		VatDAM::update($this);
	}
	
	/**
	 * Returns an instance of the V.A.T.
	 *
	 * @return Vat
	 */
	static public function getInstance(){
		return VatDAM::getInstance();
	}
}


/**
 * Represents a sales invoice.
 * @package Document
 * @author Roberto Oliveros
 */
class Invoice extends Document{
	/**
	 * Holds the invoice's number.
	 *
	 * @var integer
	 */
	private $_Number;
	
	/**
	 * Holds the invoice's correlative.
	 *
	 * @var Correlative
	 */
	private $_mCorrelative;
	
	/**
	 * Holds the invoice customer's nit.
	 *
	 * @var string
	 */
	private $_mNit;
	
	/**
	 * Holds the invoice customer's name.
	 *
	 * @var string
	 */
	private $_mName;
	
	/**
	 * Holds the Vat's percentage value.
	 *
	 * @var float
	 */
	private $_mVatValue;
	
	/**
	 * Holds from which cash register this invoice was emitted.
	 *
	 * @var CashRegister
	 */
	private $_mCashRegister;
	
	/**
	 * Holds the invoice's cash receipt.
	 *
	 * @var CashReceipt
	 */
	private $_mCashReceipt;
	
	/**
	 * Holds the invoice's additional discount.
	 *
	 * @var Discount
	 */
	private $_mDiscount;
	
	
	static public function getInstance($id, $page, &$total_pages, &$total_items){
		// Code here...
	}
	
	
	protected function insert(){
		// Code here...
	}
	
	protected function discard(){
		// Code here...
	}
}


/**
 * Represents an additional discount in an invoice.
 * @package Document
 * @author Roberto Oliveros
 */
class Discount extends Persist{
	/**
	 * Invoice in which the discount was created.
	 *
	 * @var Invoice
	 */
	private $_mInvoice;
	
	/**
	 * Holds the percentage value of the discount.
	 *
	 * @var float
	 */
	private $_mPercentage;
	
	/**
	 * Holds the user who created the discount.
	 *
	 * @var UserAccount
	 */
	private $_mUser;
	
	/**
	 * Constructs the discount with the provided data.
	 *
	 * @param UserAccount $user
	 * @param integer $status
	 */
	public function __construct(UserAccount $user, $status = Persist::IN_PROGRESS){
		parent::__construct($status);
		
		self::validateObjectFromDatabase($user);
		$this->_mUser = $user;
	}
	
	/**
	 * Returns the discount's invoice.
	 *
	 * @return invoice
	 */
	public function getInvoice(){
		return $this->_mInvoice;
	}
	
	/**
	 * Returns the discount's percentage value.
	 *
	 * @return float
	 */
	public function getPercentage(){
		return $this->_mPercentage;
	}
	
	/**
	 * Returns the discount's creator.
	 *
	 * @return UserAccount
	 */
	public function getUser(){
		return $this->_mUser;
	}
	
	/**
	 * Sets the discount's invoice.
	 *
	 * @param Invoice $obj
	 */
	public function setInvoice(Invoice $obj){
		if($obj->getStatus() != Persist::IN_PROGRESS)
			throw new Exception('Factura in&aacute;lida. La propiedad status es igual a Persist::IN_PROGRESS.');
		
		$this->_mInvoice = $obj;
	}
	
	/**
	 * Sets the discount's percentage value.
	 *
	 * @param float $value
	 */
	public function setPercentage($value){
		Number::validatePositiveFloat($value, 'Porcentage inv&aacute;lido.');
		$this->_mPercentage = $value;
	}
	
	/**
	 * Set the object's properties.
	 * 
	 * Must be call only from the database layer corresponding class. The object's status must be set to
	 * Persist::CREATED in the constructor method too.
	 * @param Invoice $invoice
	 * @param float $percentage
	 */
	public function setData(Invoice $invoice, $percentage){
		try{
			self::validateObjectFromDatabase($invoice);
			Number::validatePositiveFloat($percentage, 'Porcentage inv&aacute;lido.');
		} catch(Exception $e){
			$et = new Exception('Interno: Llamando al metodo setData en Discount con datos erroneos! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mInvoice = $invoice;
		$this->_mPercentage = $percentage;
	}
	
	/**
	 * Saves the discount's data in the database.
	 *
	 * Only applies if the object's status property is set to Persist::IN_PROGRESS.
	 */
	public function save(){
		if($this->_mStatus == Persist::IN_PROGRESS){
			$this->validateMainProperties();
			DiscountDAM::insert($this);
			$this->_mStatus = Persist::CREATED;
		}
	}
	
	/**
	 * Returns an instance of a discount from the database.
	 *
	 * Returns NULL if there was no match for the provided invoice in the database.
	 * @param Invoice $obj
	 * @return Discount
	 */
	static public function getInstance(Invoice $obj){
		self::validateObjectFromDatabase($obj);
		return DiscountDAM::getInstance($obj);
	}
	
	/**
	 * Validates the discount's main properties.
	 *
	 * Invoice must not be NULL and percentage must be greater than cero.
	 * @throws Exception
	 */
	private function validateMainProperties(){
		if(is_null($this->_mInvoice))
			throw new Exception('Factura inv&aacute;lida.');
			
		Number::validatePositiveFloat($this->_mPercentage, 'Porcentage inv&aacute;lido.');
	}
}
?>