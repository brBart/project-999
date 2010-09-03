<?php
/**
 * Library containing the CreateCashRegisterObjectCommand base class.
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
 * Defines common functionality for the create cash register object derived classes.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class CreateCashRegisterObjectCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$user = $helper->getUser();
		
		if($this->testRights($user)){
			$cash_register = $helper->getObject((int)$request->getProperty('register_key'));
			try{
				$obj = $this->createObject($cash_register);
				$key = KeyGenerator::generateKey();
				$helper->setObject($key, $obj);
				$this->displayObject($key, $obj);
			} catch(Exception $e){
				$msg = $e->getMessage();
				Page::display(array('message' => $msg), 'error_xml.tpl');
			}
		}
		else{
			$msg = 'Usuario no cuenta con los suficientes privilegios.';
			Page::display(array('message' => $msg), 'error_xml.tpl');
		}
	}
	
	/**
	 * Tests if the user has the right to create the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	abstract protected function testRights(UserAccount $user);
	
	/**
	 * Creates the desired object.
	 * @return variant
	 */
	abstract protected function createObject(CashRegister $cashRegister);
	
	/**
	 * Display the form for creating the object.
	 * @param string $key
	 * @param variant $obj
	 */
	abstract protected function displayObject($key, $obj);
}
?>