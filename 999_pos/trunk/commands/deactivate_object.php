<?php
/**
 * Library containing the DeactivateObjectCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/set_property_object.php');

/**
 * Defines functionality for setting the product's deactivated property.
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