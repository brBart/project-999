<?php
/**
 * Library containing the GetObject base class command.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('presentation/command.php');
/**
 * For displaying the results.
 */
require_once('presentation/page.php');

/**
 * Defines common functionality for getting an object's data from the database.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class GetObjectCommand extends Command{
	/**
	 * Holds the request object.
	 * @var Request
	 */
	protected $_mRequest;
	
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$this->_mRequest = $request;
		
		try{
			$obj = $this->getObject();
		} catch(Exception $e){
			$msg = $e->getMessage();
			$this->displayFailure($msg);
			return;
		}
		
		// Verify from which page this location was accessed.
		$last_cmd = $this->_mRequest->getProperty('last_cmd');
		if(!is_null($last_cmd)){
			$page = $this->_mRequest->getProperty('page');
			$back_query = array('cmd' => $last_cmd, 'page' => $page);
		}
		
		$key = KeyGenerator::generateKey();
		$helper->setObject($key, $obj);
		$this->displayObject($key, $obj, $back_query);
	}
	
	/**
	 * Gets the desired object.
	 * @return variant
	 */
	abstract protected function getObject();
	
	/**
	 * Display failure in case the object does not exists or an error occurs.
	 * @param string $msg
	 */
	abstract protected function displayFailure($msg);
	
	/**
	 * Display the form for the object.
	 * @param string $key
	 * @param variant $obj
	 * @param array $backQuery
	 */
	abstract protected function displayObject($key, $obj, $backQuery);
}
?>