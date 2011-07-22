<?php
/**
 * Library containing the SetPercentageObjectCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/set_property_object.php');

/**
 * Defines functionality for setting the object's percentage.
 * @package Command
 * @author Roberto Oliveros
 */
class SetPercentageObjectCommand extends SetPropertyObjectCommand{
	/**
	 * Set the desired property on the object.
	 * @param variant $value
	 * @param variant $obj
	 */
	protected function setProperty($value, $obj){
		$obj->setPercentage($value);
	}
}
?>