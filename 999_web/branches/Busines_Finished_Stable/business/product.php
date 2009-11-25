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
		return UnitOfMeasureDAM::getInstance($id);
	}
	
	/**
	 * Deletes the unit of measure from database.
	 * 
	 * Throws an exception due dependencies.
	 * @param UnitOfMeasure $obj
	 * @throws Exception
	 */
	static public function delete(UnitOfMeasure $obj){
		self::validateObjectFromDatabase($obj);
		if(!UnitOfMeasureDAM::delete($obj))
			throw new Exception('Unidad de Medida tiene dependencias (productos) y no se puede eliminar.');
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
		return ManufacturerDAM::getInstance($id);
	}
	
	/**
	 * Deletes the manufacturer from the database.
	 * 
	 * Throws an exception due dependencies.
	 * @param Manufacturer $obj
	 * @throws Exception
	 */
	static public function delete(Manufacturer $obj){
		self::validateObjectFromDatabase($obj);
		if(!ManufacturerDAM::delete($obj))
			throw new Exception('Casa tiene dependencias (productos) y no se puede eliminar.');
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
		Number::validatePositiveNumber($reqUnitsQuantity, 'Cantidad inv&aacute;lida.');
		
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
		return InventoryDAM::getLotsList($product, $quantity, $available);
	}
	
	/**
	 * Increases the product's quantity.
	 *
	 * @param Product $product
	 * @param integer $quantity
	 */
	static public function increase(Product $product, $quantity){
		Persist::validateObjectFromDatabase($product);
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
		String::validateString($productSKU, 'Codigo inv&aacute;lido.', 'product_sku');
		
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
		return array('product_supplier_id' => $this->getId(), 'supplier' => $this->_mSupplier->getName(),
				'product_sku' => $this->_mProductSKU);
	}
	
	/**
	 * Sets the productsupplier's deleted flag to true.
	 *
	 * Must not be called. If you need to delete the object call Product::deleteProductSupplier() method
	 * instead and pass this object as the parameter.
	 */
	public function delete(){
		$this->_mDeleted = true;
	}
	
	/**
	 * Sets the productsupplier's deleted flag to false.
	 *
	 * Must not be called. Use when the detail was previously deleted but is added again.
	 */
	public function restore(){
		$this->_mDeleted = false;
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
		String::validateString($id, 'Id inv&aacute;lido.');
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
		$this->_mBarCode = $barCode;
		if($barCode != '')
			$this->verifyBarCode($barCode);
	}
	
	/**
	 * Sets the product's packaging
	 *
	 * @param string $packaging
	 */
	public function setPackaging($packaging){
		$this->_mPackaging = $packaging;
		String::validateString($packaging, 'Presentaci&oacute;n inv&aacute;lida.');
	}
	
	/**
	 * Sets the product's description.
	 *
	 * @param string $description
	 */
	public function setDescription($description){
		$this->_mDescription = $description;
	}
	
	/**
	 * Sets the product's unit of measure.
	 *
	 * @param UnitOfMeasure $um
	 */
	public function setUnitOfMeasure(UnitOfMeasure $um = NULL){
		$this->_mUM = $um;
		if(is_null($um))
			throw new ValidateException('Seleccione una unidad de medida.');
	}
	
	/**
	 * Sets the product's manufacturer.
	 *
	 * @param Manufacturer $obj
	 */
	public function setManufacturer(Manufacturer $obj = NULL){
		$this->_mManufacturer = $obj;
		if(is_null($obj))
			throw new ValidateException('Seleccione una casa.');
	}
	
	/**
	 * Sets the product new price.
	 *
	 * @param float $price
	 */
	public function setPrice($price){
		$this->_mPrice = $price;
		Number::validateUnsignedNumber($price, 'Precio inv&aacute;lido.');
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
	public function setData($barCode, $name, $packaging, UnitOfMeasure $um, Manufacturer $manufacturer, $price,
			$deactivated, $details, $description = NULL){
		parent::setData($name);
		
		try{
			String::validateString($barCode, 'C&oacute;digo de barra inv&aacute;lido.');
			String::validateString($packaging, 'Presentaci&oacute;n inv&aacute;lida.');
			self::validateObjectFromDatabase($um);
			self::validateObjectFromDatabase($manufacturer);
			Number::validateUnsignedNumber($price, 'Precio inv&aacute;lido.');
			if(empty($details))
				throw new Exception('No hay ningun detalle.');
		} catch(Exception $e){
			$et = new Exception('Interno: Llamando al metodo setData en Product con datos erroneos! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mBarCode = $barCode;
		$this->_mPackaging = $packaging;
		$this->_mDescription = $description;
		$this->_mUM = $um;
		$this->_mManufacturer = $manufacturer;
		$this->_mPrice = $price;
		$this->_mLastPrice = $price;
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
			if($detail->getId() == $newDetail->getId())
				if($detail->isDeleted()){
					$this->restoreProductSupplier($detail);
					return;
				}
				else
					throw new ValidateException('Codigo del proveedor ya esta ingresado.', 'product_suppliers');
				
		$this->verifyProductSupplier($newDetail);
		$this->_mProductSuppliers[] = $newDetail;
	}
	
	/**
	 * Deletes the supplier from the product.
	 *
	 * @param ProductSupplier $purgeDetail
	 */
	public function deleteProductSupplier(ProductSupplier $purgeDetail){
		$temp_details = array();
		
		foreach($this->_mProductSuppliers as &$detail)
			if($detail->getId() != $purgeDetail->getId())
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
	 * @return integer
	 */
	public function save(){
		$this->validateMainProperties();
		
		foreach($this->_mProductSuppliers as $detail)
			if($detail->getStatus() == Persist::IN_PROGRESS)
				$this->verifyProductSupplier($detail);
				
		if($this->_mStatus == Persist::IN_PROGRESS){
			$this->_mId = $this->insert();
			
			if(is_null($this->_mBarCode) || $this->_mBarCode == ''){
				$this->_mBarCode = $this->generateBarCode();
				ProductDAM::setBarCode($this, $this->_mBarCode);
			}	
			
			$this->_mStatus = Persist::CREATED;
		}
		else {
			if(is_null($this->_mBarCode) || $this->_mBarCode == '')
				$this->_mBarCode = $this->generateBarCode();
			
			$this->update();
			
			if($this->_mLastPrice != $this->_mPrice)
				ChangePriceLog::write($this, $this->_mLastPrice, $this->_mPrice);
		}
		
		foreach($this->_mProductSuppliers as &$detail)
			$detail->commit($this);
			
		// For presentation layer purposes.
		return $this->_mId;
	}
	
	/**
	 * Returns an instance of a product.
	 *
	 * Returns NULL if there was no match for the provided id in the database.
	 * @param integer $id
	 * @return Product
	 */
	static public function getInstance($id){
		return ProductDAM::getInstance($id);
	}
	
	/**
	 * Returns the product's id of the provided bar code.
	 *
	 * If not found returns 0.
	 * @param string $barCode
	 * @return integer
	 */
	static public function getProductIdByBarCode($barCode){
		return ProductDAM::getIdByBarCode($barCode);
	}
	
	/**
	 * Returns the product's id of the provided supplier and sku.
	 *
	 * If not found returns 0.
	 * @param Supplier $supplier
	 * @param string $sku
	 * @return integer
	 */
	static public function getProductIdBySupplier(Supplier $supplier, $sku){
		return ProductDAM::getIdBySupplier($supplier, $sku);
	}
	
	/**
	 * Deletes the product from the database.
	 * 
	 * Throws an exception due dependencies.
	 * @param Product $obj
	 * @throws Exception
	 */
	static public function delete(Product $obj){
		self::validateObjectFromDatabase($obj);
		if(!ProductDAM::delete($obj))
			throw new Exception('Producto tiene dependencias y no se puede eliminar.');
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
		
		if(!is_null($this->_mBarCode) && $this->_mBarCode != '')
			$this->verifyBarCode($this->_mBarCode);
		
		String::validateString($this->_mPackaging, 'Presentaci&oacute;n inv&aacute;lida.', 'packaging');
		
		if(is_null($this->_mManufacturer))
			throw new ValidateException('Seleccione una casa.', 'manufacturer_id');
		
		if(is_null($this->_mUM))
			throw new ValidateException('Seleccione una unidad de medida.', 'um_id');
		
		Number::validateUnsignedNumber($this->_mPrice, 'Precio inv&aacute;lido.', 'price');
		if(!$this->hasProductSuppliers())
			throw new ValidateException('No hay ningun proveedor ingresado.', 'supplier_id');
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
	 * Verifies if another product is already using the bar code.
	 *
	 * @param string $barCode
	 * @throws Exception
	 */
	private function verifyBarCode($barCode){
		if(ProductDAM::existsBarCode($this, $barCode))
			throw new ValidateException('Codigo de barra ya esta siendo utilizado.', 'bar_code');
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
			throw new ValidateException('Codigo del proveedor ya existe en la base de datos.',
					'product_suppliers');
	}
	
	/**
	 * Generates a bar code.
	 * 
	 * If the product id can't be used a 6 digit number is returned.
	 * @return string
	 */
	private function generateBarCode(){
		if(!ProductDAM::existsBarCode($this, $this->_mId)){
			return $this->_mId;
		}
		else{
			$found = false;
			
			do{
				$new_code = rand(100000, 999999);
				if(!ProductDAM::existsBarCode($this, $new_code)) $found = true;
			}while(!$found);
			
			return $new_code;
		}
	}
	
	/**
	 * Restore a previously deleted supplier from the product.
	 *
	 * Use to move the restore supplier to the last place to simulate it is new.
	 * @param ProductSupplier $restoreDetail
	 */
	private function restoreProductSupplier(ProductSupplier $restoreDetail){
		$temp_details = array();
		
		foreach($this->_mProductSuppliers as &$detail)
			if($detail->getId() != $restoreDetail->getId())
				$temp_details[] = $detail;
		
		$restoreDetail->restore();
		$temp_details[] = $restoreDetail;
		$this->_mProductSuppliers = $temp_details;
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
			Number::validatePositiveInteger($id, 'Id inv&aacute;lido.');
		self::validateObjectFromDatabase($product);
		Number::validatePositiveNumber($quantity, 'Cantidad inv&aacute;lida.', 'quantity');
		$this->validatePercentage($percentage);
		Date::validateDate($expirationDate, 'Fecha de vencimiento inv&aacute;lida.', 'expiration_date');
		
		if(!is_null($createdDate)){
			Date::validateDate($createdDate, 'Fecha de creaci&oacute;n inv&aacute;lida.');
			$this->_mCreatedDate = $createdDate;
		}
		else
			$this->_mCreatedDate = date('d/m/Y');
			
		if(!Date::compareDates($this->_mCreatedDate, $expirationDate))
			throw new ValidateException('Dia creado es mas reciente que el de vencimiento.', 'bonus');
		
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
				throw new ValidateException('Oferta ya existe.', 'bonus');
				
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
		Number::validatePositiveInteger($id, 'Id inv&aacute;lido.');
		return BonusDAM::getInstance($id);
	}
	
	/**
	 * Returns an bonus' id which has the provided product and quantity.
	 *
	 * If not found returns 0.
	 * @param Product $product
	 * @param integer $quantity
	 * @return integer
	 */
	static public function getBonusIdByProduct(Product $product, $quantity){
		self::validateObjectFromDatabase($product);
		return BonusDAM::getId($product, $quantity);
	}
	
	/**
	 * Deletes the bonus from the database.
	 *
	 * Throws an exception due dependencies.
	 * @param Bonus $obj
	 * @throws Exception
	 */
	static public function delete(Bonus $obj){
		self::validateObjectFromDatabase($obj);
		if(!BonusDAM::delete($obj))
			throw new Exception('Oferta tiene dependencias y no se puede eliminar.');
	}
	
	/**
	 * Validates the provided percentage.
	 *
	 * Must be greater than cero. Otherwise it throws an exception.
	 * @param float $percentage
	 * @throws Exception
	 */
	private function validatePercentage($percentage){
		if(!is_numeric($percentage) || ($percentage < 1 || $percentage > 100))
			throw new ValidateException('Porcentaje inv&accute;lido. No menor que 1 ni mayor que 100.',
					'percentage');
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
	public function __construct(Product $product, $quantity = 0, $price = 0.00, $expirationDate = NULL,
			$entryDate = NULL, $id = 0, $status = Persist::IN_PROGRESS){
		parent::__construct($status);
		
		self::validateObjectFromDatabase($product);
		
		if($quantity !== 0)
			Number::validateNumber($quantity, 'Cantidad inv&aacute;lida.', 'quantity');
			
		if($price !== 0.00)
			Number::validatePositiveNumber($price, 'Precio inv&aacute;lido.', 'price');
		
		if(!is_null($expirationDate) && $expirationDate != ''){
			Date::validateDate($expirationDate, 'Fecha de vencimiento inv&aacute;lida.',
					'expiration_date');
			$expirationDate = Date::paddingDate($expirationDate);
		}
		
		if(!is_null($entryDate)){
			Date::validateDate($entryDate, 'Fecha de ingreso inv&aacute;lida.');
			$this->_mEntryDate = $entryDate;
		}
		else
			$this->_mEntryDate = date('d/m/Y');

		if($id !== 0)
			Number::validatePositiveInteger($id, 'Id inv&aacute;lido.');
		
		$this->_mProduct = $product;
		$this->_mQuantity = round($quantity);
		$this->_mPrice = round($price, 2);
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
		if($this->_mStatus == Persist::CREATED)
			return LotDAM::getQuantity($this);
		else
			return $this->_mQuantity;
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
		Number::validateUnsignedNumber($price, 'Precio inv&aacute;lido.', 'price');
		
		if($this->_mStatus == Persist::CREATED){
			LotDAM::setPrice($this, $price);
			$this->_mPrice = $price;
		}
		else
			$this->_mPrice = $price;
	}
	
	/**
	 * Sets the lot's expiration date.
	 *
	 * Necessary method because the inventory class provides methods for obtaining new lots. Date format: 
	 * 'dd/mm/yyyy'.
	 * @param string $date
	 */
	public function setExpirationDate($date){
		if($date != '')
			Date::validateDate($date, 'Fecha de vencimiento inv&aacute;lida.', 'expiration_date');
		
		if($this->_mStatus == Persist::CREATED){
			LotDAM::setExpirationDate($this, $date);
			$this->_mExpirationDate = $date;
		}
		else
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
		Number::validatePositiveNumber($quantity, 'Cantidad inv&aacute;lida.');
		if($this->_mStatus == Persist::CREATED)
			LotDAM::increase($this, $quantity);
		else
			$this->_mQuantity += $quantity;
	}
	
	/**
	 * Decrease the lot's quantity.
	 *
	 * Note that if the status property value equals Persist::IN_PROGRESS the object's quantity is modified
	 * otherwise the quantity in the database is.
	 * @param integer $quantity
	 */
	public function decrease($quantity){
		Number::validatePositiveNumber($quantity, 'Cantidad inv&aacute;lida.');
		if($this->_mStatus == Persist::CREATED)
			LotDAM::decrease($this, $quantity);
		else
			$this->_mQuantity -= $quantity;
	}
	
	/**
	 * Reserves the provided quantity in the database.
	 *
	 * @param integer $quantity
	 */
	public function reserve($quantity){
		if($this->_mStatus == Persist::CREATED){
			Number::validatePositiveNumber($quantity, 'Cantidad inv&aacute;lida.');
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
			Number::validatePositiveNumber($quantity, 'Cantidad inv&aacute;lida.');
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
	 * Returns NULL if there was no match for the provided id in the database. The optional argument is the
	 * product the lot belongs to.
	 * @param integer $id
	 * @return Lot
	 */
	static public function getInstance($id, Product $product = NULL){
		Number::validatePositiveInteger($id, 'Id inv&aacute;lido.');
		return LotDAM::getInstance($id, $product);
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
		Number::validateUnsignedNumber($lastPrice, 'Precio antiguo inv&aacute;lido.');
		Number::validateUnsignedNumber($newPrice, 'Nuevo precio inv&aacute;lido.');
		
		$date = date('d/m/Y');
		$helper = SessionHelper::getInstance();
		ChangePriceLogDAM::insert($date, $helper->getUser(), $product, $lastPrice, $newPrice);
	}
}


/**
 * Utility class for making product searches.
 * @package Product
 * @author Roberto Oliveros
 */
class ProductSearch{
	/**
	 * Returns an array with the results of the search.
	 * 
	 * The array contains the fields bar_code, name.
	 * @param string $searchString
	 * @return array
	 */
	static public function search($searchString){
		String::validateString($searchString, 'Valor inv&aacute;lido.');
		return ProductSearchDAM::getList($searchString);
	}
}


/**
 * Utility for obtaining a list of products belonging to a specific manufacturer.
 * @package Product
 * @author Roberto Oliveros
 */
class ManufacturerProductList{
	/**
	 * Returns an array with the products' id, name and packaging that belongs to the provided manufacturer.
	 *
	 * @param Manufacturer $obj
	 * @return array
	 */
	static public function getList(Manufacturer $obj){
		return ManufacturerProductListDAM::getList($obj);
	}
}


/**
 * Class for creating a kardex report.
 * @package Product
 * @author Roberto Oliveros
 */
class Kardex{
	/**
	 * Returns an array with the kardex details of the product provided.
	 *
	 * The array's fields are date, document, number, entry, withdraw and balance. The balance
	 * argument returns it respective value. If no page argument or cero is passed all the details are
	 * returned. The total_pages and total_items arguments are necessary to return their respective values.
	 * @param Product $product
	 * @param integer &$balance
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList(Product $product, &$balance, &$total_pages = 0,
			&$total_items = 0, $page = 0){
		Persist::validateObjectFromDatabase($product);
		if($page !== 0)
			Number::validatePositiveInteger($page, 'Pagina inv&accute;lida.');
			
		return KardexDAM::getList($product, $balance, $total_pages, $total_items, $page);
	}
}


/**
 * Utility class for obtaining a list of bonuses.
 * @package Product
 * @author Roberto Oliveros
 */
class ProductBonusList{
	/**
	 * Returns an array containing the bonus details belonging to the provided product.
	 *
	 * The array's fields are id, quantity, percentage, created_date and expiration_date.
	 * @param Product $product
	 * @return array
	 */
	static public function getList(Product $product){
		Persist::validateObjectFromDatabase($product);
		return ProductBonusListDAM::getList($product);
	}
}


/**
 * Utility class for generating the report.
 * @package Product
 * @author Roberto Oliveros
 */
class NegativeBalanceProductList{
	/**
	 * Returns an array containging the data of the products which has a negative balance.
	 *
	 * The array contains the fields bar_code, manufacturer, name, packaging, general_quantity, lots_quantity
	 * and balance. If no page argument or cero is passed all the details are returned. The total_pages and
	 * total_items arguments are necessary to return their respective values.
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$total_pages = 0, &$total_items = 0, $page = 0){
		if($page !== 0)
			Number::validatePositiveInteger($page, 'Pagina inv&accute;lida.');
			return NegativeBalanceProductListDAM::getList($total_pages, $total_items, $page);
	}
}


/**
 * Utility class for creating the report.
 * @package Product
 * @author Roberto Oliveros
 */
class InactiveProductList{
	/**
	 * Returns an array with the details of the products which have not seen activity during the days provided.
	 *
	 * The array fields are bar_code, manufacturer, name, packaging, quantity, last_sale and sale_quantity. If
	 * no page argument or cero is passed all the details are returned. The total_pages and total_items
	 * arguments are necessary to return their respective values.
	 * @param integer $days
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList($days, &$total_pages = 0, &$total_items = 0, $page = 0){
		Number::validatePositiveInteger($days, 'N&uacute;mero de dias inv&aacute;lido.');
		if($page !== 0)
			Number::validatePositiveInteger($page, 'Pagina inv&aacute;lida.');
			
		return InactiveProductListDAM::getList($days, $total_pages, $total_items, $page);
	}
}


/**
 * Utility for obtaining a list of products belonging to a specific supplier.
 * @package Product
 * @author Roberto Oliveros
 */
class SupplierProductList{
	/**
	 * Returns an array with the products' id and name that belongs to the provided supplier.
	 *
	 * The total_pages and total_items arguments are necessary to return their respective values.
	 * @param Supplier $obj
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList(Supplier $obj, &$total_pages = 0, &$total_items = 0, $page = 1){
		Persist::validateObjectFromDatabase($obj);
		Number::validatePositiveInteger($page, 'Pagina inv&aacute;lida.');
		return SupplierProductListDAM::getList($obj, $total_pages, $total_items, $page);
	}
}


/**
 * Utility class for obtaining a list of reserves belonging to certain product.
 * @package Product
 * @author Roberto Oliveros
 */
class ReserveList{
	/**
	 * Returns an array containing the details of the reserves belonging to the provided product.
	 *
	 * The array's fields are id, date, username, lot_id and quantity.
	 * @param Product $product
	 * @return array
	 */
	static public function getList(Product $product){
		Persist::validateObjectFromDatabase($product);
		return ReserveListDAM::getList($product);
	}
}


/**
 * Utility class for obtaining a list of products' names.
 * @package Product
 * @author Roberto Oliveros
 */
class ProductDistinctList{
	/**
	 * Returns an array containing the names of all the products.
	 *
	 * The array's field is name.
	 * @return array
	 */
	static public function getList(){
		return ProductDistinctListDAM::getList();
	}
}


/**
 * Utility class for obtaining a list of manufacturers' names.
 * @package Product
 * @author Roberto Oliveros
 */
class ManufacturerDistinctList{
	/**
	 * Returns an array containing the names of all the manufacturers.
	 *
	 * The array's field is name.
	 * @return array
	 */
	static public function getList(){
		return ManufacturerDistinctListDAM::getList();
	}
}
?>