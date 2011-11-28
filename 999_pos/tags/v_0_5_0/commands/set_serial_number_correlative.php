<?php
/**
 * Library containing the SetSerialNumberCorrelativeCommand class.
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
 * Defines functionality for setting the property serial number to a correlative object.
 * @package Command
 * @author Roberto Oliveros
 */
class SetSerialNumberCorrelativeCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$serial_number = $request->getProperty('serial_number');
		$element_id = $request->getProperty('element_id');
		$correlative = $helper->getObject((int)$request->getProperty('key'));
		
		try{
			$correlative->setSerialNumber($serial_number);
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
			
		Page::display(array('element_id' => $element_id, 'initial_number' => $correlative->getInitialNumber()),
				'set_serial_number_correlative_xml.tpl');
	}
}
?>