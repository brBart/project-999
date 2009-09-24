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
 * For paging purposes.
 */
require_once('business/itemized.php');
/**
 * For accessing the database.
 */
require_once('data/document_dam.php');

/**
 * Defines common functionality for all the document derived classes.
 * @package Document
 * @author Roberto Oliveros
 */
abstract class Document extends PersistDocument implements Itemized{
	/**
	 * Holds the date in which the document was created.
	 *
	 * Date and time format: 'dd/mm/yyyy hh:mm:ss'.
	 * @var string
	 */
	private $_mDateTime;
	
	/**
	 * Holds the documents grand total.
	 *
	 * @var float
	 */
	private $_mTotal = 0.00;
	
	/**
	 * Holds the user who created the document.
	 *
	 * @var UserAccount
	 */
	private $_mUser;
	
	/**
	 * Array containing all the document details.
	 *
	 * @var array<DocumentDetail>
	 */
	protected $_mDetails = array();
	
	/**
	 * Constructs the document with the provided data.
	 *
	 * Arguments must be passed only when called from the database layer correponding class.
	 * @param string $dateTime
	 * @param UserAccount $user
	 * @param integer $id
	 * @param integer $status
	 * @throws Exception
	 */
	public function __construct($dateTime = NULL, UserAccount $user = NULL, $id = NULL,
			$status = PersistDocument::IN_PROGRESS){
		parent::__construct($id, $status);
		
		if(!is_null($dateTime)){
			try{
				Date::validateDateTime($dateTime, 'Fecha y hora inv&aacute;lida.');
			} catch(Exception $e){
				$et = new Exception('Interno: Llamando al metodo construct en Document con datos erroneos! ' .
						$e->getMessage());
				throw $et;
			}
			$this->_mDateTime = $dateTime;
		}
		else
			$this->_mDateTime = date('d/m/Y H:i:s');
		
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
	 * Returns the document's creation date and time.
	 *
	 * @return string
	 */
	public function getDateTime(){
		return $this->_mDateTime;
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
	 * @param float $total
	 * @param array<DocumentDetail> $details
	 * @throws Exception
	 */
	public function setData($total, $details){
		try{
			Number::validatePositiveFloat($total, 'Total inv&aacute;lido.');
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
		
		// For moving the modified detail to the last place.
		$temp_details = array();
		foreach($this->_mDetails as &$detail){
			if($detail->getId() != $newDetail->getId())
				$temp_details[] = $detail;
			else{
				$detail->increase($newDetail->getQuantity());
				
				// Must increase the reserve, if there is one...
				if($detail instanceof DocProductDetail){
					$reserve = $detail->getReserve();
					if(!is_null($reserve)){
						$new_reserve = $newDetail->getReserve();
						if(!is_null($new_reserve))
							$reserve->merge($new_reserve);
					}
				}
				
				$newDetail = $detail;
			}
		}
		
		$temp_details[] = $newDetail;
		$this->_mDetails = $temp_details;
	}
	
	/**
	 * Removes the detail from the document.
	 *
	 * If the detail has a negative lot, this will be modified to its original state meaning that its quantity
	 * will be returned to its negative value before it was modified by the EntryAdjustmentEvent class. Sorry.
	 * @param DocumentDetail $purgeDetail
	 */
	public function deleteDetail(DocumentDetail $purgeDetail){
		$temp_details = array();
		
		foreach($this->_mDetails as &$detail)
			if($detail->getId() != $purgeDetail->getId())
				$temp_details[] = $detail;
			else{
				$this->_mTotal -= $purgeDetail->getTotal();
				if($purgeDetail instanceof DocProductDetail){
					$lot = $purgeDetail->getLot();
					if($lot instanceof NegativeLot){
						$negative = $lot->getNegativeQuantity();
						$lot->decrease(abs($negative));
						$lot->setNegativeQuantity(0);
						$lot->setPrice(0.00);
						$lot->setExpirationDate(NULL);
					}
				}
			}
				
		$this->_mDetails = $temp_details;
	}
	
	/**
	 * Saves the document's data in the database.
	 *
	 * Only applies if the document's status property has the PersistDocument::IN_PROGRESS value. Returns
	 * the new created id from the database on success.
	 * @return integer
	 */
	public function save(){
		if($this->_mStatus == PersistDocument::IN_PROGRESS){
			$this->validateMainProperties();
			$this->insert();
			$this->_mStatus = PersistDocument::CREATED;
			
			$i = 1;
			foreach($this->_mDetails as &$detail)
				$detail->save($this, $i++);
				
			return $this->_mId;
		}
	}
	
	/**
	 * Cancels the document and reverts its effects.
	 *
	 * The user argument registers who authorized the action. Only applies if the document status property is
	 * set to PersistDocument::CREATED.
	 * @param UserAccount $user
	 */
	public function cancel(UserAccount $user){
		if($this->_mStatus == PersistDocument::CREATED){
			self::validateObjectFromDatabase($user);
			
			$this->cancelDetails();
			$this->updateToCancelled($user);
			$this->_mStatus = PersistDocument::CANCELLED;
		}
	}
	
	
	/**
	 * Returns a document with the details corresponding to the requested page.
	 *
	 * The total_pages and total_items arguments are necessary to return their respective values. If no page
	 * argument is passed, all the details are returned.
	 * @param integer $id
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 */
	abstract static public function getInstance($id, &$total_pages = 0, &$total_items = 0, $page= 0);
	
	/**
	 * Validates the document's main properties.
	 *
	 * The details property must not be empty.
	 * @throws Exception
	 */
	protected function validateMainProperties(){
		if(empty($this->_mDetails))
			throw new ValidateException('No hay ningun detalle.', 'bar_code');
	}
	
	/**
	 * Cancels the document's details and reverts its effects.
	 *
	 * @throws Exception
	 */
	protected function cancelDetails(){
		foreach($this->_mDetails as $detail)
			if(!$detail->isCancellable())
				throw new Exception('Lotes en este documento ya fueron alterados, no se puede anular.');
				
		foreach($this->_mDetails as &$detail)
			$detail->cancel();
	}
	
	abstract protected function updateToCancelled(UserAccount $user);
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
		Number::validatePositiveNumber($quantity, 'Cantidad inv&aacute;lida.');
		Number::validateNumber($price, 'Precio inv&aacute;lido.');
		
		$this->_mQuantity = round($quantity);
		$this->_mPrice = round($price, 2);
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
		$lot = $this->_mLot;
		// For new lots that has there id equal to cero must be distinguish.
		$lot_id = (!$lot->getId()) ? str_replace('/', '', $lot->getExpirationDate()) : $lot->getId();
		
		/**
		 * @todo Check if this is OK.
		 * For distinguish new lots with same product same expiration date but with different price.
		 */
		if($this->_mTransaction instanceof Entry)
			$lot_id = $lot_id . number_format($this->_mPrice, 2);
		
		return $lot->getProduct()->getId() . $lot_id; 
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
		Number::validatePositiveNumber($quantity, 'Cantidad inv&aacute;lida.');
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
	 * Must be called only from the database layer corresponding class. Use create method instead if
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
			Number::validatePositiveNumber($quantity, 'Cantidad inv&aacute;lida.');
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
	 * Merge the provided reserve's quantity to this object quantity.
	 *
	 * Took the provided reserve's quantity and adds it to this reserve's quantity property. Then it deletes
	 * the the provided reserve from database.
	 * @param Reserve $obj
	 */
	public function merge(Reserve $obj){
		self::validateObjectFromDatabase($obj);
		$this->_mQuantity += $obj->getQuantity();
		ReserveDAM::increase($this, $obj->getQuantity());
		ReserveDAM::delete($obj);
	}
	
	/**
	 * Creates and reserve the provided quantity from the product in the database.
	 *
	 * Creates a reserve in the database and returns the instance of it.
	 * @param Lot $lot
	 * @param integer $quantity
	 * @return Reserve
	 */
	static public function create(Lot $lot, $quantity){
		Persist::validateObjectFromDatabase($lot);
		Number::validatePositiveNumber($quantity, 'Cantidad inv&aacute;lida.');
		
		$lot->reserve($quantity);
		$product = $lot->getProduct();
		Inventory::reserve($product, $quantity);
		
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
	 * @param Reserve $obj
	 */
	static public function delete(Reserve $obj){
		self::validateObjectFromDatabase($obj);
		
		$quantity = $obj->getQuantity();
		$lot = $obj->getLot();
		$lot->decreaseReserve($quantity);
		$product = $lot->getProduct();
		Inventory::decreaseReserve($product, $quantity);
		
		ReserveDAM::delete($obj);
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
	 * Returns the serial number of the default correlative.
	 *
	 * @return string
	 */
	static public function getDefaultSerialNumber(){
		return CorrelativeDAM::getDefaultSerialNumber();
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
	 * Throws an exception due dependencies. Can't be deleted if it is the default correlative.
	 * @param Correlative $obj
	 * @return boolean
	 * @throws Exception
	 */
	static public function delete(Correlative $obj){
		self::validateObjectFromDatabase($obj);
		if($obj->isDefault())
			throw new Exception('Correlativo predeterminado, no se puede eliminar.');
		
		if(!CorrelativeDAM::delete($obj))
			throw new Exception('Correlativo tiene dependencias (facturas) y no se puede eliminar.');
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
		try{
			Number::validatePositiveFloat($percentage, 'Porcentaje inv&aacute;lido.');
		} catch(Exception $e){
			$et = new Exception('Interno: Llamando al metodo construct en Vat con datos erroneos! ' .
					$e->getMessage());
			throw $et;
		}
		
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
	private $_mCustomerNit;
	
	/**
	 * Holds the invoice customer's name.
	 *
	 * @var string
	 */
	private $_mCustomerName;
	
	/**
	 * Holds the Vat's percentage value.
	 *
	 * @var float
	 */
	private $_mVatPercentage;
	
	/**
	 * Holds from which cash register this invoice was emitted.
	 *
	 * @var CashRegister
	 */
	private $_mCashRegister;
	
	/**
	 * Holds the flag that indicates if the invoice has a corresponding receipt.
	 *
	 * @var boolean
	 */
	private $_mHasCashReceipt = false;
	
	/**
	 * Holds the invoice's additional discount.
	 *
	 * @var Discount
	 */
	private $_mDiscount;
	
	/**
	 * Constructs the invoice with the provided data.
	 *
	 * Arguments must be passed only when called from the database layer correponding class. If a new Invoice
	 * its been created (PersistDocument::IN_PROGRESS) the cash register must be open, otherwise it doesn't
	 * matter because it is an already created (PersistDocument::CREATED) invoice.
	 * @param CashRegister $cashRegister
	 * @param string $dateTime
	 * @param UserAccount $user
	 * @param integer $id
	 * @param integer $status
	 * @throws Exception
	 */
	public function __construct(CashRegister $cashRegister, $dateTime = NULL, UserAccount $user = NULL,
			$id = NULL, $status = PersistDocument::IN_PROGRESS){
		parent::__construct($dateTime, $user, $id, $status);
		
		if($this->_mStatus == PersistDocument::IN_PROGRESS && !$cashRegister->isOpen())
			throw new Exception('Caja ya esta cerrada.');
				
		$this->_mCashRegister = $cashRegister;
	}
	
	/**
	 * Returns the invoice's number.
	 *
	 * @return integer
	 */
	public function getNumber(){
		return $this->_mNumber;
	}
	
	/**
	 * Returns the invoice's correlative.
	 *
	 * @return Correlative
	 */
	public function getCorrelative(){
		return $this->_mCorrelative;
	}
	
	/**
	 * Returns the invoice customer's nit.
	 *
	 * @return string
	 */
	public function getCustomerNit(){
		return $this->_mCustomerNit;
	}
	
	/**
	 * Returns the invoice customer's name.
	 *
	 * @return string
	 */
	public function getCustomerName(){
		return $this->_mCustomerName;
	}
	
	/**
	 * Returns the invoice vat's percentage.
	 *
	 * @return float
	 */
	public function getVatPercentage(){
		return $this->_mVatPercentage;
	}
	
	/**
	 * Returns the invoice's cash register.
	 *
	 * @return CashRegister
	 */
	public function getCashRegister(){
		return $this->_mCashRegister;
	}
	
	/**
	 * Returns the quantity of certain product in the invoice.
	 *
	 * @param Product $product
	 * @return integer
	 */
	public function getProductQuantity(Product $product){
		$quantity = 0;
		
		foreach($this->_mDetails as $detail)
			if($detail instanceof DocProductDetail)
				if($detail->getLot()->getProduct()->getId() == $product->getId())
					$quantity += $detail->getQuantity();
					
		return $quantity;
	}
	
	/**
	 * Returns the invoice's subtotal.
	 *
	 * @return float
	 */
	public function getSubTotal(){
		return parent::getTotal();
	}
	
	/**
	 * Returns the discount value applied to the invoice subtotal.
	 *
	 * @return float
	 */
	public function getTotalDiscount(){
		/**
		 * @todo Verify it it the result needs rounding.
		 */
		$discount = (is_null($this->_mDiscount)) ? 0.00 :
				$this->getSubTotal() * ($this->_mDiscount->getPercentage() / 100);
		return $discount;
	}
	
	/**
	 * Returns the document's grand total.
	 *
	 * @return float
	 */
	public function getTotal(){
		return parent::getTotal() - $this->getTotalDiscount();
	}
	
	/**
	 * Returns a document bonus detail of certain product.
	 *
	 * @param Product $product
	 * @return DocBonusDetail
	 */
	public function getBonusDetail(Product $product){
		foreach($this->_mDetails as $detail)
			if($detail instanceof DocBonusDetail){
				$bonus = $detail->getBonus();
				if($bonus->getProduct()->getId() == $product->getId())
					return $detail;
			}
	}
	
	/**
	 * Sets the hasCashReceipt flag value.
	 *
	 * This method is called when assigning the corresponding receipt.
	 * @param boolean $bool
	 */
	public function hasCashReceipt($bool){	
		$this->_mHasCashReceipt = (boolean)$bool;
	}
	
	/**
	 * Sets the invoice customer's nit and name.
	 *
	 * @param Customer $obj
	 */
	public function setCustomer(Customer $obj){
		self::validateObjectFromDatabase($obj);
		$this->_mCustomerNit = $obj->getNit();
		$this->_mCustomerName = $obj->getName();
	}
	
	/**
	 * Sets the invoice's discount.
	 *
	 * @param Discount $obj
	 */
	public function setDiscount(Discount $obj){
		self::validateNewObject($obj);		
		$this->_mDiscount = $obj;
		$obj->setInvoice($this);
	}
	
	/**
	 * Sets the invoice's properties.
	 *
	 * Must be called only from the database layer corresponding class. The object's status must be set to
	 * PersistDocument::CREATED in the constructor method too.
	 * @param integer $number
	 * @param Correlative $correlative
	 * @param string $nit
	 * @param string $name
	 * @param float $vatPercentage
	 * @param Discount $discount
	 * @param float $total
	 * @param array<DocumentDetail> $details
	 * @throws Exception
	 */
	public function setData($number, Correlative $correlative, $nit, $vatPercentage, $total, $details,
			$name = NULL, Discount $discount = NULL){
		parent::setData($total, $details);
		
		try{
			Number::validatePositiveInteger($number, 'N&uacute;mero de factura inv&aacute;lido.');
			self::validateObjectFromDatabase($correlative);
			Number::validatePositiveFloat($vatPercentage, 'Porcentage Iva inv&aacute;lido.');
			if(!is_null($discount))
				self::validateObjectFromDatabase($discount);
		} catch(Exception $e){
			$et = new Exception('Interno: Llamando al metodo setData en Invoice con datos erroneos! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mNumber = $number;
		$this->_mCorrelative = $correlative;
		$this->_mCustomerNit = $nit;
		$this->_mCustomerName = $name;
		$this->_mVatPercentage = $vatPercentage;
		$this->_mDiscount = $discount;
	}
	
	/**
	 * Saves the document's data in the database.
	 *
	 * Only applies if the document's status property has the PersistDocument::IN_PROGRESS value. Returns
	 * the new created id from the database on success. NOTE: Call only from the corresponding receipt!
	 * @return integer
	 */
	public function save(){
		if($this->_mStatus == PersistDocument::IN_PROGRESS){
			$serial_number = Correlative::getDefaultSerialNumber();
			if(is_null($serial_number))
				throw new Exception('No hay correlativo predeterminado.');
			
			$this->_mCorrelative = Correlative::getInstance($serial_number);
			$this->validateMainProperties();
			
			$this->_mVatPercentage = Vat::getInstance()->getPercentage();
			$current_number = $this->_mCorrelative->getCurrentNumber();
			if($this->_mCorrelative->getFinalNumber() == $current_number)
				throw new Exception('Se alcanzo el final del correlativo, favor de cambiarlo.');			
			$this->_mNumber = $this->_mCorrelative->getNextNumber();
			$this->insert();	
			$this->_mStatus = PersistDocument::CREATED;
			// Watch out, if any error occurs the database has already been altereted!
			$i = 1;
			foreach($this->_mDetails as &$detail)
				$detail->save($this, $i++);
			if(!is_null($this->_mDiscount))
				$this->_mDiscount->save();
			return $this->_mId;
		}
	}
	
	/**
	 * Does not save the invoice in the database and reverts its effects.
	 *
	 * Only applies if the object's status property is set to PersistDocument::IN_PROGRESS.
	 */
	public function discard(){
		if($this->_mStatus == Persist::IN_PROGRESS)
			foreach($this->_mDetails as &$detail)
				if($detail instanceof DocProductDetail)
					// RetailEvent isn't called because sales aren't needed anymore.
					WithdrawEvent::cancel($this, $detail);
				else
					$this->deleteDetail($detail);
	}
	
	/**
	 * Cancels the document and reverts its effects.
	 *
	 * The user argument registers who authorized the action. Only applies if the document status property is
	 * set to PersistDocument::CREATED.
	 * @param UserAccount $user
	 * @throws Exception
	 */
	public function cancel(UserAccount $user){
		if($this->_mStatus == PersistDocument::CREATED){
			self::validateObjectFromDatabase($user);
			
			if(!$this->_mCashRegister->isOpen())
				throw new Exception('Caja ya esta cerrada, no se puede anular.');
			
			$this->cancelDetails();
			$receipt = CashReceipt::getInstance($this);
			$receipt->cancel($user);
			$this->updateToCancelled($user);
			$this->_mStatus = PersistDocument::CANCELLED;
		}
	}
	
	/**
	 * Returns an invoice with the details corresponding to the requested page.
	 *
	 * The total_pages and total_items arguments are necessary to return their respective values.  If no page
	 * argument is passed, all the details are returned. Returns NULL if there was no match for the provided
	 * id in the database. 
	 * @param integer $id
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return Invoice
	 */
	static public function getInstance($id, &$total_pages = 0, &$total_items = 0, $page = 0){
		Number::validatePositiveInteger($id, 'Id inv&aacute;lido.');
		if($page !== 0)
			Number::validatePositiveInteger($page, 'N&uacute;mero de pagina inv&aacute;lido.');
			
		return InvoiceDAM::getInstance($id, $total_pages, $total_items, $page);
	}
	
	/**
	 * Returns the invoice identifier.
	 *
	 * Returns 0 if there was no match for the provided serial number and number in the database.
	 * @param string $serialNumber
	 * @param integer $number
	 * @return integer
	 */
	static public function getInvoiceId($serialNumber, $number){
		String::validateString($serialNumber, 'N&uacute;mero de serie inv&aacute;lido.');
		Number::validatePositiveInteger($number, 'N&uacute;mero de factura inv&aacute;lido.');
		return InvoiceDAM::getId($serialNumber, $number);
	}
	
	/**
	 * Validates the invoice main properties.
	 *
	 * This method call its parent validateMainProperties method. And nit must not be empty, cash receipt and
	 * correlative must not be NULL.
	 * @throws Exception
	 */
	protected function validateMainProperties(){
		parent::validateMainProperties();
		
		String::validateString($this->_mCustomerNit, 'Nit inv&aacute;lido.');
		if(is_null($this->_mCorrelative))
			throw new Exception('No hay ningun correlativo predeterminado.');
		if(!$this->_mHasCashReceipt)
			throw new Exception('Interno: Favor crear el recibo para poder cancelar la factura.');
	}
	
	/**
	 * Inserts the invoice data in the database.
	 *
	 * @throws Exception
	 */
	protected function insert(){
		$this->_mId = InvoiceDAM::insert($this);
	}
	
	/**
	 * Updates the document to cancelled in the database.
	 *
	 * @param UserAccount $user
	 */
	protected function updateToCancelled(UserAccount $user){
		InvoiceDAM::cancel($this, $user, date('d/m/Y'));
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
		self::validateNewObject($obj);
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
	 * @throws Exception
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


/**
 * Represents a purchase return document.
 * @package Document
 * @author Roberto Oliveros
 */
class PurchaseReturn extends Document{
	/**
	 * Holds the supplier for whom the return is being made.
	 *
	 * @var Supplier
	 */
	private $_mSupplier;
	
	/**
	 * Holds the supplier direct contact person name.
	 *
	 * @var string
	 */
	private $_mContact;
	
	/**
	 * Holds an explanation of why the creation of the document.
	 *
	 * @var string
	 */
	private $_mReason;
	
	/**
	 * Returns the purchase return's supplier.
	 *
	 * @return Supplier
	 */
	public function getSupplier(){
		return $this->_mSupplier;
	}
	
	/**
	 * Returns the supplier direct contact person name.
	 *
	 * @return string
	 */
	public function getContact(){
		return $this->_mContact;
	}
	
	/**
	 * Returns the reason of the document.
	 *
	 * @return string
	 */
	public function getReason(){
		return $this->_mReason;
	}
	
	/**
	 * Sets the purchase return supplier and the contact's name.
	 *
	 * @param Supplier $obj
	 */
	public function setSupplier(Supplier $obj){
		self::validateObjectFromDatabase($obj);
		$this->_mSupplier = $obj;
		$this->_mContact = $obj->getContact();
	}
	
	/**
	 * Sets the contact's name.
	 *
	 * @param string $contact
	 */
	public function setContact($contact){
		$this->_mContact = $contact;
	}
	
	/**
	 * Sets the purchase return reason.
	 *
	 * @param string $reason
	 */
	public function setReason($reason){
		String::validateString($reason, 'Motivo inv&aacute;lido.');
		$this->_mReason = $reason;
	}
	
	/**
	 * Sets the purchase return's properties.
	 *
	 * Must be called only from the database layer corresponding class. The object's status must be set to
	 * PersistDocument::CREATED in the constructor method too.
	 * @param Supplier $supplier
	 * @param string $contact
	 * @param string $reason
	 * @param float $total
	 * @param array<DocProductDetail> $details
	 * @throws Exception
	 */
	public function setData(Supplier $supplier, $reason, $total, $details, $contact = NULL){
		parent::setData($total, $details);
		
		try{
			self::validateObjectFromDatabase($supplier);
			String::validateString($reason, 'Motivo inv&aacute;lido.');
		} catch(Exception $e){
			$et = new Exception('Interno: Llamando al metodo setData en PurchaseReturn con datos erroneos! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mSupplier = $supplier;
		$this->_mContact = $contact;
		$this->_mReason = $reason;
	}
	
	/**
	 * Does not save the purchase return in the database and reverts its effects.
	 *
	 * Only applies if the object's status property is set to PersistDocument::IN_PROGRESS.
	 */
	public function discard(){
		if($this->_mStatus == Persist::IN_PROGRESS)
			foreach($this->_mDetails as &$detail)
				StrictWithdrawEvent::cancel($this, $detail);
	}
	
	/**
	 * Returns a purchase return with the details corresponding to the requested page.
	 *
	 * The total_pages and total_items arguments are necessary to return their respective values.  If no page
	 * argument is passed, all the details are returned. Returns NULL if there was no match for the provided id
	 * in the database. 
	 * @param integer $id
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return PurchaseReturn
	 */
	static public function getInstance($id, &$total_pages = 0, &$total_items = 0, $page = 0){
		Number::validatePositiveInteger($id, 'Id inv&aacute;lido.');
		if($page !== 0)
			Number::validatePositiveInteger($page, 'N&uacute;mero de pagina inv&aacute;lido.');
			
		return PurchaseReturnDAM::getInstance($id, $total_pages, $total_items, $page);
	}
	
	/**
	 * Validates the purchase return's main properties.
	 *
	 * Supplier must not be null and reason must not be empty.
	 * @throws Exception
	 */
	protected function validateMainProperties(){
		parent::validateMainProperties();
		
		if(is_null($this->_mSupplier))
			throw new Exception('Proveedor inv&aacute;lido.');
		String::validateString($this->_mReason, 'Motivo inv&aacute;lido.');
	}
	
	/**
	 * Inserts the purchase return's data in the database.
	 *
	 */
	protected function insert(){
		$this->_mId = PurchaseReturnDAM::insert($this);
	}
	
	/**
	 * Updates the document to cancelled in the database.
	 * 
	 * @param UserAccount $user
	 */
	protected function updateToCancelled(UserAccount $user){
		PurchaseReturnDAM::cancel($this, $user, date('d/m/Y'));
	}
}


/**
 * Represents a shipment document.
 * @package Document
 * @author Roberto Oliveros
 */
class Shipment extends Document{
	/**
	 * Holds the branch for whom the shipment is being made.
	 *
	 * @var Branch
	 */
	private $_mBranch;
	
	/**
	 * Holds the branch direct contact person name.
	 *
	 * @var string
	 */
	private $_mContact;
	
	/**
	 * Returns the shipment's branch.
	 *
	 * @return Branch
	 */
	public function getBranch(){
		return $this->_mBranch;
	}
	
	/**
	 * Returns the supplier direct contact person name.
	 *
	 * @return string
	 */
	public function getContact(){
		return $this->_mContact;
	}
	
	/**
	 * Sets the shipment's branch and contact name.
	 *
	 * @param Branch $obj
	 */
	public function setBranch(Branch $obj){
		self::validateObjectFromDatabase($obj);
		$this->_mBranch = $obj;
		$this->_mContact = $obj->getContact();
	}
	
	/**
	 * Sets the shipment's contact name.
	 *
	 * @param string $contact
	 */
	public function setContact($contact){
		$this->_mContact = $contact;
	}
	
	/**
	 * Sets the shipment's properties.
	 *
	 * Must be called only from the database layer corresponding class. The object's status must be set to
	 * PersistDocument::CREATED in the constructor method too.
	 * @param Branch $branch
	 * @param float $total
	 * @param array<DocProductDetail> $details
	 * @param string $contact
	 * @throws Exception
	 */
	public function setData(Branch $branch, $total, $details, $contact = NULL){
		parent::setData($total, $details);
		
		try{
			self::validateObjectFromDatabase($branch);
		} catch(Exception $e){
			$et = new Exception('Interno: Llamando al metodo setData en Shipment con datos erroneos! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mBranch = $branch;
		$this->_mContact = $contact;
	}
	
	/**
	 * Does not save the shipment in the database and reverts its effects.
	 *
	 * Only applies if the object's status property is set to PersistDocument::IN_PROGRESS.
	 */
	public function discard(){
		if($this->_mStatus == Persist::IN_PROGRESS)
			foreach($this->_mDetails as &$detail)
				WithdrawEvent::cancel($this, $detail);
	}
	
	/**
	 * Returns a shipment with the details corresponding to the requested page.
	 *
	 * The total_pages and total_items arguments are necessary to return their respective values.  If no page
	 * argument is passed, all the details are returned. Returns NULL if there was no match for the provided id
	 * in the database. 
	 * @param integer $id
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return Shipment
	 */
	static public function getInstance($id, &$total_pages = 0, &$total_items = 0, $page = 0){
		Number::validatePositiveInteger($id, 'Id inv&aacute;lido.');
		if($page !== 0)
			Number::validatePositiveInteger($page, 'N&uacute;mero de pagina inv&aacute;lido.');
			
		return ShipmentDAM::getInstance($id, $total_pages, $total_items, $page);
	}
	
	/**
	 * Validates the shipment's main properties.
	 *
	 * Branch must not be null.
	 * @throws Exception
	 */
	protected function validateMainProperties(){
		parent::validateMainProperties();
		
		if(is_null($this->_mBranch))
			throw new Exception('Sucursal inv&aacute;lida.');
	}
	
	/**
	 * Inserts the shipment's data in the database.
	 *
	 */
	protected function insert(){
		$this->_mId = ShipmentDAM::insert($this);
	}
	
	/**
	 * Updates the document to cancelled in the database.
	 * 
	 * @param UserAccount $user
	 */
	protected function updateToCancelled(UserAccount $user){
		ShipmentDAM::cancel($this, $user, date('d/m/Y'));
	}
}


/**
 * Represents a purchase receipt document.
 * @package Document
 * @author Roberto Oliveros
 */
class Receipt extends Document{
	/**
	 * Holds the supplier from whom the merchandise is being received.
	 *
	 * @var Supplier
	 */
	private $_mSupplier;
	
	/**
	 * Holds the supplier's shipment document number.
	 *
	 * @var string
	 */
	private $_mShipmentNumber;
	
	/**
	 * Holds the supplie's shipment document total amount.
	 *
	 * @var float
	 */
	private $_mShipmentTotal;
	
	/**
	 * Returns the receipt's supplier.
	 *
	 * @return Supplier
	 */
	public function getSupplier(){
		return $this->_mSupplier;
	}
	
	/**
	 * Returns the supplier's shipment document number.
	 *
	 * @return string
	 */
	public function getShipmentNumber(){
		return $this->_mShipmentNumber;
	}
	
	/**
	 * Returns the supplier's shipment document total amount.
	 *
	 * @return float
	 */
	public function getShipmentTotal(){
		return $this->_mShipmentTotal;
	}
	
	/**
	 * Sets the receipt's supplier.
	 *
	 * @param Supplier $obj
	 */
	public function setSupplier(Supplier $obj = NULL){
		$this->_mSupplier = $obj;
		if(is_null($obj))
			throw new ValidateException('Seleccione un proveedor.');
	}
	
	/**
	 * Sets the receipt's shipment number.
	 *
	 * @param string $number
	 */
	public function setShipmentNumber($number){
		$this->_mShipmentNumber = $number;
		String::validateString($number, 'N&uacute;mero de envio inv&aacute;lido.');
	}
	
	/**
	 * Sets the receipt's shipment total amount.
	 *
	 * @param float $amount
	 */
	public function setShipmentTotal($amount){
		$this->_mShipmentTotal = $amount;
		Number::validatePositiveNumber($amount, 'Total del envio inv&aacute;lido.');
	}
	
	/**
	 * Sets the receipt's properties.
	 *
	 * Must be called only from the database layer corresponding class. The object's status must be set to
	 * PersistDocument::CREATED in the constructor method too.
	 * @param Supplier $supplier
	 * @param string $shipmentNumber
	 * @param float $shipmentTotal
	 * @param float $total
	 * @param array<DocProductDetail> $details
	 * @throws Exception
	 */
	public function setData(Supplier $supplier, $shipmentNumber, $total, $details){
		parent::setData($total, $details);
		
		try{
			self::validateObjectFromDatabase($supplier);
			String::validateString($shipmentNumber, 'N&uacute;mero de envio inv&aacute;lido.');
		} catch(Exception $e){
			$et = new Exception('Interno: Llamando al metodo setData en Receipt con datos erroneos! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mSupplier = $supplier;
		$this->_mShipmentNumber = $shipmentNumber;
		$this->_mShipmentTotal = $total;
	}
	
	/**
	 * Does not save the receipt in the database and reverts its effects.
	 *
	 * Only applies if the object's status property is set to PersistDocument::IN_PROGRESS.
	 */
	public function discard(){
		if($this->_mStatus == Persist::IN_PROGRESS)
			foreach($this->_mDetails as &$detail)
				EntryEvent::cancel($this, $detail);
	}
	
	/**
	 * Returns a receipt with the details corresponding to the requested page.
	 *
	 * The total_pages and total_items arguments are necessary to return their respective values.  If no page
	 * argument is passed, all the details are returned. Returns NULL if there was no match for the provided id
	 * in the database. 
	 * @param integer $id
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return Receipt
	 */
	static public function getInstance($id, &$total_pages = 0, &$total_items = 0, $page = 0){
		Number::validatePositiveInteger($id, 'Id inv&aacute;lido.');
		if($page !== 0)
			Number::validatePositiveInteger($page, 'N&uacute;mero de pagina inv&aacute;lido.');
			
		return ReceiptDAM::getInstance($id, $total_pages, $total_items, $page);
	}
	
	/**
	 * Validates the receipt's main properties.
	 *
	 * Supplier must not be NULL, the shipment number must not be empty and the shimpment total amount must
	 * match with the receipt total amount.
	 */
	protected function validateMainProperties(){
		parent::validateMainProperties();
		
		if(is_null($this->_mSupplier))
			throw new ValidateException('Proveedor inv&aacute;lido.', 'organization_id');
			
		String::validateString($this->_mShipmentNumber, 'N&uacute;mero de envio inv&aacute;lido.',
				'shipment_number');
		
		if(bccomp($this->_mShipmentTotal, $this->getTotal(), 2) != 0)
			throw new ValidateException('El total del envio no coincide con el del recibo.',
					'shipment_total');
	}
	
	/**
	 * Inserts the receipt's data in the database.
	 *
	 */
	protected function insert(){
		$this->_mId = ReceiptDAM::insert($this);
	}
	
	/**
	 * Updates the document to cancelled in the database.
	 * 
	 * @param UserAccount $user
	 */
	protected function updateToCancelled(UserAccount $user){
		ReceiptDAM::cancel($this, $user, date('d/m/Y'));
	}
}


/**
 * Defines common functionality for the inventory adjustment documents.
 * @package Document
 * @author Roberto Oliveros
 */
abstract class AdjustmentDocument extends Document{
/**
	 * Holds the reason of why the creation of the document.
	 *
	 * @var string
	 */
	private $_mReason;
	
	/**
	 * Returns the document's reason.
	 *
	 * @return string
	 */
	public function getReason(){
		return $this->_mReason;
	}
	
	/**
	 * Sets the document's reason.
	 *
	 * @param string $reason
	 */
	public function setReason($reason){
		String::validateString($reason, 'Motivo inv&aacute;lido.');
		$this->_mReason = $reason;
	}
	
	/**
	 * Sets the document's properties.
	 *
	 * Must be called only from the database layer corresponding class. The object's status must be set to
	 * PersistDocument::CREATED in the constructor method too.
	 * @param string $reason
	 * @param float $total
	 * @param array<DocProductDetail> $details
	 * @throws Exception
	 */
	public function setData($reason, $total, $details){
		parent::setData($total, $details);
		
		try{
			String::validateString($reason, 'Motivo inv&aacute;lido.');
		} catch(Exception $e){
			$et = new Exception('Interno: Llamando al metodo setData en EntryIA con datos erroneos! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mReason = $reason;
	}
	
	/**
	 * Validates the document's main properties.
	 *
	 * Reason must not be empty.
	 */
	protected function validateMainProperties(){
		parent::validateMainProperties();
			
		String::validateString($this->_mReason, 'Motivo inv&aacute;lido.');
	}
}


/**
 * Represents an entry inventory adjustment document.
 * @package Document
 * @author Roberto Oliveros
 */
class EntryIA extends AdjustmentDocument{
	/**
	 * Does not save the document in the database and reverts its effects.
	 *
	 * Only applies if the object's status property is set to PersistDocument::IN_PROGRESS.
	 */
	public function discard(){
		if($this->_mStatus == Persist::IN_PROGRESS)
			foreach($this->_mDetails as &$detail)
				EntryAdjustmentEvent::cancel($this, $detail);
	}
	
	/**
	 * Returns an entry inventory adjustment document with the details corresponding to the requested page.
	 *
	 * The total_pages and total_items arguments are necessary to return their respective values.  If no page
	 * argument is passed, all the details are returned. Returns NULL if there was no match for the provided id
	 * in the database. 
	 * @param integer $id
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return EntryIA
	 */
	static public function getInstance($id, &$total_pages = 0, &$total_items = 0, $page = 0){
		Number::validatePositiveInteger($id, 'Id inv&aacute;lido.');
		if($page !== 0)
			Number::validatePositiveInteger($page, 'N&uacute;mero de pagina inv&aacute;lido.');
			
		return EntryIADAM::getInstance($id, $total_pages, $total_items, $page);
	}
	
	/**
	 * Inserts the document's data in the database.
	 *
	 */
	protected function insert(){
		$this->_mId = EntryIADAM::insert($this);
	}
	
	/**
	 * Updates the document to cancelled in the database.
	 * 
	 * @param UserAccount $user
	 */
	protected function updateToCancelled(UserAccount $user){
		EntryIADAM::cancel($this, $user, date('d/m/Y'));
	}
}


/**
 * Represents a withdraw inventory adjustment document.
 * @package Document
 * @author Roberto Oliveros
 */
class WithdrawIA extends AdjustmentDocument{
	/**
	 * Does not save the document in the database and reverts its effects.
	 *
	 * Only applies if the object's status property is set to PersistDocument::IN_PROGRESS.
	 */
	public function discard(){
		if($this->_mStatus == Persist::IN_PROGRESS)
			foreach($this->_mDetails as &$detail)
				StrictWithdrawEvent::cancel($this, $detail);
	}
	
	/**
	 * Returns a withdraw inventory adjustment document with the details corresponding to the requested page.
	 *
	 * The total_pages and total_items arguments are necessary to return their respective values.  If no page
	 * argument is passed, all the details are returned. Returns NULL if there was no match for the provided id
	 * in the database. 
	 * @param integer $id
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return WithdrawIA
	 */
	static public function getInstance($id, &$total_pages = 0, &$total_items = 0, $page = 0){
		Number::validatePositiveInteger($id, 'Id inv&aacute;lido.');
		if($page !== 0)
			Number::validatePositiveInteger($page, 'N&uacute;mero de pagina inv&aacute;lido.');
			
		return WithdrawIADAM::getInstance($id, $total_pages, $total_items, $page);
	}
	
	/**
	 * Inserts the document's data in the database.
	 *
	 */
	protected function insert(){
		$this->_mId = WithdrawIADAM::insert($this);
	}
	
	/**
	 * Updates the document to cancelled in the database.
	 * 
	 * @param UserAccount $user
	 */
	protected function updateToCancelled(UserAccount $user){
		WithdrawIADAM::cancel($this, $user, date('d/m/Y'));
	}
}
?>