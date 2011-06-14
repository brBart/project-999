<?php
/**
 * Library containing the GetIsOpenCashRegisterCommand base class.
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
 * Returns if the cash register is open or not.
 * @package Command
 * @author Roberto Oliveros
 */
class GetIsOpenCashRegisterCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$cash_register = $helper->getObject((int)$request->getProperty('key'));
		Page::display(array('status' => (int)$cash_register->isOpen()),
		'cash_register_status_xml.tpl');
	}
}
?>