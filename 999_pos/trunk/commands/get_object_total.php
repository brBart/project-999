<?php
/**
 * Library containing the GetObjectTotal class command.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_object_property.php');

/**
 * Returns the total property of an object.
 * @package Command
 * @author Roberto Oliveros
 */
class GetObjectTotalCommand extends GetObjectPropertyCommand{
	/**
	 * Returns the value of the property requested.
	 * @param variant $obj
	 * @return variant
	 */
	protected function getProperty($obj){
		return number_format($obj->getTotal(), 2);
	}
}
?>