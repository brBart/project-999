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
	private $_mAccountName;
	
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
	 * Constructs the account with the provided account's name and status.
	 *
	 * Parameters must be set only if called from the database layer.
	 * @param string $accountName
	 * @param integer $status
	 */
	public function __construct($accountName = NULL, $status = PersistObjet::IN_PROGRESS){
		parent::__construct($status);
		
		try{
			$this->validateAccountName($accountName);
		} catch(Exception $e){
			$et = new Exception('Internal error, calling UserAccount constructor method with bad data! ' .
					$e->getMessage());
			throw $et;
		}
		
		$this->_mAccountName = $accountName;
	}
	
	/**
	 * Returns the account's name.
	 *
	 * @return string
	 */
	public function getAccountName(){
		return $this->_mAccountName;
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
	 * @param string $accountName
	 */
	public function setAccountName($accountName){
		if($this->_mStatus == self::CREATED)
			throw new Exception('No se puede editar el nombre de la cuenta.');
		
		$this->validateAccountName($accountName);
		
		$this->verifyAccountName($accountName);
		
		$this->_mAccountName = $accountName;
	}
	
	/**
	 * Sets the account's user names.
	 *
	 * @param string $userNames
	 */
	public function setUserNames($userNames){
		$this->validateUserNames($userNames);
		$this->_mUserNames = $userNames;
	}
	
	/**
	 * Sets the account's user last names.
	 *
	 * @param string $userLastNames
	 */
	public function setUserLastNames($userLastNames){
		$this->validateUserLastNames($userLastNames);
		$this->_mUserLastNames = $userLastNames;
	}
	
	/**
	 * Sets the account's password.
	 *
	 * It encrypts the password before setting it.
	 * @param string $password
	 */
	public function setPassword($password){
		$this->validatePassword($password);
		$this->_mPassword = UserAccountUtility::encrypt($password);
	}
	
	/**
	 * Sets the account's role.
	 *
	 * @param Role $obj
	 */
	public function setRole(Role $obj){
		$this->validateRole($obj);
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
	 * Saves account's data to the database.
	 * 
	 * If the object's status set to PersistObject::IN_PROGRESS the method insert()
	 * is called, if it's set to PersistObject::CREATED the method update() is called.
	 * @return void
	 */
	public function save(){
		if(UserAccountUtility::isRoot($this->_mAccountName))
			throw new Exception('Cuenta reservada para el superusuario.');
		
		$this->validateMainProperties();
		
		if($this->_mStatus == self::IN_PROGRESS){
			$this->verifyAccountName($this->_mAccountName);
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
	 * @param string $accountName
	 * @return UserAccount
	 */
	static public function getInstance($accountName){
		self::validateAccountName($accountName);
		
		if(UserAccountUtility::isRoot($accountName))
			return new UserAccount(UserAccountUtility::ROOT, PersistObject::CREATED);
		else
			return UserAccountDAM::getInstance($accountName);
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
	 * Verifies the account's name, user names, user last names and password are not emty. The role's status
	 * property must be set to other than PersistObject::IN_PROGRESS. Otherwise it throws an exception.
	 */
	protected function validateMainProperties(){
		$this->validateAccountName($this->_mAccountName);
		$this->validateUserNames($this->_mUserNames);
		$this->validateUserLastNames($this->_mUserLastNames);
		$this->validatePassword($this->_mPassword);
		
		if(is_null($this->_mRole))
			throw new Exception('Rol inv&accute;lido.');
		else
			$this->validateRole($this->_mRole);
	}
	
	/**
	 * Validates the account's name.
	 *
	 * Must not be empty. Otherwise it throws en exception.
	 * @param string $accountName
	 */
	private function validateAccountName($accountName){
		if(empty($accountName))
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
	 * Validates the account's role.
	 *
	 * Role status property must be set to other than PersistObject::IN_PROGRESS. Otherwise it throws an
	 * exception.
	 * @param Role $obj
	 */
	private function validateRole(Role $obj){
		if($obj->getStatus() != self::CREATED)
			throw new Exception('PersistObject::IN_PROGRESS role provided.');
	}
	
	/**
	 * Verifies if the account's name already exists in the database.
	 *
	 * @param string $accountName
	 */
	private function verifyAccountName($accountName){
		if(UserAccountDAM::exists($accountName))
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
	 * Returns true if it is the name of the supersuser account, otherwise false.
	 *
	 * @param string $accountName
	 * @return boolean
	 */
	static public function isRoot($accountName){
		if(strtoupper($accountName) == 'ROOT')
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
		
		$account_name = $account->getAccountName();
		if(!self::isValid($account_name, $password))
			throw new Exception('Contrase&ntilde;a inv&aacute;lida.');
		
		if(self::isRoot($account_name))
			return UserAccountUtilityDAM::changeRootPassword($newPassword);
		else
			return UserAccountUtilityDAM::changePassword($account, $newPassword);
	}
	
	/**
	 * Verifies the user account exists in the database.
	 *
	 * Returns true if the user account exists and has the provided password. Otherwise returns false.
	 * @param string $accountName
	 * @param string $password
	 * @return boolean
	 */
	static public function isValid($accountName, $password){
		if(self::isRoot($accountName))
			return UserAccountUtilityDAM::isValidRoot(self::encrypt($password));
		else
			return UserAccountUtilityDAM::isValid($accountName, self::encrypt($password));
	}
	
	/**
	 * Encrypts the provided passwrod.
	 *
	 * @param string $password
	 * @return string
	 */
	static public function encrypt($password){
		
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