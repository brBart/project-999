<?php
/**
 * Library containing the SetTimeTableShiftCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/set_property_object.php');

/**
 * Defines functionality for setting the time table property to the shift object.
 * @package Command
 * @author Roberto Oliveros
 */
class SetTimeTableShiftCommand extends SetPropertyObjectCommand{
	/**
	 * Set the desired property on the object.
	 * @param variant $value
	 * @param variant $obj
	 */
	protected function setProperty($value, $obj){
		$obj->setTimeTable($value);
	}
}
?>