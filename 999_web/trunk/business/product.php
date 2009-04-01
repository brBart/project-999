<?php
/**
 * Library containing all the classes regarding products.
 * @package Product
 * @author Roberto Oliveros
 */

require_once('business/persist.php');

class UnitOfMeasure extends Identifier{
	/**
	 * Holds the unit's name.
	 *
	 * @var string
	 */
	private $_mName;
	
	
	public function __construct($id = NULL, $status = PersistObject::IN_PROGRESS){
		
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
?>