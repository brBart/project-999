<?php
/**
 * Library containing the ShowNotFoundCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('presentation/command.php');

/**
 * Defines common functionality for displaying a not found command failure.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class ShowNotFoundCommand extends Command{
	/**
	 * Holds the helper object.
	 * @var Request
	 */
	protected $_mHelper;
	
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$this->_mHelper = $helper;
		
		$msg = 'Interno: Comando no existe.';
		$type = $request->getProperty('type');
		
		if($type == 'xml')
			// Is an ajax request.
			Page::display(array('message' => $msg), 'error_xml.tpl');
		else
			$this->displayFailure($msg);
	}
	
	/**
	 * Displays the failure message to the user in html format.
	 * @param string $msg
	 */
	abstract protected function displayFailure($msg);
}
?>