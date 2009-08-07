<?php
/**
 * Library containing the DeactivateObject class command.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/set_property_object.php');

/**
 * Defines functionality for setting the product's description.
 * @package Command
 * @author Roberto Oliveros
 */
class DeactivateObjectCommand extends SetPropertyObjectCommand{
	/**
	 * Set the desired property on the object.
	 * @param variant $value
	 * @param variant $obj
	 */
	protected function setProperty($value, $obj){
		$obj->deactivate((boolean)$value);
	}
}
?>