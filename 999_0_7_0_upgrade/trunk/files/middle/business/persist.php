<?php
/**
 * Library with utility class for persist functionality.
 * @package Persist
 * @author Roberto Oliveros
 */

/**
 * For validating values purposes.
 */
require_once('business/validator.php');

/**
 * Defines common functionality for derived classes with persistence characteristics.
 * @package Persist
 * @author Roberto Oliveros
 */
abstract class Persist{
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
	 * The status must be set to Persist::IN_PROGRESS in case it's just been created and its data has 
	 * not been set or Persist::CREATED if it's data is from database.
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
}


/**
 * Defines common functionality for persist objects derived classes.
 * @package Persist
 * @author Roberto Oliveros
 */
abstract class PersistObject extends Persist{
	/**
	 * Saves the object's data in the database.
	 *
	 */
	abstract public function save();
	
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


/**
 * Defines common functionality for the object's which use an internal identifier.
 * @package Persist
 * @author Roberto Oliveros
 */
abstract class Identifier extends PersistObject{
	/**
	 * Holds the object's id.
	 *
	 * @var integer
	 */
	protected $_mId;
	
	/**
	 * Holds the object's name.
	 *
	 * @var string
	 */
	protected $_mName;
	
	/**
	 * Constructs the object with the provided id and status.
	 * 
	 * @param integer $id
	 * @param integer $status
	 * @throws Exception
	 */
	public function __construct($id, $status){
		parent::__construct($status);
		
		if(!is_null($id))
			try{
				Number::validatePositiveInteger($id, 'Id inv&aacute;lido.');
			} catch(ValidateException $e){
				$et = new Exception('Interno: Llamando al metodo construct en Identifier con datos erroneos! ' .
						$e->getMessage());
				throw $et;
			}
			
		$this->_mId = $id;
	}
	
	/**
	 * Returns the object's id.
	 *
	 * @return integer
	 */
	public function getId(){
		return $this->_mId;
	}
	
	/**
	 * Returns the object's name.
	 *
	 * @return string
	 */
	public function getName(){
		return $this->_mName;
	}
	
	/**
	 * Sets the object's name.
	 *
	 * @param string $name
	 */
	public function setName($name){
		$this->_mName = $name;
		// Validation move last for presentation purposes.
		String::validateString($name, 'Nombre inv&aacute;lido.');
	}
	
	/**
	 * Set the object's data provided by the database.
	 * 
	 * Must be call only from the database layer corresponding class. The object's status must be set to
	 * Persist::CREATED in the constructor method too.
	 * @param string $name
	 * @return void
	 * @throws Exception
	 */
	public function setData($name){
		try{
			String::validateString($name, 'Nombre inv&aacute;lido.');
		} catch(ValidateException $e){
			$et = new Exception('Interno: Llamando al metodo setData en Identifier con datos erroneos! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mName = $name;
	}
	
	/**
	 * Saves object's data in the database.
	 * 
	 * If the object's status set to Persist::IN_PROGRESS the method insert()
	 * is called, if it's set to Persist::CREATED the method update() is called.
	 * @return integer
	 */
	public function save(){
		$this->validateMainProperties();
		
		if($this->_mStatus == self::IN_PROGRESS){
			$this->_mId = $this->insert();
			$this->_mStatus = self::CREATED;
		}
		else
			$this->update();

		// Needed by the presentation layer.
		return $this->_mId;
	}
	
	/**
	 * Validates the object's main properties.
	 * 
	 * Verifies that the object's name is not empty. Otherwise it throws an exception.
	 * @return void
	 */
	protected function validateMainProperties(){
		String::validateString($this->_mName, 'Nombre inv&aacute;lido.', 'name');
	}
}


/**
 * Defines common functionality for persist documents derived classes.
 * @package Persist
 * @author Roberto Oliveros
 */
abstract class PersistDocument extends Persist{
	/**
	 * Statys type.
	 * 
	 * Indicates that the document has been cancelled.
	 */
	const CANCELLED = 2;
	
	/**
	 * Holds the document's identifier.
	 *
	 * @var integer
	 */
	protected $_mId;
	
	/**
	 * Constructs the object with the provided id and status.
	 * 
	 * @param integer $id
	 * @param integer $status
	 * @throws Exception
	 */
	public function __construct($id, $status){
		parent::__construct($status);
		
		if(!is_null($id))
			try{
				Number::validatePositiveNumber($id, 'Id inv&aacute;lido.');
			} catch(ValidateException $e){
				$et = new Exception('Interno: Llamando al metodo construct en Document con datos erroneos! ' .
						$e->getMessage());
				throw $et;
			}
			
		$this->_mId = $id;
	}
	
	/**
	 * Returns the document's id.
	 *
	 * @return integer
	 */
	public function getId(){
		return $this->_mId;
	}
	
	/**
	 * Does not save the document's data and reverts the effects.
	 *
	 */
	abstract public function discard();
	
	/**
	 * Cancels the document and reverts its effects.
	 *
	 * The user argument tells who authorized the action.
	 * @param UserAccount $user
	 * @param string $reason
	 */
	abstract public function cancel(UserAccount $user, $reason = NULL);
	
	/**
	 * Inserts the document's data into the database.
	 *
	 */
	abstract protected function insert();
}
?>