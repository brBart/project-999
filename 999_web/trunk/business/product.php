<?php
/**
 * Library containing all the classes regarding products.
 * @package Product
 * @author Roberto Oliveros
 */

/**
 * Includes the Persist package.
 */
require_once('business/persist.php');
/**
 * Includes the ProductDAM package for accessing the database.
 */
require_once('data/product_dam.php');

/**
 * Represents a unit of measure for a certain product.
 * @package Product
 * @author Roberto Oliveros
 */
class UnitOfMeasure extends Identifier{
	/**
	 * Constructs the object with the provided id and status.
	 *
	 * Parameters must be set only if the method is called from the database layer.
	 * @param integer $id
	 * @param integer $status
	 */
	public function __construct($id = NULL, $status = Persist::IN_PROGRESS){
		parent::__construct($id, $status);
	}
	
	/**
	 * Returns an instance of a unit of measure.
	 * 
	 * Returns NULL if there was no match in the database.
	 * @param integer $id
	 * @return UnitOfMeasure
	 */
	static public function getInstance($id){
		self::validateId($id);
		return UnitOfMeasureDAM::getInstance($id);
	}
	
	/**
	 * Deletes the unit of measure from database.
	 * 
	 * Returns true confirming the deletion, otherwise false due dependencies.
	 * @param UnitOfMeasure $obj
	 * @return boolean
	 */
	static public function delete(UnitOfMeasure $obj){
		self::validateObjectForDelete($obj);
		return UnitOfMeasureDAM::delete($obj);
	}
	
	/**
	 * Inserts the object's data to the database.
	 *
	 * Returns the new created id from the database.
	 * @return integer
	 */
	protected function insert(){
		return UnitOfMeasureDAM::insert($this);
	}
	
	/**
	 * Updates the object's data in the database.
	 *
	 */
	protected function update(){
		UnitOfMeasureDAM::update($this);
	}
}


/**
 * Represents the manufacturer of a certain product.
 * @package Product
 * @author Roberto Oliveros
 */
class Manufacturer extends Identifier{
/**
	 * Constructs the object with the provided id and status.
	 *
	 * Parameters must be set only if the method is called from the database layer.
	 * @param integer $id
	 * @param integer $status
	 */
	public function __construct($id = NULL, $status = Persist::IN_PROGRESS){
		parent::__construct($id, $status);
	}
	
	/**
	 * Returns an instance of a manufacturer.
	 * 
	 * Returns NULL if there was no match in the database.
	 * @param integer $id
	 * @return Manufacturer
	 */
	static public function getInstance($id){
		self::validateId($id);
		return ManufacturerDAM::getInstance($id);
	}
	
	/**
	 * Deletes the manufacturer from the database.
	 * 
	 * Returns true confirming the deletion, otherwise false due dependencies.
	 * @param Manufacturer $obj
	 * @return boolean
	 */
	static public function delete(Manufacturer $obj){
		self::validateObjectForDelete($obj);
		return ManufacturerDAM::delete($obj);
	}
	
	/**
	 * Inserts the object's data to the database.
	 *
	 * Returns the new created id from the database.
	 * @return integer
	 */
	protected function insert(){
		return ManufacturerDAM::insert($this);
	}
	
	/**
	 * Updates the object's data in the database.
	 *
	 */
	protected function update(){
		ManufacturerDAM::update($this);
	}
}


/**
 * Class with necessary functionality for controlling a product's inventory.
 * @package Product
 * @author Roberto Oliveros
 */
class Inventory{
	/**
	 * Returns the available quantity of the inventory's product.
	 *
	 * @param Product $product
	 * @return integer
	 */
	static public function getAvailable(Product $product){
		self::validateProduct($product);
		return InventoryDAM::getAvailable($product);
	}
	
	/**
	 * Returns the quantity on hand of the inventory's product.
	 *
	 * @param Product $product
	 * @return integer
	 */
	static public function getQuantity(Product $product){
		self::validateProduct($product);
		return InventoryDAM::getQuantity($product);
	}
	
	/**
	 * Returns the necessary lots to fulfill the requested quantity of units.
	 *
	 * Returns an array containing the necessary lots with available quantity. If the requested quantity
	 * cannot be fulfilled new lots are created and included.
	 * @param Product $product
	 * @param integer $reqUnitsQuantity
	 * @return array<Lot>
	 */
	static public function getLots(Product $product, $reqUnitsQuantity){
		self::validateProduct($product);
		self::validateQuantity($reqUnitsQuantity);
		
		// Get the lots from the database with available stock.
		$in_stock_lots = InventoryDAM::getLots($product);
		// The returned qrray with the lots which fulfill the requested quantity of units.
		$lots = array();

		$lot = current($in_stock_lots);
		do{
			// Verify the available quantity of the lot.
			if(!$lot)
				$available = 0;
			else
				$available = $lot->getAvailable();

			// If there is available quantity but doesn't fulfill the requested quantity.
			if($available > 0 && $available < $reqUnitsQuantity){
				$lots[] = $lot;
				$reqUnitsQuantity = $reqUnitsQuantity - $available;
			// If the available quantity does fulfill the requested quantity.
			}elseif($available > 0 && $available >= $reqUnitsQuantity){
				$lots[] = $lot;
				$reqUnitsQuantity = 0;
			// Otherwise create a new empty lot.
			}else {
				$new_lot = new Lot($this->_mProduct, 0, 0);
				$this->addLot($new_lot);
				$lots[] = $new_lot;
				$reqUnitsQuantity = 0;
			}
			
			$lot = next($in_stock_lots);
		} while($reqUnitsQuantity > 0);
		
		return $lots;
	}
	
	/**
	 * Returns the necessary existing lots with negative quantity.
	 *
	 * Returns an array including lots with negative quantities. If the requested quantity cannot be fulfilled
	 * new lots are created and included.
	 * @param Product $product
	 * @param integer $reqUnitsQuantity
	 * @return array<Lot>
	 */
	static public function getNegativeLots(Product $product, $reqUnitsQuantity){
		self::validateProduct($product);
		self::validateQuantity($reqUnitsQuantity);
		
		// Get the negative lots from the database.
		$negative_lots = InventoryDAM::getNegativeLots($product);
		// The returned array with the negative lots.
		$lots = array();
		
		$lot = current($negative_lots);
		do{
			// Verify the negative quantity of the lot.
			if(!$lot)
				$negative = 0;
			else
				$negative = -1 * $lot->getAvailable();

			// If it has a negative quantity but doesn't fulfill the requested quantity.
			if($negative > 0 && $negative < $reqUnitsQuantity){
				$lots[] = $lot;
				$reqUnitsQuantity = $reqUnitsQuantity - $negative;
			// If it does fulfill the requested quantity.
			}elseif($negative > 0 && $negative >= $reqUnitsQuantity){
				$lots[] = $lot;
				$reqUnitsQuantity = 0;
			// Otherwise create a new lot.
			}else {
				$lots[] = new Lot($this->_mProduct, 0, 0);
				$reqUnitsQuantity = 0;
			}
			
			$lot = next($negative_lots);
		} while($reqUnitsQuantity > 0);
		
		return $lots;
	}
	
	/**
	 * Reserves the specified quantity from the provided product in the database.
	 *
	 * @param Product $product
	 * @param integer $quantity
	 */
	static public function reserve(Product $product, $quantity){
		self::validateProduct($product);
		self::validateQuantity($quantity);
		InventoryDAM::reserve($product, $quantity);
	}
	
	/**
	 * Decreases the product's reserve by the quantity provided.
	 *
	 * @param Product $product
	 * @param integer $quantity
	 */
	static public function decreaseReserve(Product $product, $quantity){
		self::validateProduct($product);
		self::validateQuantity($quantity);
		InventoryDAM::decreaseReserve($product, $quantity);
	}
	
	/**
	 * Decreases the product's quantity.
	 *
	 * @param Product $product
	 * @param integer $quantity
	 */
	static public function decrease(Product $product, $quantity){
		self::validateProduct($product);
		self::validateQuantity($quantity);
		InventoryDAM::decrease($product, $quantity);
	}
	
	/**
	 * Increases the product's quantity.
	 *
	 * @param Product $product
	 * @param integer $quantity
	 */
	static public function increase(Product $product, $quantity){
		self::validateProduct($product);
		self::validateQuantity($quantity);
		InventoryDAM::increase($product, $quantity);
	}
	
	/**
	 * Validates if the product's status property other than Persist::IN_PROGRESS.
	 *
	 * Throws an exception if it is not.
	 * @param Product $product
	 */
	static private function validateProduct(Product $product){
		if($product->getStatus() == PersistObject::IN_PROGRESS)
			throw new Exception('Persist::IN_PROGRESS product provided.');
	}
	
	/**
	 * Validates the quantity.
	 *
	 * Must be greater than cero. Otherwise it throws an exception.
	 * @param integer $quantity
	 */
	static private function validateQuantity($quantity){
		if(!is_int($quantity) || $quantity < 1)
			throw new Exception('Cantidad inv&aacute;lida.');
	}
}



class productDetail{
	/**
	 * Holds the product's supplier.
	 *
	 * @var Supplier
	 */
	private $_mSupplier;
	
	/**
	 * Holds the supplier's product's id.
	 *
	 * @var string
	 */
	private $_mProductId;
	
	/**
	 * Holds the detail actual status.
	 *
	 * @var integer
	 */
	private $_mStatus;
	
	/**
	 * Holds the next action that must be taken on the detail, e.g. delete, save.
	 *
	 * @var string
	 */
	private $_mAction;
}
?>