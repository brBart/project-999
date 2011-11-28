<?php
/**
 * Library containing the PrintObjectCommand base class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('presentation/command.php');
/**
 * For displaying the object details.
 */
require_once('business/itemized.php');

/**
 * Defines common functionality for displaying an object for printing purposes.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class PrintObjectCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$obj = $helper->getObject((int)$request->getProperty('key'));
		$details = DetailsPrinter::showPage($obj);
		$this->displayObject($obj, $details);
	}
	
	/**
	 * Displays the object's information.
	 * @param variant $obj
	 * @param array $details
	 */
	abstract protected function displayObject($obj, $details);
}
?>