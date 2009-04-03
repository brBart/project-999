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
?>