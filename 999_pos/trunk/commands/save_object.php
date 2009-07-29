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
 * Defines functionality for saving an object's data in the database.
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
		$key = (int)$request->getProperty('key');
		$obj = $helper->getObject($key);
		
		try{
			$id = $obj->save();
		} catch(ValidateException $e){
			$msg = $e->getMessage();
			$elementid = $e->getProperty();
			header('Content-Type: text/xml');
			Page::display(array('success' => '0', 'elementid' => $element_id, 'message' => $msg),
					'validate_xml.tpl');
			return;
		} catch(Exception $e){
			$msg = $e->getMessage();
			header('Content-Type: text/xml');
			Page::display(array('message' => $msg), 'error_xml.tpl');
			return;
		}
		
		header('Content-Type: text/xml');
		Page::display(array('id' => $id), 'save_object_xml.tpl');
		$helper->removeObject($key);
	}
}
?>