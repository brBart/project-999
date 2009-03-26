<?php
/**
 * Library with utility class for persist functionality.
 * @package Persist
 * @author Roberto Oliveros
 */

/**
 * Defines common functionality for derived classes with persistence characteristics.
 * @package Persist
 * @author Roberto Oliveros
 */
abstract class PersistObject{
	/**
	 * Status type.
	 * 
	 * Indicates that the object's data has just been created and is not in the database.
	 */
	const IN_PROGRESS = 0;
	
	/**
	 * Status type.
	 * 
	 * Indicates that the object's data is already in the database.
	 */
	const CREATED = 1;
	
	/**
	 * Holds the actual status of the object.
	 *
	 * @var integer
	 */
	protected $_mStatus;
	
	/**
	 * Construct the object with the provided status.
	 * 
	 * The status must be set to PersistObject::IN_PROGRESS in case it's just been created and its data has 
	 * not been set or PersistObject::CREATED if it's data is from database.
	 * @param integer $status
	 */
	public function __construct($status){
		$this->_mStatus = $status;
	}
	
	/**
	 * Returns the object's status.
	 *
	 * @return integer
	 */
	public function getStatus(){
		return $this->_mStatus;
	}
	
	/**
	 * Saves object's data in the database.
	 * 
	 * If the object's status set to PersistObject::IN_PROGRESS the method insert()
	 * is called, if it's set to PersistObject::CREATED the method update() is called.
	 * @return void
	 */
	public function save(){
		$this->validateMainProperties();
		
		if($this->_mStatus == self::IN_PROGRESS){
			$this->_mId = $this->insert();
			$this->_mStatus = self::CREATED;
		}
		else
			$this->update();
	}
	
	/**
	 * Validates if the object can be deleted in the database.
	 * 
	 * The object's status must be diferent than PersistObject::IN_PROGRESS. Otherwise it throws an
	 * exception.
	 * @param PersistObject $obj
	 * @return void
	 */
	static protected function validateObjectForDelete(PersistObject $obj){
		if ($obj->_mStatus == self::IN_PROGRESS)
			throw new Exception('Cannot delete a PersistObject::IN_PROGRESS object from database.');
	}
	
	/**
	 * Inserts the object's data in the database.
	 * @return integer
	 */
	abstract protected function insert();
	
	/**
	 * Updates the object's data in the database.
	 * @return void
	 */
	abstract protected function update();
	
	/**
	 * Validates the object's main properties.
	 * @return void
	 */
	abstract protected function validateMainProperties();
}
?>