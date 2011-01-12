<?php
/**
 * Library containing the SetNumberObjectCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/set_property_object.php');

/**
 * Defines functionality for setting the property number on an object.
 * @package Command
 * @author Roberto Oliveros
 */
class SetNumberObjectCommand extends SetPropertyObjectCommand{
	/**
	 * Set the desired property on the object.
	 * @param variant $value
	 * @param variant $obj
	 */
	protected function setProperty($value, $obj){
		$obj->setNumber($value);
	}
}
?>