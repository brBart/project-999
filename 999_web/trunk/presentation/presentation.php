<?php
/**
 * Library containing utility classes for presentation purposes.
 * @package Presentation
 * @author Roberto Oliveros
 */


/**
 * Utility class for keeping session data.
 * @package Presentation
 * @author Roberto Oliveros
 */
class SessionHelper{
	/**
	 * Instance of the helper.
	 *
	 * @var SessionHelper
	 */
	static private $_mInstance;
	
	/**
	 * Starts the session.
	 *
	 */
	private function __construct(){
		//session_start();
	}
	
	/**
	 * Returns the current session's user.
	 *
	 * @return UserAccount
	 */
	public function getUser(){
		return $_SESSION['user'];
	}
	
	/**
	 * Returns the key's corresponding object.
	 *
	 * @param integer $key
	 * @return variant
	 */
	public function getObject($key){
		$this->validateKey($key);
		return $_SESSION[$key];
	}
	
	/**
	 * Sets the session's user.
	 *
	 * @param UserAccount $user
	 */
	public function setUser(UserAccount $user){
		Persist::validateObjectFromDatabase($user);
		$_SESSION['user'] = $user;
	}
	
	/**
	 * Stores an object or value in the session.
	 *
	 * @param integer $key
	 * @param variant $obj
	 */
	public function setObject($key, $obj){
		$this->validateKey($key);
		$_SESSION[$key] = $obj;
	}
	
	/**
	 * Unsets the user from the session.
	 *
	 */
	public function removeUser(){
		$_SESSION['user'] = NULL;
	}
	
	/**
	 * Unsets the object from the session.
	 *
	 * @param integer $key
	 */
	public function removeObject($key){
		$this->validateKey($key);
		$_SESSION[$key] = NULL;
	}
	
	/**
	 * Returns the instance of the session helper.
	 *
	 * @return SessionHelper
	 */
	static public function getInstance(){
		if(is_null(self::$_mInstance))
			self::$_mInstance = new SessionHelper();
			
		return self::$_mInstance;
	}
	
	/**
	 * Validates if the value of the provided key is correct.
	 *
	 * Must be greater than cero.
	 * @param integer $key
	 * @throws Exception
	 */
	private function validateKey($key){
		if(!is_int($key) || $key < 1)
			throw new Exception('Internal error, invalid key value!');
	}
}
?>