<?php
/**
 * Library with classes for accessing the database tables regarding products.
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
		if($id == 123){
			$um = new UnitOfMeasure($id, PersistObject::CREATED);
			$um->setData('unitario');
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
		// Code here...
	}
	
	/**
	 * Updates the object's data in the database.
	 *
	 * @param UnitOfMeasure $obj
	 */
	static public function update(UnitOfMeasure $obj){
		// Code here...
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
?>