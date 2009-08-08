<?php
/**
 * Library containing the SetPropertyObject base class command.
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
 * Defines common functionality for the set property object derived classes.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class SetPropertyObjectCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$value = $request->getProperty('value');
		$element_id = $request->getProperty('element_id');
		$obj = $helper->getObject((int)$request->getProperty('key'));
		
		try{
			$this->setProperty($value, $obj);
		} catch(ValidateException $e){
			$msg = $e->getMessage();
			Page::display(array('success' => '0', 'element_id' => $element_id, 'message' => $msg),
					'validate_xml.tpl');
			return;
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('message' => $msg), 'error_xml.tpl');
			return;
		}
			
		Page::display(array('success' => '1', 'element_id' => $element_id), 'validate_xml.tpl');
	}
	
	/**
	 * Set the desired property on the object.
	 * @param variant $value
	 * @param variant $obj
	 */
	abstract protected function setProperty($value, $obj);
}
?>