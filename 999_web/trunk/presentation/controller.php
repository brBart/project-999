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
	 * @param string $key
	 * @return string
	 */
	public function getProperty($key){
		return $this->_mProperties[$key];
	}
	
	/**
	 * Sets an item in the properties array.
	 * @param string $key
	 * @param string $val
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
	 * Holds the session helper.
	 * @var SessionHelper
	 */
	protected $_mHelper;
	
	/**
	 * Private constructor to make the run method the only way.
	 */
	protected function __construct(){}
	
	/**
	 * It starts to run the system.
	 * 
	 * It obtains and executes the command in the cmd argument provided by the user.
	 */
	abstract static public function run();
	
	/**
	 * Obtains and sets the session helper.
	 */
	protected function init(){
		$this->_mHelper = ActiveSession::getHelper();
	}
	
	/**
	 * Handles the request from the user.
	 */
	protected function handleRequest(){
		$request = new Request();
		
		// Check if the user has already login.
		if(!is_null($this->_mHelper->getUser())){
			$cmd = $request->getProperty('cmd');
			if($cmd == ''){
				$command = $this->getDefaultCommand();
				$command->execute($request, $this->_mHelper);
				return;
			}
			
			$command = CommandResolver::getCommand($cmd);
			if(is_null($command)){
				// If the command provided was not found.
				$command = $this->getNotFoundCommand();
				$command->execute($request, $this->_mHelper);
			}
			else
				$command->execute($request, $this->_mHelper);
		}
		else{
			$command = $this->getNotLoginCommand();
			$command->execute($request, $this->_mHelper);
		}
	}
	
	/**
	 * Returns the default command for the controller.
	 * @return Command
	 */
	abstract protected function getDefaultCommand();
	
	/**
	 * Returns the default NotFoundCommand for the controller.
	 * @return Command
	 */
	abstract protected function getNotFoundCommand();
	
	/**
	 * Returns the default NotLoginCommand for the controller.
	 * @return Command
	 */
	abstract protected function getNotLoginCommand();
}


/**
 * Executes the inventory side of the system.
 * @package Controller
 * @author Roberto Oliveros
 */
class InventoryController extends Controller{
	/**
	 * It starts to run the system.
	 * 
	 * It obtains and executes the command in the cmd argument provided by the user.
	 */
	static public function run(){
		$instance = new InventoryController();
		$instance->init();
		$instance->handleRequest();	
	}
	
	/**
	 * Returns the default command for the controller.
	 * @return Command
	 */
	protected function getDefaultCommand(){
		return CommandResolver::getCommand('show_home_inventory');
	}
	
	/**
	 * Returns the default NotFoundCommand for the controller.
	 * @return Command
	 */
	protected function getNotFoundCommand(){
		return CommandResolver::getCommand('show_not_found_inventory');
	}
	
	/**
 	 * Returns the default NotLoginCommand for the controller.
	 * @return Command
 	 */
	protected function getNotLoginCommand(){
		return CommandResolver::getCommand('login_inventory');
	}
}


/**
 * Executes the administration side of the system.
 * @package Controller
 * @author Roberto Oliveros
 */
class AdminController extends Controller{
	/**
	 * It starts to run the system.
	 * 
	 * It obtains and executes the command in the cmd argument provided by the user.
	 */
	static public function run(){
		$instance = new AdminController();
		$instance->init();
		$instance->handleRequest();	
	}
	
	/**
	 * Returns the default command for the controller.
	 * @return Command
	 */
	protected function getDefaultCommand(){
		return CommandResolver::getCommand('show_home_admin');
	}
	
	/**
	 * Returns the default NotFoundCommand for the controller.
	 * @return Command
	 */
	protected function getNotFoundCommand(){
		return CommandResolver::getCommand('show_not_found_admin');
	}
	
	/**
 	 * Returns the default NotLoginCommand for the controller.
	 * @return Command
 	 */
	protected function getNotLoginCommand(){
		return CommandResolver::getCommand('login_admin');
	}
}


/**
 * Executes the POS side of the system.
 * @package Controller
 * @author Roberto Oliveros
 */
class POSController extends Controller{
	/**
	 * It starts to run the system.
	 * 
	 * It obtains and executes the command in the cmd argument provided by the user.
	 */
	static public function run(){
		$instance = new POSController();
		$instance->init();
		$instance->handleRequest();	
	}
	
	/**
	 * Returns the default command for the controller.
	 * @return Command
	 */
	protected function getDefaultCommand(){
		return CommandResolver::getCommand('show_home_pos');
	}
	
	/**
	 * Returns the default NotFoundCommand for the controller.
	 * @return Command
	 */
	protected function getNotFoundCommand(){
		return CommandResolver::getCommand('show_not_found_pos');
	}
	
	/**
 	 * Returns the default NotLoginCommand for the controller.
	 * @return Command
 	 */
	protected function getNotLoginCommand(){
		return CommandResolver::getCommand('login_pos');
	}
}


/**
 * Class in charge of obtaining the requested command.
 * @package Controller
 * @author Roberto Oliveros
 */
class CommandResolver{
	/**
	 * Returns an instance of the command name provided.
	 * 
	 * Returns NULL in case such command does not exists.
	 * @param string $cmd
	 * @return Command
	 */
	static public function getCommand($cmd){
		if($cmd == '')
			return NULL;
			
		$cmd = str_replace(array('.', '/'), '', $cmd);
		$file_path = COMMANDS_DIR . $cmd . '.php';
		$class_name = self::getCommandName($cmd) . 'Command';
		if(file_exists($file_path)){
			require_once($file_path);
			if(class_exists($class_name)){
				$cmd_class = new ReflectionClass($class_name);
				return $cmd_class->newInstance();
			}
		}
		
		return NULL;
	}
	
	/**
	 * Receives the name of the file of the command and it returns the formated name of the command.
	 * 
	 * e.g. show_login_inventory, returns ShowLoginInventory.
	 * @param string $cmd
	 * @return string
	 */
	static private function getCommandName($cmd){
		$cmd = str_replace(array('_'), ' ', $cmd);
		$cmd = ucwords($cmd);
		return str_replace(array(' '), '', $cmd);
	}
}
?>