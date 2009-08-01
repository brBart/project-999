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
		$obj = $this->getObject($request->getProperty('id'));
		if(is_null($obj))
			$this->displayFailure();
		else{
			$key = KeyGenerator::generateKey();
			$helper->setObject($key, $obj);
			$this->displayObject($key, $obj);
		}
	}
	
	/**
	 * Gets the desired object.
	 * @param string $id
	 * @return variant
	 */
	abstract protected function getObject($id);
	
	/**
	 * Display failure in case the object does not exists.
	 */
	abstract protected function displayFailure();
	
	/**
	 * Display the form for the object.
	 * @param string $key
	 * @param variant $obj
	 */
	abstract protected function displayObject($key, $obj);
}
?>