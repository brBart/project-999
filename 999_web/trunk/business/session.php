<?php
/**
 * Library containing utility classes for presentation purposes.
 * @package Session
 * @author Roberto Oliveros
 */


/**
 * Helper class to pass the session helper object between other objects.
 * @package Session
 * @author Roberto Oliveros
 */
class ActiveSession{
	/**
	 * Holds the session helper object.
	 * @var SessionHelper
	 */
	static private $_mHelper;
	
	/**
	 * Sets the session helper object.
	 * @param SessionHelper $helper
	 */
	static public function setHelper(SessionHelper $helper){
		self::$_mHelper = $helper;
	}
	
	/**
	 * Returns the session helper object.
	 * @return SessionHelper
	 */
	static public function getHelper(){
		return self::$_mHelper;
	}
}


/**
 * Utility class for keeping session data.
 * @package Session
 * @author Roberto Oliveros
 */
abstract class SessionHelper{
	/**
	 * Name of the module in use.
	 * @var string
	 */
	protected $_mModuleName;
	
	/**
	 * Instance of the helper.
	 * @var SessionHelper
	 */
	static protected $_mInstance;
	
	/**
	 * Starts the session.
	 *
	 */
	protected function __construct(){
		session_start();
	}
	
	/**
	 * Returns the current session's user.
	 *
	 * @return UserAccount
	 */
	public function getUser(){
		return $_SESSION[$this->_mModuleName]['user'];
	}
	
	/**
	 * Returns the key's corresponding object.
	 *
	 * @param integer $key
	 * @return variant
	 */
	public function getObject($key){
		$this->validateKey($key);
		return $_SESSION[$this->_mModuleName]['objects'][$key];
	}
	
	/**
	 * Returns the array of subjects cached.
	 * 
	 * @return array
	 */
	public function getSubjects(){
		return $_SESSION[$this->_mModuleName]['subjects'];
	}
	
	/**
	 * Returns the array of actions cached.
	 * 
	 * @return array
	 */
	public function getActions(){
		return $_SESSION[$this->_mModuleName]['actions'];
	}
	
	/**
	 * Sets the session's user.
	 *
	 * @param UserAccount $user
	 */
	public function setUser(UserAccount $user){
		Persist::validateObjectFromDatabase($user, 'UserAccount');
		$_SESSION[$this->_mModuleName]['user'] = $user;
	}
	
	/**
	 * Stores an object or value in the session.
	 *
	 * @param integer $key
	 * @param variant $obj
	 */
	public function setObject($key, $obj){
		$this->validateKey($key);
		$_SESSION[$this->_mModuleName]['objects'][$key] = $obj;
	}
	
	/**
	 * Sets the array of subjects cached.
	 * 
	 * @param array $subjectsArray
	 */
	public function setSubjects($subjectsArray){
		$_SESSION[$this->_mModuleName]['subjects'] = $subjectsArray;
	}
	
	/**
	 * Sets the array of actions cached.
	 * 
	 * @param array $actionsArray
	 */
	public function setActions($actionsArray){
		$_SESSION[$this->_mModuleName]['actions'] = $actionsArray;
	}
	
	/**
	 * Unsets the user from the session.
	 *
	 */
	public function removeUser(){
		$_SESSION[$this->_mModuleName]['user'] = NULL;
	}
	
	/**
	 * Unsets the object from the session.
	 *
	 * @param integer $key
	 */
	public function removeObject($key){
		$this->validateKey($key);
		$_SESSION[$this->_mModuleName]['objects'][$key] = NULL;
	}
	
	/**
	 * Returns the instance of the session helper.
	 *
	 * @return SessionHelper
	 */
	abstract static public function getInstance();
	
	/**
	 * Validates if the value of the provided key is correct.
	 *
	 * Must be greater than cero.
	 * @param integer $key
	 * @throws Exception
	 */
	protected function validateKey($key){
		if(!is_int($key) || $key < 1)
			throw new Exception('Internal error, invalid key value!');
	}
}

/**
 * Utility class for keeping session data on the operations side of the system.
 * @package Session
 * @author Roberto Oliveros
 */
class OperationsSession extends SessionHelper{
	/**
	 * Name of the module in use.
	 * @var string
	 */
	protected $_mModuleName = 'Operations';
	
	/**
	 * Returns the instance of the session helper.
	 *
	 * @return SessionHelper
	 */
	static public function getInstance(){
		if(is_null(self::$_mInstance))
			self::$_mInstance = new OperationsSession();
			
		return self::$_mInstance;
	}
}


/**
 * Utility class for keeping session data on the administration side of the system.
 * @package Session
 * @author Roberto Oliveros
 */
class AdminSession extends SessionHelper{
	/**
	 * Name of the module in use.
	 * @var string
	 */
	protected $_mModuleName = 'Admin';
	
	/**
	 * Returns the instance of the session helper.
	 *
	 * @return SessionHelper
	 */
	static public function getInstance(){
		if(is_null(self::$_mInstance))
			self::$_mInstance = new AdminSession();
			
		return self::$_mInstance;
	}
}


/**
 * Class for generating keys to identify objects in the session object.
 * @package Session
 * @author Roberto Oliveros
 */
class KeyGenerator{
	/**
	 * Generates a random integer key between 1000 and 9999.
	 * @return integer
	 */
	static public function generateKey(){
		return rand(1000, 9999);
	}
}
?>