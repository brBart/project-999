<?php
/**
 * Library containing the SetInitialNumberCorrelativeCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/set_property_object.php');

/**
 * Defines functionality for setting the property initial number to a correlative object.
 * @package Command
 * @author Roberto Oliveros
 */
class SetInitialNumberCorrelativeCommand extends SetPropertyObjectCommand{
	/**
	 * Set the desired property on the object.
	 * @param variant $value
	 * @param variant $obj
	 */
	protected function setProperty($value, $obj){
		$obj->setInitialNumber($value);
	}
}
?>