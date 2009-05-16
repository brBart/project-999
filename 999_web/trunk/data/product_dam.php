<?php
/**
 * Library with classes for accessing the database tables regarding products.
 * @package ProductDAM
 * @author Roberto Oliveros
 */

/**
 * Class for accessing unit of measure tables in the database.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class UnitOfMeasureDAM{
	static private $_mName = 'Unitario';
	
	/**
	 * Returns an instance of a unit of measure.
	 *
	 * Returns NULL if there was no match of the provided id in the database.
	 * @param integer $id
	 * @return UnitOfMeasure
	 */
	static public function getInstance($id){
		if($id == 123){
			$um = new UnitOfMeasure($id, PersistObject::CREATED);
			$um->setData(self::$_mName);
			return $um;
		}
		else
			return NULL;
	}
	
	/**
	 * Insert the object's data in the database.
	 *
	 * Returns the new created id from the database.
	 * @param UnitOfMeasure $obj
	 * @return integer
	 */
	static public function insert(UnitOfMeasure $obj){
		return 123;
	}
	
	/**
	 * Updates the object's data in the database.
	 *
	 * @param UnitOfMeasure $obj
	 */
	static public function update(UnitOfMeasure $obj){
		self::$_mName = $obj->getName();
	}
	
	/**
	 * Deletes the object from the database.
	 *
	 * Returns true on success, otherwise false due dependencies.
	 * @param UnitOfMeasure $obj
	 * @return boolean
	 */
	static public function delete(UnitOfMeasure $obj){
		if($obj->getId() == 123)
			return true;
		else
			return false;
	}
}


/**
 * Class for accessing manufacturer's tables in the database.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class ManufacturerDAM{
	static private $_mName = 'Bayer';
	
	/**
	 * Returns an instance of a manufacturer.
	 *
	 * Returns NULL if there was no match of the provided id in the database.
	 * @param integer $id
	 * @return Manufacturer
	 */
	static public function getInstance($id){
		if($id == 123){
			$manufacturer = new Manufacturer($id, PersistObject::CREATED);
			$manufacturer->setData(self::$_mName);
			return $manufacturer;
		}
		else
			return NULL;
	}
	
	/**
	 * Insert the object's data in the database.
	 *
	 * Returns the new created id from the database.
	 * @param Manufacturer $obj
	 * @return integer
	 */
	static public function insert(Manufacturer $obj){
		return 123;
	}
	
	/**
	 * Updates the object's data in the database.
	 *
	 * @param Manufacturer $obj
	 */
	static public function update(Manufacturer $obj){
		self::$_mName = $obj->getName();
	}
	
	/**
	 * Deletes the object from the database.
	 *
	 * Returns true on success, otherwise false due dependencies.
	 * @param Manufacturer $obj
	 * @return boolean
	 */
	static public function delete(Manufacturer $obj){
		if($obj->getId() == 123)
			return true;
		else
			return false;
	}
}


/**
 * Class for accessing the database tables regarding products and lots.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class InventoryDAM{
	static private $_mQuantity123 = 20;
	static private $_mReserve123 = 8;
	
	static private $_mQuantity124 = 45;
	static private $_mReserve124 = 15;
	
	static private $_mQuantity125 = 70;
	static private $_mReserve125 = 10;
	
	/**
	 * Returns the available quantity of the product's inventory.
	 *
	 * @param Product $obj
	 * @return integer
	 */
	static public function getAvailable(Product $obj){
		switch($obj->getId()){
			case 123:
				return self::$_mQuantity123 - self::$_mReserve123;
				break;
				
			case 124:
				return self::$_mQuantity124 - self::$_mReserve124;
				break;
				
			case 125:
				return self::$_mQuantity125 - self::$_mReserve125;
				break;
				
			default:
		}	
	}
	
	/**
	 * Returns the quantity on hand of the product's inventory.
	 *
	 * @param Product $obj
	 * @return integer
	 */
	static public function getQuantity(Product $obj){
		switch($obj->getId()){
			case 123:
				return self::$_mQuantity123;
				break;
				 
			case 124:
				return self::$_mQuantity124;
				break;
				
			case 125:
				return self::$_mQuantity125;
				break;
				
			default:
		}	
	}
	
	/**
	 * Returns the lots with available quantities of the provided product.
	 *
	 * Returns an array with all the lots that contains available quantities.
	 * @param Product $obj
	 * @return array<Lot>
	 */
	static public function getLots(Product $obj){
		switch($obj->getId()){
			case 123:
				$lots = array();
				
				$lot = Lot::getInstance(4321);
				if($lot->getAvailable() > 0)
					$lots[] = $lot;
					
				$lot = Lot::getInstance(4322);
				if($lot->getAvailable() > 0)
					$lots[] = $lot;
				
				return $lots;
				break;
				
			case 124:
				$lots = array();
				
				$lot = Lot::getInstance(5432);
				if($lot->getAvailable() > 0)
					$lots[] = $lot;
					
				$lot = Lot::getInstance(5433);
				if($lot->getAvailable() > 0)
					$lots[] = $lot;
					
				return $lots;
				break;
				
			case 125:
				$lots = array();
				
				$lot = Lot::getInstance(6543);
				if($lot->getAvailable() > 0)
					$lots[] = $lot;
					
				$lot = Lot::getInstance(6544);
				if($lot->getAvailable() > 0)
					$lots[] = $lot;
					
				return $lots;
				break;
				
			default:
		}
	}
	
	/**
	 * Returns the lots of the provided product with negative quantities.
	 *
	 * Returns an array with all the lots that contains negative quantities.
	 * @param Product $obj
	 * @return array<Lot>
	 */
	static public function getNegativeLots(Product $obj){
		if($obj->getId() == 123){
			$lots = array();
			$lots[] = Lot::getInstance(4320);
			return $lots;
		}
	}
	
	/**
	 * Increases the product's quantity in the database.
	 *
	 * @param Product $product
	 * @param integer $quantity
	 */
	static public function increase(Product $product, $quantity){
		switch($product->getId()){
			case 123:
				self::$_mQuantity123 += $quantity;
				break;
				
			case 124:
				self::$_mQuantity124 += $quantity;
				break;
				
			case 125:
				self::$_mQuantity125 += $quantity;
				break;
				
			default:
		}
	}
	
	/**
	 * Decreases the product's quantity in the database.
	 *
	 * @param Product $product
	 * @param integer $quantity
	 */
	static public function decrease(Product $product, $quantity){
		switch($product->getId()){
			case 123:
				self::$_mQuantity123 -= $quantity;
				break;
				
			case 124:
				self::$_mQuantity124 -= $quantity;
				break;
				
			case 125:
				self::$_mQuantity125 -= $quantity;
				break;
				
			default:
		}
	}
	
	/**
	 * Reserves the specified quantity for the provided product in the database.
	 *
	 * @param Product $product
	 * @param integer $quantity
	 */
	static public function reserve(Product $product, $quantity){
		switch($product->getId()){
			case 123:
				self::$_mReserve123 += $quantity;
				break;
				
			case 124:
				self::$_mReserve124 += $quantity;
				break;
				
			case 125:
				self::$_mReserve125 += $quantity;
				break;
				
			default:
		}
	}
	
	/**
	 * Decreases the product's reserve by the quantity provided in the database.
	 *
	 * @param Product $product
	 * @param integer $quantity
	 */
	static public function decreaseReserve(Product $product, $quantity){
		switch($product->getId()){
			case 123:
				self::$_mReserve123 -= $quantity;
				break;
				
			case 124:
				self::$_mReserve124 -= $quantity;
				break;
				
			case 125:
				self::$_mReserve125 -= $quantity;
				break;
				
			default:
		}
	}
}


/**
 * Class in charge of accessing the database tables regarding the product's suppliers.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class ProductSupplierDAM{
	/**
	 * Inserts the product detail's data into the database.
	 *
	 * @param Product $product
	 * @param ProductSupplier $detail
	 */
	static public function insert(Product $product, ProductSupplier $detail){
		// Code here...
	}
	
	/**
	 * Deletes the product detail from the database.
	 *
	 * @param Product $product
	 * @param ProductSupplier $detail
	 */
	static public function delete(Product $product, ProductSupplier $detail){
		// Code here...
	}
}


/**
 * Class responsible for accessing the product's tables in the database.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class ProductDAM{
	static private $_mPackaging = '120 ml';
	
	/**
	 * Returns true if a product already uses the bar code in the database.
	 *
	 * @param string $barCode
	 * @return boolean
	 */
	static public function existsBarCode(Product $product, $barCode){
		if($barCode == '123456')
			return true;
		else
			return false;
	}
	
	/**
	 * Verifies if the product detail already exists in the database.
	 *
	 * @param ProductSupplier $detail
	 * @return boolean
	 */
	static public function existsProductSupplier(ProductSupplier $detail){
		if($detail->getId() == '123ABC')
			return true;
		else
			return false;
	}
	
	/**
	 * Sets the bar code of an existing product.
	 *
	 * @param string $barCode
	 */
	static public function setBarCode($barCode){
		// Code here...
	}
	
	/**
	 * Returns an instance of a product.
	 *
	 * Returns NULL in case there was no match for the provided id in the database.
	 * @param integer $id
	 * @return Product
	 */
	static public function getInstance($id){
		switch($id){
			case 123:
				$product = new Product($id, Persist::CREATED);
				$um = UnitOfMeasure::getInstance(123);
				$manufacturer = Manufacturer::getInstance(123);
				$details = array();
				$details[] = new ProductSupplier(Supplier::getInstance(123), 'Abb213', Persist::CREATED);
				$product->setData('Pepto Bismol', '12345', self::$_mPackaging, 'Para dolores de estomagol.', $um,
						$manufacturer, 12.65, false, $details);
				return $product;
				break;
				
			case 124:
				$product = new Product($id, Persist::CREATED);
				$um = UnitOfMeasure::getInstance(123);
				$manufacturer = Manufacturer::getInstance(123);
				$details = array();
				$details[] = new ProductSupplier(Supplier::getInstance(123), 'Bom214', Persist::CREATED);
				$product->setData('Aspirina', '65432', self::$_mPackaging, 'Para dolores de cabeza.', $um,
						$manufacturer, 7.90, false, $details);
				return $product;
				break;
				
			case 125:
				$product = new Product($id, Persist::CREATED);
				$um = UnitOfMeasure::getInstance(123);
				$manufacturer = Manufacturer::getInstance(123);
				$details = array();
				$details[] = new ProductSupplier(Supplier::getInstance(123), 'Raf214', Persist::CREATED);
				$product->setData('Pharmaton', '35138', self::$_mPackaging, 'Puras vitaminas.', $um,
						$manufacturer, 65.73, false, $details);
				return $product;
				break;
				
			default:
				return NULL;
		}	
	}
	
	/**
	 * Returns an instance of a product.
	 *
	 * Returns a product whick bar code matches the one provided. If not found returns NULL.
	 * @param string $barCode
	 * @return Product
	 */
	static public function getInstanceByBarCode($barCode){
		if($barCode == '12345'){
			$product = new Product(123, Persist::CREATED);
			$um = UnitOfMeasure::getInstance(123);
			$manufacturer = Manufacturer::getInstance(123);
			$details = array();
			$details[] = new ProductSupplier(Supplier::getInstance(123), 'Abb213', Persist::CREATED);
			$product->setData('Pepto Bismol', '12345', self::$_mPackaging, 'Para dolores de estomagol.', $um,
					$manufacturer, 12.65, false, $details);
			return $product;
		}
		else
			return NULL;
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
		if($supplier->getId() == 123 && $sku == 'Abb213'){
			$product = new Product(123, Persist::CREATED);
			$um = UnitOfMeasure::getInstance(123);
			$manufacturer = Manufacturer::getInstance(123);
			$details = array();
			$details[] = new ProductSupplier($supplier, 'Abb213', Persist::CREATED);
			$product->setData('Pepto Bismol', '12345', self::$_mPackaging, 'Para dolores de estomagol.', $um,
					$manufacturer, 12.65, false, $details);
			return $product;
		}
		else
			return NULL;
	}

	/**
	 * Inserts the product's data into the database.
	 *
	 * Returns the new created id from the database.
	 * @param Product $obj
	 * @return integer
	 */
	static public function insert(Product $obj){
		return 123;
	}
	
	/**
	 * Updates the product's data in the database.
	 *
	 * @param Product $obj
	 */
	static public function update(Product $obj){
		self::$_mPackaging = $obj->getPackaging();
	}
	
	/**
	 * Deletes the object from the database.
	 *
	 * Returns true on success, otherwise false due dependencies.
	 * @param Product $obj
	 * @return boolean
	 */
	static public function delete(Product $obj){
		if($obj->getId() == 123)
			return true;
		else
			return false;
	}
}


/**
 * Class in charge of accessing database tables regarding the bonus.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class BonusDAM{
	/**
	 * Verifies if the bonus already exists in the database.
	 *
	 * Returns true if it does.
	 * @param Product $product
	 * @param integer $quantity
	 * @return boolean
	 */
	static public function exists(Product $product, $quantity){
		if($product->getId() == 123 && $quantity == 4)
			return true;
		else
			return false;
	}
	
	/**
	 * Returns an instance of a bonus.
	 *
	 * Returns NULL if there was no match for the provided id in the database.
	 * @param integer $id
	 * @return Bonus
	 */
	static public function getInstance($id){
		if($id == 123){
			$product = Product::getInstance(123);
			$bonus = new Bonus($product, 4, 25.00, '15/05/2009', '01/04/2009', $id, Persist::CREATED);
			return $bonus;
		}
		else
			return NULL;
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
		switch($product->getId()){
			case 123:
				if($quantity >= 4)
					return new Bonus($product, 4, 25.00, '15/05/2009', '01/04/2009', 123, Persist::CREATED);
				break;
				
			case 124:
				if($quantity >= 11)
					return new Bonus($product, 11, 15.00, '15/06/2009', '01/04/2009', 125, Persist::CREATED);
				elseif($quantity >= 4)
					return new Bonus($product, 4, 5.00, '15/06/2009', '01/04/2009', 124, Persist::CREATED);
				break;
				
			case 125:
				if($quantity >= 11)
					return new Bonus($product, 11, 25.00, '15/06/2009', '01/04/2009', 127, Persist::CREATED);
				elseif($quantity >= 5)
					return new Bonus($product, 5, 15.00, '15/06/2009', '01/04/2009', 126, Persist::CREATED);
				break;
				
			default:
				return NULL;
		}
	}
	
	/**
	 * Inserts the bonus' data in the database.
	 *
	 * Returns the new created id from the database.
	 * @param Bonus $obj
	 * @return integer
	 */
	static public function insert(Bonus $obj){
		return 123;
	}
	
	/**
	 * Deletes the object from the database.
	 *
	 * Returns true on success, otherwise false due dependencies.
	 * @param Bonus $obj
	 * @return boolean
	 */
	static public function delete(Bonus $obj){
		if($obj->getId() == 123)
			return true;
		else
			return false;
	}
}


/**
 * Class for accessing database tables regarding lots.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class LotDAM{
	static private $_mQuantity123 = 18;
	static private $_mReserve123 = 3;
	
	static private $_mQuantity4320 = -3;
	static private $_mReserve4320 = 0;
	static private $_mNegative4320 = 0;
	
	static private $_mQuantity4321 = 8;
	static private $_mReserve4321 = 4;
	
	static private $_mQuantity4322 = 12;
	static private $_mReserve4322 = 4;
	
	static private $_mQuantity5432 = 15;
	static private $_mReserve5432 = 10;
	
	static private $_mQuantity5433 = 30;
	static private $_mReserve5433 = 5;
	
	static private $_mQuantity6543 = 40;
	static private $_mReserve6543 = 10;
	
	static private $_mQuantity6544 = 30;
	static private $_mReserve6544 = 0;
	
	/**
	 * Returns the lot's quantity.
	 *
	 * @param Lot $obj
	 * @return integer
	 */
	static public function getQuantity(Lot $obj){
		switch($obj->getId()){
			case 123:
				return self::$_mQuantity123;
				break;
				
			case 4320:
				return self::$_mQuantity4320;
				break;
			
			case 4321:
				return self::$_mQuantity4321;
				break;
				
			case 4322;
				return self::$_mQuantity4322;
				break;
				
			case 5432:
				return self::$_mQuantity5432;
				break;
				
			case 5433;
				return self::$_mQuantity5433;
				break;
				
			case 6543:
				return self::$_mQuantity6543;
				break;
				
			case 6544;
				return self::$_mQuantity6544;
				break;

			default:
				return 0;
		}
	}
	
	/**
	 * Returns the quantity available of the lot.
	 *
	 * @param Lot $obj
	 * @return integer
	 */
	static public function getAvailable(Lot $obj){
		switch($obj->getId()){
			case 123:
				return self::$_mQuantity123 - self::$_mReserve123;
				break;
				
			case 4320:
				return self::$_mQuantity4320 - self::$_mReserve4320;
				break;
			
			case 4321:
				return self::$_mQuantity4321 - self::$_mReserve4321;
				break;
				
			case 4322;
				return self::$_mQuantity4322 - self::$_mReserve4322;
				break;
				
			case 5432:
				return self::$_mQuantity5432 - self::$_mReserve5432;
				break;
				
			case 5433;
				return self::$_mQuantity5433 - self::$_mReserve5433;
				break;
				
			case 6543:
				return self::$_mQuantity6543 - self::$_mReserve6543;
				break;
				
			case 6544;
				return self::$_mQuantity6544 - self::$_mReserve6544;
				break;
				
			default:
				return 0;
		}
	}
	
	/**
	 * Returns the negative quantity of a negative lot.
	 *
	 * @param NegativeLot $obj
	 * @return integer
	 */
	static public function getNegativeQuantity(NegativeLot $obj){
		if($obj->getId() == 4320)
			return self::$_mNegative4320;
		else
			return 0;
	}
	
	/**
	 * Deactivates the lot in the database.
	 *
	 * @param Lot $obj
	 */
	static public function deactivate(Lot $obj){
		switch($obj->getId()){
			case 123:
				self::$_mQuantity123 = 0;
				self::$_mReserve123 = 0;
				break;
				
			case 4320:
				self::$_mQuantity4320 = 0;
				self::$_mReserve4320 = 0;
				break;
			
			case 4321:
				self::$_mQuantity4321 = 0;
				self::$_mReserve4321 = 0;
				break;
				
			case 4322;
				self::$_mQuantity4322 = 0;
				self::$_mReserve4322 = 0;
				break;
				
			case 5432:
				self::$_mQuantity5432 = 0;
				self::$_mReserve5432 = 0;
				break;
				
			case 5433;
				self::$_mQuantity5433 = 0;
				self::$_mReserve5433 = 0;
				break;
				
			case 6543:
				self::$_mQuantity6543 = 0;
				self::$_mReserve6543 = 0;
				break;
				
			case 6544;
				self::$_mQuantity6544 = 0;
				self::$_mReserve6544 = 0;
				break;

			default:
				// Do nothing
		}
	}
	
	/**
	 * Sets the lot's price in the database.
	 *
	 * @param Lot $obj
	 * @param float $price
	 */
	static public function setPrice(Lot $obj, $price){
		// Code here...
	}
	
	/**
	 * Sets the lot's expiration date in the database.
	 *
	 * @param Lot $obj
	 * @param string $date
	 */
	static public function setExpirationDate(Lot $obj, $date){
		// Code here...
	}
	
	/**
	 * Increases the lot's quantity in the database.
	 *
	 * @param Lot $obj
	 * @param integer $quantity
	 */
	static public function increase(Lot $obj, $quantity){
		switch($obj->getId()){
			case 123:
				self::$_mQuantity123 += $quantity;
				break;
				
			case 4320:
				self::$_mQuantity4320 += $quantity;
				break;
			
			case 4321:
				self::$_mQuantity4321 += $quantity;
				break;
				
			case 4322;
				self::$_mQuantity4322 += $quantity;
				break;
				
			case 5432:
				self::$_mQuantity5432 += $quantity;
				break;
				
			case 5433;
				self::$_mQuantity5433 += $quantity;
				break;
				
			case 6543:
				self::$_mQuantity6543 += $quantity;
				break;
				
			case 6544;
				self::$_mQuantity6544 += $quantity;
				break;

			default:
				// Do nothing
		}
	}
	
	/**
	 * Decrease the lot's quantity in the database.
	 *
	 * @param Lot $obj
	 * @param integer $quantity
	 */
	static public function decrease(Lot $obj, $quantity){
		switch($obj->getId()){
			case 123:
				self::$_mQuantity123 -= $quantity;
				break;
				
			case 4320:
				self::$_mQuantity4320 -= $quantity;
				break;
			
			case 4321:
				self::$_mQuantity4321 -= $quantity;
				break;
				
			case 4322;
				self::$_mQuantity4322 -= $quantity;
				break;
				
			case 5432:
				self::$_mQuantity5432 -= $quantity;
				break;
				
			case 5433;
				self::$_mQuantity5433 -= $quantity;
				break;
				
			case 6543:
				self::$_mQuantity6543 -= $quantity;
				break;
				
			case 6544;
				self::$_mQuantity6544 -= $quantity;
				break;

			default:
				// Do nothing
		}
	}
	
	/**
	 * Reserves the provided quantity in the database.
	 *
	 * @param Lot $obj
	 * @param integer $quantity
	 */
	static public function reserve(Lot $obj, $quantity){
		switch($obj->getId()){
			case 123:
				self::$_mReserve123 += $quantity;
				break;
				
			case 4320:
				self::$_mReserve4320 += $quantity;
				break;
			
			case 4321:
				self::$_mReserve4321 += $quantity;
				break;
				
			case 4322;
				self::$_mReserve4322 += $quantity;
				break;
				
			case 5432:
				self::$_mReserve5432 += $quantity;
				break;
				
			case 5433;
				self::$_mReserve5433 += $quantity;
				break;
				
			case 6543:
				self::$_mReserve6543 += $quantity;
				break;
				
			case 6544;
				self::$_mReserve6544 += $quantity;
				break;

			default:
				// Do nothing
		}
	}
	
	/**
	 * Decreases the reserve quantity of the lot in the database.
	 *
	 * @param Lot $obj
	 * @param integer $quantity
	 */
	static public function decreaseReserve(Lot $obj, $quantity){
		switch($obj->getId()){
			case 123:
				self::$_mReserve123 -= $quantity;
				break;
				
			case 4320:
				self::$_mReserve4320 -= $quantity;
				break;
			
			case 4321:
				self::$_mReserve4321 -= $quantity;
				break;
				
			case 4322;
				self::$_mReserve4322 -= $quantity;
				break;
				
			case 5432:
				self::$_mReserve5432 -= $quantity;
				break;
				
			case 5433;
				self::$_mReserve5433 -= $quantity;
				break;
				
			case 6543:
				self::$_mReserve6543 -= $quantity;
				break;
				
			case 6544;
				self::$_mReserve6544 -= $quantity;
				break;

			default:
				// Do nothing
		}
	}
	
	/**
	 * Updates the negative quantity of a negative lot.
	 *
	 * @param NegativeLot $log
	 * @param integer $quantity
	 */
	static public function updateNegativeQuantity(NegativeLot $lot, $quantity){
		if($lot->getId() == 4320)
			self::$_mNegative4320 = $quantity;
	}
	
	/**
	 * Returns an instance of a lot.
	 *
	 * Returns NULL if there was no match for the provided id in the database.
	 * @param integer $id
	 * @return Lot
	 */
	static public function getInstance($id){
		$product = Product::getInstance(123);
		$product2 = Product::getInstance(124);
		$product3 = Product::getInstance(125);
		switch($id){
			case 123:
				$lot = new Lot($product, 0, 12.65, '31/12/2009', '15/04/2009', $id, Persist::CREATED);
				return $lot;
				break;
				
			case 4320:
				$lot = new NegativeLot($product, 0, 0.00, NULL, NULL, 4320, Persist::CREATED);
				return $lot;
				break; 

			case 4321:
				$lot = new Lot($product, 0, 14.68, '15/08/2009', '10/01/2009', $id, Persist::CREATED);
				return $lot;
				break;
				
			case 4322:
				$lot = new Lot($product, 0, 15.25, '15/11/2009', '15/02/2009', $id, Persist::CREATED);
				return $lot;
				break;
				
			case 5432:
				$lot = new Lot($product2, 0, 7.10, '15/12/2009', '10/01/2009', $id, Persist::CREATED);
				return $lot;
				break;
				
			case 5433:
				$lot = new Lot($product2, 0, 7.55, '20/12/2009', '15/02/2009', $id, Persist::CREATED);
				return $lot;
				break;
				
			case 6543:
				$lot = new Lot($product3, 0, 65.00, '15/12/2009', '10/01/2009', $id, Persist::CREATED);
				return $lot;
				break;
				
			case 6544:
				$lot = new Lot($product3, 0, 64.55, '20/12/2009', '15/02/2009', $id, Persist::CREATED);
				return $lot;
				break;
				
			default:
				return NULL;
		}
	}
	
	/**
	 * Inserts the lot's data in the database.
	 *
	 * Returns the new created id from the database.
	 * @param Lot $obj
	 * @return integer
	 */
	static public function insert(Lot $obj){
		return 123;
	}
}


/**
 * Utility class for logging in the database any change price event.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class ChangePriceLogDAM{
	/**
	 * Logs the event in the database.
	 *
	 * Date format: 'dd/mm/yyyy'.
	 * @param string $date
	 * @param UserAccount $user
	 * @param Product $product
	 * @param float $lastPrice
	 * @param float $newPrice
	 */
	static public function write($date, UserAccount $user, Product $product, $lastPrice, $newPrice){
		// Code here...
	}
}


/**
 * Utility class for accessing the database for searches.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class ProductSearchDAM{
	/**
	 * Makes a search for a product in the database.
	 *
	 * The array returned contains the fields bar_code, name.
	 * @param string $searchString
	 * @return array
	 */
	static public function search($searchString){
		return array(array('bar_code' => '3245325', 'name' => 'Aspirina Ni�os'), array('bar_code' => '3245325',
				'name' => 'Aspirina Adultos'));
	}
}
?>