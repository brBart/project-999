<?php
/**
 * Library containing the EditObject command.
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
 * Defines common functionality for the delete object derived classes.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class DeleteObjectCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$user = $helper->getUser();
		
		if($this->testRights($user)){
			$obj = $helper->getObject((int)$request->getProperty('key'));
			
			try{
				$this->deleteObject($obj);
			} catch(Exception $e){
				$msg = $e->getMessage();
				
				Page::display(array('message' => $msg), 'error_xml.tpl');
				return;
			}
		}
	}
}
?>