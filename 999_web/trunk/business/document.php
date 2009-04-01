<?php
/**
 * Library defining all the necessary classes regarding documents.
 * @package Document
 * @author Roberto Oliveros
 */

/**
 * Defines common functionality for all the document derived classes.
 * @package Document
 * @author Roberto Oliveros
 */
abstract class Document extends PersisDocument{
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
abstract class Detail{
	/**
	 * Holds the detail's quantity.
	 *
	 * @var integer
	 */
	private $_mQuantity;
	
	/**
	 * Holds the detail's item price.
	 *
	 * @var float
	 */
	private $_mPrice;
	
	/**
	 * Constructs the deatail with the provided quantity and price.
	 *
	 * @param integer $quantity
	 * @param float $price
	 */
	public function __construct($quantity, $price){
		$this->validateQuantity($quantity);
		$this->validatePrice($price);
		
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
	 * Saves the detail's data in the database.
	 *
	 * The document is where this detail belongs to. The number parameter is necessary to keep the order in
	 * which all the details where created.
	 * @param Document $document
	 * @param integer $number
	 */
	abstract public function save(Document $document, $number);
	
	/**
	 * Undoes every action previously taken.
	 *
	 */
	abstract public function cancel();
	
	/**
	 * Validates the detail's quantity.
	 *
	 * Must be greater than cero. Otherwise it throws an exception.
	 * @param integer $quantity
	 */
	private function validateQuantity($quantity){
		if(!is_int($quantity) || $quantity < 1)
			throw new Exception('Cantidad inv&aacute;lida.');
	}
	
	/**
	 * Validates the detail's price.
	 *
	 * Must be greater or equal to cero. Otherwise it throws an exception.
	 * @param float $price
	 */
	private function validatePrice($price){
		if(!is_float($price) || $price < 0)
			throw new Exception('Precio inv&accute;lido.');
	}
}
?>