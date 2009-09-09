<?php
/**
 * Library containing the SaveObject command.
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
			$element_id = $e->getProperty();
			Page::display(array('success' => '0', 'element_id' => $element_id, 'message' => $msg),
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