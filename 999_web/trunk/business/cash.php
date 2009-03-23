<?php
/**
 * Library with utility classes for the cash flow.
 * @package Cash
 * @author Roberto Oliveros
 */

require_once('include/config.php');

/**
 * Class representing a bank.
 * @package Cash
 * @author Roberto Oliveros
 */
class Bank{
	/**
	 * Internal identifier.
	 *
	 * @var integer
	 */
	private $_mId;
	
	/**
	 * Name of the Bank.
	 * @var string
	 */
	private $_mName;
	
	/**
	 * Internal status of the object, e.g. JUST_CREATED or FROM DATABASE.
	 *
	 * @var integer
	 */
	private $_mStatus;
	
	
	public function __construct($id = NULL, $status = JUST_CREATED){
		
		if(!is_null($id))
			$this->validateId($id);
			
		$this->_mId = $id;
		$this->_mStatus = $status;
	}
	
	/**
	 * Returns the name of the Bank.
	 *
	 * @return string
	 */
	public function getName(){
		return $this->_mName;
	}
	
	/**
	 * Returns the Bank's id.
	 *
	 * @return integer
	 */
	public function getId(){
		return $this->_mId;
	}
	
	/**
	 * Sets the Bank's name.
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName($name){
		$this->validateName($name);
		$this->_mName = $name;
	}
	
	/**
	 * Sets the Bank's data. Must be called only from the data layer corresponding class. Lack of experience,
	 * sorry.
	 *
	 * @param string $name
	 * @return void
	 */
	public function setData($name){
		$this->validateName($name);
		$this->_mName = $name;
	}
	
	/**
	 * Saves Bank's data in the database.
	 * @return void
	 */
	public function save(){
		$this->validateMainProperties();
		
		if($this->_mStatus == JUST_CREATED){
			$this->_mId = BankDAM::insert($this);
			$this->_mStatus = FROM_DATABASE;
		}
		else
			BankDAM::update($this);
	}
	
	/**
	 * Returns instance of a Bank if a match was found in the database for the provided id. Otherwise returns
	 * NULL.
	 *
	 * @param integer $id
	 * @return Bank
	 */
	static public function getInstance($id){
		self::validateId($id);
		return BankDAM::getInstance($id);
	}
	
	/**
	 * Deletes Bank from database. Returns true confirming the deletion, false otherwise because it has
	 * dependencies.
	 *
	 * @param Bank $obj
	 * @return boolean
	 */
	static public function delete(Bank $obj){
		if ($obj->_mStatus == JUST_CREATED)
			throw new Exception('Cannot delete a just created organization from database.');
			
		return BankDAM::delete($obj);
	}
	
	/**
	 * Validates the Bank's name.
	 *
	 * @param string $name
	 * @return void
	 */
	private function validateName($name){
		if(empty($name))
			throw new Exception('Nombre inv&aacute;lido.');
	}
	
	/**
	 * Validates the Bank's id.
	 *
	 * @param integer $id
	 * @return void
	 */
	private function validateId($id){
		if(!is_int($id))
			throw new Exception('Id inv&aacute;lido.');
	}
	
	/**
	 * Validates Bank's main properties.
	 * @return void
	 */
	private function validateMainProperties(){
		$this->validateName($this->_mName);
	}
}

?>