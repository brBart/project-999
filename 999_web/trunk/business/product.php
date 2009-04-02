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
	 * Holds the unit's name.
	 *
	 * @var string
	 */
	private $_mName;
	
	/**
	 * Constructs the object with the provided id and status.
	 *
	 * Parameters must be set only if the method is called from the database layer.
	 * @param integer $id
	 * @param integer $status
	 */
	public function __construct($id = NULL, $status = PersistObject::IN_PROGRESS){
		parent::__construct($id, $status);
	}
	
	/**
	 * Returns the unit's name.
	 *
	 * @return string
	 */
	public function getName(){
		return $this->_mName;
	}
	
	/**
	 * Sets the unit's name.
	 *
	 * @param string $name
	 */
	public function setName($name){
		$this->validateName($name);
		$this->_mName = $name;
	}
	
	/**
	 * Sets the object's properties.
	 *
	 * Must be called only from the database layer corresponding class. The object's status must be set to
	 * PersistObject::CREATED in the constructor method too.
	 * @param string $name
	 */
	public function setData($name){
		try{
			$this->validateName($name);
		} catch(Exception $e){
			$et = new Exception('Internal error, calling UnitOfMeasure constructor method with bad data! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mName = $name;
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
	
	/**
	 * Validates object's main properties.
	 *
	 * Verifies if the object's name is not empty. Otherwise it throws an exception.
	 */
	protected function validateMainProperties(){
		$this->validateName($this->_mName);
	}
	
	/**
	 * Validates the unit's name.
	 *
	 * Must not be empty. Otherwise it throws an exception.
	 * @param string $name
	 */
	private function validateName($name){
		if(empty($name))
			throw new Exception('Nombre inv&accute;lido.');
	}
}


/**
 * Represents the manufacturer of a certain product.
 * @package Product
 * @author Roberto Oliveros
 */
class Manufacturer extends Identifier{
	
}
?>