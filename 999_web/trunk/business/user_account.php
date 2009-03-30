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
	
	
	public function __construct($name, $status = PersistObjet::IN_PROGRESS){
		try{
			$this->validateName($name);
		} catch(Exception $e){
			$et = new Exception('Internal error, calling UserAccount constructor method with bad data! ' .
					$e->getMessage());
			throw $et;
		}
		
		
	}
	
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
	 * Returns the account's role.
	 *
	 * @return Role
	 */
	public function getRole(){
		return $this->_mRole;
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
	
	/**
	 * Sets the account's user names.
	 *
	 * @param string $names
	 */
	public function setUserNames($names){
		$this->validateUserNames($names);
		$this->_mUserNames = $names;
	}
	
	/**
	 * Sets the account's user last names.
	 *
	 * @param string $lastNames
	 */
	public function setUserLastNames($lastNames){
		$this->validateUserLastNames($lastNames);
		$this->_mUserLastNames = $lastNames;
	}
	
	/**
	 * Sets the account's password.
	 *
	 * It encrypts the password before setting it.
	 * @param string $password
	 */
	public function setPassword($password){
		$this->validatePassword($password);
		$this->_mPassword = PasswordHasher::encrypt($password);
	}
	
	/**
	 * Sets the account's deactivation flag value.
	 *
	 * @param boolean $bool
	 */
	public function deactivate($bool){
		$this->_mDeactivated = (boolean)$bool;
	}
	
	/**
	 * Sets the account's properties.
	 *
	 * Must be called only from the database layer corresponding class. The object's status must be set to
	 * PersistObject::CREATED in the constructor method too.
	 * @param string $userNames
	 * @param string $userLastNames
	 * @param Role $role
	 * @param boolean $deactivated
	 */
	public function setData($userNames, $userLastNames, $role, $deactivated){
		try{
			$this->validateUserNames($userNames);
			$this->validateUserLastNames($userLastNames);
			$this->validateRole($role);
		} catch(Exception $e){
			$et = new Exception('Internal error, calling UserAccount setData method with bad data! '.
					$e->getMessage());
			throw $et;
		}
		
		$this->_mUserNames = $userNames;
		$this->_mUserLastNames = $userLastNames;
		$this->_mRole = $role;
		$this->_mDeactivated = (boolean)$deactivated;
	}
	
	/**
	 * Returns instance of a user account.
	 *
	 * Returns NULL if there was no match in the database for the providad account name.
	 * @param string $name
	 * @return UserAccount
	 */
	static public function getInstance($name){
		self::validateName($name);
		
		if(strtoupper($name) == 'ROOT')
			return new UserAccount('ROOT', PersistObject::CREATED);
		else
			return UserAccountDAM::getInstance($name);
	}
	
	/**
	 * Validates the account's name.
	 *
	 * Must not be empty. Otherwise it throws en exception.
	 * @param string $name
	 */
	private function validateName($name){
		if(empty($name))
			throw new Exception('Nombre inv&aacute;lido.');
	}
	
	/**
	 * Validates the account's user names.
	 *
	 * Must not be empty. Otherwise it throws an exception.
	 * @param string $names
	 */
	private function validateUserNames($names){
		if(empty($names))
			throw new Exception('Nombres invalidos.');
	}
	
	/**
	 * Validates the account's user last names.
	 *
	 * Must not be empty. Otherwise it throws an exception.
	 * @param string $lastNames
	 */
	private function validateUserLastNames($lastNames){
		if(empty($lastNames))
			throw new Exception('Apellidos invalidos.');
	}
	
	/**
	 * Validates the account's password.
	 *
	 * Must not be empty. Otherwise it throws an exception.
	 * @param string $password
	 */
	private function validatePassword($password){
		if(empty($password))
			throw new Exception('Contrase&ntilde;a inv&aacute;lida.');
	}
	
	/**
	 * Verifies if the account's name already exists in the database.
	 *
	 * @param string $name
	 */
	private function verifyName($name){
		if(UserAccountDAM::exists($name))
			throw new Exception('Nombre de cuenta ya existe.');
	}
}
?>