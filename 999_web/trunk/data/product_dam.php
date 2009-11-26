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
	/**
	 * Returns the available quantity of the product's inventory.
	 *
	 * @param Product $obj
	 * @return integer
	 */
	static public function getAvailable(Product $obj){
		$sql = 'CALL product_available_quantity_get(:product_id)';
		$params = array(':product_id' => $obj->getId());
		return (int)DatabaseHandler::getOne($sql, $params);
	}
	
	/**
	 * Returns the quantity on hand of the product's inventory.
	 *
	 * @param Product $obj
	 * @return integer
	 */
	static public function getQuantity(Product $obj){
		$sql = 'CALL product_quantity_get(:product_id)';
		$params = array(':product_id' => $obj->getId());
		return (int)DatabaseHandler::getOne($sql, $params);
	}
	
	/**
	 * Returns the lots with available quantities of the provided product.
	 *
	 * Returns an array with all the lots that contains available quantities.
	 * @param Product $obj
	 * @return array<Lot>
	 */
	static public function getLots(Product $obj){
		$sql = 'CALL product_lot_available_get(:product_id)';
		$params = array(':product_id' => $obj->getId());
		$result = DatabaseHandler::getAll($sql, $params);
		
		$lots = array();
		foreach($result as $lot)
			$lots[] = Lot::getInstance((int)$lot['lot_id'], $obj);
			
		return $lots;
	}
	
	/**
	 * Returns the lots information of the provided product.
	 *
	 * Returns an array with data of all the lots which quantity is over cero.
	 * @param Product $obj
	 * @param integer &$quantity
	 * @param integer &$available
	 * @return array
	 */
	static public function getLotsList(Product $obj, &$quantity, &$available){
		$sql = 'CALL product_lot_total_quantity_get(:product_id)';
		$params = array(':product_id' => $obj->getId());
		$quantity = (int)DatabaseHandler::getOne($sql, $params);
		
		$sql = 'CALL product_lot_total_available_get(:product_id)';
		$params = array(':product_id' => $obj->getId());
		$available = (int)DatabaseHandler::getOne($sql, $params);
		
		$sql = 'CALL product_lot_get(:product_id)';
		$params = array(':product_id' => $obj->getId());
		return DatabaseHandler::getAll($sql, $params);
	}
	
	/**
	 * Increases the product's quantity in the database.
	 *
	 * @param Product $product
	 * @param integer $quantity
	 */
	static public function increase(Product $product, $quantity){
		$sql = 'CALL product_increase_quantity(:product_id, :quantity)';
		$params = array(':product_id' => $product->getId(), ':quantity' => $quantity);
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Decreases the product's quantity in the database.
	 *
	 * @param Product $product
	 * @param integer $quantity
	 */
	static public function decrease(Product $product, $quantity){
		$sql = 'CALL product_decrease_quantity(:product_id, :quantity)';
		$params = array(':product_id' => $product->getId(), ':quantity' => $quantity);
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Reserves the specified quantity for the provided product in the database.
	 *
	 * @param Product $product
	 * @param integer $quantity
	 */
	static public function reserve(Product $product, $quantity){
		$sql = 'CALL product_increase_reserved(:product_id, :quantity)';
		$params = array(':product_id' => $product->getId(), ':quantity' => $quantity);
		DatabaseHandler::execute($sql, $params);
	}
	
	/**
	 * Decreases the product's reserve by the quantity provided in the database.
	 *
	 * @param Product $product
	 * @param integer $quantity
	 */
	static public function decreaseReserve(Product $product, $quantity){
		$sql = 'CALL product_decrease_reserved(:product_id, :quantity)';
		$params = array(':product_id' => $product->getId(), ':quantity' => $quantity);
		DatabaseHandler::execute($sql, $params);
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
		$sql = 'CALL product_supplier_delete(:product_id, :supplier_id, :sku)';
		$supplier = $detail->getSupplier();
		$params = array(':product_id' => $product->getId(), ':supplier_id' => $supplier->getId(),
				'sku' => $detail->getProductSKU());
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
	 * @param string $barCode
	 */
	static public function setBarCode(Product $obj, $barCode){
		$sql = 'CALL product_bar_code_update(:product_id, :bar_code)';
		$params = array(':product_id' => $obj->getId(), ':bar_code' => $barCode);
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
			
			$product->setData($result['bar_code'], $result['name'], $result['packaging'], $um, $manufacturer,
					$result['price'], (boolean)$result['deactivated'], $details, $result['description']);
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
	 * Returns an instance of a lot.
	 *
	 * Returns NULL if there was no match for the provided id in the database. The optional argument is the
	 * product the lot belongs to. If not provided, it will be fetch from the database too.
	 * @param integer $id
	 * @return Lot
	 */
	static public function getInstance($id, Product $product = NULL){
		$sql = 'CALL lot_get(:lot_id)';
		$params = array(':lot_id' => $id);
		$result = DatabaseHandler::getRow($sql, $params);
		
		if(!empty($result)){
			if(is_null($product) || $product->getId() != $result['product_id'])
				$product = Product::getInstance((int)$result['product_id']);
			
			$lot = new Lot($product, 0, (float)$result['price'], $result['expiration_date'],
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
 * Utility class for logging in the database any change on a product's price.
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
	static public function insert($date, UserAccount $user, Product $product, $lastPrice, $newPrice){
		$sql = 'CALL change_price_log_insert(:username, :product_id, :date, :last_price, :new_price)';
		$params = array(':username' => $user->getUserName(), ':product_id' => $product->getId(),
				':date' => Date::dbFormat($date), ':last_price' => $lastPrice, ':new_price' => $newPrice);
		DatabaseHandler::execute($sql, $params);
	}
}


/**
 * Utility class for accessing the database for product searches.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class ProductSearchDAM{
	/**
	 * Makes a search for a product in the database.
	 *
	 * The array returned contains the fields bar_code and name of the product which name is similar or exactly as
	 * the search string provided.
	 * @param string $searchString
	 * @return array
	 */
	static public function getList($searchString){
		$sql = 'CALL product_search(:search_string)';
		$params = array(':search_string' => $searchString);
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Class for accessing database tables regarding manufacturers and products.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class ManufacturerProductListDAM{
	/**
	 * Returns an array with the fields product_id, name and packaging of the product that belongs to the
	 * provided manufacturer.
	 *
	 * @param Manufacturer $obj
	 * @return array
	 */
	static public function getList(Manufacturer $obj){
		$sql = 'CALL manufacturer_product_list_get(:manufacturer_id)';
		$params = array(':manufacturer_id' => $obj->getId());
		return DatabaseHandler::getAll($sql, $params);
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
	 * The array's fields are created_date, document, number, lot_id, entry, withdraw and balance. The balance
	 * argument returns it respective value. If no page argument or cero is passed all the details are
	 * returned. The totalPages and totalItems arguments are necessary to return their respective values.
	 * @param Product $product
	 * @param integer &$balance
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList(Product $product, &$balance, &$totalPages, &$totalItems, $page){
		$sql = 'CALL kardex_count(:product_id)';
		$params = array(':product_id' => $product->getId());
		$totalItems = DatabaseHandler::getOne($sql, $params);
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		$sql = 'CALL product_balance_foward_get(:product_id)';
		$balance = DatabaseHandler::getOne($sql, $params);
		
		if($page > 1){
			$sql = 'CALL kardex_balance_foward_get(:product_id, :last_item, :balance_foward)';
			$params = array(':product_id' => $product->getId(),
					':last_item' => ($page - 1) * ITEMS_PER_PAGE, ':balance_foward' => $balance);
			$balance = DatabaseHandler::getOne($sql, $params);
		}
		
		if($page > 0)
			$params = array('product_id' => $product->getId(), ':start_item' => ($page - 1) * ITEMS_PER_PAGE,
					'items_per_page' => ITEMS_PER_PAGE);
		else
			$params = array('product_id' => $product->getId(), ':start_item' => 0,
					':items_per_page' => $totalItems);
		
		$params = array_merge($params, array(':balance_foward' => $balance));
		$sql = 'CALL kardex_get(:product_id, :start_item, :items_per_page, :balance_foward)';
		return DatabaseHandler::getAll($sql, $params);
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
	 * The array's fields are bonus_id, quantity, percentage, created_date and expired_date.
	 * @param Product $product
	 * @return array
	 */
	static public function getList(Product $product){
		$sql = 'CALL product_bonus_list_get(:product_id)';
		$params = array(':product_id' => $product->getId());
		return DatabaseHandler::getAll($sql, $params);
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
	 * and balance. If no page argument or cero is passed all the details are returned. The totalPages and
	 * totalItems arguments are necessary to return their respective values.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList(&$totalPages, &$totalItems, $page){
		$sql = 'CALL product_negative_balance_count()';
		$totalItems = DatabaseHandler::getOne($sql);
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		if($page > 0)
			$params = array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE);
		else
			$params = array(':start_item' => 0, ':items_per_page' => $totalItems);
		
		$sql = 'CALL product_negative_balance_get(:start_item, :items_per_page)';
		return DatabaseHandler::getAll($sql, $params);
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
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList($days, &$totalPages, &$totalItems, $page){
		$sql = 'CALL product_inactive_count(:days)';
		$params = array(':days' => $days);
		$totalItems = DatabaseHandler::getOne($sql, $params);
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		if($page > 0)
			$params = array(':days' => $days, ':start_item' => ($page - 1) * ITEMS_PER_PAGE,
					'items_per_page' => ITEMS_PER_PAGE);
		else
			$params = array(':days' => $days, ':start_item' => 0, ':items_per_page' => $totalItems);
		
		$sql = 'CALL product_inactive_get(:days, :start_item, :items_per_page)';
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Class for accessing database tables regarding suppliers and products.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class SupplierProductListDAM{
	/**
	 * Returns an array with the fields product_id and name of all the products that belongs to the provided
	 * supplier.
	 *
	 * @param Supplier $obj
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	static public function getList(Supplier $obj, &$totalPages, &$totalItems, $page){
		$sql = 'CALL supplier_product_list_count(:supplier_id)';
		$params = array(':supplier_id' => $obj->getId());
		$totalItems = DatabaseHandler::getOne($sql, $params);
		
		$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
		
		if($page > 0)
			$params = array_merge($params, 
					array(':start_item' => ($page - 1) * ITEMS_PER_PAGE, 'items_per_page' => ITEMS_PER_PAGE));
		else
			$params = array_merge($params, array(':start_item' => 0, ':items_per_page' => $totalItems));
		
		$sql = 'CALL supplier_product_list_get(:supplier_id, :start_item, :items_per_page)';
		return DatabaseHandler::getAll($sql, $params);
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
	 * The array's fields are reserve_id, created_date, user_account_username, lot_id and quantity.
	 * @param Product $product
	 * @return array
	 */
	static public function getList(Product $product){
		$sql = 'CALL reserve_list_get(:product_id)';
		$params = array(':product_id' => $product->getId());
		return DatabaseHandler::getAll($sql, $params);
	}
}


/**
 * Utility class for obtaining a list of products' name.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class ProductDistinctListDAM{
	/**
	 * Returns an array containing the names of all the products.
	 *
	 * The array's field is name.
	 * @return array
	 */
	static public function getList(){
		$sql = 'CALL product_distinct_list_get()';
		return DatabaseHandler::getAll($sql);
	}
}


/**
 * Utility class for obtaining a list of manufacturers' name.
 * @package ProductDAM
 * @author Roberto Oliveros
 */
class ManufacturerDistinctListDAM{
	/**
	 * Returns an array containing the names of all the manufacturers.
	 *
	 * The array's field is name.
	 * @return array
	 */
	static public function getList(){
		$sql = 'CALL manufacturer_distinct_list_get()';
		return DatabaseHandler::getAll($sql);
	}
}
?>