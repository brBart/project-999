<?php
/**
 * Library containing the CreateObject command.
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
 * Defines common functionality for the create object derived classes.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class CreateObjectCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$user = $helper->getUser();
		if(!$this->testRights($user))
			$this->displayFailure();
		else{
			$obj = $this->createObject();
			$key = KeyGenerator::generateKey();
			$helper->setObject($key, $obj);
			$this->displayObject($key, $obj);
		}
	}
	
	/**
	 * Tests if the user has the right to create an object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	abstract protected function testRights(UserAccount $user);
	
	/**
	 * Display failure in case the user doesn't have rights.
	 */
	abstract protected function displayFailure();
	
	/**
	 * Creates the desired object.
	 * @return variant
	 */
	abstract protected function createObject();
	
	/**
	 * Display the form for creating the object.
	 * @param string $key
	 * @param variant $obj
	 */
	abstract protected function displayObject($key, $obj);
}
?>