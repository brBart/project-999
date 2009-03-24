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
	 * Status type. Indicates that the object instance has been just created.
	 *
	 */
	const IN_PROGRESS = 0;
	
	/**
	 * Status type. Indicates that the object instance is already in the database.
	 *
	 */
	const CREATED = 1;
	
	/**
	 * Holds the PersistObject's status type.
	 *
	 * @var integer
	 */
	protected $_mStatus;
	
	/**
	 * Receives the status of the created object, e.g. PersistObject::IN_PROGRESS if just been created or
	 * PersistObject::CREATED if it's data is from database.
	 *
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
	 * Saves PersistObject's data in the database.
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
	 * Proves that the received PersistObject's status != PersistObject::IN_PROGRESS. Otherwise it throws an
	 * exception.
	 *
	 * @param Organization $organ
	 * @return boolean
	 */
	static protected function validateObjectForDelete(PersistObject $obj){
		if ($obj->_mStatus == self::IN_PROGRESS)
			throw new Exception('Cannot delete a PersistObject::IN_PROGRESS object from database.');
	}
	
	/**
	 * Defines functionality for inserting data in the database.
	 * @return integer
	 */
	abstract protected function insert();
	
	/**
	 * Defines functionality for updating the object's data in the database.
	 * @return void
	 */
	abstract protected function update();
	
	/**
	 * Defines functionality for validating the object's main properties.
	 * @return void
	 */
	abstract protected function validateMainProperties();
}
?>