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
	 * Holds the account's username.
	 *
	 * @var string
	 */
	private $_mUserName;
	
	/**
	 * Holds the user's first name.
	 *
	 * @var string
	 */
	private $_mFirstName;
	
	/**
	 * Holds the user's last name.
	 *
	 * @var string
	 */
	private $_mLastName;
	
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
	private $_mDeactivated = false;
	
	/**
	 * Constructs the account with the provided account's username and status.
	 *
	 * Parameters must be set only if called from the database layer.
	 * @param string $userName
	 * @param integer $status
	 */
	public function __construct($userName = NULL, $status = PersistObject::IN_PROGRESS){
		parent::__construct($status);
		
		if(!is_null($userName))
			try{
				UserAccountUtility::validateUserName($userName);
			} catch(Exception $e){
				$et = new Exception('Internal error, calling UserAccount constructor method with bad data! ' .
						$e->getMessage());
				throw $et;
			}
		
		$this->_mUserName = $userName;
	}
	
	/**
	 * Returns the account's username.
	 *
	 * @return string
	 */
	public function getUserName(){
		return $this->_mUserName;
	}
	
	/**
	 * Retuns the user's first name.
	 *
	 * @return string
	 */
	public function getFirstName(){
		return $this->_mFirstName;
	}
	
	/**
	 * Retuns the user's last name.
	 *
	 * @return string
	 */
	public function getLastName(){
		return $this->_mLastName;
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
	 * Sets the account's username.
	 *
	 * @param string $userName
	 */
	public function setUserName($userName){
		if($this->_mStatus == self::CREATED)
			throw new Exception('No se puede editar el nombre de la cuenta.');
		
		UserAccountUtility::validateUserName($userName);
		
		$this->verifyUserName($userName);
		
		$this->_mUserName = $userName;
	}
	
	/**
	 * Sets the user's first name.
	 *
	 * @param string $firstName
	 */
	public function setFirstName($firstName){
		$this->validateFirstName($firstName);
		$this->_mFirstName = $firstName;
	}
	
	/**
	 * Sets the user last name.
	 *
	 * @param string $lastName
	 */
	public function setLastName($lastName){
		$this->validateLastName($lastName);
		$this->_mLastName = $lastName;
	}
	
	/**
	 * Sets the account's password.
	 *
	 * It encrypts the password before setting it.
	 * @param string $password
	 */
	public function setPassword($password){
		UserAccountUtility::validatePassword($password);
		$this->_mPassword = UserAccountUtility::encrypt($password);
	}
	
	/**
	 * Sets the account's role.
	 *
	 * @param Role $obj
	 */
	public function setRole(Role $obj){
		$this->_mRole = $obj;
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
	 * @param string $firstName
	 * @param string $lastName
	 * @param Role $role
	 * @param boolean $deactivated
	 */
	public function setData($firstName, $lastName, $role, $deactivated){
		try{
			$this->validateFirstName($firstName);
			$this->validateLastName($lastName);
		} catch(Exception $e){
			$et = new Exception('Internal error, calling UserAccount setData method with bad data! '.
					$e->getMessage());
			throw $et;
		}
		
		$this->_mFirstName = $firstName;
		$this->_mLastName = $lastName;
		$this->_mRole = $role;
		$this->_mDeactivated = (boolean)$deactivated;
	}
	
	/**
	 * Saves account's data to the database.
	 * 
	 * If the object's status set to PersistObject::IN_PROGRESS the method insert()
	 * is called, if it's set to PersistObject::CREATED the method update() is called.
	 * @return void
	 */
	public function save(){
		if(UserAccountUtility::isRoot($this->_mUserName))
			throw new Exception('Cuenta reservada para el superusuario.');
		
		$this->validateMainProperties();
		
		if($this->_mStatus == self::IN_PROGRESS){
			$this->verifyUserName($this->_mUserName);
			$this->insert();
			$this->_mStatus = self::CREATED;
		}
		else
			$this->update();
	}
	
	/**
	 * Returns instance of a user account.
	 *
	 * Returns NULL if there was no match in the database for the providad account name.
	 * @param string $userName
	 * @return UserAccount
	 */
	static public function getInstance($userName){
		UserAccountUtility::validateUserName($userName);
		
		if(UserAccountUtility::isRoot($userName))
			return new UserAccount(UserAccountUtility::ROOT, PersistObject::CREATED);
		else
			return UserAccountDAM::getInstance($userName);
	}
	
	/**
	 * Deletes the account from the database.
	 *
	 * Returns true confirming the deletion, otherwise false due dependencies.
	 * @param UserAccount $obj
	 * @return boolean
	 */
	static public function delete(UserAccount $obj){
		self::validateObjectForDelete($obj);			
		return UserAccountDAM::delete($obj);
	}
	
	/**
	 * Validates account's main properties.
	 * 
	 * Verifies the account's username, fisrt name, last name and password are not emty. The role's status
	 * property must be set to other than PersistObject::IN_PROGRESS. Otherwise it throws an exception.
	 */
	protected function validateMainProperties(){
		UserAccountUtility::validateUserName($this->_mUserName);
		$this->validateFirstName($this->_mFirstName);
		$this->validateLastName($this->_mLastName);
		UserAccountUtility::validatePassword($this->_mPassword);
		
		if(is_null($this->_mRole))
			throw new Exception('Rol inv&accute;lido.');
	}
	
	/**
	 * Inserts the account's data in the database.
	 *
	 */
	protected function insert(){
		UserAccountDAM::insert($this);
	}
	
	/**
	 * Updates the account's data in the database.
	 *
	 */
	protected function update(){
		UserAccountDAM::update($this);
	}
	
	/**
	 * Validates the user's first name.
	 *
	 * Must not be empty. Otherwise it throws an exception.
	 * @param string $firstName
	 */
	private function validateFirstName($firstName){
		if(empty($firstName))
			throw new Exception('Nombres invalidos.');
	}
	
	/**
	 * Validates the user's last name.
	 *
	 * Must not be empty. Otherwise it throws an exception.
	 * @param string $lastName
	 */
	private function validateLastName($lastName){
		if(empty($lastName))
			throw new Exception('Apellidos invalidos.');
	}
	
	/**
	 * Verifies if the account's name already exists in the database.
	 *
	 * @param string $userName
	 */
	private function verifyUserName($userName){
		if(UserAccountDAM::exists($userName))
			throw new Exception('Nombre de cuenta ya existe.');
	}
}


/**
 * Defines necessary routines regarding user accounts.
 * @package UserAccount
 * @author Roberto Oliveros
 */
class UserAccountUtility{
	/**
	 * Name of the superuser account.
	 *
	 */
	const ROOT = 'ROOT';
	
	/**
	 * Prefix for the hashing functionality for passwords.
	 *
	 */
	const HASH_PREFIX = 'bO2';
	
	/**
	 * Verifies the user account exists in the database.
	 *
	 * Returns true if the user account exists and has the provided password. Otherwise returns false.
	 * @param string $userName
	 * @param string $password
	 * @return boolean
	 */
	static public function isValid($userName, $password){
		self::validateUserName($userName);
		self::validatePassword($password);
		
		if(self::isRoot($userName))
			return UserAccountUtilityDAM::isValidRoot(self::encrypt($password));
		else
			return UserAccountUtilityDAM::isValid($userName, self::encrypt($password));
	}
	
	/**
	 * Returns true if it is the name of the supersuser account, otherwise false.
	 *
	 * @param string $userName
	 * @return boolean
	 */
	static public function isRoot($userName){
		if(strtoupper($userName) == 'ROOT')
			return true;
		else
			return false;
	}
	
	/**
	 * Changes the user account's password.
	 *
	 * Returns true on success.
	 * @param UserAccount $account
	 * @param string $password
	 * @param string $newPassword
	 * @return boolean
	 */
	static public function changePassword(UserAccount $account, $password, $newPassword){
		self::validateUserAccount($account);
		self::validatePassword($password);
		self::validatePassword($newPassword);
		
		$account_name = $account->getUserName();
		if(!self::isValid($account_name, $password))
			throw new Exception('Contrase&ntilde;a inv&aacute;lida.');
		
		if(self::isRoot($account_name))
			return UserAccountUtilityDAM::changeRootPassword(self::encrypt($newPassword));
		else
			return UserAccountUtilityDAM::changePassword($account, self::encrypt($newPassword));
	}
	
	/**
	 * Encrypts the provided password.
	 *
	 * @param string $password
	 * @return string
	 */
	static public function encrypt($password){
		return sha1(HASH_PREFIX . $password);
	}
	
	/**
	 * Validates the account's username.
	 *
	 * Must not be empty. Otherwise it throws en exception.
	 * @param string $userName
	 */
	static public function validateUserName($userName){
		if(empty($userName))
			throw new Exception('Nombre inv&aacute;lido.');
	}
	
	/**
	 * Validates the account's password.
	 *
	 * Must not be empty. Otherwise it throws an exception.
	 * @param string $password
	 */
	static public function validatePassword($password){
		if(empty($password))
			throw new Exception('Contrase&ntilde;a inv&aacute;lida.');
	}
	
	/**
	 * Verifies if the account is recently created.
	 * 
	 * Verifies if the account's status property is set to PersistObject::IN_PROGRESS. It throws an exception
	 * if it does.
	 * @param UserAccount $account
	 */
	static private function validateUserAccount(UserAccount $account){
		if($account->getStatus() == PersistObject::IN_PROGRESS)
			throw new Exception('PersistObject::IN_PROGRESS account provided.');
	}
}
?>