<?php
/**
 * Library containing the GetUniqueObjectCommand base class.
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
 * Defines common functionality for getting a unique object's data from the database.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class GetUniqueObjectCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		try{
			$obj = $this->getObject();
		} catch(Exception $e){
			$msg = $e->getMessage();
			$this->displayFailure($msg);
			return;
		}
		
		$key = KeyGenerator::generateKey();
		$helper->setObject($key, $obj);
		$this->displayObject($key, $obj);
	}
	
	/**
	 * Gets the desired object.
	 * @return variant
	 */
	abstract protected function getObject();
	
	/**
	 * Display failure in case an error occurs.
	 * @param string $msg
	 */
	abstract protected function displayFailure($msg);
	
	/**
	 * Display the form for the object.
	 * @param string $key
	 * @param variant $obj
	 */
	abstract protected function displayObject($key, $obj);
}
?>