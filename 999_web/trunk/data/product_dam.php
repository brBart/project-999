<?php
/**
 * Library with classes for accessing the database tables regarding products.
 * @package ProductDAM
 * @author Roberto Oliveros
 */

/**
 * For accessing the database.
 */
require_once('data/database_handler.php');

/**
 * Class for accessing unit of measure tables in the database.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class UnitOfMeasureDAM{
	/**
	 * Returns an instance of a unit of measure.
	 *
	 * Returns NULL if there was no match of the provided id in the database.
	 * @param integer $id
	 * @return UnitOfMeasure
	 */
	static public function getInstance($id){
		$sql = 'CALL unit_of_measure_get(:unit_of_measure_id)';
		$params = array(':unit_of_measure_id' => $id);
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			$um = new UnitOfMeasure($id, Persist::CREATED);
			$um->setData($result['name']);
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
		$sql = 'CALL unit_of_measure_insert(:name)';
		$params = array(':name' => $obj->getName());
		DatabaseHandler::execute($sql, $params);
		
		$sql = 'CALL get_last_insert_id()';
		return (int)DatabaseHandler::getOne($sql);
	}
	
	/**
	 * Updates the object's data in the database.
	 *
	 * @param UnitOfMeasure $obj
	 */
	static public function update(UnitOfMeasure $obj){
		$sql = 'CALL unit_of_measure_update(:unit_of_measure_id, :name)';
		$params = array(':unit_of_measure_id' => $obj->getId(), ':name' => $obj->getName());
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Deletes the object from the database.
	 *
	 * Returns true on success, otherwise false due dependencies.
	 * @param UnitOfMeasure $obj
	 * @return boolean
	 */
	static public function delete(UnitOfMeasure $obj){
		$sql = 'CALL unit_of_measure_dependencies(:unit_of_measure_id)';
		$params = array(':unit_of_measure_id' => $obj->getId());
		$result = DatabaseHandler::getOne($sql, $params);
		
		// If there are dependencies in the product table.
		if($result) return false;
		
		$sql = 'CALL unit_of_measure_delete(:unit_of_measure_id)';
		DatabaseHandler::execute($sql, $params);
		return true;
	}
}


/**
 * Class for accessing manufacturer's tables in the database.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class ManufacturerDAM{
	/**
	 * Returns an instance of a manufacturer.
	 *
	 * Returns NULL if there was no match of the provided id in the database.
	 * @param integer $id
	 * @return Manufacturer
	 */
	static public function getInstance($id){
		$sql = 'CALL manufacturer_get(:manufacturer_id)';
		$params = array(':manufacturer_id' => $id);
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			$manufacturer = new Manufacturer($id, Persist::CREATED);
			$manufacturer->setData($result['name']);
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
		$sql = 'CALL manufacturer_insert(:name)';
		$params = array(':name' => $obj->getName());
		DatabaseHandler::execute($sql, $params);
		
		$sql = 'CALL get_last_insert_id()';
		return (int)DatabaseHandler::getOne($sql);
	}
	
	/**
	 * Updates the object's data in the database.
	 *
	 * @param Manufacturer $obj
	 */
	static public function update(Manufacturer $obj){
		$sql = 'CALL manufacturer_update(:manufacturer_id, :name)';
		$params = array(':manufacturer_id' => $obj->getId(), ':name' => $obj->getName());
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Deletes the object from the database.
	 *
	 * Returns true on success, otherwise false due dependencies.
	 * @param Manufacturer $obj
	 * @return boolean
	 */
	static public function delete(Manufacturer $obj){
		$sql = 'CALL manufacturer_dependencies(:manufacturer_id)';
		$params = array(':manufacturer_id' => $obj->getId());
		$result = DatabaseHandler::getOne($sql, $params);
		
		// If there are dependencies in the product table.
		if($result) return false;
		
		$sql = 'CALL manufacturer_delete(:manufacturer_id)';
		DatabaseHandler::execute($sql, $params);
		return true;
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
		$sql = 'CALL product_supplier_insert(:product_id, :supplier_id, :sku)';
		$supplier = $detail->getSupplier();
		$params = array(':product_id' => $product->getId(), ':supplier_id' => $supplier->getId(),
				':sku' => $detail->getProductSKU());
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Deletes the product detail from the database.
	 *
	 * @param Product $product
	 * @param ProductSupplier $detail
	 */
	static public function delete(Product $product, ProductSupplier $detail){
		$sql = 'CALL product_supplier_delete(:product_id, :supplier_id)';
		$supplier = $detail->getSupplier();
		$params = array(':product_id' => $product->getId(), ':supplier_id' => $supplier->getId());
		DatabaseHandler::execute($sql, $params);
	}
}


/**
 * Class responsible for accessing the product's tables in the database.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class ProductDAM{
	/**
	 * Returns true if a product already uses the bar code in the database.
	 *
	 * @param string $barCode
	 * @return boolean
	 */
	static public function existsBarCode(Product $product, $barCode){
		$sql = 'CALL product_bar_code_exists(:product_id, :bar_code)';
		$params = array(':product_id' => (is_null($product->getId())) ? 0 : $product->getId(),
				':bar_code' => $barCode);
		return (boolean)DatabaseHandler::getOne($sql, $params);
	}
	
	/**
	 * Verifies if the product detail already exists in the database.
	 *
	 * @param ProductSupplier $detail
	 * @return boolean
	 */
	static public function existsProductSupplier(ProductSupplier $detail){
		$sql = 'CALL product_supplier_exists(:supplier_id, :sku)';
		$supplier = $detail->getSupplier();
		$params = array(':supplier_id' => $supplier->getId(), ':sku' => $detail->getProductSKU());
		return (boolean)DatabaseHandler::getOne($sql, $params);
	}
	
	/**
	 * Sets the bar code of an existing product.
	 *
	 * @param Product $obj
	 */
	static public function setBarCode(Product $obj){
		$sql = 'CALL product_bar_code_update(:product_id)';
		$params = array(':product_id' => $obj->getId());
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Returns an instance of a product.
	 *
	 * Returns NULL in case there was no match for the provided id in the database.
	 * @param integer $id
	 * @return Product
	 */
	static public function getInstance($id){
		$sql = 'CALL product_get(:product_id)';
		$params = array(':product_id' => $id);
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			$product = new Product($id, Persist::CREATED);
			$um = UnitOfMeasure::getInstance((int)$result['unit_of_measure_id']);
			$manufacturer = Manufacturer::getInstance((int)$result['manufacturer_id']);
			
			$sql = 'CALL product_supplier_get(:product_id)';
			$items_result = DatabaseHandler::getAll($sql, $params);
			
			$details = array();
			foreach($items_result as $detail){
				$supplier = Supplier::getInstance((int)$detail['supplier_id']);
				$details[] = new ProductSupplier($supplier, $detail['sku'], Persist::CREATED);
			}
			
			$product->setData($result['name'], $result['packaging'],
					$result['description'], $um, $manufacturer, (float)$result['price'],
					(boolean)$result['deactivated'], $details, $result['bar_code']);
			return $product;
		}
		else
			return NULL;
	}
	
	/**
	 * Returns the product's id of the provided bar code.
	 *
	 * If not found returns 0.
	 * @param string $barCode
	 * @return integer
	 */
	static public function getIdByBarCode($barCode){
		$sql = 'CALL product_id_get(:bar_code)';
		$params = array(':bar_code' => $barCode);
		return (int)DatabaseHandler::getOne($sql, $params);
	}
	
	/**
	 * Returns the product's id of the provided supplier and sku.
	 *
	 * If not found returns 0.
	 * @param Supplier $supplier
	 * @param string $sku
	 * @return integer
	 */
	static public function getIdBySupplier(Supplier $supplier, $sku){
		$sql = 'CALL product_supplier_product_id_get(:supplier_id, :sku)';
		$params = array(':supplier_id' => $supplier->getId(), ':sku' => $sku);
		return (int)DatabaseHandler::getOne($sql, $params);
	}

	/**
	 * Inserts the product's data into the database.
	 *
	 * Returns the new created id from the database.
	 * @param Product $obj
	 * @return integer
	 */
	static public function insert(Product $obj){
		$sql = 'CALL product_insert(:bar_code, :name, :packaging, :description, :unit_of_measure_id, ' .
				':manufacturer_id, :price, :deactivated)';
		$um = $obj->getUnitOfMeasure();
		$manufacturer = $obj->getManufacturer();
		$params = array(':bar_code' => $obj->getBarCode(), ':name' => $obj->getName(),
				':packaging' => $obj->getPackaging(), ':description' => $obj->getDescription(),
				':unit_of_measure_id' => $um->getId(), ':manufacturer_id' => $manufacturer->getId(),
				':price' => $obj->getPrice(), ':deactivated' => (int)$obj->isDeactivated());
		DatabaseHandler::execute($sql, $params);
		
		$sql = 'CALL get_last_insert_id()';
		return (int)DatabaseHandler::getOne($sql);
	}
	
	/**
	 * Updates the product's data in the database.
	 *
	 * @param Product $obj
	 */
	static public function update(Product $obj){
		$sql = 'CALL product_update(:product_id, :bar_code, :name, :packaging, :description, ' .
				':unit_of_measure_id, :manufacturer_id, :price, :deactivated)';
		$um = $obj->getUnitOfMeasure();
		$manufacturer = $obj->getManufacturer();
		$params = array(':product_id' => $obj->getId(), ':bar_code' => $obj->getBarCode(),
				':name' => $obj->getName(), ':packaging' => $obj->getPackaging(),
				':description' => $obj->getDescription(), ':unit_of_measure_id' => $um->getId(),
				':manufacturer_id' => $manufacturer->getId(), ':price' => $obj->getPrice(),
				':deactivated' => (int)$obj->isDeactivated());
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Deletes the object from the database.
	 *
	 * Returns true on success, otherwise false due dependencies.
	 * @param Product $obj
	 * @return boolean
	 */
	static public function delete(Product $obj){
		$sql = 'CALL product_dependencies(:product_id)';
		$params = array(':product_id' => $obj->getId());
		$result = DatabaseHandler::getOne($sql, $params);
		
		// If there are dependencies in the lot, bonus, comparison_product and count_product tables.
		if($result) return false;
		
		$sql = 'CALL product_delete(:product_id)';
		DatabaseHandler::execute($sql, $params);
		return true;
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
		$sql = 'CALL bonus_exists(:product_id, :quantity)';
		$params = array(':product_id' => $product->getId(), ':quantity' => $quantity);
		return (boolean)DatabaseHandler::getOne($sql, $params);
	}
	
	/**
	 * Returns an instance of a bonus.
	 *
	 * Returns NULL if there was no match for the provided id in the database.
	 * @param integer $id
	 * @return Bonus
	 */
	static public function getInstance($id){
		$sql = 'CALL bonus_get(:bonus_id)';
		$params = array(':bonus_id' => $id);
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			$product = Product::getInstance((int)$result['product_id']);
			$bonus = new Bonus($product, (int)$result['quantity'], (float)$result['percentage'],
					$result['expiration_date'], $result['created_date'], $id, Persist::CREATED);
			return $bonus;
		}
		else
			return NULL;
	}
	
	/**
	 * Returns an bonus' id which has the provided product and quantity.
	 *
	 * If not found returns 0.
	 * @param Product $product
	 * @param integer $quantity
	 * @return integer
	 */
	static public function getId(Product $product, $quantity){
		$sql = 'CALL bonus_id_get(:product_id, :quantity)';
		$params = array(':product_id' => $product->getId(), ':quantity' => $quantity);
		return (int)DatabaseHandler::getOne($sql, $params);
	}
	
	/**
	 * Inserts the bonus' data in the database.
	 *
	 * Returns the new created id from the database.
	 * @param Bonus $obj
	 * @return integer
	 */
	static public function insert(Bonus $obj){
		$sql = 'CALL bonus_insert(:product_id, :quantity, :percentage, :created_date, :expiration_date)';
		$product = $obj->getProduct();
		$params = array(':product_id' => $product->getId(), ':quantity' => $obj->getQuantity(),
				':percentage' => $obj->getPercentage(), ':created_date' => Date::dbFormat($obj->getCreatedDate()),
				':expiration_date' => Date::dbFormat($obj->getExpirationDate()));
		DatabaseHandler::execute($sql, $params);
		
		$sql = 'CALL get_last_insert_id()';
		return (int)DatabaseHandler::getOne($sql);
	}
	
	/**
	 * Deletes the object from the database.
	 *
	 * Returns true on success, otherwise false due dependencies.
	 * @param Bonus $obj
	 * @return boolean
	 */
	static public function delete(Bonus $obj){
		$sql = 'CALL bonus_dependencies(:bonus_id)';
		$params = array(':bonus_id' => $obj->getId());
		$result = DatabaseHandler::getOne($sql, $params);
		
		// If there are dependencies in the invoice_bonus table.
		if($result) return false;
		
		$sql = 'CALL bonus_delete(:bonus_id)';
		DatabaseHandler::execute($sql, $params);
		return true;
	}
}


/**
 * Class for accessing database tables regarding lots.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class LotDAM{
	/**
	 * Returns the lot's quantity.
	 *
	 * @param Lot $obj
	 * @return integer
	 */
	static public function getQuantity(Lot $obj){
		$sql = 'CALL lot_quantity_get(:lot_id)';
		$params = array(':lot_id' => $obj->getId());
		return (int)DatabaseHandler::getOne($sql, $params);
	}
	
	/**
	 * Returns the quantity available of the lot.
	 *
	 * @param Lot $obj
	 * @return integer
	 */
	static public function getAvailable(Lot $obj){
		$sql = 'CALL lot_available_quantity_get(:lot_id)';
		$params = array(':lot_id' => $obj->getId());
		return (int)DatabaseHandler::getOne($sql, $params);
	}
	
	/**
	 * Returns the negative quantity of a negative lot.
	 *
	 * @param NegativeLot $obj
	 * @return integer
	 */
	static public function getNegativeQuantity(NegativeLot $obj){
		$sql = 'CALL lot_negative_quantity_get(:lot_id)';
		$params = array(':lot_id' => $obj->getId());
		return (int)DatabaseHandler::getOne($sql, $params);
	}
	
	/**
	 * Deactivates the lot in the database.
	 *
	 * Sets the quantity and reserved quantity of the lot to 0.
	 * @param Lot $obj
	 */
	static public function deactivate(Lot $obj){
		$sql = 'CALL lot_deactivate(:lot_id)';
		$params = array(':lot_id' => $obj->getId());
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Sets the lot's price in the database.
	 *
	 * @param Lot $obj
	 * @param float $price
	 */
	static public function setPrice(Lot $obj, $price){
		$sql = 'CALL lot_price_update(:lot_id, :price)';
		$params = array(':lot_id' => $obj->getId(), ':price' => $price);
		DatabaseHandler::execute($sql ,$params);
	}
	
	/**
	 * Sets the lot's expiration date in the database.
	 *
	 * @param Lot $obj
	 * @param string $date
	 */
	static public function setExpirationDate(Lot $obj, $date){
		$sql = 'CALL lot_expiration_date_update(:lot_id, :expiration_date)';
		$params = array(':lot_id' => $obj->getId(), 'expiration_date' =>
				(is_null($date) || $date == '') ? NULL : Date::dbFormat($date));
		DatabaseHandler::execute($sql ,$params);
	}
	
	/**
	 * Increases the lot's quantity in the database.
	 *
	 * @param Lot $obj
	 * @param integer $quantity
	 */
	static public function increase(Lot $obj, $quantity){
		$sql = 'CALL lot_increase_quantity(:lot_id, :quantity)';
		$params = array(':lot_id' => $obj->getId(), ':quantity' => $quantity);
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Decrease the lot's quantity in the database.
	 *
	 * @param Lot $obj
	 * @param integer $quantity
	 */
	static public function decrease(Lot $obj, $quantity){
		$sql = 'CALL lot_decrease_quantity(:lot_id, :quantity)';
		$params = array(':lot_id' => $obj->getId(), ':quantity' => $quantity);
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Reserves the provided quantity in the database.
	 *
	 * @param Lot $obj
	 * @param integer $quantity
	 */
	static public function reserve(Lot $obj, $quantity){
		$sql = 'CALL lot_increase_reserved(:lot_id, :quantity)';
		$params = array(':lot_id' => $obj->getId(), ':quantity' => $quantity);
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Decreases the reserve quantity of the lot in the database.
	 *
	 * @param Lot $obj
	 * @param integer $quantity
	 */
	static public function decreaseReserve(Lot $obj, $quantity){
		$sql = 'CALL lot_decrease_reserved(:lot_id, :quantity)';
		$params = array(':lot_id' => $obj->getId(), ':quantity' => $quantity);
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Updates the negative quantity of a negative lot.
	 *
	 * @param NegativeLot $lot
	 * @param integer $quantity
	 */
	static public function updateNegativeQuantity(NegativeLot $lot, $quantity){
		$sql = 'CALL lot_negative_quantity_update(:lot_id, :quantity)';
		$params = array(':lot_id' => $lot->getId(), ':quantity' => $quantity);
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Returns an instance of a lot.
	 *
	 * Returns NULL if there was no match for the provided id in the database.
	 * @param integer $id
	 * @return Lot
	 */
	static public function getInstance($id){
		$sql = 'CALL lot_get(:lot_id)';
		$params = array(':lot_id' => $id);
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			$product = Product::getInstance((int)$result['product_id']);
			
			$quantity = (int)$result['quantity'];
			$negative = (int)$result['negative_quantity'];
			if($quantity < 0 || $negative < 0)
				$lot = new NegativeLot($product, $quantity, (float)$result['price'], $result['expiration_date'],
						$result['entry_date'], $id, Persist::CREATED);
			else
				$lot = new Lot($product, $quantity, (float)$result['price'], $result['expiration_date'],
						$result['entry_date'], $id, Persist::CREATED);
			
			return $lot;
		}
		return NULL;
	}
	
	/**
	 * Inserts the lot's data in the database.
	 *
	 * Returns the new created id from the database.
	 * @param Lot $obj
	 * @return integer
	 */
	static public function insert(Lot $obj){
		$sql = 'CALL lot_insert(:product_id, :entry_date, :expiration_date, :price, :quantity)';
		$product = $obj->getProduct();
		$expiration_date = $obj->getExpirationDate();
		$params = array(':product_id' => $product->getId(),
				':entry_date' => Date::dbFormat($obj->getEntryDate()),
				'expiration_date' =>
				(is_null($expiration_date) || $expiration_date == '') ? NULL : Date::dbFormat($expiration_date),
				':price' => $obj->getPrice(), ':quantity' => $obj->getQuantity());
		DatabaseHandler::execute($sql, $params);
		
		$sql = 'CALL get_last_insert_id()';
		return (int)DatabaseHandler::getOne($sql);
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
		return array(array('bar_code' => '3245325', 'name' => 'Aspirina Niños'), array('bar_code' => '3245325',
				'name' => 'Aspirina Adultos'));
	}
}


/**
 * Class for accessing database tables regarding manufacturers and products.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class ManufacturerProductListDAM{
	/**
	 * Returns an array with the products' id and name that belongs to the provided manufacturer.
	 *
	 * @param Manufacturer $obj
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList(Manufacturer $obj, &$total_pages, &$total_items, $page){
		$total_pages = 1;
		$total_items = 2;
		return array(array('id' => 123, 'name' => 'Pepto'), array('id' => 124, 'name' => 'Aspirina'));
	}
}


/**
 * Class for creating a kardex report.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class KardexDAM{
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
	static public function getData(Product $product, &$balance, &$total_pages, &$total_items, $page){
		$balance = 30;
		$total_pages = 1;
		$total_items = 2;
		return array(array('date' => '21/04/2009', 'document' => 'Factura', 'number' => 'A021-32442',
				'entry' => 0, 'withdraw' => 5, 'balance' => 25), array('date' => '29/04/2009',
				'document' => 'Recibo', 'number' => '98465', 'entry' => 10, 'withdraw' => 0, 'balance' => 35));
	}
}


/**
 * Utility class for obtaining a list of bonuses.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class ProductBonusListDAM{
	/**
	 * Returns an array containing the bonus details belonging to the provided product.
	 *
	 * The array's fields are id, quantity, percentage, created_date and expiration_date.
	 * @param Product $product
	 * @return array
	 */
	static public function getList(Product $product){
		return array(array('id' => 123, 'quantity' => 4, 'percentage' => 15, 'created_date' => '20/04/2009',
				'expiration_date' => '10/10/2009'), array('id' => 124, 'quantity' => 8, 'percentage' => 25,
				'created_date' => '30/04/2009', 'expiration_date' => '10/11/2009'));
	}
}


/**
 * Utility class for generating the report.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class NegativeBalanceProductListDAM{
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
	static public function getList(&$total_pages, &$total_items, $page){
		$total_pages = 1;
		$total_items = 2;
		return array(array('bar_code' => '47349292', 'manufacturer' => 'Mattel', 'name' => 'Transformer',
				'packaging' => 'caja', 'general_quantity' => 5, 'lots_quantity' => 6, 'balance' => -1),
				array('bar_code' => '17846291', 'manufacturer' => 'Mattel', 'name' => 'Barby',
				'packaging' => 'caja', 'general_quantity' => 0, 'lots_quantity' => -1, 'balance' => -1));
	}
}


/**
 * Utility class for creating the report.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class InactiveProductListDAM{
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
	static public function getList($days, &$total_pages, &$total_items, $page){
		$total_pages = 1;
		$total_items = 2;
		return array(array('bar_code' => '438929', 'manufacturer' => 'Mattel', 'name' => 'Caperi',
				'packaging' => 'caja', 'quantity' => 6, 'last_sale' => '28/02/2009', 'sale_quantity' => 1),
				array('bar_code' => '639922', 'manufacturer' => 'Chiplte', 'name' => 'Yeah',
				'packaging' => 'caja', 'quantity' => 12, 'last_sale' => '01/03/2009', 'sale_quantity' => 1));
	}
}


/**
 * Class for accessing database tables regarding suppliers and products.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class SupplierProductListDAM{
	/**
	 * Returns an array with the products' id and name that belongs to the provided supplier.
	 *
	 * @param Supplier $obj
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function getList(Supplier $obj, &$total_pages, &$total_items, $page){
		$total_pages = 1;
		$total_items = 2;
		return array(array('id' => 123, 'name' => 'Pepto'), array('id' => 124, 'name' => 'Aspirina'));
	}
}


/**
 * Utility class for obtaining a list of reserves belonging to certain product.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class ReserveListDAM{
	/**
	 * Returns an array containing the details of the reserves belonging to the provided product.
	 *
	 * The array's fields are id, date, username, lot_id and quantity.
	 * @param Product $product
	 * @return array
	 */
	static public function getList(Product $product){
		return array(array('id' => 123, 'date' => '15/05/2009', 'username' => 'roboli', 'id' => '4321',
				'quantity' => 2), array('id' => 129, 'date' => '15/05/2009', 'username' => 'roboli',
				'id' => '4329', 'quantity' => 1));
	}
}
?>