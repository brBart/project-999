<?php
/**
 * Library containing the SetRegimeCorrelativeCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/set_property_object.php');

/**
 * Defines functionality for setting the property regime to a correlative object.
 * @package Command
 * @author Roberto Oliveros
 */
class SetRegimeCorrelativeCommand extends SetPropertyObjectCommand{
	/**
	 * Set the desired property on the object.
	 * @param variant $value
	 * @param variant $obj
	 */
	protected function setProperty($value, $obj){
		$obj->setRegime($value);
	}
}
?>