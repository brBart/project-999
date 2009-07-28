<?php
/**
 * Library containing the save object command.
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
 * Defines functionality for saving an objects data in the database.
 * @package Command
 * @author Roberto Oliveros
 */
class SaveObjectCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$key = $request->getProperty('key');
		$obj = $helper->getObject((int)$key);
		
		header('Content-Type: text/xml');
		
		try{
			$id = $obj->save();
		} catch(ValidateException $e){
			$msg = $e->getMessage();
			$elementid = $e->getProperty();
			Page::display(array('success' => '0', 'elementid' => $element_id, 'message' => $msg),
					'validate_xml.tpl');
			return;
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('message' => $msg), 'error_xml.tpl');
			return;
		}
		
		Page::display(array('id' => $id), 'save_object_xml.tpl');
	}
}
?>