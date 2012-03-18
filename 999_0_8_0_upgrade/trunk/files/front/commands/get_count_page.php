<?php
/**
 * Library containing the GetCountPageCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_object_page.php');

/**
 * Returns the name of the template to use for displaying the count's details.
 * @package Command
 * @author Roberto Oliveros
 */
class GetCountPageCommand extends GetObjectPageCommand{
	/**
	 * Returns the name of the template to use.
	 * @return string
	 */
	protected function getTemplate(){
		return 'count_page_xml.tpl';
	}
	
	/**
	 * Returns the params to display for the object.
	 * @param variant $obj
	 */
	protected function getObjectParams($obj, Request $request){
		return array('total' => $obj->getTotal());
	}
}
?>