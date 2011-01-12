<?php
/**
 * Library containing the SetReasonObjectCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/set_property_object.php');

/**
 * Defines functionality for setting the property reason on an object.
 * @package Command
 * @author Roberto Oliveros
 */
class SetReasonObjectCommand extends SetPropertyObjectCommand{
	/**
	 * Set the desired property on the object.
	 * @param variant $value
	 * @param variant $obj
	 */
	protected function setProperty($value, $obj){
		$obj->setReason($value);
	}
}
?>