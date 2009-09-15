<?php
/**
 * Library containing the GetObjectProperty base class command.
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
 * Defines common functionality for getting an object's property.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class GetObjectPropertyCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$obj = $helper->getObject((int)$request->getProperty('key'));
		$value = $this->getProperty($obj);
		Page::display(array('value' => $value), 'object_property_xml.tpl');
	}
	
	/**
	 * Returns the value of the property requested.
	 * @param variant $obj
	 * @return variant
	 */
	abstract protected function getProperty($obj);
}
?>