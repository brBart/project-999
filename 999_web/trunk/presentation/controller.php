<?php
/**
 * Library with all the necessary classes to make the controller class functionalties perform.
 * @package Controller
 * @author Roberto Oliveros 
 */

/**
 * For storing session values.
 */
require_once('business/session.php');

/**
 * Represents a user request.
 * @package Controller
 * @author Roberto Oliveros
 */
class Request{
	/**
	 * Holds all the arguments entered by the user.
	 * @var array
	 */
	private $_mProperties;
	
	/**
	 * Constructs the request object fulfilling the properties array with the arguments received.
	 */
	public function __construct(){
		if($_SERVER['REQUEST_METHOD']){
			$this->_mProperties = $_REQUEST;
			return;
		}
		
		foreach($_SERVER['argv'] as $arg){
			if(strpos($arg, '=')){
				list($key, $val) = explode('=', $arg);
				$this->setProperty($key, $val);
			}
		}
	}
	
	/**
	 * Returns an item of the properties array.
	 * @param $key
	 * @return string
	 */
	public function getProperty($key){
		return $this->_mProperties[$key];
	}
	
	/**
	 * Sets an item in the properties array.
	 * @param $key
	 * @param $val
	 */
	private function setProperty($key, $val){
		$this->_mProperties[$key] = $val;
	}
}


/**
 * Defines common functionality for derived controller classes.
 * @package Controller
 * @author Roberto Oliveros
 */
abstract class Controller{
	/**
	 * Private constructor to make the run method the only way.
	 */
	private function __construct(){}
	
	/**
	 * It starts to run the system.
	 * 
	 * It obtains and executes the command in the cmd argument provided by the user.
	 */
	static public function run(){
		$helper = SessionHelper::getInstance();
		$request = new Request();
		$type = $request->getProperty('type');
		
		// Check if the user has already login.
		if($helper->getUser()){
			$cmd = $request->getProperty('cmd');
			$cmd = str_replace(array('.', '/'), '', $cmd);
			$command = CommandResolver::getCommand($cmd);
			
			// If the command provided was not found.
			if(!$command){
				$command = $this->getNotFoundCommand();
				$command->execute($request, $helper);
			}
			else
				$command->execute($request, $helper);
		}
		else{
			$command = $this->getNotLoginCommand();
			$command->execute($request, $helper);
		}
	}
}


/**
 * Executes the operations side of the system.
 * @package Controller
 * @author Roberto Oliveros
 */
class OperationsController extends Controller{
	
	
}
?>