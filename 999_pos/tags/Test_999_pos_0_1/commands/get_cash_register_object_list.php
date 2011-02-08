<?php
/**
 * Library containing the GetCashRegisterObjectListCommand base class.
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
 * Library for getting the list.
 */
require_once('business/cash.php');

/**
 * Defines common functionality for getting an object's list of ids.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class GetCashRegisterObjectListCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$register = $helper->getObject((int)$request->getProperty('key'));
		$list = $this->getList($register);
		$this->displayList($list);
	}
	
	/**
	 * Gets the desired object's list.
	 * @param CashRegister $register
	 * @return array
	 */
	abstract protected function getList(CashRegister $register);
	
	/**
	 * Display the object's list.
	 * @param array $list
	 */
	abstract protected function displayList($list);
}
?>