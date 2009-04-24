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
 * For date validation.
 */
require_once('business/validator.php');
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
		self::validateObjectFromDatabase($obj);
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
		self::validateObjectFromDatabase($obj);
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
	 * Returns the available quantity of the product's inventory.
	 *
	 * @param Product $product
	 * @return integer
	 */
	static public function getAvailable(Product $product){
		Persist::validateObjectFromDatabase($product);
		return InventoryDAM::getAvailable($product);
	}
	
	/**
	 * Returns the quantity on hand of the product's inventory.
	 *
	 * @param Product $product
	 * @return integer
	 */
	static public function getQuantity(Product $product){
		Persist::validateObjectFromDatabase($product);
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
		Persist::validateObjectFromDatabase($product);
		Number::validateQuantity($reqUnitsQuantity);
		
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
				$new_lot = new Lot($product);
				$new_lot->save();
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
		Persist::validateObjectFromDatabase($product);
		Number::validateQuantity($reqUnitsQuantity);
		
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
				$lots[] = new Lot($product);
				$reqUnitsQuantity = 0;
			}
			
			$lot = next($negative_lots);
		} while($reqUnitsQuantity > 0);
		
		return $lots;
	}
	
	/**
	 * Returns an array with the details of the lots.
	 * 
	 * The passed parameters are returned with the sum quantity and the sum quantity available of all the
	 * product's lots.
	 * @param Product $product
	 * @param integer $quantity
	 * @param integer $available
	 * @return array
	 */
	static public function showLots(Product $product, &$quantity, &$available){
		Persist::validateObjectFromDatabase($product);
		
		$lots = InventoryDAM::getLots($product);
		
		foreach($lots as $lot){
			$lot_details[] = $lot->show();
			$quantity += $lot->getQuantity();
			$available += $lot->getAvailable();
		}
		
		return $lot_details;
	}
	
	/**
	 * Increases the product's quantity.
	 *
	 * @param Product $product
	 * @param integer $quantity
	 */
	static public function increase(Product $product, $quantity){
		Persist::validateObjectFromDatabase($product);
		Number::validateQuantity($quantity);
		InventoryDAM::increase($product, $quantity);
	}
	
	/**
	 * Decreases the product's quantity.
	 *
	 * @param Product $product
	 * @param integer $quantity
	 */
	static public function decrease(Product $product, $quantity){
		Persist::validateObjectFromDatabase($product);
		Number::validateQuantity($quantity);
		InventoryDAM::decrease($product, $quantity);
	}
	
	/**
	 * Reserves the specified quantity for the provided product in the database.
	 *
	 * @param Product $product
	 * @param integer $quantity
	 */
	static public function reserve(Product $product, $quantity){
		Persist::validateObjectFromDatabase($product);
		Number::validateQuantity($quantity);
		InventoryDAM::reserve($product, $quantity);
	}
	
	/**
	 * Decreases the product's reserve by the quantity provided.
	 *
	 * @param Product $product
	 * @param integer $quantity
	 */
	static public function decreaseReserve(Product $product, $quantity){
		Persist::validateObjectFromDatabase($product);
		Number::validateQuantity($quantity);
		InventoryDAM::decreaseReserve($product, $quantity);
	}
}


/**
 * Supplier which distributes a certain product. Contains the product's code(SKU) that the supplier
 * uses in its system.
 * @package Product
 * @author Roberto Oliveros
 */
class ProductSupplier extends Persist{
	/**
	 * Holds the product's supplier.
	 *
	 * @var Supplier
	 */
	private $_mSupplier;
	
	/**
	 * Holds the product's SKU.
	 *
	 * @var string
	 */
	private $_mProductSKU;
	
	/**
	 * Flag that indicates if the productsupplier has to be deteled.
	 *
	 * @var boolean
	 */
	private $_mDeleted = false;
	
	/**
	 * Constructs the productsupplier with the provided supplier, product's sku and status.
	 *
	 * @param Supplier $supplier
	 * @param string $productSKU
	 * @param integer $status
	 */
	public function __construct(Supplier $supplier, $productSKU, $status = Persist::IN_PROGRESS){
		parent::__construct($status);
		
		self::validateObjectFromDatabase($supplier);
		$this->validateProductSKU($productSKU);
		
		$this->_mSupplier = $supplier;
		$this->_mProductSKU = $productSKU;
	}
	
	/**
	 * Returns the productsupplier's id.
	 *
	 * @return string
	 */
	public function getId(){
		return $this->_mSupplier->getId() . $this->_mProductSKU;
	}
	
	/**
	 * Returns the supplier's product's SKU.
	 *
	 * @return string
	 */
	public function getProductSKU(){
		return $this->_mProductSKU;
	}
	
	/**
	 * Returns the detail's supplier.
	 *
	 * @return Supplier
	 */
	public function getSupplier(){
		return $this->_mSupplier;
	}
	
	/**
	 * Returns the value of the productsupplier's deleted flag.
	 *
	 * @return boolean
	 */
	public function isDeleted(){
		return $this->_mDeleted;
	}
	
	/**
	 * Retuns an array for displaying the detail's data.
	 *
	 * An array with the fields supplier and product_sku is returned.
	 * @return array
	 */
	public function show(){
		return array('supplier' => $this->_mSupplier->getName(), 'product_sku' => $this->_mProductSKU);
	}
	
	/**
	 * Sets the productsupplier's deleted flag to true.
	 *
	 * Must not be called. If you need to delete the object call Product::delete method instead and pass
	 * this object as the parameter.
	 */
	public function delete(){
		$this->_mDeleted = true;
	}
	
	/**
	 * Insert or deletes the productsupplier in the database.
	 *
	 * Depending of the status property or its deleted flag, the appropiate action is taken.
	 * @param Product $product
	 */
	public function commit(Product $product){
		self::validateObjectFromDatabase($product);
		
		if($this->_mStatus == Persist::IN_PROGRESS)
			ProductSupplierDAM::insert($product, $this);
		elseif($this->_mStatus == Persist::CREATED && $this->_mDeleted)
			ProductSupplierDAM::delete($product, $this);
	}
	
	/**
	 * Validates the provided product's sku.
	 * 
	 * Must not be empty. Otherwise it throws an exception.
	 * @param integer $ProductSKU
	 * @throws Exception
	 */
	public function validateProductSKU($productSKU){
		if(empty($productSKU))
			throw new Exception('Invalid product SKU!');
	}
}


/**
 * Represents a product in the inventory.
 * @package Product
 * @author Roberto Oliveros
 */
class Product extends Identifier{
	/**
	 * Holds the product's bar code.
	 *
	 * @var string
	 */
	private $_mBarCode;
	
	/**
	 * Holds the product's packaging.
	 *
	 * @var string
	 */
	private $_mPackaging;
	
	/**
	 * Holds the product's description.
	 *
	 * @var string
	 */
	private $_mDescription;
	
	/**
	 * Holds the product's unit of measure.
	 *
	 * @var UnitOfMeasure
	 */
	private $_mUM;
	
	/**
	 * Holds the product's manufacturer.
	 *
	 * @var Manufacturer
	 */
	private $_mManufacturer;
	
	/**
	 * Holds the product's price.
	 *
	 * @var float
	 */
	private $_mPrice;
	
	/**
	 * Holds the product's last price.
	 *
	 * @var float
	 */
	private $_mLastPrice;
	
	/**
	 * Holds flag that indicates whether the product is deactivated or not.
	 *
	 * @var boolean
	 */
	private $_mDeactivated = false;
	
	/**
	 * Holds the suppliers of this product.
	 *
	 * @var array<ProductSupplier>
	 */
	private $_mProductSuppliers = array();
	
	/**
	 * Constructs the product with the provided id and status.
	 *
	 * Paramaters must be set only if the method is called from the database layer.
	 * @param integer $id
	 * @param integer $status
	 */
	public function __construct($id = NULL, $status = Persist::IN_PROGRESS){
		parent::__construct($id, $status);
	}
	
	/**
	 * Returns the product's bar code.
	 *
	 * @return string
	 */
	public function getBarCode(){
		return $this->_mBarCode;
	}
	
	/**
	 * Returns the product's packaging.
	 *
	 * @return string
	 */
	public function getPackaging(){
		return $this->_mPackaging;
	}
	
	/**
	 * Returns the product's description.
	 *
	 * @return string
	 */
	public function getDescription(){
		return $this->_mDescription;
	}
	
	/**
	 * Returns the product's price.
	 *
	 * @return float
	 */
	public function getPrice(){
		return $this->_mPrice;
	}
	
	/**
	 * Returns the flag's value.
	 *
	 * @return boolean
	 */
	public function isDeactivated(){
		return $this->_mDeactivated;
	}
	
	/**
	 * Returns the product's unit of measure.
	 *
	 * @return UnitOfMeasure
	 */
	public function getUnitOfMeasure(){
		return $this->_mUM;
	}
	
	/**
	 * Returns the product's manufacturer.
	 *
	 * @return Manufacturer
	 */
	public function getManufacturer(){
		return $this->_mManufacturer;
	}
	
	/**
	 * Returns a product's supplier.
	 *
	 * Returns the detail which id matches the provided id.
	 * @param string $id
	 * @return ProductDetail
	 */
	public function getProductSupplier($id){
		$this->validateProductSupplierId($id);
		foreach($this->_mProductSuppliers as &$detail)
			if($detail->getId() == $id && !$detail->isDeleted())
				return $detail;
	}
	
	/**
	 * Returns an array of data with all the suppliers of this product.
	 *
	 * @return array
	 */
	public function showProductSuppliers(){
		$details = array();
		
		foreach($this->_mProductSuppliers as $detail)
			if(!$detail->isDeleted())
				$details[] = $detail->show();
			
		return $details;
	}
	
	/**
	 * Sets the product's bar code.
	 *
	 * @param string $barCode
	 */
	public function setBarCode($barCode){
		$this->validateBarCode($barCode);
		$this->verifyBarCode($barCode);
		$this->_mBarCode = $barCode;
	}
	
	/**
	 * Sets the product's packaging
	 *
	 * @param string $packaging
	 */
	public function setPackaging($packaging){
		$this->validatePackaging($packaging);
		$this->_mPackaging = $packaging;
	}
	
	/**
	 * Sets the product's description.
	 *
	 * @param string $description
	 */
	public function setDescription($description){
		$this->validateDescription($description);
		$this->_mDescription = $description;
	}
	
	/**
	 * Sets the product's unit of measure.
	 *
	 * @param UnitOfMeasure $um
	 */
	public function setUnitOfMeasure(UnitOfMeasure $um){
		self::validateObjectFromDatabase($um);
		$this->_mUM = $um;
	}
	
	/**
	 * Sets the product's manufacturer.
	 *
	 * @param Manufacturer $obj
	 */
	public function setManufacturer(Manufacturer $obj){
		self::validateObjectFromDatabase($obj);
		$this->_mManufacturer = $obj;
	}
	
	/**
	 * Sets the product new price.
	 *
	 * @param float $price
	 */
	public function setPrice($price){
		Number::validatePrice($price);
		if($this->_mPrice != $price){
			$this->_mLastPrice = $this->_mPrice;
			$this->_mPrice = $price;
		}
	}
	
	/**
	 * Sets the product's deactivated flag value.
	 *
	 * @param boolean $bool
	 */
	public function deactivate($bool){
		$this->_mDeactivated = (boolean)$bool;
	}
	
	/**
	 * Sets the product's properties.
	 *
	 * Must be called only from the database layer corresponding class. The object's status must be set to
	 * Persist::CREATED in the constructor method too.
	 * @param string $barCode
	 * @param string $packaging
	 * @param string $description
	 * @param UnitOfMeasure $um
	 * @param Manufacturer $manufacturer
	 * @param float $price
	 * @param boolean $deactivated
	 * @param array<ProductSupplier> $details
	 * @throws Exception
	 */
	public function setData($name, $barCode, $packaging, $description, UnitOfMeasure $um,
			Manufacturer $manufacturer, $price, $deactivated, $details){
		parent::setData($name);
		
		try{
			$this->validateBarCode($barCode);
			$this->validatePackaging($packaging);
			$this->validateDescription($description);
			self::validateObjectFromDatabase($um);
			self::validateObjectFromDatabase($manufacturer);
			Number::validatePrice($price);
			if(empty($details))
				throw new Exception('No hay ningun detalle.');
		} catch(Exception $e){
			$et = new Exception('Internal error, calling Product constructor method with bad data! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mBarCode = $barCode;
		$this->_mPackaging = $packaging;
		$this->_mDescription = $description;
		$this->_mUM = $um;
		$this->_mManufacturer = $manufacturer;
		$this->_mPrice = $price;
		$this->_mDeactivated = (boolean)$deactivated;
		$this->_mProductSuppliers = $details;
	}
	
	/**
	 * Adds a supplier to the list of suppliers of this product.
	 *
	 * @param ProductSupplier $newDetail
	 * @throws Exception
	 */
	public function addProductSupplier(ProductSupplier $newDetail){
		foreach($this->_mProductSuppliers as $detail)
			if($detail == $newDetail && !$detail->isDeleted())
				throw new Exception('Codigo del proveedor ya esta ingresado.');
				
		$this->verifyProductSupplier($newDetail);
			
		$this->_mProductSuppliers[] = $newDetail;
	}
	
	/**
	 * Removes a supplier from this product.
	 *
	 * @param ProductSupplier $purgeDetail
	 */
	public function deleteProductSupplier(ProductSupplier $purgeDetail){
		$temp_details = array();
		
		foreach($this->_mProductSuppliers as &$detail)
			if($detail != $purgeDetail)
				$temp_details[] = $detail;
			else
				if($detail->getStatus() == Persist::CREATED){
					$temp_details[] = $detail;
					$detail->delete();
				}
		
		$this->_mProductSuppliers = $temp_details;
	}
	
	/**
	 * Saves the product's data in the database.
	 *
	 */
	public function save(){
		$this->validateMainProperties();
		
		foreach($this->_mProductSuppliers as $detail)
			if($detail->getStatus() == Persist::IN_PROGRESS)
				$this->verifyProductSupplier($detail);
				
		if($this->_mStatus == Persist::IN_PROGRESS){
			$this->_mId = $this->insert();
			
			if(is_null($this->_mBarCode)){
				ProductDAM::setBarCode($this);
				$this->_mBarCode = $this->_mId;
			}
			
			$this->_mStatus = Persist::CREATED;
		}
		else {
			$this->update();
			
			if($this->_mLastPrice)
				ChangePriceLog::write($this, $this->_mLastPrice, $this->_mPrice);
		}
		
		foreach($this->_mProductSuppliers as $detail)
			$detail->commit($this);
	}
	
	/**
	 * Returns an instance of a product.
	 *
	 * Returns NULL if there was no match for the provided id in the database.
	 * @param integer $id
	 * @return Product
	 */
	static public function getInstance($id){
		self::validateId($id);
		return ProductDAM::getInstance($id);
	}
	
	/**
	 * Returns an instance of a product.
	 *
	 * Returns a product which bar code matches the one provided. If not found returns NULL.
	 * @param string $barCode
	 * @return Product
	 */
	static public function getInstanceByBarCode($barCode){
		self::validateBarCode($barCode);
		return ProductDAM::getInstanceByBarCode($barCode);
	}
	
	/**
	 * Returns an instance of a product.
	 *
	 * Returns a product which has a supplier with the provided product's sku. If not found returns NULL.
	 * @param Supplier $supplier
	 * @param string $sku
	 * @return Product
	 */
	static public function getInstanceBySupplier(Supplier $supplier, $sku){
		self::validateObjectFromDatabase($supplier);
		ProductSupplier::validateProductSKU($sku);
		return ProductDAM::getInstanceBySupplier($supplier, $sku);
	}
	
	/**
	 * Deletes the product from the database.
	 * 
	 * Returns true confirming the deletion, otherwise false due dependencies.
	 * @param Product $obj
	 * @return boolean
	 */
	static public function delete(Product $obj){
		self::validateObjectFromDatabase($obj);
		return ProductDAM::delete($obj);
	}
	
	/**
	 * Inserts the product's data in the database.
	 *
	 * Returns the new created id from the database.
	 * @return integer
	 */
	protected function insert(){
		return ProductDAM::insert($this);
	}
	
	/**
	 * Updates the product's data in the database.
	 *
	 */
	protected function update(){
		ProductDAM::update($this);
	}
	
	/**
	 * Validates the object's main properties.
	 * @throws Exception
	 */
	protected function validateMainProperties(){
		parent::validateMainProperties();
		$this->validatePackaging($this->_mPackaging);
		$this->validateDescription($this->_mDescription);
		
		if(is_null($this->_mUM))
			throw new Exception('Unidad de medida inv&aacute;lida.');
		else
			self::validateObjectFromDatabase($this->_mUM);
			
		if(is_null($this->_mManufacturer))
			throw new Exception('Fabricante inv&aacute;lido.');
		else
			self::validateObjectFromDatabase($this->_mManufacturer);
		
		Number::validatePrice($this->_mPrice);
		if(!$this->hasProductSuppliers())
			throw new Exception('No hay ningun proveedor ingresado.');
	}
	
	/**
	 * Returns true if the product has any suppliers. False if not.
	 *
	 * @return boolean
	 */
	private function hasProductSuppliers(){
		if(empty($this->_mProductSuppliers))
			return false;
			
		$count = count($this->_mProductSuppliers);
		$deleted = 0;
		
		foreach($this->_mProductSuppliers as $detail)
			if($detail->isDeleted())
				$deleted++;
				
		if($deleted == $count)
			return false;
		else
			return true;
	}
	
	/**
	 * Validates the product's bar code.
	 *
	 * Must not be empty. Otherwise it throws an exception.
	 * @param string $barCode
	 * @throws Exception
	 */
	private function validateBarCode($barCode){
		if(empty($barCode))
			throw new Exception('Codigo de barra inv&aacute;lido;');
	}
	
	/**
	 * Validates the product's packaging.
	 *
	 * Must not be empty. Otherwise it throws an exception.
	 * @param string $packaging
	 * @throws Exception
	 */
	private function validatePackaging($packaging){
		if(empty($packaging))
			throw new Exception('Presentacion inv&aacute;lida.');
	}
	
	/**
	 * Validates the product's description.
	 *
	 * Must not be empty. Otherwise it throws an exception.
	 * @param string $description
	 * @throws Exception
	 */
	private function validateDescription($description){
		if(empty($description))
			throw new Exception('Descripcion inv&aacute;lida.');
	}
	
	/**
	 * Validates a detail's id.
	 *
	 * Must not be empty. Otherwise it throws an exception.
	 * @param string $id
	 * @throws Exception
	 */
	private function validateProductSupplierId($id){
		if(empty($id))
			throw new Exception('Id inv&aacute;lido.');
	}
	
	/**
	 * Verifies if another product is already using the bar code.
	 *
	 * @param string $barCode
	 * @throws Exception
	 */
	private function verifyBarCode($barCode){
		if(ProductDAM::existsBarCode($this, $barCode))
			throw new Exception('Codigo de barra ya esta siendo utilizado.');
	}
	
	/**
	 * Verifies if the product detail already exists in the database.
	 *
	 * It throws an exception if it does.
	 * @param ProductSupplier $detail
	 * @throws Exception
	 */
	private function verifyProductSupplier(ProductSupplier $detail){
		if(ProductDAM::existsProductSupplier($detail))
			throw new Exception('Codigo del proveedor ya existe en la base de datos.');
	}
}


/**
 * Represents a bonus or discount a certain product may have.
 * @package Product
 * @author Roberto Oliveros
 */
class Bonus extends Persist{
	/**
	 * Holds the bonus identifier.
	 *
	 * @var integer
	 */
	private $_mId;
	
	/**
	 * Holds the product owner of this bonus.
	 *
	 * @var Product
	 */
	private $_mProduct;
	
	/**
	 * Represents the quantity of certain product so the bonus can be accomplished.
	 *
	 * @var integer
	 */
	private $_mQuantity;
	
	/**
	 * Holds the percentage for discounts.
	 *
	 * @var float
	 */
	private $_mPercentage;
	
	/**
	 * Holds the date in which the bonus expires.
	 *
	 * Date format: 'dd/mm/yyyy'.
	 * @var string
	 */
	private $_mExpirationDate;
	
	/**
	 * Holds the date in which the bonus was created.
	 *
	 * Date format: 'dd/mm/yyyy'.
	 * @var string
	 */
	private $_mCreatedDate;
	
	/**
	 * Constructs the bonus with the provided data.
	 *
	 * Note that expiration date must be greater than the created date.
	 * @param Product $product
	 * @param integer $quantity
	 * @param float $percentage
	 * @param string $expirationDate
	 * @param string $createdDate
	 * @param integer $id
	 * @param integer $status
	 * @throws Exception
	 */
	public function __construct(Product $product, $quantity, $percentage, $expirationDate,
			$createdDate = NULL, $id = NULL, $status = Persist::IN_PROGRESS){
		parent::__construct($status);		
		
		if(!is_null($id))
			Identifier::validateId($id);
		self::validateObjectFromDatabase($product);
		Number::validateQuantity($quantity);
		$this->validatePercentage($percentage);
		Date::validateDate($expirationDate);
		
		if(!is_null($createdDate)){
			Date::validateDate($createdDate);
			if(!Date::compareDates($createdDate, $expirationDate))
				throw new Exception('Dia creado es mayor que el de expiracion.');
			$this->_mCreatedDate = $createdDate;
		}
		else
			$this->_mCreatedDate = date('d/m/Y');
		
		$this->_mId = $id;
		$this->_mProduct = $product;
		$this->_mQuantity = $quantity;
		$this->_mPercentage = $percentage;
		$this->_mExpirationDate = $expirationDate;
	}
	
	/**
	 * Returns the bonus' id.
	 *
	 * @return integer
	 */
	public function getId(){
		return $this->_mId;
	}
	
	/**
	 * Returns the quantity a product must reach to accomplish the bonus.
	 *
	 * @return integer
	 */
	public function getQuantity(){
		return $this->_mQuantity;
	}
	
	/**
	 * Returns the bonus' percentage to use in discounts.
	 *
	 * @return float
	 */
	public function getPercentage(){
		return $this->_mPercentage;
	}
	
	/**
	 * Returns date in which the bonus expires.
	 *
	 * @return string
	 */
	public function getExpirationDate(){
		return $this->_mExpirationDate;
	}
	
	/**
	 * Returns date in which the bonus was created.
	 *
	 * @return string
	 */
	public function getCreatedDate(){
		return $this->_mCreatedDate;
	}
	
	/**
	 * Returns the product which this bonus belongs to.
	 *
	 * @return Product
	 */
	public function getProduct(){
		return $this->_mProduct;
	}
	
	/**
	 * Inserts the bonus in the database.
	 * 
	 * Only applies if the bonus's status property has the Persist::IN_PROGRESS value.
	 * @throws Exception
	 */
	public function save(){
		if($this->_mStatus == Persist::IN_PROGRESS){
			if(BonusDAM::exists($this->_mProduct, $this->_mQuantity))
				throw new Exception('Oferta ya existe.');
				
			$this->_mId = BonusDAM::insert($this);
			$this->_mStatus = Persist::CREATED;
		}
	}
	
	/**
	 * Returns an instance of a bonus.
	 *
	 * Returns NULL if there was no match for the id provided in the database.
	 * @param integer $id
	 * @return Bonus
	 */
	static public function getInstance($id){
		Identifier::validateId($id);
		return BonusDAM::getInstance($id);
	}
	
	/**
	 * Returns an instance of a bonus.
	 *
	 * Returns the bonus which belongs to the provided product and contains the same quantity.
	 * @param Product $product
	 * @param integer $quantity
	 * @return Bonus
	 */
	static public function getInstanceByProduct(Product $product, $quantity){
		self::validateObjectFromDatabase($product);
		Number::validateQuantity($quantity);
		return BonusDAM::getInstanceByProduct($product, $quantity);
	}
	
	/**
	 * Deletes the bonus from the database.
	 *
	 * Returns true confirming the deletion, otherwise false due dependencies.
	 * @param Bonus $obj
	 * @return boolean
	 */
	static public function delete(Bonus $obj){
		self::validateObjectFromDatabase($obj);
		return BonusDAM::delete($obj);
	}
	
	/**
	 * Validates the provided percentage.
	 *
	 * Must be greater than cero. Otherwise it throws an exception.
	 * @param float $percentage
	 * @throws Exception
	 */
	private function validatePercentage($percentage){
		if(!is_float($percentage) || ($percentage < 1 || $percentage > 100))
			throw new Exception('Porcentaje inv&accute;lido.');
	}
}


/**
 * Represents a lot of certain product in the inventory.
 * 
 * Because the lack of experience the class lot represents two kinds of lots. One is for the recent created
 * lot for an entry document, the other is when the data is obtain from the database. For that, the quantity
 * property only is needed when the lot is recently created or Persist::IN_PROGRESS, otherwise the property
 * is queried directly to the database. And get available method only works when Persist::CREATED. Sorry.
 * @package Product
 * @author Roberto Oliveros
 */
class Lot extends Persist{
	/**
	 * Holds the lot's id.
	 *
	 * @var integer
	 */
	private $_mId;
	
	/**
	 * Holds the product of what the lot is made of.
	 *
	 * @var Product
	 */
	private $_mProduct;
	
	/**
	 * Indicates the how many items the lot has.
	 *
	 * @var integer
	 */
	private $_mQuantity;
	
	/**
	 * Holds the price for each item in the lot.
	 *
	 * @var float
	 */
	private $_mPrice;
	
	/**
	 * Holds the date in which the products of the lot expire.
	 *
	 * Date format: 'dd/mm/yyyy'.
	 * @var string
	 */
	private $_mExpirationDate;
	
	/**
	 * Date in which the lot entered in the inventory.
	 *
	 * Date format: 'dd/mm/yyyy'.
	 * @var string
	 */
	private $_mEntryDate;
	
	/**
	 * Constructs the lot with the provided data.
	 *
	 * @param Product $product
	 * @param integer $quantity
	 * @param float $price
	 * @param string $expirationDate
	 * @param string $entryDate
	 * @param integer $id
	 * @param integer $status
	 */
	public function __construct(Product $product, $quantity = 0, $price = 0.0, $expirationDate = NULL,
			$entryDate = NULL, $id = 0, $status = Persist::IN_PROGRESS){
		parent::__construct($status);
		
		self::validateObjectFromDatabase($product);
		
		if($quantity !== 0)
			$this->validateQuantity($quantity);
			
		if($price !== 0)
			Number::validatePrice($price);
		
		if(!is_null($expirationDate))
			Date::validateDate($expirationDate);
		
		if(!is_null($entryDate)){
			Date::validateDate($entryDate);
			$this->_mEntryDate = $entryDate;
		}
		else
			$this->_mEntryDate = date('d/m/Y');

		if($id !== 0)
			$this->validateId($id);
		
		$this->_mProduct = $product;
		$this->_mQuantity = $quantity;
		$this->_mPrice = $price;
		$this->_mExpirationDate = $expirationDate;
		$this->_mId = $id;
	}
	
	/**
	 * Returns lot's id.
	 *
	 * @return integer
	 */
	public function getId(){
		return $this->_mId;
	}
	
	/**
	 * Returns the lot's product.
	 *
	 * @return Product
	 */
	public function getProduct(){
		return $this->_mProduct;
	}
	
	/**
	 * Returns the lot's quantity.
	 *
	 * @return integer
	 */
	public function getQuantity(){
		if($this->_mStatus == Persist::IN_PROGRESS)
			return $this->_mQuantity;
		else
			return LotDAM::getQuantity($this);
	}
	
	/**
	 * Returns lot's price.
	 *
	 * @return float
	 */
	public function getPrice(){
		return $this->_mPrice;
	}
	
	/**
	 * Returns lot's expiration date.
	 *
	 * @return string
	 */
	public function getExpirationDate(){
		return $this->_mExpirationDate;
	}
	
	/**
	 * Returns lot's entry date.
	 *
	 * @return string
	 */
	public function getEntryDate(){
		return $this->_mEntryDate;
	}
	
	/**
	 * Returns the quantity available of the lot.
	 *
	 * @return integer
	 */
	public function getAvailable(){
		if($this->_mStatus == Persist::CREATED)
			return LotDAM::getAvailable($this);
		else
			return 0;
	}
	
	/**
	 * Returns an array with the lot's data for displaying.
	 *
	 * The fields are id, entry_date, expiration_date, price and available.
	 * @return array
	 */
	public function show(){
		return array('id' => $this->_mId, 'entry_date' => $this->_mEntryDate,
				'expiration_date' => $this->_mExpirationDate, 'price' => $this->_mPrice,
				'quantity' => $this->getQuantity(), 'available' => $this->getAvailable());
	}
	
	/**
	 * Sets the lot's price.
	 *
	 * Necessary method because the inventory class provides methods for obtaining new lots.
	 * @param float $price
	 */
	public function setPrice($price){
		Number::validatePrice($price);
		$this->_mPrice = $price;
	}
	
	/**
	 * Sets the lot's expiration date.
	 *
	 * Necessary method because the inventory class provides methods for obtaining new lots.
	 * @param string $date
	 */
	public function setExpirationDate($date){
		Date::validateDate($date);
		$this->_mExpirationDate = $date;
	}
	
	/**
	 * Deactivates the lot.
	 *
	 */
	public function deactivate(){
		if($this->_mStatus == Persist::CREATED)
			LotDAM::deactivate($this);
	}
	
	/**
	 * Increases the lot's quantity.
	 *
	 * Note that if the status property value equals Persist::IN_PROGRESS the object's quantity is modified
	 * otherwise the quantity in the database is.
	 * @param integer $quantity
	 */
	public function increase($quantity){
		Number::validateQuantity($quantity);
		if($this->_mStatus == Persist::IN_PROGRESS)
			$this->_mQuantity += $quantity;
		else
			LotDAM::increase($this, $quantity);
	}
	
	/**
	 * Decrease the lot's quantity.
	 *
	 * Note that if the status property value equals Persist::IN_PROGRESS the object's quantity is modified
	 * otherwise the quantity in the database is.
	 * @param integer $quantity
	 */
	public function decrease($quantity){
		Number::validateQuantity($quantity);
		if($this->_mStatus == Persist::IN_PROGRESS)
			$this->_mQuantity -= $quantity;
		else
			LotDAM::decrease($this, $quantity);
	}
	
	/**
	 * Reserves the provided quantity in the database.
	 *
	 * @param integer $quantity
	 */
	public function reserve($quantity){
		if($this->_mStatus == Persist::CREATED){
			Number::validateQuantity($quantity);
			LotDAM::reserve($this, $quantity);
		}
	}
	
	/**
	 * Decreases the reserve quantity of the lot in the database.
	 *
	 * @param integer $quantity
	 */
	public function decreaseReserve($quantity){
		if($this->_mStatus == Persist::CREATED){
			Number::validateQuantity($quantity);
			LotDAM::decreaseReserve($this, $quantity);
		}
	}
	
	/**
	 * Saves the lot's data in the database.
	 *
	 * Only applies if the lot's status property has the Persist::IN_PROGRESS value.
	 */
	public function save(){
		if($this->_mStatus == Persist::IN_PROGRESS){
			$this->_mId = LotDAM::insert($this);
			$this->_mStatus = Persist::CREATED;
		}
	}
	
	/**
	 * Returns an instance of a lot.
	 *
	 * Returns NULL if there was no match for the provided id in the database.
	 * @param integer $id
	 * @return Lot
	 */
	static public function getInstance($id){
		Identifier::validateId($id);
		return LotDAM::getInstance($id);
	}
	
	/**
	 * Validates the provided id.
	 *
	 * Must be greater or equal to cero. Otherwise it throws an exception.
	 * @param integer $id
	 * @throws Exception
	 */
	private function validateId($id){
		if(!is_int($id) || $id < 0)
			throw new Exception('Internal error, invalid lot id.');
	}
	
	/**
	 * Validates the provided quantity.
	 *
	 * Must be an integer. Otherwise it throws an exception.
	 * @param integer $quantity
	 * @throws Exception
	 */
	private function validateQuantity($quantity){
		if(!is_int($quantity))
			throw new Exception('Internal error, invalid quantity.');
	}
}


/**
 * Represents a lot with a negative quantity.
 * @package Product
 * @author Roberto Oliveros
 */
class NegativeLot extends Lot{
	/**
	 * Returns the lot's negative quantity.
	 *
	 * @return integer
	 */
	public function getNegativeQuantity(){
		if($this->_mStatus == Persist::CREATED)
			return LotDAM::getNegativeQuantity($this);
		else
			return 0;
	}
	
	/**
	 * Sets the negative quantity.
	 *
	 * @param integer $quantity
	 * @throws Exception
	 */
	public function setNegativeQuantity($quantity){
		if($this->_mStatus == Persist::CREATED){
			if(!is_int($quantity) || $quantity > 0)
				throw new Exception('Internal error, invalid negative quantity!');
			
			LotDAM::UpdateNegativeQuantity($this, $quantity);
		}
	}
}


/**
 * Utility class to register any price change of any product.
 * @package Product
 * @author Roberto Oliveros
 */
class ChangePriceLog{
	/**
	 * Register the event in the database.
	 *
	 * @param Product $product
	 * @param float $lastPrice
	 * @param float $newPrice
	 */
	static public function write(Product $product, $lastPrice, $newPrice){
		Persist::validateObjectFromDatabase($product);
		Number::validatePrice($lastPrice);
		Number::validatePrice($newPrice);
		
		$date = date('d/m/Y');
		$helper = SessionHelper::getInstance();
		ChangePriceLogDAM::write($date, $helper->getUser(), $product, $lastPrice, $newPrice);
	}
}
?>