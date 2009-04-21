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
	 * Holds the document's identifier.
	 *
	 * @var integer
	 */
	private $_mId;
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
		Number::validateQuantity($quantity);
		Number::validatePrice($price);
		
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
		$this->validateNumber($number);
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
	
	/**
	 * Validates the provided number.
	 *
	 * Must be greater than cero. Otherwise it throws an exception.
	 * @param integer $number
	 * @throws Exception
	 */
	private function validateNumber($number){
		if(!is_int($number) || $number < 1)
			throw new Exception('Internal error, number invalid!');
	}
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
	 * @param Lot $lot
	 * @param Transaction $transaction
	 * @param Reserve $reserve
	 * @param integer $quantity
	 * @param float $price
	 */
	public function __construct(Lot $lot, Transaction $transaction, Reserve $reserve, $quantity, $price){
		parent::__construct($quantity, $price);
		
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
		Number::validateQuantity($quantity);
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
class Reserve{
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
	public function __construct($id, Lot $lot, $quantity, UserAccount $user, $date){
		try{
			Identifier::validateId($id);
			Persist::validateObjectFromDatabase($lot);
			Number::validateQuantity($quantity);
			Persist::validateObjectFromDatabase($user);
			Date::validateDate($date);
		} catch(Exception $e){
			$et = new Exception('Internal error, calling Reserve construct method with bad data! ' .
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
		Number::validateQuantity($quantity);
		
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
		Identifier::validateId($id);
		return ReserveDAM::getInstance($id);
	}
	
	/**
	 * Deletes the reserve from the database.
	 *
	 * Returns true on success. Otherwise false due dependencies.
	 * @param Reserve $obj
	 * @return boolean
	 * @throws Exception
	 */
	static public function delete(Reserve $obj){
		if(!ReserveDAM::exists($obj))
			throw new Exception('Internal error, trying to delete an nonexistent object.');
			
		return ReserveDAM::delete($obj);
	}
}
?>