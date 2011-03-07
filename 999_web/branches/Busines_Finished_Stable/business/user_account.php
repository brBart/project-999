<?php
/**
 * Utility library regarding everything user accounts and authentication.
 * @package UserAccount
 * @author Roberto Oliveros
 */

/**
 * Includes the Persist package.
 */
require_once('business/persist.php');
/**
 * Includes the UserAccounDAM package for accessing the database.
 */
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
	 * @throws Exception
	 */
	public function __construct($id, $name){
		try{
			Number::validatePositiveInteger($id, 'Id inv&aacute;lido.');
			String::validateString($name, 'Nombre inv&aacute;lido.');
		} catch(Exception $e){
			$et = new Exception('Interno: Llamando al metodo construct en Role con datos erroneos! ' .
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
		return RoleDAM::getInstance($id);
	}
}


/**
 * Represents a user account for accesing the system.
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
	 * Constructs the account with the provided username and status.
	 *
	 * Parameters must be set only if called from the database layer.
	 * @param string $userName
	 * @param integer $status
	 * @throws Exception
	 */
	public function __construct($userName = NULL, $status = Persist::IN_PROGRESS){
		parent::__construct($status);
		
		if(!is_null($userName))
			try{
				String::validateAlphanum($userName, 'Usuario inv&aacute;lido.');
			} catch(Exception $e){
				$et = new Exception('Interno: Llamando al metodo construct en UserAccount con datos erroneos! ' .
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
	 * @throws Exception
	 */
	public function setUserName($userName){
		if($this->_mStatus == self::CREATED)
			throw new Exception('No se puede editar el nombre de la cuenta.');
		
		$this->_mUserName = $userName;
		String::validateAlphanum($userName, 'Cuenta inv&aacute;lida.');
		$this->verifyUserName($userName);
	}
	
	/**
	 * Sets the user's first name.
	 *
	 * @param string $firstName
	 */
	public function setFirstName($firstName){
		$this->_mFirstName = $firstName;
		String::validateString($firstName, 'Nombre inv&aacute;lido.');
	}
	
	/**
	 * Sets the user last name.
	 *
	 * @param string $lastName
	 */
	public function setLastName($lastName){
		$this->_mLastName = $lastName;
		String::validateString($lastName, 'Nombre inv&aacute;lido.');
	}
	
	/**
	 * Sets the account's password.
	 *
	 * It encrypts the password before setting it.
	 * @param string $password
	 */
	public function setPassword($password){
		if($password != '')
			$this->_mPassword = UserAccountUtility::encrypt($password);
		else
			$this->_mPassword = $password;
			
		if($this->_mStatus == Persist::IN_PROGRESS)
			String::validateString($password, 'Contrase&ntilde;a inv&aacute;lida.');
	}
	
	/**
	 * Sets the account's role.
	 *
	 * @param Role $obj
	 */
	public function setRole(Role $obj = NULL){
		$this->_mRole = $obj;
		if(is_null($obj))
			throw new ValidateException('Seleccione un rol.');
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
	 * Persist::CREATED in the constructor method too.
	 * @param string $firstName
	 * @param string $lastName
	 * @param Role $role
	 * @param boolean $deactivated
	 * @throws Exception
	 */
	public function setData($firstName, $lastName, $role, $deactivated){
		try{
			String::validateString($firstName, 'Nombre inv&aacute;lido.');
			String::validateString($lastName, 'Apellido inv&aacute;lido.');
		} catch(Exception $e){
			$et = new Exception('Interno: Llamando al metodo setData en UserAccount con datos erroneos! '.
					$e->getMessage());
			throw $et;
		}
		
		$this->_mFirstName = $firstName;
		$this->_mLastName = $lastName;
		$this->_mRole = $role;
		$this->_mPassword = '';
		$this->_mDeactivated = (boolean)$deactivated;
	}
	
	/**
	 * Saves account's data to the database.
	 * 
	 * If the object's status is set to Persist::IN_PROGRESS the method insert()
	 * is called, if it's set to Persist::CREATED the method update() is called.
	 * @return string
	 * @throws Exception
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
			
		// Needed by the presentation layer.
		return $this->_mUserName;
	}
	
	/**
	 * Returns an instance of a user account.
	 *
	 * Returns NULL if there was no match in the database for the providad username.
	 * @param string $userName
	 * @return UserAccount
	 */
	static public function getInstance($userName){
		String::validateString($userName, 'Usuario inv&aacute;lido.');
		
		if(UserAccountUtility::isRoot($userName))
			return new UserAccount(UserAccountUtility::ROOT, Persist::CREATED);
		else
			return UserAccountDAM::getInstance($userName);
	}
	
	/**
	 * Deletes the account from the database.
	 *
	 * Throws an exception due dependencies.
	 * @param UserAccount $obj
	 * @throws Exception
	 */
	static public function delete(UserAccount $obj){
		self::validateObjectFromDatabase($obj);			
		if(!UserAccountDAM::delete($obj))
			throw new Exception('Cuenta de Usuario tiene dependencias y no se puede eliminar.');
	}
	
	/**
	 * Validates account's main properties.
	 * 
	 * Verifies that the account's username, fisrt name, last name and password are not emty. The role
	 * property must not be NULL. Otherwise it throws an exception.
	 * @throws Exception
	 */
	protected function validateMainProperties(){
		String::validateString($this->_mUserName, 'Cuenta inv&aacute;lida.', 'username');
		if(is_null($this->_mRole))
			throw new ValidateException('Seleccione un rol.', 'role_id');
		String::validateString($this->_mFirstName, 'Nombre inv&aacute;lido.', 'first_name');
		String::validateString($this->_mLastName, 'Apellido inv&aacute;lido.', 'last_name');
		if($this->_mStatus == Persist::IN_PROGRESS)
			String::validateString($this->_mPassword, 'Contrase&ntilde;a inv&aacute;lida.', 'password');
		if($this->_mStatus == Persist::CREATED && $this->_mDeactivated
			&& $this->_mUserName == ActiveSession::getHelper()->getUser()->getUserName())
			throw new ValidateException('Cuenta esta en uso, no se puede desactivar.', 'deactivated');
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
		if($this->_mPassword != '')
			UserAccountDAM::update($this);
		else
			UserAccountDAM::updateNoPassword($this);
	}
	
	/**
	 * Verifies if the account's username already exists in the database.
	 * 
	 * Throws an exception if it does.
	 * @param string $userName
	 * @throws Exception
	 */
	private function verifyUserName($userName){
		if(UserAccountDAM::exists($userName))
			throw new ValidateException('Nombre de cuenta ya existe.', 'username');
	}
}


/**
 * Defines necessary routines regarding user accounts.
 * @package UserAccount
 * @author Roberto Oliveros
 */
class UserAccountUtility{
	/**
	 * Username of the superuser account.
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
	 * Returns true if the user account exists and uses the provided password. Otherwise returns false.
	 * @param string $userName
	 * @param string $password
	 * @return boolean
	 */
	static public function isValid($userName, $password){
		if(self::isRoot($userName))
			return UserAccountUtilityDAM::isValidRoot(self::encrypt($password));
		else
			return UserAccountUtilityDAM::isValid($userName, self::encrypt($password));
	}
	
	/**
	 * Returns true if it is the username of the supersuser account, otherwise false.
	 *
	 * @param string $userName
	 * @return boolean
	 */
	static public function isRoot($userName){
		if(strtoupper($userName) == self::ROOT)
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
	 * @throws Exception
	 */
	static public function changePassword(UserAccount $account, $password, $newPassword){
		$account_name = $account->getUserName();
		if(!self::isValid($account_name, $password))
			throw new ValidateException('Contrase&ntilde;a actual incorrecta.');
		
		if(self::isRoot($account_name))
			UserAccountUtilityDAM::changeRootPassword(self::encrypt($newPassword));
		else
			UserAccountUtilityDAM::changePassword($account, self::encrypt($newPassword));
	}
	
	/**
	 * Encrypts the provided password.
	 *
	 * @param string $password
	 * @return string
	 */
	static public function encrypt($password){
		return sha1(self::HASH_PREFIX . $password);
	}
}


/**
 * Class in charge of coordinating the access authorizations in the system.
 * @package UserAccount
 * @author Roberto Oliveros
 */
class AccessManager{
	/**
	 * Returns true if the user has to right to perform the provided action over the provided subject.
	 *
	 * It throws an exception in case the provided action or subject doesn't exists.
	 * @param UserAccount $account
	 * @param string $subject
	 * @param string $action
	 * @return boolean
	 * @throws Exception
	 */
	static public function isAllowed(UserAccount $account, $subject, $action){
		Persist::validateObjectFromDatabase($account);
		String::validateString($subject, 'Subject inv&aacute;lido.');
		String::validateString($action, 'Action inv&aacute;lido.');
		
		if(UserAccountUtility::isRoot($account->getUserName()))
			return true;
		
		$subject_id = Subject::getId($subject);
		if($subject_id == 0)
			throw new Exception('Interno: Subject no existe.');
			
		$action_id = Action::getId($action);
		if($action_id == 0)
			throw new Exception('Interno: Action no existe.');
			
		return AccessManagerDAM::isAllowed($account, $subject_id, $action_id);		
	}
}


/**
 * Class that keep the requested subjects cached.
 * @package UserAccount
 * @author Roberto Oliveros
 */
class Subject{
	/**
	 * Returns the id for the provided subject name.
	 * 
	 * In case the subject doesn't exists it returns NULL.
	 * @param string $subject
	 * @return integer
	 */
	static public function getId($subject){
		$subjects_array = self::getSubjects();
		
		if(array_key_exists($subject, $subjects_array))
			return $subjects_array[$subject];
		else{
			// If not in the cached array, look in the database.
			$subject_id = SubjectDAM::getId($subject);
			if($subject_id != 0){
				// Cache the subject.
				$subjects_array[$subject] = $subject_id;
				self::setSubjects($subjects_array);				
			}

			return $subject_id;
		}
	}
	
	/**
	 * Returns the cached array of subjects from the session.
	 * 
	 * @return array
	 */
	static private function getSubjects(){
		$helper = ActiveSession::getHelper();
		$array = $helper->getSubjects();
		// If there was no array, return an new one.
		(empty($array)) ? $subjects_array = array() : $subjects_array = $array;
		return $subjects_array;
	}
	
	/**
	 * Stores the array of subjects in the session.
	 * 
	 * @param array $subjectsArray
	 */
	static private function setSubjects($subjectsArray){
		$helper = ActiveSession::getHelper();
		$helper->setSubjects($subjectsArray);
	}
}


/**
 * Class that keep the requested actions cached.
 * @package UserAccount
 * @author Roberto Oliveros
 */
class Action{
	/**
	 * Returns the id for the provided action name.
	 * 
	 * In case the action doesn't exists it returns NULL.
	 * @param string $action
	 * @return integer
	 */
	static public function getId($action){
		$actions_array = self::getActions();
		
		if(array_key_exists($action, $actions_array))
			return $actions_array[$action];
		else{
			// If not in the cached array, look in the database.
			$action_id = ActionDAM::getId($action);
			if($action_id != 0){
				// Cache the subject.
				$actions_array[$action] = $action_id;
				self::setActions($actions_array);	
			}
			
			return $action_id;
		}
	}
	
	/**
	 * Returns the cached array of actions from the session.
	 * 
	 * @return array
	 */
	static private function getActions(){
		$helper = ActiveSession::getHelper();
		$array = $helper->getActions();
		// If there was no array, return an new one.
		(empty($array)) ? $actions_array = array() : $actions_array = $array;
		return $actions_array;
	}
	
	/**
	 * Stores the array of actions in the session.
	 * 
	 * @param array $actionsArray
	 */
	static private function setActions($actionsArray){
		$helper = ActiveSession::getHelper();
		$helper->setActions($actionsArray);
	}
}
?>