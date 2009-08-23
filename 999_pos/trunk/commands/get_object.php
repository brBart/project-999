<?php
/**
 * Library containing the get object base class command.
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
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$obj = $this->getObject($request);
		if(is_null($obj))
			$this->displayFailure();
		else{
			// Verify from which page this location was accessed.
			$last_cmd = $request->getProperty('last_cmd');
			if(!is_null($last_cmd)){
				$page = $request->getProperty('page');
				$back_query = array('cmd' => $last_cmd, 'page' => $page);
			}
			
			$key = KeyGenerator::generateKey();
			$helper->setObject($key, $obj);
			$this->displayObject($key, $obj, $back_query);
		}
	}
	
	/**
	 * Gets the desired object.
	 * @param Request
	 * @return variant
	 */
	abstract protected function getObject($request);
	
	/**
	 * Display failure in case the object does not exists.
	 */
	abstract protected function displayFailure();
	
	/**
	 * Display the form for the object.
	 * @param string $key
	 * @param variant $obj
	 * @param array $backQuery
	 */
	abstract protected function displayObject($key, $obj, $backQuery);
}
?>