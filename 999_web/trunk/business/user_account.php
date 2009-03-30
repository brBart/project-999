<?php
/**
 * Utility library regarding everything user accounts and authentication.
 * @package UserAccount
 * @author Roberto Oliveros
 */

require_once('business/persist.php');
require_once('data/user_account_dam.php');

/**
 * Represents a role a user might play in the system.
 * @package UserAccount
 * @author Roberto Oliveros
 */
class Role{
	/**
	 * Holds the role's identifier.
	 *
	 * @var integer
	 */
	private $_mId;
	
	/**
	 * Holds the role's name.
	 *
	 * @var string
	 */
	private $_mName;
	
	/**
	 * Constructs the role with the provided id and name.
	 *
	 * Must be called only from the database layer. Used getInstance() instead with a valid id.
	 * @param integer $id
	 * @param string $name
	 */
	public function __construct($id, $name){
		try{
			Identifier::validateId($id);
			$this->validateName($name);
		} catch(Exception $e){
			$et = new Exception('Internal error, calling Role constructor method with bad data! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mId = $id;
		$this->_mName = $name;
	}
	
	/**
	 * Returns the role's id.
	 *
	 * @return integer
	 */
	public function getId(){
		return $this->_mId;
	}
	
	/**
	 * Retuns the role's name.
	 *
	 * @return string
	 */
	public function getName(){
		return $this->_mName;
	}
	
	/**
	 * Returns an instance of a role.
	 *
	 * Returns NULL if there was no match for the provided id in the database.
	 * @param integer $id
	 * @return Role
	 */
	static public function getInstance($id){
		Identifier::validateId($id);
		return RoleDAM::getInstance($id);
	}
	
	/**
	 * Validates the role's name.
	 *
	 * Must not be empty. Otherwise it throws an exception.
	 * @param string $name
	 */
	private function validateName($name){
		if(empty($name))
			throw new Exception('Nombre inv&aacute;lido.');
	}
}


/**
 * Represents and defines functionality regarding user accounts.
 * @package UserAccount
 * @author Roberto Oliveros
 */
class UserAccount extends PersistObject{
	/**
	 * Holds the account's name.
	 *
	 * @var string
	 */
	private $_mName;
	
	/**
	 * Holds the user's names.
	 *
	 * @var string
	 */
	private $_mUserNames;
	
	/**
	 * Holds the user's last names.
	 *
	 * @var string
	 */
	private $_mUserLastNames;
	
	/**
	 * Holds the account's encrypted password.
	 *
	 * @var string
	 */
	private $_mPassword;
	
	/**
	 * Holds the account's role.
	 *
	 * @var Role
	 */
	private $_mRole;
	
	/**
	 * Holds the account's deactivate flag.
	 *
	 * Holds true if the account is deactivated, otherwise is false.
	 * @var boolean
	 */
	private $_mDeactivated;
	
	/**
	 * Returns the account's name.
	 *
	 * @return string
	 */
	public function getName(){
		return $this->_mName;
	}
	
	/**
	 * Retuns the account's user names.
	 *
	 * @return string
	 */
	public function getUserNames(){
		return $this->_mUserNames;
	}
	
	/**
	 * Retuns the account's user last names.
	 *
	 * @return string
	 */
	public function getUserLastNames(){
		return $this->_mUserLastNames;
	}
	
	/**
	 * Retuns the account's encrypted password.
	 *
	 * @return string
	 */
	public function getPassword(){
		return $this->_mPassword;
	}
	
	/**
	 * Retuns value of the account's deactivated flag.
	 *
	 * @return boolean
	 */
	public function isDeactivated(){
		return $this->_mDeactivated;
	}
	
	/**
	 * Sets the account's name.
	 *
	 * @param string $name
	 */
	public function setName($name){
		if($this->_mStatus == self::CREATED)
			throw new Exception('No se puede editar el nombre de la cuenta.');
		
		$this->validateName($name);
		
		$this->verifyName($name);
		
		$this->_mName = $name;
	}
}
?>