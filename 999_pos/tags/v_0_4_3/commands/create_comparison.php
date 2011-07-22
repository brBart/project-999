<?php
/**
 * Library containing the CreateComparisonCommand base class.
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
 * For creating the comparison.
 */
require_once('business/inventory.php');

/**
 * Defines functionality for creating a comparison.
 * @package Command
 * @author Roberto Oliveros
 */
class CreateComparisonCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		try{
			 $count = Count::getInstance($request->getProperty('count_id'));
			 if(is_null($count))
			 	throw new ValidateException('Conteo no existe', 'count_id');
			 
			 $reason = $request->getProperty('reason');
			 $general = (boolean)$request->getProperty('general');
			 $comparison_id = ComparisonEvent::apply($count, $reason, $general);
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
		
		Page::display(array('id' => $comparison_id), 'save_object_xml.tpl');
	}
}
?>