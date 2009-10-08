<?php
/**
 * Library containing the DiscardDocumentCommand class.
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
 * For removing the product from the receipt.
 */
require_once('business/event.php');

/**
 * Defines functionality for the discarding a document.
 * @package Command
 * @author Roberto Oliveros
 */
class DiscardDocumentCommand extends Command{
	/**
	 * Executes the tasks of the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$obj = $helper->getObject((int)$request->getProperty('key'));
		
		try{
			$obj->discard();
			Page::display(array(), 'success_xml.tpl');
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('message' => $msg), 'error_xml.tpl');
		}
	}
}
?>