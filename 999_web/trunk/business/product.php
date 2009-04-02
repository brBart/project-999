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
?>